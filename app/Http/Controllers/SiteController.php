<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
