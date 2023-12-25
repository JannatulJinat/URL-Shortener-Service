<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Url;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class URLController extends Controller
{
    public function index(){
        return Url::all();
    }
    public function show($url){
        return Url::where('long_url', $url)->get();
    }
    public function create(Request $request){
        $request->validate([
            'longUrl'=>'required|url'
        ]);
        $result = Url::where('long_url', '=', $request->longUrl)->first();
        if($result == NULL)
        {
            //$shortUrl =  Str::random(1);
            // do
            // {
            //     $shortUrl = rand(1,4);
            //     $haveRecord = Url::where('short_url_path', '=', $shortUrl)->first();
            // }while($haveRecord);
            $shortUrl = Str::random(5);
            Url::create([
                'long_url'=>  $request->longUrl,
                'short_url_path' => $shortUrl
            ]);
            return response()->json([
                "longUrl"=> $request->longUrl,
                "shortUrl"=> $shortUrl
                 ], Response::HTTP_CREATED, [],  JSON_UNESCAPED_SLASHES);
        }
        else{
            return response()->json([
                "longUrl"=> $request->longUrl,
                "shortUrl"=>  "http://127.0.0.1:8000/" . $result->short_url_path
                 ], Response::HTTP_ACCEPTED, [], JSON_UNESCAPED_SLASHES);
        }
    }

    public function redirectLink($shortUrl)
    {
        $result = Url::where('short_url_path', $shortUrl)->first();
        return redirect($result->long_url);
    }
}
