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
        $accessToken = session("accessToken");
        $fb->setToken($accessToken);
        $options1 = ["fields" => "name,id,access_token","limit" => 100];
        $options2 = ["fields" => "name,id","limit" => 100];
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
        // dd($goutteClient->getRequest()->getUri());
        $link_img = "https:".$crawler->filter('.featured-image img')->eq(0)->extract('src')[0];
        return $link_img;
    }

    public function getImageOther($goutteClient,$link){
        $crawler = $goutteClient->request('GET', $link);
        $link_img = $crawler->filterXpath('//meta[@property="og:image"]')->attr('content');
        return $link_img;
    }

    // Api get Image Product Link from Product Link
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
        } else if(strpos($link, "https://goo.gl/X00uCh") > -1){
            $link_img = $this->getGooLink($goutteClient, $link);
        }
        else{
            $link_img = $this->getImageOther($goutteClient, $link);
        }
        return response()->json(["image_link" =>  $link_img]);
    }

    public function submitAds(Request $request){
        $userToken = session("accessToken");
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
         $fb->setToken($userToken);
         $form_data = [
            "name" => $campaignName,
            "objective" => "CONVERSIONS",
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
            "url_tags" => "utm_source=sma"
         ];

         $res = $fb->postData("$adaccount_id/adcreatives",$form_data);
         $adcreative_id = $res['id'];

         /**
          * Fourth create Adset
          */
         $adsets = [];
         $ads = [];
         $promoted_object = '{"pixel_id": "1663266663946071","custom_event_type": "PURCHASE"}';
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
                "promoted_object" => $promoted_object,
                "daily_budget" => 500,
                "optimization_goal" => "OFFSITE_CONVERSIONS",
                "billing_event" => "IMPRESSIONS",
                "is_autobid" => TRUE,
                "attribution_window_days" => 1,
                "start_time" => Carbon::now()->toDateTimeString(),
                "status" => "PAUSED",
                "targeting" => $targeting,
            ];
            $res = $fb->postData("$adaccount_id/adsets",$form_data);
            $adset_id = $res['id'];
            array_push($adsets, $adset_id);
            $creativeAds = [
                "creative_id" => $adcreative_id
            ];
            $creativeAds = json_encode($creativeAds);
            $form_ads = [
                "adset_id" => $adset_id,
                "creative" => $creativeAds,
                "name" => $today
            ];
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