<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Marketing\FacebookController;
use GuzzleHttp\Client;
use Carbon\Carbon;

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
}
