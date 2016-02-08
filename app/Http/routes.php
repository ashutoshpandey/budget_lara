<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/find-budget', 'BudgetController@find');
Route::post('/create-budget', 'BudgetController@create');
Route::post('/update-budget', 'BudgetController@update');
Route::get('/all-budgets', 'BudgetController@all');

Route::get('/all-budget-shares', 'BudgetController@shares');
Route::post('/share-budget', 'BudgetController@share');

Route::post('/add-budget-item', 'BudgetController@addItem');
Route::get('/all-budget-items', 'BudgetController@items');

Route::get('/get-customer-info', 'CustomerController@info');
Route::post('/create-customer', 'CustomerController@create');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
