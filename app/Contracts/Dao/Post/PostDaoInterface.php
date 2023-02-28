<?php
namespace App\Contracts\Dao\Post;

use App\Models\Posts;
use Illuminate\Http\Request;

interface PostDaoInterface{

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
   * @return Posts $post
   */
  public function store($validated);
  
  /**
   * to update a post
   * @param Array $validated
   * @param Posts $post
   * @return Posts $post
   */
  public function update($validated,Posts $post);

  /**
   * to delete a post
   * @param Posts $post
   * @return Posts $post
   */
  public function destroy(Posts $post);

  /**
   * to upload csv file 
   * @param Posts $post
   * @return Array $posts
   */
  public function uploadCsv($post);

  /**
   * @return Posts $posts
   */
  public function downloadCsv();
}