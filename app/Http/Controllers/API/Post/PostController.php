<?php

namespace App\Http\Controllers\API\Post;

use App\Contracts\Services\Post\PostServiceInterface;
use App\Models\Posts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * System Name : Bulletin Board
 * Module Name : PostController
 */
class PostController extends Controller
{
	/**
	 * post interface
	 */
	private $postInterface;

	public function __construct(PostServiceInterface $postServiceInterface)
	{
		$this->postInterface = $postServiceInterface;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		return response()->json($this->postInterface->index($request));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'title'           => 'required',
			'description'     => 'required',
			'create_user_id'  => 'required',
			'updated_user_id' => 'required',
		]);
		return $this->postInterface->store($validated);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Posts $post)
	{
		return $this->postInterface->show($post);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Posts $post)
	{
		$validated = $request->validate([
			'title'       => 'required|max:255',
			'description' => 'required',
			'status'      => 'required'
		]);
		return $this->postInterface->update($validated, $post);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Posts $post)
	{
		return $this->postInterface->destroy($post);

		$post->delete();
		return response()->json([
			'message' => 'Post delete success'
		], 200);
	}

	/**
	 * upload csv file to the database
	 * @param Request $request contain a csv file
	 * @return Posts $posts
	 */
	public function uploadCsv(Request $request)
	{
		$request->validate([
			'csv' => 'required|mimes:csv,txt',
		]);
		return $this->postInterface->uploadCsv($request);
	}

	/**
	 * download csv file
	 * @return File $csvfile
	 */
	public function downloadCsv()
	{
		return $this->postInterface->downloadCsv();
	}
}
