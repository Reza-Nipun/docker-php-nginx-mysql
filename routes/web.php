<?php

use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/users', [App\Http\Controllers\HomeController::class, 'users'])->name('users');
Route::get('/addresses', [App\Http\Controllers\HomeController::class, 'addresses'])->name('addresses');
Route::get('/find_user_and_create_address/{user_id}', [App\Http\Controllers\HomeController::class, 'findUserAndCreateAddress'])->name('find_user_and_create_address');
Route::get('/create_user_associate_with_address', [App\Http\Controllers\HomeController::class, 'createUserAndAssociateAddress'])->name('create_user_associate_with_address');
Route::get('/skills', [App\Http\Controllers\HomeController::class, 'skills'])->name('skills');
Route::get('/posts', [App\Http\Controllers\HomeController::class, 'posts'])->name('posts');
Route::get('/videos', [App\Http\Controllers\HomeController::class, 'videos'])->name('videos');
Route::get('/post_tag/{type}', [App\Http\Controllers\HomeController::class, 'postTagAttachDetachSync'])->name('post_tag_attach_detach_sync');
Route::get('/tags', [App\Http\Controllers\HomeController::class, 'tags'])->name('tags');
Route::get('/projects', [App\Http\Controllers\HomeController::class, 'projects'])->name('projects');
Route::get('/posts_comments', [App\Http\Controllers\HomeController::class, 'postComments'])->name('posts_comments');
Route::get('/videos_comments', [App\Http\Controllers\HomeController::class, 'videoComments'])->name('videos_comments');
Route::get('/comments', [App\Http\Controllers\HomeController::class, 'comments'])->name('comments');

Route::get('/create_video', function() {
    $faker = Factory::create();
    $user = User::find(1);

    $video = $user->videos()->create([
        'title' => $faker->sentence,
    ]);

    $video->tags()->create([
        'name' => $faker->word,
    ]);

    $tag = Tag::find(1);

    $video->tags()->attach($tag->id);

    return 'Video created successfully';
});

