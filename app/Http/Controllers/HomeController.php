<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use Faker\Factory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show all Users with their address.
     *
     * @return void
     */
    public function users()
    {
        $users = User::with('address', 'skills', 'posts')->get();

        $users->each(function($user) {
            echo '<pre>'; 
            print_r('Name: '.$user->name .' -> Country: '.($user->address->country ?? ''));
            echo '</pre>';
            $user->skills->each(function($skill) {
                echo '<pre>'; 
                print_r('Skill: '.$skill->skill);
                echo '</pre>';
            });
            $user->posts->each(function($post) {
                echo '<pre>'; 
                print_r('Post Title: '.$post->title);
                print_r("\nPost Description: ".$post->description);
                echo '</pre>';
            });
            echo '<pre>'; 
            echo '------------------------';
            echo '</pre>';
        });

        return request()->json(200, $users);
    }

    /**
     * Show all Addresses with their associated user.
     *
     * @return json
     */
    public function addresses()
    {
        $addresses = Address::with('owner')->get();
    
        $addresses->each(function($address) {
            echo '<pre>'; 
            print_r('Country: '.$address->country .' -> User: '.($address->owner->name ?? ''));
            echo '</pre>';
        });

        return request()->json(200, $addresses);
    }

    /**
     * Find a user and create an address for that user.
     *
     * @param int $user_id
     * @return json
     */
    public function findUserAndCreateAddress($user_id)
    {
        $faker = Factory::create();
        $user = \App\Models\User::findOrFail($user_id);
        $user->address()->create([
            'country' => $faker->country,
        ]);

        return request()->json(200, $user->address);
    }

    /**
     * Create a user and associate an address with that user.
     *
     * @return json
     */
    public function createUserAndAssociateAddress()
    {
        $faker = Factory::create();
        $user = \App\Models\User::factory(1)->create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $faker->password,
        ])->first();

        $address = new \App\Models\Address([
            'country' => $faker->country,
        ]);

        $address->owner()->associate($user);
        $address->save();

        return request()->json(200, $address->owner);
    }

    /**
     * Show all skills with their associated user.
     *
     * @return json
     */
    public function skills()
    {
        $skills = \App\Models\Skill::with('owner')->get();
    
        $skills->each(function($skill) {
            echo '<pre>'; 
            print_r('Skill: '.$skill->skill .' -> User: '.($skill->owner->name ?? ''));
            echo '</pre>';
        });

        return request()->json(200, $skills);
    }

    /**
     * Show all posts with their associated user.
     *
     * @return json
     */
    public function posts()
    {
        $posts = \App\Models\Post::with(['user', 'tags'])->get();
    
        $posts->each(function($post) {
            echo '<pre>'; 
            print_r('Post: '.$post->title .' -> User: '.($post->user->name ?? ''));
            echo '</pre>';
            // $post->tags->each(function($tag) {
            //     echo '<pre>'; 
            //     print_r('Tag: '.$tag->name.' / Status: '.$tag->pivot->status.' / Created: '.$tag->pivot->created_at.' / Updated: '.$tag->pivot->updated_at);
            //     echo '</pre>';
            // });
            $post->tags->each(function($tag) {
                echo '<pre>'; 
                print_r('Tag: '.$tag->name);
                echo '</pre>';
            });
            echo '<pre>';
            echo '------------------------';
            echo '</pre>';
        });

        return request()->json(200, $posts);
    }

    /**
     * Show all posts with their associated user.
     *
     * @return json
     */
    public function videos()
    {
        $videos = \App\Models\Video::query()->get();
    
        $videos->each(function($video) {
            echo '<pre>';
            print_r('Video: '.$video->title);
            echo '</pre>';
            // $post->tags->each(function($tag) {
            //     echo '<pre>'; 
            //     print_r('Tag: '.$tag->name.' / Status: '.$tag->pivot->status.' / Created: '.$tag->pivot->created_at.' / Updated: '.$tag->pivot->updated_at);
            //     echo '</pre>';
            // });
            $video->tags->each(function($tag) {
                echo '<pre>'; 
                print_r('Tag: '.$tag->name);
                echo '</pre>';
            });
            echo '<pre>';
            echo '------------------------';
            echo '</pre>';
        });

        return request()->json(200, $videos);
    }

    public function postTagAttachDetachSync($type)
    {
        $post = \App\Models\Post::first();

        $tag = \App\Models\Tag::first();

        if($type == 'detach') {
            $post->tags()->$type([
                $tag->id
            ]);
        } elseif($type == 'attach') {
            $post->tags()->$type([
                $tag->id => [
                    'status' => 'active',
                ]
            ]);
        } elseif($type == 'sync') {
            $post->tags()->$type([
                3 => [
                    'status' => 'active',
                ], 
                2 => [
                    'status' => 'inactive',
                ]
            ]);
        }

        // $post->tags()->attach($tag);
        // $post->tags()->attach([2, 3, 4]);

        return request()->json(200, $post->tags);
    }

    public function tags()
    {
        $tags = \App\Models\Tag::query()->get();
    
        $tags->each(function($tag) {
            echo '<pre>'; 
            print_r('Tag: '.$tag->name);
            echo '</pre>';
            $tag->posts->each(function($post) {
                echo '<pre>'; 
                print_r('Post: '.$post->title);
                echo '</pre>';
            });
            $tag->videos->each(function($post) {
                echo '<pre>'; 
                print_r('Video: '.$post->title);
                echo '</pre>';
            });
            echo '<pre>';
            echo '------------------------';
            echo '</pre>';
        });

        return request()->json(200, $tags);
    }

    public function projects()
    {
        $projects = \App\Models\Project::whereHas('users')->with('users')->get();
    
        $projects->each(function($project) {
            echo '<pre>'; 
            print_r('Project: '.$project->title);
            echo '</pre>';
            $project->users->each(function($user) {
                echo '<pre>'; 
                print_r('User: '.$user->name);
                echo '</pre>';
            });
            $project->tasks->each(function($task) {
                echo '<pre>'; 
                print_r('HasManyThrough Task: '.$task->title);
                echo '</pre>';
            });
            echo '<pre>'; 
            print_r('HasOneThrough Task: '.$project->task->title);
            echo '</pre>';
            echo '<pre>';
            echo '------------------------';
            echo '</pre>';
        });

        return request()->json(200, $projects);
    }

    public function postComments()
    {
        $posts = Post::all();

        $posts->each(function($post) {
            echo '<pre>'; 
            print_r('Post: '.$post->title);
            echo '</pre>';
            $comments = $post->comments()->get();
        
            $comments->each(function($comment) {
                echo '<pre>'; 
                print_r('Comment: '.$comment->body);
                echo '</pre>';
                // echo '<pre>'; 
                // print_r('User: '.$comment->user->name);
                // echo '</pre>';
                // echo '<pre>'; 
                // print_r('Post: '.$comment->post->title);
                // echo '</pre>';
                echo '<pre>';
                echo '------------------------';
                echo '</pre>';
            });
        });

        return request()->json(200, $posts);
    }

    public function videoComments()
    {
        $videos = Video::all();

        $videos->each(function($video) {
            echo '<pre>'; 
            print_r('Video: '.$video->title);
            echo '</pre>';
            $comments = $video->comments()->get();
        
            $comments->each(function($comment) {
                echo '<pre>'; 
                print_r('Comment: '.$comment->body);
                echo '</pre>';
                // echo '<pre>'; 
                // print_r('User: '.$comment->user->name);
                // echo '</pre>';
                // echo '<pre>'; 
                // print_r('Post: '.$comment->post->title);
                // echo '</pre>';
                echo '<pre>';
                echo '------------------------';
                echo '</pre>';
            });
        });

        return request()->json(200, $videos);
    }

    public function comments()
    {
        $comments = Comment::all();

        $comments->each(function($comment) {
            echo '<pre>'; 
            print_r('Comment: '.$comment->body);
            echo '</pre>';
            echo '<pre>'; 
            print_r('User: '.$comment->user->name);
            echo '</pre>';
            echo '<pre>'; 
            print_r($comment->commentable_type.': '.$comment->commentable->title);
            echo '</pre>';
            echo '<pre>';
            echo '------------------------';
            echo '</pre>';
        });

        return request()->json(200, $comments);
    }
}
