<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\IntroController;
use App\Http\Controllers\Admin\AdminConfigController;
/**use App\Http\Controllers\Auth\AuthenticatedSessionController;**/

/**Route::get('/', function () {
    return view('welcome');
}); **/
Route::get('/', [HomeController::class, 'index'])->name('home'); 
/**Route::middleware('guest')->get('/', [AuthenticatedSessionController::class, 'create'])->name('home');**/
/**Route::redirect('/', '/login')->name('home');**/



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
