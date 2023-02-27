<?php

namespace App\Dao\Post;

use App\Contracts\Dao\Post\PostDaoInterface;
use App\Models\Posts;
use Illuminate\Support\Facades\Auth;

class PostDao implements PostDaoInterface
{
    public function index()
    {
        // if the user is login or not
        if (Auth::check()) {
            $user = Auth::user();
            // if the use is admin and return all posts
            if ($user->type == '0') {
                return Posts::join('users', 'posts.create_user_id', '=', 'users.id')
                    ->when(request('search'), function ($query) {
                        $query->where('posts.title', 'like', '%' . request('search') . '%')
                            ->orWhere('posts.description', 'like', '%' . request('search') . '%');
                    })->orderby('id', 'desc')->paginate(5, ['posts.id', 'posts.title', 'posts.description', 'posts.created_at', 'posts.status', 'users.name']);
                // if the user is a normal user just returning a user's created posts
            } else if ($user->type == '1') {
                return Posts::join('users', 'posts.create_user_id', '=', 'users.id')
                    ->where('posts.create_user_id', $user->id)
                    ->when(request('search'), function ($query) {
                        $query->where('posts.title', 'like', '%' . request('search') . '%')
                            ->orWhere('posts.description', 'like', '%' . request('search') . '%');
                    })->orderby('id', 'desc')->paginate(5, ['posts.id', 'posts.title', 'posts.description', 'posts.created_at', 'posts.status', 'users.name']);
            }
            // if the user is not login / return a post that is active 
        } else {
            return Posts::join('users', 'users.id', '=', 'posts.create_user_id')
                ->where('status', 1)
                ->where(function ($q) {
                    $q->when(request('search'), function ($query) {
                        $query->where('posts.title', 'like', '%' . request('search') . '%')
                            ->orWhere('posts.description', 'like', '%' . request('search') . '%');
                    });
                })
                ->paginate(
                    5,
                    ['posts.id', 'posts.title', 'posts.description', 'posts.created_at', 'posts.status', 'users.name']
                );
        }
    }
    public function show(Posts $post)
    {
        return $post;
    }
    public function store($validated)
    {
        $post = Posts::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'create_user_id' => $validated['create_user_id'],
            'updated_user_id' => $validated['updated_user_id'],
        ]);

        return $post;
    }

    public function update($validated, $post)
    {
        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'updated_user_id' => Auth::id()
        ]);

        return $post;
    }

    public function destroy($post)
    {
        $post->update(['deleted_user_id' => Auth::id()]);
        $post->delete();

        return $post;
    }

    public function uploadCsv($post)
    {
        $post = Posts::create([
            'title' => $post['title'],
            'description' => $post['description'],
            'status' => $post['status'],
            'create_user_id' => Auth::id(),
            'updated_user_id' => Auth::id(),
        ]);

        return $post;
    }

    public function downloadCsv()
    {
        $user = Auth::user();
        if ($user->type == '0') {
            return $posts = Posts::withTrashed()->get();
        } else {
            return $posts = Posts::where('create_user_id', $user->id)->withTrashed()->get();
        }
    }
}
