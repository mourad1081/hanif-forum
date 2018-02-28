<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
// a changer
Route::get('/mon-profil', 'ProfileController@index')->name('profil')->middleware('auth');
Route::get('/users/{user}', 'ProfileController@getProfile')->name('user')->middleware('member');

Route::group(['middleware' => 'member'], function () {
    Route::get('/sections/{category}', 'HomeController@getSection')->name('section');
    Route::get('/sections/{category}/create-discussion', 'HomeController@createDiscussionView')->name('create-discussion');
    Route::post('/sections/{category}/create-discussion', 'HomeController@createDiscussion');
    Route::get('/discussions/{discussion}', 'HomeController@getDiscussion')->name('discussion');
    // poster un message dans une discussion
    Route::post('/discussions/{discussion}/post-message', 'HomeController@postMessage');
    // modifier un message
    Route::post('/messages/update/{post}', 'HomeController@updateMessage');
});

Route::get('/administration',                  'AdministrationController@index');
Route::post('/categories/{category}/update',   'AdministrationController@updateCategory');
Route::post('/categories/create',              'AdministrationController@createCategory');
Route::get('/categories/{category}/archive',   'AdministrationController@archiveCategory');
Route::get('/categories/{category}/unarchive', 'AdministrationController@unarchiveCategory');

Route::get('/users/{user}/activate',       'AdministrationController@activateUser');
Route::get('/users/{user}/deactivate',     'AdministrationController@deactivateUser');
Route::get('/users/{user}/remove-picture', 'AdministrationController@removePicture');
Route::post('/users/{user}/set-role',      'AdministrationController@setUserRole');
Route::post('/users/{user}/set-grade',     'AdministrationController@setUserGrade');
Route::get('/users/{user}/ban',            'AdministrationController@banUser');
Route::get('/users/{user}/unban',          'AdministrationController@unbanUser');
Route::post('/users/{user}/update-picture','ProfileController@uploadImage');


