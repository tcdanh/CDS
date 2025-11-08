<?php

use App\Http\Controllers\FamilyInfoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompensationInfoController;
use App\Http\Controllers\PersonalHistoryController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\TrainingInfoController;
use App\Http\Controllers\PlanningInfoController;
use App\Http\Controllers\WorkInfoController;
use App\Http\Controllers\RecognitionInfoController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDetailController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\IntroController;
use App\Http\Controllers\Admin\AdminConfigController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/**Route::get('/', function () {
    return view('welcome');
}); **/
/**Route::get('/', [HomeController::class, 'index'])->name('home'); **/
/**Route::middleware('guest')->get('/', [AuthenticatedSessionController::class, 'create'])->name('home');**/
Route::redirect('/', '/cds/public/login')->name('home');



/**Route::get('/dashboard', function () { 
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); **/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/scientific-profile', [PersonalInfoController::class, 'show'])->name('scientific-profiles.show');

    Route::get('/scientific-profile/family', [FamilyInfoController::class, 'index'])->name('scientific-profiles.family');

    Route::get('/scientific-profile/history', [PersonalHistoryController::class, 'index'])->name('scientific-profiles.history');

    Route::get('/scientific-profile/training', [TrainingInfoController::class, 'index'])->name('scientific-profiles.training');
    Route::get('/scientific-profile/work', [WorkInfoController::class, 'index'])->name('scientific-profiles.work');
    Route::get('/scientific-profile/planning', [PlanningInfoController::class, 'index'])->name('scientific-profiles.planning');
    Route::get('/scientific-profile/compensation', [CompensationInfoController::class, 'index'])->name('scientific-profiles.compensation');
    Route::get('/scientific-profile/compensation/edit', [CompensationInfoController::class, 'edit'])->name('scientific-profiles.compensation.edit');
    Route::get('/scientific-profile/recognition', [RecognitionInfoController::class, 'index'])->name('scientific-profiles.recognition');
    Route::get('/scientific-profile/recognition/edit', [RecognitionInfoController::class, 'edit'])->name('scientific-profiles.recognition.edit');
    Route::get('/scientific-profile/edit', [PersonalInfoController::class, 'edit'])->name('scientific-profiles.edit');
    Route::put('/scientific-profile', [PersonalInfoController::class, 'update'])->name('scientific-profiles.update');

    Route::resource('project-management', ProjectController::class)
        ->parameters(['project-management' => 'project'])
        ->except(['show']);

    Route::get('project-management/{project}', [ProjectDetailController::class, 'show'])
        ->name('project-management.show');

    Route::get('project-management/{project}/detail/create', [ProjectDetailController::class, 'create'])
        ->name('project-details.create');
    Route::post('project-management/{project}/detail', [ProjectDetailController::class, 'store'])
        ->name('project-details.store');
    Route::get('project-management/{project}/detail/edit', [ProjectDetailController::class, 'edit'])
        ->name('project-details.edit');
    Route::put('project-management/{project}/detail', [ProjectDetailController::class, 'update'])
        ->name('project-details.update');
    Route::get('project-management/{project}/detail/download', [ProjectDetailController::class, 'download'])
        ->name('project-details.download');
});

Route::resource('banner_article', App\Http\Controllers\BannerArticleController::class)->middleware('auth');
Route::get('banner_article/{id}/confirm-delete', [BannerArticleController::class, 'confirmDelete'])->name('banner_article.confirmDelete');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('news', NewsController::class)->names('admin.news');
});
Route::get('/news/{slug}', [NewsController::class, 'showPublic'])->name('news.public');

//Admin settings
Route::prefix('admin_setting')->middleware(['auth', 'admin_setting'])->group(function () {
    Route::get('/index', [AdminConfigController::class, 'index'])->name('admin_setting.index');
    Route::put('/update-user/{id}', [AdminConfigController::class, 'updateUser'])->name('admin_setting.update_user');
    
});


Route::middleware(['auth'])->prefix('about')->group(function () {
    Route::resource('intros', IntroController::class)->names('about');
    //Route thêm cấu trúc gắn với intro_id
    Route::get('intros/{intro}/create-structure', [IntroController::class, 'create_structure'])
    ->name('about.structures.create');
    // Route lưu structure mới
    Route::post('structures/store', [IntroController::class, 'store_structure'])->name('about.structures.store');
    // Sửa structure
    Route::get('structures/{structure}/edit', [IntroController::class, 'edit_structure'])->name('about.structures.edit');
    Route::put('structures/{structure}', [IntroController::class, 'update_structure'])->name('about.structures.update');
    // Route xóa structure
    Route::delete('structures/{structure}', [IntroController::class, 'destroy_structure'])->name('about.structures.destroy');
    // Route tạo và lưu Achievement (nằm trong IntroController)
    Route::get('intros/{intro}/create-achievement', [IntroController::class, 'create_achievement'])
    ->name('about.achievements.create');
    Route::post('achievements/store', [IntroController::class, 'store_achievement'])
    ->name('about.achievements.store');
});
Route::get('/about', [IntroController::class, 'index_front'])->name('about.index_front');



require __DIR__.'/auth.php';
