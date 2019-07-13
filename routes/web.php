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

Route::get('user/verify/{verification_code}', 'API\AuthController@verifyUser');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@postReset')->name('password.reset');


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

//Users
Route::get('/users/{search?}', 'UserController@listUsers')->name('user.list');
Route::get('/user/profile/{id}', 'UserController@userProfile')->name('user.profile');
Route::post('/user/activate/{id}', 'UserController@activateUser')->name('user.activate');
Route::match(['delete'],'/user/desactivate/{id}', 'UserController@desactivateUser')->name('user.desactivate');
Route::post('/user/edit/{id}', 'UserController@userModify')->name('user.edit');

//Publications
Route::get('/publications/{search?}', 'PublicationController@listPublications')->name('publication.list');
Route::get('/publication/{id}', 'PublicationController@publicationSpecific')->name('publication.specific');
Route::get('/publicationsByUser/{id}', 'PublicationController@publicationsByUser')->name('user.publications');
Route::get('/commentsByPublication/{publication}/{user}', 'PublicationController@commentsByPublication')->name('comments.publication');
Route::match(['delete'],'/publication/desactivate/{id}', 'PublicationController@desactivatePublication')->name('publication.desactivate');
Route::post('/publication/activate/{id}', 'PublicationController@activatePublication')->name('publication.activate');


//Comments
Route::get('/comments/{search?}', 'CommentController@listComments')->name('comment.list');
Route::get('/commentsByUser/{id}', 'CommentController@commentsByUser')->name('user.comments');
Route::match(['delete'],'/comment/desactivate/{id}', 'CommentController@desactivateComment')->name('comment.desactivate');
Route::post('/comment/activate/{id}', 'CommentController@activateComment')->name('comment.activate');


//Notifications

Route::get('/notifications/{search?}', 'NotificationController@listNotifications')->name('notification.list');
Route::get('/notification/create', 'NotificationController@createNotification')->name('notification.create');
Route::post('/notification/save', 'NotificationController@saveNotification')->name('notification.save');
Route::match(['delete'],'/notification/desactivate/{id}', 'NotificationController@desactivateNotification')->name('notification.desactivate');
Route::post('/notification/activate/{id}', 'NotificationController@activateNotification')->name('notification.activate');
Route::match(['delete'],'/notification/destroy/{id}', 'NotificationController@destroyPublication')->name('notification.destroy');