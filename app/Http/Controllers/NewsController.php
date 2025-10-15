<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource items
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        //$slug = Str::slug($request->title);
        //$originalSlug = $slug;
        //$count = 1;

        //while (News::where('slug', $slug)->exists()) {
        //    $slug = $originalSlug . '-' . $count++;
        //}
        $data = $request->only(['title', 'content']);
        $imagePath = null;
        if ($request->hasFile('image')) {
            //$imagePath = $request->file('image')->store('news', 'public');
            $filename = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images/news'), $filename);
            $data['image'] = $filename;
        }
        // Slug sẽ được tự động tạo trong model (boot method)
        News::create($data);
        //News::create([
        //    'title'   => $request->title,
        //    'slug'    => $slug,
        //    'content' => $validated[request->content],
        //    'image'   => $imagePath,
        //]);

        return redirect()->route('admin.news.index')->with('success', 'News article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    public function showPublic($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return view('news.show_public', compact('news'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $news->title   = $request->title;
        $news->content = $request->content;

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $news->image = $request->file('image')->store('news', 'public');
        }

        $news->save();

        return redirect()->route('news.index')->with('success', 'News article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('news.index')->with('success', 'News article deleted.');
    }
}
