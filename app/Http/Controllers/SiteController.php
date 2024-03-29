<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\get(
     * path="/siteMap",
     * operationId="siteMap",
     * tags={"site"},
     * summary="Get siteMap",
     * description="Get siteMap here",
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function siteMap()
    {
        $dataArray = array();
        $site = new Site;
        $reportAtt = $site->siteMap();

        return response()->json([
            'location' => $reportAtt

        ]);
        // return view('movie', ['users' => $users]);
    }
    /**
     * @OA\Post(
     * path="/siteMapDetails",
     * summary="Site Map Details",
     * description="Site Map Details",
     * operationId="siteMapDetails",
     * tags={"site"},
     *     @OA\RequestBody(
     *        required = true,
     *        description="Site Map Details",
     *        @OA\JsonContent(
     *                type="array",
     *                example={
     *                      "site_id":"1",
     *                },
     *                @OA\Items(
     *                  @OA\Property(
     *                         property="site_id",
     *                         type="int",
     *                         example="10"
     *                      ),
     *                ),
     *                
     *                ),
     *                
     *        ),
     * @OA\Response(
     *    response=422,
     *    description="Site",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Site")
     *        )
     *     )
     * )
     */
    public function siteMapDetails(Request $request)
    {
        $sid = $request->site_id;
        $dataArray = array();
        $reportAtt = DB::select(DB::raw("SELECT s.site_id,s.site_name,s.latitude,s.longitude,o.oname,z.zname,r.rname,l.lname
        FROM private.site s inner join private.organization o
        on o.oid=s.oid inner join private.zone z on z.zid=s.zid inner join private.region r on
        s.rid=r.rid inner join private.location l on l.lid=s.lid where s.site_id='$sid'
        "));

        return response()->json([
            'locationDetails' => $reportAtt

        ]);
        // return view('movie', ['users' => $users]);
    }
    /**
     * @OA\get(
     * path="/siteStatus",
     * operationId="siteStatus",
     * tags={"site"},
     * summary="Get siteStatus",
     * description="Get siteStatus here",
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function siteStatus()
    {
        $site = new Site;
        $siteStatus = $site->getStatus();
        $getStatusOnlineOffline = $site->getStatusOnlineOffline();
        array_push($siteStatus, $getStatusOnlineOffline[0]);

        return response()->json([
            'site_status' => $siteStatus

        ]);
        // return view('movie', ['users' => $users]);
    }
    /**
     * @OA\get(
     * path="/siteData",
     * operationId="siteData",
     * tags={"site"},
     * summary="Get siteData",
     * description="Get siteData here",
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function siteData()
    {
        $site = new Site;
        $siteStatus = $site->getsiteData();

        return response()->json([
            'site_data' => $siteStatus

        ]);
        // return view('movie', ['users' => $users]);
    }
    /**
     * @OA\get(
     * path="/activeEvent",
     * operationId="activeEvent",
     * tags={"site"},
     * summary="Get activeEvent",
     * description="Get activeEvent here",
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function activeEvent()
    {
        $site = new Site();
        $data = array();
        $siteStatus = $site->getStatus();
        $getStatusOnlineOffline = $site->getStatusOnlineOffline();
        $totalSite = $site->getTotalSite();
        $getActiveEvent = $site->getActiveEvent();


        for ($i = 0; $i < count($siteStatus); $i++) {
            $data[$siteStatus[$i]->alarm_type] = $siteStatus[$i]->count;
        }
        $data['online_site'] = $getStatusOnlineOffline[0]->online_site;
        $data['offline_site'] = $getStatusOnlineOffline[0]->offline_site;
        $data['site_count'] = $totalSite[0]->total_site;

        $data2 = array();
        for ($i = 0; $i < count($getActiveEvent); $i++) {
            $activeEvent = array();
            $activeEvent['site_id'] = $getActiveEvent[$i]->site_id;
            $activeEvent['site_name'] = $getActiveEvent[$i]->site_name;
            $activeEvent['online_offline_status'] = $getActiveEvent[$i]->online_offline_status == true ? 'online' : 'offline';
            $activeEvent['rname'] = $getActiveEvent[$i]->rname;
            $activeEvent['start_time'] = date("Y-m-d h:i:s", substr($getActiveEvent[$i]->set_time, 0, 10));
            $activeEvent['end_time'] = 0;
            $activeEvent['time_elapsed'] = 0;

            if ($getActiveEvent[$i]->reset_time != '') {
                $activeEvent['end_time'] = date("Y-m-d h:i:s", substr($getActiveEvent[$i]->reset_time, 0, 10));
                $a = date("Y-m-d h:i:s", substr($getActiveEvent[$i]->set_time, 0, 10));
                $b = date("Y-m-d h:i:s", substr($getActiveEvent[$i]->reset_time, 0, 10));
                $d1 = new DateTime($a);
                $d2 = new DateTime($b);
                $interval = $d1->diff($d2);
                $activeEvent['time_elapsed'] = $interval->format('%y years %m months %d days %h hours %i minutes %s seconds');
            }

            array_push($data2, $activeEvent);
        }
        $data['table_data'] =  $data2;





        return response()->json([
            'active_event' => $data

        ]);
    }
    /**
     * @OA\get(
     * path="/siteLiveWidget",
     * operationId="siteLiveWidget",
     * tags={"site"},
     * summary="Get siteLiveWidget",
     * description="Get siteLiveWidget here",
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function siteLiveWidget()
    {
        $dataArray = array();
        $site = new Site;
        $siteLiveWidget = $site->siteLiveWidget();

        return response()->json([
            'live_widget' => $siteLiveWidget

        ]);
        // return view('movie', ['users' => $users]);
    }
}
