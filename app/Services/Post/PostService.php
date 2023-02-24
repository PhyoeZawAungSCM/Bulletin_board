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

class PostService implements PostServiceInterface
{
    /**
     * postDao
     */
    private $postDao;

    /**
     * @param interface PostDaoInterface
     * @return null
     */
    public function __construct(PostDaoInterface $postDao)
    {
        $this->postDao = $postDao;
    }
    public function index()
    {
        try {
            return $this->postDao->index();
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'some error occur',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function show(Posts $post)
    {
        return $this->postDao->show($post);
    }
    public function store($validated)
    {
        try {
            $post = $this->postDao->store($validated);
            return response()->json([
                'message' => 'Post create success',
                'data' => $post
            ]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json([
                    'message' => "Post already exist"
                ], 422);
            }
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Some Error Occur',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function update($validated, $post)
    {
        try {
            $post = $this->postDao->update($validated, $post);
            return response()->json([
                "message" => "Post Update Success",
                "data" => $post
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                "message" => 'Some error occur in updating',
                'error' => $th->getMessage()
            ]);
        }
    }
    public function destroy($post)
    {
        try {
            $post = $this->postDao->destroy($post);
            return response()->json([
                'message' => 'Post delete success',
                'data' => $post
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'Some error occur in deleting',
                'error' => $th->getMessage()
            ], 500);
        }
    }
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
            $temp = array();

            // read through before end of the file
            while (!feof($file)) {
                $data = fgetcsv($file, 1000, ',');
                if (!empty($data)) {
                    array_push($temp, array(
                        'title' => $data[0],
                        'description' => $data[1],
                        'status' => $data[2],

                    ));
                }
            }
            // close the file 
            fclose($file);
            // delete the file 
            ///Storage::delete('Uploaded_csv/'.$filename);

            $tempPosts = array();

            DB::beginTransaction();
            // loop through all the upload data
            for ($i = 1; $i < count($temp); $i++) {
                try {
                    $post = $this->postDao->uploadCsv($temp[$i]);
                    array_push($tempPosts, $post);
                } catch (QueryException $e) {
                    DB::rollBack();
                    return response()->json([
                        'message' => $temp[$i]['title'] . ' already exists',
                        'error' => $e
                    ], 400);
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'created all posts',
                'data' => $tempPosts
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => "Some error occur",
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function downloadCsv()
    {
        $user = Auth::user();
        $posts = $this->postDao->downloadCsv();
        $storedpath = Storage::path('DownloadCsv');
        $filename = $user->name . uniqid() . '.' . 'csv';
        $filepath = $storedpath . '/' . $filename;
        $file = fopen($filepath, 'w');
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
        fclose($file);
        return Storage::download('DownloadCsv/' . $filename);
    }
    private function formatDate($date)
    {
        return Carbon::create($date)->format('Y/m/d');
    }
}
