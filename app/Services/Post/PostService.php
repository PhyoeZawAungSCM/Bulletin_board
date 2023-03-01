<?php

namespace App\Services\Post;

use App\Models\Posts;
use Illuminate\Http\Request;
use App\Contracts\Services\Post\PostServiceInterface;
use App\Contracts\Dao\Post\PostDaoInterface;
use Carbon\Carbon;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 *System Name: Bulletin board
 *Module Name: PostService
 */
class PostService implements PostServiceInterface
{
	/**
	 * postDao
	 */
	private $postDao;

	/**
	 * @param  PostDaoInterface $postDao
	 * @return null
	 */
	public function __construct(PostDaoInterface $postDao)
	{
		$this->postDao = $postDao;
	}

	/**
	 * to get the posts
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		try {
			return $this->postDao->index($request);
		} catch (Throwable $th) {
			return response()->json([
				'message' => 'some error occur',
				'error'   => $th->getMessage()
			], 500);
		}
	}

	/**
	 * to get a specific post
	 * @param Posts $post
	 * @return Response
	 */
	public function show(Posts $post)
	{
		return $this->postDao->show($post);
	}

	/**
	 * to store a post
	 * @param Array $validated
	 * @return Response
	 */
	public function store($validated)
	{
		try {
			$post = $this->postDao->store($validated);
			return response()->json([
				'message' => 'Post create success',
				'data'    => $post
			]);
		} catch (QueryException $e) {
			$errorCode = $e->errorInfo[1];
			if ($errorCode == 1062) {
				return response()->json([
					'message' => 'Post already exist'
				], 422);
			}
		} catch (Throwable $th) {
			return response()->json([
				'message' => 'Some Error Occur',
				'error'   => $th->getMessage(),
			], 500);
		}
	}

	/**
	 * to update a specific post
	 * @param Array $validated
	 * @param Posts $post
	 * @return Response
	 *
	 */
	public function update($validated, $post)
	{
		try {
			$post = $this->postDao->update($validated, $post);
			return response()->json([
				'message' => 'Post Update Success',
				'data'    => $post
			], 200);
		} catch (Throwable $th) {
			return response()->json([
				'message' => 'Some error occur in updating',
				'error'   => $th->getMessage()
			]);
		}
	}

	/**
	 * to delete a post
	 * @param Posts $post
	 * @return Response
	 */
	public function destroy($post)
	{
		try {
			$post = $this->postDao->destroy($post);
			return response()->json([
				'message' => 'Post delete success',
				'data'    => $post
			], 200);
		} catch (Throwable $th) {
			return response()->json([
				'message' => 'Some error occur in deleting',
				'error'   => $th->getMessage()
			], 500);
		}
	}

	/**
	 * to upload csv file
	 *@param Request $request
	 * @return Response
	 */
	public function uploadCsv(Request $request)
	{
		// get the real extension of the file because the file may stores as .txt in storage
		$extenstion = $request->file('csv')->getClientOriginalExtension();

		// make unique id for file name and set the extenstion
		$filename = uniqid() . '.' . $extenstion;

		try {
			// store the file in starage
			$Storefile = $request->file('csv')->storeAs('Uploaded_csv', $filename);

			// getting the path of the file
			$path = Storage::path($Storefile);

			//open and read the file
			$file = fopen($path, 'r');

			// temporary array for storing csv data
			$temp = [];

			// read through before end of the file
			while (!feof($file)) {
				$data = fgetcsv($file, 1000, ',');
				if (!empty($data)) {
					array_push($temp, [
						'title'       => $data[0],
						'description' => $data[1],
						'status'      => $data[2],
					]);
				}
			}
			// close the file
			fclose($file);
			// delete the file
			///Storage::delete('Uploaded_csv/'.$filename);

			$tempPosts = [];

			DB::beginTransaction();
			// loop through all the upload data
			for ($i = 1; $i < count($temp); $i++) {
				try {
					$post = $this->postDao->uploadCsv($temp[$i]);
					array_push($tempPosts, $post);
				} catch (QueryException $e) {
					DB::rollBack();
					return response()
						->json([
							'message' => $temp[$i]['title'] . ' already exists',
							'error'   => $e
						], 400);
				}
			}
			DB::commit();
			return response()->json([
				'message' => 'created all posts',
				'data'    => $tempPosts
			], 200);
		} catch (Throwable $th) {
			return response()->json([
				'message' => 'Some error occur',
				'error'   => $th->getMessage()
			], 500);
		}
	}

	/**
	 * to down posts as csv file
	 * @return Response
	 */
	public function downloadCsv()
	{
		// getting the auth user
		$user = Auth::user();
		// getting all of the posts list
		$posts = $this->postDao->downloadCsv();
		// get the path of file DownloadCsv
		$storedpath = Storage::path('DownloadCsv');
		// create file name with user name and unique id
		$filename = $user->name . uniqid() . '.' . 'csv';
		// get the file path that need to store in storage
		$filepath = $storedpath . '/' . $filename;
		// fopen create the file and open it
		$file = fopen($filepath, 'w');
		// puts csv title on the created file
		fputcsv($file, [
			'id',
			'title',
			'description',
			'status',
			'create_user_id',
			'updated_user_id',
			'deleted_user_id',
			'deleted-at',
			'updated_at',
			'created_at'
		], ',');

		// loop through all the file and store in file
		foreach ($posts as $post) {
			fputcsv($file, [
				$post->id,
				$post->title,
				$post->description,
				$post->status,
				$post->create_user_id,
				$post->updated_user_id,
				$post->deleted_user_id,
				$this->formatDate($post->deleted_at),
				$this->formatDate($post->updated_at),
				$this->formatDate($post->created_at)
			], ',');
		}
		// close the file
		fclose($file);
		// retrun
		return Storage::download('DownloadCsv/' . $filename);
	}

	/**
	 * Change the date format to (y/m/d)
	 * @param Date $date
	 * @return Date $date
	 *
	 */
	private function formatDate($date)
	{
		if ($date == null) {
			return $date;
		}
		return Carbon::create($date)->format('Y/m/d');
	}
}
