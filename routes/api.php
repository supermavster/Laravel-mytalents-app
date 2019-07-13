<?php
Route::post('register', 'API\AuthController@register');
Route::post('login', 'API\AuthController@login');
Route::post('recover', 'API\AuthController@recover');


//Rutas protegidas por JWT
Route::group(['middleware' => ['jwt.auth']], function() {
  	Route::apiResource('user', 'API\UserController')->only(['index','show','update']);
    Route::apiResource('publication', 'API\PublicationController')->only(['index','store','show','update','destroy']);
    Route::apiResource('comment', 'API\CommentController')->only(['index','store','show','update','destroy']);
    Route::apiResource('like', 'API\LikeController')->only(['index','store','destroy']);
    Route::apiResource('notification', 'API\NotificationController')->only(['index','store','show','update','destroy']);

    Route::get('logout', 'API\AuthController@logout');
});
