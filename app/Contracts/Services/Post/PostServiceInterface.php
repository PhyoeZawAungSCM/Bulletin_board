<?php
namespace App\Contracts\Services\Post;
use App\Models\Posts;
use Illuminate\Http\Request;

interface PostServiceInterface{

     /**
   * To save post
   * @return Object $posts
   */
    public function index(Request $request);

    /**
     * to show a post
     * @param  Posts $post
     * @return Posts $post
     */
    public function show(Posts $post);

    /**
     * to store a post
     * @param  Array $validated
     * @return Object json object with message and data
     */
    public function store($validated);
    
    /**
     * to update a post
     * @param Array $validated 
     * @param Posts $post
     * @return Object json object with message and data
     */
    public function update($validated,Posts $post);

    /**
     * to delete a post
     * @param Posts $post
     * @return Object json object with message and data
     */
    public function destroy(Posts $post);

    /**
     * to upload csv file 
     * @param Request $request
     * @return JSON with message of success or not with all uploaded $posts
     */
    public function uploadCsv(Request $request);

    public function downloadCsv();

}