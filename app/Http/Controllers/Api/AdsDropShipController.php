<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Marketing\FacebookController;
use App\Niche;
use App\Interest;
use App\PageManager;
use App\TypeProduct;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;
use Session;
use Exception;
use Carbon\Carbon;

class AdsDropShipController extends Controller
{
    /**
     * Api for index page autoads
     * @return [json] [description]
     */
	public function index()
    {
        $fb = new FacebookController();
        $accessToken = session("access_token");
        $fb->setToken($accessToken);
        $options1 = ["fields" => "name,id,access_token","limit" => 100];
        $options2 = ["fields" => "name,id,adspixels.limit(50){name}","limit" => 100];
        $pages = $fb->getEdge("me/accounts",$options1);
        $adAccounts = $fb->getEdge("me/adaccounts",$options2);
    	$niches = Niche::with('Interests')->get();
    	$types = TypeProduct::all();
    	$arrResponse = [
    		"niches" => $niches,
    		"pages" => $pages,
    		"types" => $types,
            "adaccounts" => $adAccounts
    	];
    	return response()->json($arrResponse);
    }

    // Funtion get link gool.gl
    public function getGooLink($goutteClient,$link){
        $crawler = $goutteClient->request('GET', $link);
        $realLink = $goutteClient->getRequest()->getUri();
        if(strpos($realLink, "https://triplewiser.com/") > -1){
            return $this->getImageTriple($goutteClient, $realLink);
        } else {
            return $this->getImageOther($goutteClient, $realLink);
        }
    }

    /**
     * Funtion get Image_link Triplewise
     * @param  [goutte] $goutteClient goutteClient
     * @param  [string] $link         Product link
     * @return [string]               Image Product
     */
    public function getImageTriple($goutteClient,$link){
        $crawler = $goutteClient->request('GET', $link);
        $link_img = "https:".$crawler->filter('.featured-image img')->eq(0)->extract('src')[0];
        return $link_img;
    }

    public function getImageOther($goutteClient,$link){
        $crawler = $goutteClient->request('GET', $link);
        $link_img = $crawler->filterXpath('//meta[@property="og:image"]')->attr('content');
        return $link_img;
    }

    /**
     * Get Link Image
     * @param  Request $request [description]
     * @return [string]           url image mac dinh cua product link
     */
    public function getImageLink(Request $request){
        $link = $request->link;
        $goutteClient = new GoutteClient();
        $header = [
            "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Encoding" => "gzip, deflate, sdch"
        ];
        $guzzleClient = new Client(["headers" => $header]);
        $goutteClient->setClient($guzzleClient);
        if(strpos($link, "https://triplewiser.com/") > -1){
            $link_img = $this->getImageTriple($goutteClient,$link);
        } else if(strpos($link, "https://goo.gl") > -1){
            $link_img = $this->getGooLink($goutteClient, $link);
        }
        else{
            $link_img = $this->getImageOther($goutteClient, $link);
        }
        return response()->json(["image_link" =>  $link_img]);
    }

    public function getCountry(Request $request)
    {
        $q = $request->q;
        $accessToken = $request->session()->get('access_token');
        $fb = new FacebookController();
        $fb->setToken($accessToken);
        $result = [];
        $kq = $fb->getEdge("search",['q' => $q,'type' => 'adcountry']);
        foreach($kq as $k){
            $result[] = [$k['country_code'] => $k['name']];
        }
        return response()->json($kq);
    }

    public function uploadImage(Request $request)
    {
        $client_path = storage_path("app/public/client.json");
        $save_path = storage_path("app/public/save.json");
        $tmpFile = $request->file->getRealPath();
        $fileName = $request->file->getClientOriginalName();
        $fileMinme = $request->file->getMimeType();
        $token = json_decode(file_get_contents($save_path),true);
        $client = new \Google_Client();
        $client->setApplicationName("Upload Image");
        $client->setAuthConfig($client_path);
        $client->setAccessType('offline'); 
        $client->setApprovalPrompt("force");      
        $client->setAccessToken($token);
        if ($client->isAccessTokenExpired())
        {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($save_path, json_encode($client->getAccessToken()));
        }
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
          'name' => $fileName,
          'parents' => array("0B8uqwM4G2aXQeGp0a0xSaHFwZEU")));
        $content = file_get_contents($tmpFile);
        $driveService = new \Google_Service_Drive($client);
        $file = $driveService->files->create($fileMetadata, array(
          'data' => $content,
          'mimeType' => $fileMinme,
          'uploadType' => 'multipart',
          'fields' => 'id,webContentLink'));
        $fileId = $file->id;
        $permission = new \Google_Service_Drive_Permission();
        $permission->setRole( 'reader' );
        $permission->setType( 'anyone' );       
        $driveService->permissions->create($fileId,$permission);
        $link_image = $file->webContentLink;
        return response()->json(['link' => $link_image]);
    }


    /**
     * Submit Ads
     * @param  Request $request [description]
     * @return [boolean]           
     */
    public function submitAds(Request $request){
        $userToken = session("access_token");
        $niche = $request->niche;
        $country = $request->country;
        $pageToken = $request->page["access_token"];
        $arrInterests = $request->interest;
        $description = $request->description;
        $product_link = $request->product_link;
        $description = str_ireplace('$link$', $product_link, $description);
        $page_id = $request->page_id;
        $minage= $request->minage;
        $maxage = $request->maxage;
        $image_link = $request->image_link;
        $adaccount_id = $request->ad_account['id'];
        $targeting = json_decode($arrInterests[0]['targeting']);
        $typeAds = $request->typeAds;
        $typeProduct = $request->typeProduct;
        $fb = new FacebookController();
        $fb->setToken($pageToken);
        /**
         * First Step create Post
         */
        $form_data = [
           "caption" => $description,
            "url" => $image_link,
        ];
         $res = $fb->postData("me/photos",$form_data);
         if($res != null){
            $postId = $res["post_id"];
         } else {
            return response(["error" => "Khong the tao post"],400);
         }

         /**
          * Second create Ads Campaign
          */
         $today = Carbon::now()->toDateString();
         $campaignName = $today."  $niche - Dropship";
         if($typeAds= "CONVERSIONS"){
            $campaignName = $campaignName." WC $typeProduct";
         } else {
            $campaignName = $campaignName." PPE $typeProduct";
         }

         $fb->setToken($userToken);
         $form_data = [
            "name" => $campaignName,
            "objective" => $typeAds,
            "status" => "PAUSED"
         ];
         $res = $fb->postData("$adaccount_id/campaigns",$form_data);
         if($res != null){
            $campaignID = $res["id"];
         } else {
            return response(["error" => "Khong the tao campaign"],400);
         }
    
        /**
          * Third Create AdsCreative
          */
         
         $form_data = [
            "object_story_id" => $postId,
         ];

         $res = $fb->postData("$adaccount_id/adcreatives",$form_data);
         $adcreative_id = $res['id'];

         /**
          * Fourth create Adset
          */
         $adsets = [];
         $ads = [];
         foreach($arrInterests as $interest){
            $targeting = $interest['targeting'];
            $targeting = json_decode($targeting,true);
            $targeting['age_max'] = $maxage;
            $targeting['age_min'] = $minage;
            $targeting['geo_locations']['countries'][0] = $country;
            $targeting = json_encode($targeting);

            $form_data = [
                "campaign_id" => $campaignID,
                "name" => $interest['name']."- $today $minage-$maxage",
                "daily_budget" => 500,
                "billing_event" => "IMPRESSIONS",
                "is_autobid" => TRUE,
                "attribution_window_days" => 1,
                "start_time" => Carbon::now()->toDateTimeString(),
                "status" => "PAUSED",
                "targeting" => $targeting,
            ];
            if($typeAds == "CONVERSIONS")
            {
              if($request->pixel != null){
                  $pixel_id = $request->pixel['id'];
                  $promoted_object = '{"pixel_id": "'. $pixel_id .'","custom_event_type": "PURCHASE"}';
                  $form_data['promoted_object'] = $promoted_object;
                  $form_data['optimization_goal'] = "OFFSITE_CONVERSIONS";
              }                  
          }
          else 
          {
              if($request->pixel != null){
                  $form_data['optimization_goal'] = "POST_ENGAGEMENT";
              }            
          }
            $res = $fb->postData("$adaccount_id/adsets",$form_data);
            $adset_id = $res['id'];
            array_push($adsets, $adset_id);
            $creativeAds = [
                "creative_id" => $adcreative_id
            ];
            $creativeAds = json_encode($creativeAds);
            $tracking_specs = "[{'action.type' : 'offsite_conversion','fb_pixel' : '".$request->pixel['id']."'}]";
            $form_ads = [
                "adset_id" => $adset_id,
                "creative" => $creativeAds,
                "name" => $today,
                "tracking_specs" => $tracking_specs
            ];
            // dd($form_ads);
            $res = $fb->postData("$adaccount_id/ads",$form_ads);
            array_push($ads, $res['id']);
         }

         $response = [
            "campaign" => $campaignID,
            "post" => $postId,
            "adsets" => $adsets,
            "ads" => $ads
         ];
         return response()->json($response);
    }
}