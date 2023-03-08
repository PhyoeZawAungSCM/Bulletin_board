<?php

namespace App\Dao\Post;

use App\Contracts\Dao\Post\PostDaoInterface;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * System Name : Bulletin Board
 * Module Name : PostDao
 */
class PostDao implements PostDaoInterface
{
	/**
	 * to get all the post based on type
	 * @param Request $request
	 * @return Posts $posts
	 */
	public function index(Request $request)
	{
		$token = $request->bearerToken();
		$user = auth('sanctum')->user();
		// can't use this
		//$user = Auth::user();
		// if the user is login or not
		if ($user && $token != null) {
			// if the use is admin and return all posts
			if ($user->type == '0') {
				return Posts::join('users', 'posts.create_user_id', '=', 'users.id')
							->when(request('search'), function ($query) {
								$query->where('posts.title', 'like', '%' . request('search') . '%')
						->orWhere('posts.description', 'like', '%' . request('search') . '%');
							})->orderby('id', 'desc')
							->paginate(config('constant.paginatePage'), ['posts.id', 'posts.title', 'posts.description', 'posts.created_at', 'posts.status', 'users.name']);
			// if the user is a normal user just returning a user's created posts
			} elseif ($user->type == '1') {
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
					config('constant.paginatePage'),
					['posts.id', 'posts.title', 'posts.description', 'posts.created_at', 'posts.status', 'users.name']
				);
		}
	}

	/**
	 * to get a post
	 * @param Posts $post
	 * @return Posts $posts
	 */
	public function show(Posts $post)
	{
		return $post;
	}

	/**
	 * to create a post
	 * @param Array $validated
	 * @return Posts $post
	 */
	public function store($validated)
	{
		$post = Posts::create([
			'title'           => $validated['title'],
			'description'     => $validated['description'],
			'create_user_id'  => $validated['create_user_id'],
			'updated_user_id' => $validated['updated_user_id'],
		]);

		return $post;
	}

	/**
	 * to update a post
	 * @param Array $validated
	 * @param Posts $post
	 * @return Posts $post
	 */
	public function update($validated, $post)
	{
		$post->update([
			'title'           => $validated['title'],
			'description'     => $validated['description'],
			'status'          => $validated['status'],
			'updated_user_id' => Auth::id()
		]);

		return $post;
	}

	/**
	 * to delete a post
	 * @param Posts $post
	 * @return Posts $post
	 */
	public function destroy($post)
	{
		$post->update(['deleted_user_id' => Auth::id()]);
		$post->delete();

		return $post;
	}

	/**
	 * to upload csv file
	 * @param Posts $post
	 * @return Posts $post
	 */
	public function uploadCsv($post)
	{
		$post = Posts::create([
			'title'           => $post['title'],
			'description'     => $post['description'],
			'status'          => $post['status'],
			'create_user_id'  => Auth::id(),
			'updated_user_id' => Auth::id(),
		]);

		return $post;
	}

	/**
	 * to download csv file
	 * @return Posts $posts
	 */
	public function downloadCsv()
	{
		$user = Auth::user();
		if ($user->type == '0') {
			// $results = Posts::withTrashed()->get();
			// foreach ($results as $result) {
			// 	Log::info(print_r($result, true));
			// }

			return Posts::withTrashed()->get();
		} else {
			return Posts::where('create_user_id', $user->id)->withTrashed()->get();
		}
	}
}
