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
  $githubUser = new GithubUser($username);
    return $githubUser->toJson();
});

Route::middleware('api')->get('/users/{username}/repos', function ($username) {
    $githubUser = new GithubUser($username);
    
});
