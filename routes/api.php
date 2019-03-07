<?php

use Illuminate\Http\Request;
use App\GithubUser;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->get('/users/{username}', function ($username) {
  $responseArr = [
    'type'=>'response',
    'body'=>'',
  ];

  try{
    $githubUser = new GithubUser($username);
    $responseArr['body'] = $githubUser->toArray();
    $statusCode = 200;
  } catch (\Exception $e) {
    $responseArr['type'] = "error";
    $responseArr['body'] = $e->getMessage();
    $statusCode = 500;
  }
  return response(json_encode($responseArr), $statusCode)->header('Content-Type', 'application/json');
});

Route::middleware('api')->get('/users/{username}/repos', function ($username) {
  $responseArr = [
    'type'=>'response',
    'body'=>'',
  ];

  try{
    $githubUser = new GithubUser($username);
    $responseArr['body'] = $githubUser->getGithubRepository()->getRepositiories();
    $statusCode = 200;
  } catch (\Exception $e) {
    $responseArr['type'] = "error";
    $responseArr['body'] = $e->getMessage();
    $statusCode = 500;
  }
  return response(json_encode($responseArr), $statusCode)->header('Content-Type', 'application/json');
});
