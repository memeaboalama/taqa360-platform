<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BackupFilesController extends Controller
{
    public function getAllImageUrls()
    {
        $imageUrls = DB::table('items')->select('image as image_url')
            ->union(DB::table('driver_files')->select('path as image_url'))
            ->union(DB::table('market_place_categories')->select('image as image_url'))
            ->union(DB::table('merchant_branches')->select('cover_image as image_url'))
            ->union(DB::table('merchant_files')->select('path as image_url'))
            ->union(DB::table('banners')->select('image as image_url'))
            ->get();


        // $siteName = Config::get('app.url').'/storage/';
        // $modifiedImageUrls = $imageUrls->map(function ($image) use ($siteName){
        //     $image->image_url = $siteName.$image->image_url;
        //     return $image;
        // });    

        return response()->json($imageUrls);
    }


    public function getTodayImageUrls()
    {
        $today = Carbon::today();

        $imageUrls = DB::table('items')->select('image as image_url')
            ->whereDate('created_at', $today)
            ->union(DB::table('driver_files')->select('path as image_url')->whereDate('created_at', $today))
            ->union(DB::table('market_place_categories')->select('image as image_url')->whereDate('created_at', $today))
            ->union(DB::table('merchant_branches')->select('cover_image as image_url')->whereDate('created_at', $today))
            ->union(DB::table('merchant_files')->select('path as image_url')->whereDate('created_at', $today))
            ->union(DB::table('banners')->select('image as image_url')->whereDate('created_at', $today))
            ->toSql();

            // Log::info($imageUrls);
        // $siteName = Config::get('app.url').'/storage/';
        // $modifiedImageUrls = $imageUrls->map(function ($image) use ($siteName){
        //     $image->image_url = $siteName.$image->image_url;
        //     return $image;
        // });    

        return response()->json($imageUrls);
    }

}
