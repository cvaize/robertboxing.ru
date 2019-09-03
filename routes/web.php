<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index')->name('welcome');
Route::get('/posts', 'WelcomeController@posts')->name('posts');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('vkposts', 'Media\Vk\VkPostController');
	Route::get('/admin', 'Requests\RequestSiteController@index')->name('admin');
	Route::get('/admin/requests', 'Requests\RequestSiteController@index')->name('requests.index');
	Route::get('/admin/requests/{request}', 'Requests\RequestSiteController@show')->name('requests.show');
	Route::resource('/admin/users', 'Users\UserController');
	Route::get('/admin/feedback', 'Requests\FeedbackController@index')->name('feedback.index');
	Route::get('/admin/feedback/{feedback}/edit', 'Requests\FeedbackController@edit')->name('feedback.edit');
	Route::patch('/admin/feedback/{feedback}', 'Requests\FeedbackController@update')->name('feedback.update');
});

Route::post('/requests', 'Requests\RequestSiteController@store')->name('requests.store');
Route::post('/feedback', 'Requests\FeedbackController@store')->name('feedback.store');
//Route::get('/time', 'Requests\RequestSiteController@time')->name('requests.time');
Route::get('/testing', 'Requests\RequestSiteController@testing')->name('requests.testing');

//Route::get('testingApii', function (\App\Models\Apis\TrainingApi $trainingApi) {
//	$days = \App\Models\Apis\TrainingApi::daysWhichHasTime()->get();
//	return $days;
//});