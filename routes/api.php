<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});

Route::post('pullrequest/create', 'PullRequestController@postCreatePullRequest')->name('create');
Route::post('task/create', 'PullRequestController@createTask')->name('createTask');
Route::post('task/complete/{task}', 'TaskController@complete')->name('createTask');


Route::post('pullrequest/review', 'PullRequestController@postReviewPullRequest')->name('review');
Route::get('pullrequest', 'PullRequestController@index')->name('index');
Route::get('pullrequest/create', 'PullRequestController@getCreatePullRequest')->name('create');
Route::get('pullrequest/review/{id}', 'PullRequestController@getReviewPullRequest')->name('review');
Route::get('pullrequest/fix/{id}', 'PullRequestController@getFixPullRequest')->name('fix');
Route::post('pullrequest/fix', 'PullRequestController@postFixPullRequest')->name('fix');

Route::post('bpmn', 'BpmnController@get');


