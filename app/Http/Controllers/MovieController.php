<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Promise\all;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\get(
     * path="/api/movies",
     * operationId="movies",
     * tags={"API for Site Status"},
     * summary="Get movies",
     * description="Get movies here",
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index()
    {
        $dataArray = array();
        $movies = DB::table('movies')->get();
        $genres = DB::table('genres')->get();
        for ($i = 0; $i < count($movies); $i++) {
            $data['id'] = $movies[$i]->id;
            $data['title'] = $movies[$i]->title;
            $data['number_in_stock'] = $movies[$i]->number_in_stock;
            $data['daily_rental_rate'] = $movies[$i]->daily_rental_rate;
            $data['liked'] = $movies[$i]->liked;

            for ($j = 0; $j < count($genres); $j++) {
                if ($movies[$i]->genre_id == $genres[$j]->id) {
                    $genresArr = array();
                    $genresArr['id'] = $genres[$j]->id;
                    $genresArr['name'] = $genres[$j]->name;
                    $data['genre'] = $genresArr;
                    break;
                }
            }
            array_push($dataArray, $data);
        }

        return response()->json([
            'movies' => $dataArray,
            'genres' => $genres

        ]);
        // return view('movie', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\post(
     * path="/movies",
     * operationId="moviesstore",
     * tags={"API for Site Status"},
     * summary="User movies store",
     * description="User movies store here",
     *     @OA\RequestBody(
     *      @OA\JsonContent(
     *                type="array",
     *                example={
     *                  "title": "asdsa",
     *                  "genre_id": "1",
     *                  "number_in_stock": "4",
     *                  "daily_rental_rate": "2",
     *                  "liked": "true",
     *                },
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="name",
     *                         type="string",
     *                      ),
     *                ),
     *                ),
     *                
     *        ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(Request $request)
    {
        $data = array();
        $data = $request->all();
        $data['publish_date'] = date('Y-m-d');
        $data['created_at'] = date('Y-m-d');
        DB::table('movies')->insert($data);
        return response()->json(['success' => "Done"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\put(
     * path="/movies/{id}",
     * operationId="moviesupdate",
     * tags={"API for Site Status"},
     * summary="Update Movie",
     * description="Update Movie here",
     *     @OA\RequestBody(
     *      @OA\JsonContent(
     *                type="array",
     *                example={
     *                  "title": "asdsa",
     *                  "genre_id": "1",
     *                  "number_in_stock": "5",
     *                  "daily_rental_rate": "5",
     *                  "liked": "true",
     *                },
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="name",
     *                         type="string",
     *                      ),
     *                ),
     *                ),
     *                
     *        ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(Request $request, $id)
    {
        $movieData = DB::table('movies')->find($id);
        $data = array();
        $data = $request->all();
        $data['updated_at'] = Date("Y-m-d");
        //DB::table('movies')->save($movieData);
        DB::table('movies')->where('id', $id)->update($data);
        return response()->json(['success' => "Updated"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
