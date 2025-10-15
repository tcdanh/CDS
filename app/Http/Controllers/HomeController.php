<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BannerArticle;
use App\Models\Intro;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $banners = BannerArticle::latest()->take(5)->get();
        $intro = Intro::latest()->first(); // hoặc ->find(1) nếu bạn có intro mặc định
        $news = News::latest()->get();
        return view('frontend.home', [
            'banners' => $banners,
            'intro' => $intro,
            'news' => $news,
        ]);
    }
}
