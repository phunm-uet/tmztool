<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Marketing\FacebookController;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Niche;

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
        $accessToken = session("accessToken");
        $fb->setToken($accessToken); 
        $options = ["fields" => "id,fan_count,link,name,picture{url}","limit" => 200];
        $pages = $fb->getEdge("me/accounts",$options);
        return response()->json($pages);
    }

    /**
     * Get data 2 wwek
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getDatas(Request $request)
    {
        $fb = new FacebookController();
        $accessToken = session("accessToken");
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

    public function topPosts(Request $request)
    {
        $fb = new FacebookController();
        $accessToken = session("accessToken");
        $fb->setToken($accessToken);        
        $id = $request->id;
        $options = [
            "fields" => "insights.metric(post_impressions_unique),reactions.limit(1).summary(true),picture",
            "limit" => 10
        ];
        $data = $fb->getEdge("$id/posts/",$options);
        return response()->json($data);
    }

    public function getDataForSetAds(Request $request)
    {
        $fb = new FacebookController();
        $accessToken = session("accessToken");
        $fb->setToken($accessToken);
        $options = ["fields" => "name,id,adspixels.limit(50)","limit" => 100];
        $adAccounts = $fb->getEdge("me/adaccounts",$options);
        $niches = Niche::with('Interests')->get();
        $arrResponse = [
            "niches" => $niches,
            "adaccounts" => $adAccounts        
        ];
        
        return response()->json($arrResponse);
    }


    public function submitAds(Request $request){
        $userToken = session("accessToken");
        $niche = $request->niche;
        $country = $request->country;
        $arrInterests = $request->interest;
        $minage= $request->minage;
        $maxage = $request->maxage;
        $post_id = $request->post_id;
        $adaccount_id = $request->ad_account['id'];
        $fb = new FacebookController();

         /**
          * Second create Ads Campaign
          */
         $today = Carbon::now()->toDateString();
         $campaignName = $request->name_campaign;
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
            "object_story_id" => $post_id,
            "url_tags" => "utm_source=sma"
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
                "optimization_goal" => "OFFSITE_CONVERSIONS",
                "billing_event" => "IMPRESSIONS",
                "is_autobid" => TRUE,
                "attribution_window_days" => 1,
                "start_time" => Carbon::now()->toDateTimeString(),
                "status" => "PAUSED",
                "targeting" => $targeting,
            ];
            if($request->pixel != null){
                $pixel_id = $request->pixel['id'];
                $promoted_object = '{"pixel_id": "'. $pixel_id .'","custom_event_type": "PURCHASE"}';
                $form_data['promoted_object'] = $promoted_object;
            }
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
            "adsets" => $adsets,
            "ads" => $ads
         ];
         return response()->json($response);
    }
}
