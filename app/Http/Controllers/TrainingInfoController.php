<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithPersonalInfo;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingInfoController extends Controller
{
    use InteractsWithPersonalInfo;

    public function index(Request $request): View
    {
        $currentUser = $request->user();
        $canManageProfiles = $this->userCanManageProfiles($currentUser);

        if ($canManageProfiles && ! $request->filled('user')) {
            $personalInfos = PersonalInfo::with(['user:id,name'])
                ->withCount('trainingRecords')
                ->orderByDesc('updated_at')
                ->orderBy('full_name')
                ->get();

            return view('scientific_profiles.index', [
                'personalInfos' => $personalInfos,
                'currentUser' => $currentUser,
                'mode' => 'training',
            ]);
        }

        $targetUser = $currentUser;

        if ($request->filled('user')) {
            $requestedUserId = (int) $request->query('user');

            if (! $canManageProfiles && $requestedUserId !== $currentUser->getKey()) {
                abort(403);
            }

            $targetUser = User::findOrFail($requestedUserId);
        }

        $info = $this->ensureInfo($targetUser);
        $info->load([
            'trainingRecords' => function ($query) {
                $query->orderBy('category')->orderBy('position')->orderBy('id');
            },
        ]);

        return view('scientific_profiles.training', [
            'info' => $info,
            'canEdit' => $targetUser->is($currentUser),
            'canManageProfiles' => $canManageProfiles,
            'targetUser' => $targetUser,
        ]);
    }
}
