<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithPersonalInfo;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeachingInfoController extends Controller
{
    use InteractsWithPersonalInfo;

    public function index(Request $request): View
    {
        $currentUser = $request->user();
        $canManageProfiles = $this->userCanManageProfiles($currentUser);

        if ($canManageProfiles && ! $request->filled('user')) {
            $personalInfos = PersonalInfo::with(['user:id,name'])
                ->withCount(['teachingActivities', 'supervisionActivities'])
                ->orderByDesc('updated_at')
                ->orderBy('full_name')
                ->get();

            return view('scientific_profiles.index', [
                'personalInfos' => $personalInfos,
                'currentUser' => $currentUser,
                'mode' => 'teaching',
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
            'teachingActivities' => fn ($query) => $query->orderBy('position')->orderBy('id'),
            'supervisionActivities' => fn ($query) => $query->orderBy('position')->orderBy('id'),
        ]);

        return view('scientific_profiles.teaching', [
            'info' => $info,
            'canEdit' => $targetUser->is($currentUser),
            'canManageProfiles' => $canManageProfiles,
            'targetUser' => $targetUser,
        ]);
    }

    public function edit(Request $request): View
    {
        $info = $this->ensureInfo($request->user());
        $info->load([
            'teachingActivities' => fn ($query) => $query->orderBy('position')->orderBy('id'),
            'supervisionActivities' => fn ($query) => $query->orderBy('position')->orderBy('id'),
        ]);

        return view('scientific_profiles.edit_teaching', [
            'info' => $info,
        ]);
    }
}
