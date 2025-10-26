<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePersonalInfoRequest;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PersonalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): View
    {
        $info = $this->ensureInfo($request->user());

        return view('scientific_profiles.show', [
            'info' => $info,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): View
    {
        $info = $this->ensureInfo($request->user());

        return view('scientific_profiles.edit', [
            'info' => $info,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonalInfoRequest $request): RedirectResponse
    {
        $info = $this->ensureInfo($request->user());
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($info->avatar_path && file_exists(public_path($info->avatar_path))) {
                unlink(public_path($info->avatar_path));
            }

            // Tạo thư mục nếu chưa có
            if (!file_exists(public_path('uploads/avatars'))) {
                mkdir(public_path('uploads/avatars'), 0777, true);
            }

            // Lưu file mới
            $file = $request->file('avatar');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $fileName);

            // Lưu đường dẫn vào DB
            $data['avatar_path'] = 'uploads/avatars/' . $fileName;
        }

        unset($data['avatar']);

        $info->fill($data);
        $info->save();

        return redirect()
            ->route('scientific-profiles.show')
            ->with('status', 'personal-info-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    protected function ensureInfo(User $user): PersonalInfo
    {
        return $user->personalInfo()->firstOrCreate([], [
            'full_name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
