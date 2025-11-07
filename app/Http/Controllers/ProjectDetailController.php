<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectDetailController extends Controller
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
    public function create(Project $project)
    {
        if ($project->detail) {
            return redirect()
                ->route('project-details.edit', $project)
                ->with('info', 'Thông tin chi tiết đã tồn tại, vui lòng cập nhật.');
        }

        return view('project_management.detail.create', compact('project'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        if ($project->detail) {
            return redirect()
                ->route('project-details.edit', $project)
                ->with('info', 'Thông tin chi tiết đã tồn tại, vui lòng cập nhật.');
        }
        
        $data = $this->validateDetail($request);
        
        if ($request->hasFile('contract_storage_file')) {
            $path = $request->file('contract_storage_file')->store('HD_projects', 'public');

            if (! $path) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['contract_storage_file' => 'Không thể tải tệp lên, vui lòng thử lại.']);
            }

            $data['contract_storage_path'] = $path;
        }

        $project->detail()->create($data);

        return redirect()
            ->route('project-management.show', $project)
            ->with('success', 'Đã thêm thông tin hợp đồng & tài chính.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        $project->load(['principalInvestigator', 'detail']);

        return view('project_management.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $project->load('detail');

        if (! $project->detail) {
            return redirect()
                ->route('project-details.create', $project)
                ->with('info', 'Chưa có thông tin chi tiết, vui lòng thêm mới.');
        }

        return view('project_management.detail.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validateDetail($request);

        if ($request->hasFile('contract_storage_file')) {
            /** if ($project->detail && $project->detail->contract_storage_path) {
                Storage::disk('public')->delete($project->detail->contract_storage_path);
            }

            $path = $request->file('contract_storage_file')->store('HD_projects', 'public');

            if (! $path) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['contract_storage_file' => 'Không thể tải tệp lên, vui lòng thử lại.']);
            }

            $data['contract_storage_path'] = $path; */
            // Xóa file cũ (nếu có) trong public/uploads/Hopdong
            if ($project->detail && $project->detail->contract_storage_path) {
                $oldPath = public_path($project->detail->contract_storage_path);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('contract_storage_file');

            // Tạo tên file duy nhất
            $filename = time() . '_' . $file->getClientOriginalName();

            // Thư mục đích: public/uploads/Hopdong
            $destination = public_path('uploads/hopdong');

            // Đảm bảo thư mục tồn tại
            if (! is_dir($destination)) {
                mkdir($destination, 0775, true);
            }

            // Di chuyển file
            $file->move($destination, $filename);

            // Lưu đường dẫn TƯƠNG ĐỐI để dùng trong DB & view
            $data['contract_storage_path'] = 'uploads/hopdong/' . $filename;
        }

        $project->detail()->updateOrCreate(
            ['project_id' => $project->id],
            $data
        );

        return redirect()
            ->route('project-management.show', $project)
            ->with('success', 'Đã cập nhật thông tin hợp đồng & tài chính.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function download(Project $project)
    {
        $detail = $project->detail;

        if (! $detail || ! $detail->contract_storage_path || ! Storage::disk('public')->exists($detail->contract_storage_path)) {
            return redirect()
                ->route('project-management.show', $project)
                ->with('info', 'File hợp đồng không tồn tại.');
        }

        return Storage::disk('public')->download(
            $detail->contract_storage_path,
            basename($detail->contract_storage_path)
        );
    }

    private function validateDetail(Request $request): array
    {
        $currencyFields = [
            'direct_labor_cost',
            'material_cost',
            'other_cost',
            'management_cost',
        ];

        foreach ($currencyFields as $field) {
            if ($request->filled($field)) {
                $request->merge([
                    $field => str_replace(',', '', $request->input($field)),
                ]);
            }
        }

        $data = $request->validate([
            'contract_number' => ['nullable', 'string', 'max:255'],
            'contract_signed_at' => ['nullable', 'date'],
            'contract_storage_file' => ['nullable', 'file', 'max:6204800'],
            'direct_labor_cost' => ['nullable', 'numeric', 'min:0'],
            'material_cost' => ['nullable', 'numeric', 'min:0'],
            'other_cost' => ['nullable', 'numeric', 'min:0'],
            'management_cost' => ['nullable', 'numeric', 'min:0'],
            'is_extended' => ['required', 'boolean'],
            'extension_details' => ['nullable', 'string'],
        ]);

        unset($data['contract_storage_file']);

        return $data;
    }
}
