<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Intro;
use App\Models\Structure;
use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;

class IntroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 2 trường hợp
        if (Auth::check()) {
            // Trường hợp đã login: backend
            //$intros = Intro::with('user')->latest()->get();
            $intros = Intro::with(['user', 'structures', 'achievements' => function($query) {
            $query->orderBy('thoigian', 'desc'); // Sắp xếp theo mốc thời gian 
            }])->latest()->get();
            return view('about.index_back', compact('intros'));
        } else {
            // Trường hợp chưa login: frontend
            $intro = Intro::latest()->first();
            return view('about.index_front', compact('intro'));
        }
    }
    public function index_front()
    {   
        $intro = Intro::latest()->first();
        return view('about.index_front', compact('intro'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('about.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'short_description' => 'required|string|max:1000',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'goals' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $data = $request->only(['short_description', 'vision', 'mission', 'goals']);
        $data['user_id'] = auth()->id();
    
        if ($request->hasFile('image')) {
            $filename = time().'_'.$request->image->getClientOriginalName();
            $request->image->move(public_path('images/intros'), $filename);
            $data['image'] = $filename;
        }
    
        \App\Models\Intro::create($data);
    
        return redirect()->route('about.index')->with('success', 'Intro đã được tạo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $intro = \App\Models\Intro::findOrFail($id);
        return view('about.edit', compact('intro'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $intro = \App\Models\Intro::findOrFail($id);

        $request->validate([
            'short_description' => 'required|string|max:1000',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'goals' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $intro->short_description = $request->short_description;
        $intro->vision = $request->vision;
        $intro->mission = $request->mission;
        $intro->goals = $request->goals;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            $oldPath = public_path('images/intros/' . $intro->image);
            if ($intro->image && File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $filename = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images/intros'), $filename);
            $intro->image = $filename;
        }

        $intro->save();

        return redirect()->route('about.index')->with('success', 'Đã cập nhật Giới thiệu.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //$intro = Intro::with(['structures', 'achievements'])->findOrFail($id);
        $intro = Intro::with(['structures'])->findOrFail($id);
        // 🧹 Xoá ảnh intro nếu có
        if ($intro->image) {
            $imagePath = public_path('images/intros/' . $intro->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // 🧹 Xoá structures và ảnh
        foreach ($intro->structures as $structure) {
            if ($structure->image) {
                $structureImage = public_path('images/intros/' . $structure->image);
                if (File::exists($structureImage)) {
                    File::delete($structureImage);
                }
            }
            $structure->delete();
        }


        // 🧹 Xoá bản ghi Intro
        $intro->delete();

        return redirect()->route('about.index')->with('success', 'Đã xoá bản giới thiệu và toàn bộ dữ liệu liên quan.');
    }


    //Structures
    /**
     * Show the form for creating a new resource.
     */
    public function create_structure($introId)
    {
        $intro = Intro::findOrFail($introId);
        return view('about.create_structure', compact('intro'));
    }

    public function store_structure(Request $request)
    {
        $request->validate([
            'intro_id' => 'required|exists:intros,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['intro_id', 'name', 'position', 'description']);

        if ($request->hasFile('image')) {
            //$data['image'] = $request->file('image')->store('structures', 'public');
            // Đặt tên file mới
            $filename = time() . '_' . $request->image->getClientOriginalName();

            // Di chuyển ảnh vào thư mục public/images/intros
            $request->image->move(public_path('images/intros'), $filename);

            // Lưu tên file vào dữ liệu
            $data['image'] = $filename;
        }

        \App\Models\Structure::create($data);

        return redirect()->route('about.index')->with('success', 'Thêm thành viên thành công!');
    }

    public function edit_structure($id)
    {
        $structure = \App\Models\Structure::findOrFail($id);
        return view('about.edit_structure', compact('structure'));
    }

    public function update_structure(Request $request, $id)
    {
        $structure = \App\Models\Structure::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'position', 'description']);

        if ($request->hasFile('image')) {
            //$data['image'] = $request->file('image')->store('structures', 'public');
            // Đặt tên file mới
            $filename = time() . '_' . $request->image->getClientOriginalName();

            // Di chuyển ảnh vào thư mục public/images/intros
            $request->image->move(public_path('images/intros'), $filename);

            // Lưu tên file vào dữ liệu
            $data['image'] = $filename;
        }

        $structure->update($data);

        return redirect()->route('about.index')->with('success', 'Cập nhật thành viên thành công!');
    }

    public function destroy_structure($id)
    {
        $structure = \App\Models\Structure::findOrFail($id);

        // Optional: Nếu có ảnh, xóa luôn file khỏi storage (nếu cần)
        if ($structure->image) {
            //\Storage::disk('public')->delete($structure->image);
            $imagePath = public_path('images/intros/' . $structure->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $structure->delete();

        return redirect()->route('about.index')->with('success', 'Xoá thành viên thành công!');
    }
    //End structures

    //Achievements
    /**
     * Show the form for creating a new resource.
     */
    public function create_achievement($introId)
    {
        $intro = Intro::findOrFail($introId);
        return view('about.create_achievement', compact('intro'));
    }

    public function store_achievement(Request $request)
    {
        $request->validate([
            'intro_id' => 'required|exists:intros,id',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thoigian' => 'required|string',
        ]);

        Achievement::create([
            'intro_id' => $request->intro_id,
            'type' => $request->type,
            'description' => $request->description,
            'thoigian' => $request->thoigian,
        ]);

        return redirect()->route('about.index')->with('success', 'Thành tựu đã được thêm.');
    }
}
