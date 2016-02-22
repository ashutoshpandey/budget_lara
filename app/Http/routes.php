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
Route::get('/remove-budget', 'BudgetController@removeBudget');

Route::get('/all-budget-shares', 'BudgetController@shares');
Route::post('/share-budget', 'BudgetController@share');
Route::get('/remove-budget-share', 'BudgetController@removeBudgetShare');

Route::post('/add-budget-item', 'BudgetController@addItem');
Route::get('/all-budget-items', 'BudgetController@items');
Route::get('/all-budget-items-filtered', 'BudgetController@itemsFiltered');
Route::get('/remove-item', 'BudgetController@removeItem');

Route::post('/add-category', 'CategoryController@add');
Route::post('/remove-category', 'CategoryController@remove');
Route::get('/all-categories', 'CategoryController@all');

Route::post('/add-payment-mode', 'PaymentModeController@add');
Route::post('/remove-payment-mode', 'PaymentModeController@remove');
Route::get('/all-payment-modes', 'PaymentModeController@all');

Route::get('/get-customer-info', 'CustomerController@info');
Route::post('/create-customer', 'CustomerController@create');
Route::post('/update-customer', 'CustomerController@update');
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
