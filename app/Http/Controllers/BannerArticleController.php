<?php

namespace App\Http\Controllers;

use App\Models\BannerArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannerArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = BannerArticle::latest()->paginate(10);
        return view('banner_article.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('banner_article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tittle' => 'required|max:255',
            'mota_en' => 'required|max:1000',
            'mota_vn' => 'required|max:1000',
            'hinhanh' => 'required|image|mimes:jpg,png|max:2048',
            'link' => 'nullable|url',
        ]);
    
        // Upload file
        $filename = time().'_'.$request->file('hinhanh')->getClientOriginalName();
        $request->file('hinhanh')->move(public_path('images/banner_article'), $filename);
    
        BannerArticle::create([
            'tittle' => $request->tittle,
            'mota_en' => $request->mota_en,
            'mota_vn' => $request->mota_vn,
            'hinhanh' => $filename,
            'id_user' => Auth::id(),
            'link' => $request->link,
        ]);
    
        return redirect()->route('banner_article.index')->with('success', 'Banner created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BannerArticle $bannerArticle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = BannerArticle::findOrFail($id);
        return view('banner_article.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $banner = BannerArticle::findOrFail($id);

        $request->validate([
            'tittle' => 'required|max:255',
            'mota_en' => 'required|max:1000',
            'mota_vn' => 'required|max:1000',
            'link' => 'nullable|url',
            'hinhanh' => 'nullable|image|mimes:jpg,png|max:2048',
            ]);

        if ($request->hasFile('hinhanh')) {
            $filename = time().'_'.$request->file('hinhanh')->getClientOriginalName();
            $request->file('hinhanh')->move(public_path('images/banner_article'), $filename);
            $banner->hinhanh = $filename;
        }

        $banner->update([
            'tittle' => $request->tittle,
            'mota_en' => $request->mota_en,
            'mota_vn' => $request->mota_vn,
            'link' => $request->link,
            'hinhanh' => $banner->hinhanh, // vẫn giữ hình cũ nếu không upload mới
            ]);

        return redirect()->route('banner_article.index')->with('success', 'Đã cập nhật banner.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = BannerArticle::findOrFail($id);
        $banner->delete();

        return redirect()->route('banner_article.index')->with('success', 'Đã xoá banner.');
    }

    public function confirmDelete($id)
    {
        $banner = BannerArticle::findOrFail($id);
        return view('banner_article.destroy', compact('banner'));
    }
}
