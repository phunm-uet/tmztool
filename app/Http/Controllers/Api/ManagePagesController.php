<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Marketing\FacebookController;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Niche;
use App\Interest;
use App\AdsRunning;
class ManagePagesController extends Controller
{
    const PAGE_FAN_ADDS = "page_fan_adds";
    const PAGE_REACH = "page_impressions_unique";
    const PAGE_ENGAGEMENT = "page_engaged_users";
	/**
	 * API get Pages
	 * @return [json] all pages
	 */
    public function getPages()
    {
        $fb = new FacebookController();
        $accessToken = session("access_token");
        $fb->setToken($accessToken); 
        $options = ["fields" => "id,fan_count,link,name,picture{url}","limit" => 200];
        $pages = $fb->getEdge("me/accounts",$options);
        return response()->json($pages);
    }

    /**
     * Get data current week,and last week
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getDatas(Request $request)
    {
        $fb = new FacebookController();
        $accessToken = session("access_token");
        $fb->setToken($accessToken);
        $type = $request->type;
        $id = $request->id;
        $today = Carbon::now();
        $tdTimeStamp = $today->timestamp;
        $lastweek = $today->subWeek();
        $lwTimeStamp = $lastweek->timestamp;
        $two_week_ago = $today->subWeek(1);
        $twTimeStamp = $two_week_ago->timestamp;
        $options = [
            "period" => "day",
            "since" => $lwTimeStamp,
            "until" => $tdTimeStamp
        ];

        if($type == 'likes') $options['metric'] = self::PAGE_FAN_ADDS;
        if($type == 'reach') $options['metric'] = self::PAGE_REACH;
        if($type == 'engagement') $options['metric'] = self::PAGE_ENGAGEMENT;
        $week1 = $fb->getEdge("$id/insights/",$options);
        $options['since'] =  $twTimeStamp;
        $options['until'] = $lwTimeStamp;
        $week2 = $fb->getEdge("$id/insights/",$options);
        return response()->json(["week1" => $week1,"week2" => $week2]);
    }

    /**
     * Lay thong tin 10 post moi nhat cua fanpage
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function topPosts(Request $request)
    {
        $fb = new FacebookController();
        $accessToken = session("access_token");
        $fb->setToken($accessToken);        
        $id = $request->id;
        $options = [
            "fields" => "insights.metric(post_impressions_unique),reactions.limit(1).summary(true),picture,comments.limit(1).summary(true),shares,created_time",
            "limit" => 10
        ];
        $data = $fb->getEdge("$id/posts/",$options);
        return response()->json($data);
    }
    /**
     * Lay thong tin quang cao tu fb
     * @param  Request $request [description]
     * @return [json]   thong so quang cao fb
     */
    public function getDataForSetAds(Request $request)
    {
        $fb = new FacebookController();
        $accessToken = session("access_token");
        $fb->setToken($accessToken);
        $options = ["fields" => "name,id,adspixels.limit(50){name}","limit" => 100];
        $adAccounts = $fb->getEdge("me/adaccounts",$options);
        $niches = Niche::with('Interests')->get();
        $arrResponse = [
            "niches" => $niches,
            "adaccounts" => $adAccounts        
        ];
        
        return response()->json($arrResponse);
    }

    /**
     * Tao campaign trong detail page
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function submitAds(Request $request){
        $userToken = session("access_token");
        $niche = $request->niche;
        $country = $request->country;
        $arrInterests = $request->interest;
        $minage= $request->minage;
        $maxage = $request->maxage;
        $post_id = $request->post_id;
        $adaccount_id = $request->ad_account['id'];
        $typeAds = $request->typeAds;
        $budget = $request->budget;
        $budget = (int)($budget) * 100;
        $pageId = $request->id;;
        $fb = new FacebookController();

         /**
          *  Create Ads Campaign
          */
         $today = Carbon::now()->toDateString();
         $campaignName = $request->name_campaign;
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
            "object_story_id" => $post_id,
         ];

         $res = $fb->postData("$adaccount_id/adcreatives",$form_data);
         $adcreative_id = $res['id'];

         /**
          * Fourth create Adset
          */
         $adsets = [];
         $ads = [];
         foreach($arrInterests as $interest_id){
            $interest = Interest::find($interest_id);
            $targeting = $interest['targeting'];
            $targeting = json_decode($targeting,true);
            $targeting['age_max'] = $maxage;
            $targeting['age_min'] = $minage;
            $targeting['geo_locations']['countries'][0] = $country;
            $targeting = json_encode($targeting);
            $form_data = [
                "campaign_id" => $campaignID,
                "name" => $interest['name']."- $today $minage-$maxage",
                "daily_budget" => $budget,
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
                  // $form_data['targeting_optimization'] = "NONE";
              }              
            } else {

              if($request->pixel != null){
                  $form_data['optimization_goal'] = "POST_ENGAGEMENT";
              }  
            }
            $res = $fb->postData("$adaccount_id/adsets",$form_data);
            $adset_id = $res['id'];
            array_push($adsets, $adset_id);
            $creativeAds = [
                "creative_id" => $adcreative_id,

            ];
            $creativeAds = json_encode($creativeAds);
            $tracking_specs = "[{'action.type' : 'offsite_conversion','fb_pixel' : '".$request->pixel['id']."'}]";            
            $form_ads = [
                "adset_id" => $adset_id,
                "creative" => $creativeAds,
                "name" => $today,
                "tracking_specs" => $tracking_specs
            ];
            $res = $fb->postData("$adaccount_id/ads",$form_ads);
            array_push($ads, $res['id']);
         }
         $adsRunning = new AdsRunning;
         $adsRunning->campaign_id = $campaignID;
         $adsRunning->campaign_name = $campaignName;
         $adsRunning->post_id = $post_id;
         $adsRunning->page_id = $pageId;
          $adsRunning->save();
         $response = [
            "campaign" => $campaignID,
            "adsets" => $adsets,
            "ads" => $ads
         ];
         return response()->json($response);
    }

    public function getCampaignRuning(Request $request)
    {
      $pageId = $request->id;
      $campaigns = AdsRunning::where("page_id",$pageId)->get();
      return response()->json($campaigns);
    }

    public function getCampaignInfo(Request $request)
    {
        $fb = new FacebookController();
        $accessToken = session("access_token");
        $fb->setToken($accessToken);        
        $campaign_id = $request->campaign_id;
        $campaign = AdsRunning::where("campaign_id",$campaign_id)->first();
        $page_id = $campaign->post_id;
        // dd($page_id);
        $campaignOptions = [
            "date_preset" => "lifetime",
            "fields" => "cost_per_inline_post_engagement,spend,cpc,cpm"
        ];
        $campaignInfos = $fb->getEdge("$campaign_id/insights",$campaignOptions);
        $campaignInfos = $campaignInfos[0];
        $postOptions = ['fields' => 'reactions.limit(1).summary(true),picture,comments.limit(1).summary(true),shares,full_picture'];
        $postInfos = $fb->getNode("$page_id",$postOptions);
        return response()->json(['campaign' => $campaignInfos,'post' => $postInfos]);
    }


    public function pauseAds(Request $request)
    {
      $campaignId = $request->campaign_id;
      $fb = new FacebookController();
      $fb->postData($campaignId,['status' => "PAUSED"]);
      return response()->json(['success' => 1]);
    }
}
