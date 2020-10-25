<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

Route::get('verificar-servicios-completados', 'ScriptController@verificarServiciosCompletados');
Route::get('unir-campos-nombre-telefono', 'ScriptController@unir_campos_nombre_telefono');
Route::get('corregir-servicios-transferencia', 'ScriptController@corregir_servicios_transferencia');
Route::group(['middleware' => 'https'], function(){
	Auth::routes();
	Route::post('login', 'Auth\LoginController@login')->name('login');
	Route::post('register', 'Auth\RegisterController@create')->name('register');

	Route::post('recover-password', 'UserController@recover_password')->name('user.recover-password');
	Route::get('verify-email/{usuario}', 'UserController@verify_email')->name('user.verify-email');

	Route::get('/', 'HomeController@index')->name('home');
	Route::get('home', 'HomeController@index');
	Route::get('new-service', 'HomeController@guest_service');
	Route::post('new-service', 'ServiceController@store')->name('guest.new-service');

	Route::group(['middleware' => ['auth', 'noti.chat']], function(){

		Route::get('services/show-route/{id}', 'ServiceController@show_route')->name('services.show-route');

		Route::prefix('messenger')->group(function () {
				Route::get('t/{id}', 'MessageController@laravelMessenger')->name('messenger');
				Route::post('send', 'MessageController@store')->name('message.store');
				Route::get('threads', 'MessageController@loadThreads')->name('threads');
				Route::get('more/messages', 'MessageController@moreMessages')->name('more.messages');
				Route::delete('delete/{id}', 'MessageController@destroy')->name('delete');
				// AJAX requests.
				Route::prefix('ajax')->group(function () {
					Route::post('make-seen', 'MessageController@makeSeen')->name('make-seen');
				});
			});
		
		Route::group(['middleware' => 'client'], function(){
			Route::get('dashboard', 'UserController@index')->name('dashboard');
			Route::get('profile', 'UserController@profile')->name('profile');
			Route::post('update-profile', 'UserController@update_profile')->name('update-profile');
			Route::get('my-addresses', 'UserController@my_addresses')->name('my-addresses');
			Route::post('store-address', 'UserController@store_address')->name('store-address');
			Route::get('delete-address/{id}', 'UserController@delete_address')->name('delete-address');
			Route::get('my-remember-data', 'UserController@my_remember_data')->name('my-remember-data');
			Route::post('store-data', 'UserController@store_data')->name('store-data');
			Route::get('edit-data/{id}', 'UserController@edit_data')->name('edit-data');
			Route::post('update-data', 'UserController@update_data')->name('update-data');
			Route::post('delete-data', 'UserController@delete_data')->name('delete-data');

			Route::group(['prefix' => 'services'], function() {
				Route::get('/', 'ServiceController@index')->name('services.index');
				Route::get('create', 'ServiceController@create')->name('services.create');
				Route::post('store', 'ServiceController@store')->name('services.store');
				Route::get('show/{id}', 'ServiceController@show')->name('services.show');	
				Route::get('edit/{id}', 'ServiceController@edit')->name('services.edit');
				Route::post('update', 'ServiceController@update')->name('services.update');
				Route::get('cancel/{id}', 'ServiceController@cancel')->name('services.cancel');
				Route::get('record', 'ServiceController@completed')->name('services.record');
				Route::get('load-address/{id}', 'ServiceController@load_address')->name('services.load-address');
				Route::get('load-data/{id}', 'ServiceController@load_data')->name('services.load-data');
			});
		});
		
		Route::group(['prefix' => 'brever', 'middleware' => 'brever'], function(){
			Route::get('/', 'UserController@index')->name('brever.index');
			Route::get('profile', 'UserController@profile')->name('brever.profile');
			Route::post('update-profile', 'UserController@update_profile')->name('brever.update-profile');
			Route::group(['prefix' => 'services'], function(){
				Route::get('/', 'ServiceController@index')->name('brever.services');
				Route::post('take', 'ServiceController@take')->name('brever.services.take');
				Route::get('show/{id}', 'ServiceController@show')->name('brever.services.show');
				Route::get('assigned', 'ServiceController@assigned')->name('brever.services.assigned');
				Route::post('start', 'ServiceController@start')->name('brever.services.start');
				Route::get('started', 'ServiceController@started')->name('brever.services.started');
				Route::post('confirm', 'ServiceController@confirm')->name('brever.services.confirm');
				Route::post('complete', 'ServiceController@complete')->name('brever.services.complete');
				Route::get('completed', 'ServiceController@completed')->name('brever.services.completed');
				Route::get('canceled', 'ServiceController@canceled')->name('brever.services.canceled');
			});
			Route::group(['prefix' => 'financial'], function(){
				Route::get('account-status', 'FinancialController@account_status')->name('brever.financial.account-status');
				Route::get('balance-transfers', 'FinancialController@balance_transfers')->name('brever.financial.balance-transfers');
				Route::post('transfer', 'FinancialController@transfer')->name('brever.financial.transfer');
			});
		});

		Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function(){
			Route::get('/', 'UserController@index')->name('admin.index');
			Route::group(['prefix' => 'services'], function(){
				Route::get('/', 'ServiceController@index')->name('admin.services');
				Route::get('create', 'ServiceController@create')->name('admin.services.create');
				Route::get('load-remember-data/{user_id}', 'ServiceController@load_remember_data')->name('admin.services.load-remember-data');
				Route::get('load-data/{id}', 'ServiceController@load_data')->name('admin.services.load-data');
				Route::post('store', 'ServiceController@store')->name('admin.services.store');
				Route::get('show/{id}', 'ServiceController@show')->name('admin.services.show');
				Route::post('add-brever', 'ServiceController@add_brever')->name('admin.services.add-brever');
				Route::get('edit/{id}', 'ServiceController@edit')->name('admin.services.edit');
				Route::post('update', 'ServiceController@update')->name('admin.services.update');
				Route::post('change-status', 'ServiceController@change_status')->name('admin.services.change-status');
				Route::get('assigned', 'ServiceController@assigned')->name('admin.services.assigned');
				Route::get('start/{id}', 'ServiceController@start')->name('admin.services.start');
				Route::get('started', 'ServiceController@started')->name('admin.services.started');
				Route::get('confirm/{id}', 'ServiceController@confirm')->name('admin.services.confirm');
				Route::get('confirmed', 'ServiceController@confirmed')->name('admin.services.confirmed');
				Route::get('complete/{id}', 'ServiceController@complete')->name('admin.services.complete');
				Route::get('completed', 'ServiceController@completed')->name('admin.services.completed');
				Route::get('canceled', 'ServiceController@canceled')->name('admin.services.canceled');
				Route::get('confirm-payment/{id}', 'ServiceController@confirm_payment')->name('admin.services.confirm-payment');
			});

			Route::group(['prefix' => 'users'], function(){
				Route::get('create', 'UserController@create')->name('admin.users.create');
				Route::post('store', 'UserController@store')->name('admin.users.store');
				Route::get('edit/{id}', 'UserController@edit')->name('admin.users.edit');
				Route::post('update', 'UserController@update')->name('admin.users.update');
				Route::get('clients', 'UserController@clients')->name('admin.users.clients');
				Route::get('show-remember-data/{client_id}', 'UserController@show_remember_data')->name('admin.users.show-remember-data');
				Route::get('edit-remember-data/{data_id}', 'UserController@edit_data')->name('admin.users.edit-remember-data');
				Route::post('update-remember-data', 'UserController@update_data')->name('admin.users.update-remember-data');
				Route::get('brevers', 'UserController@brevers')->name('admin.users.brevers');
				Route::get('make-vip/{usuario}/{accion}', 'UserController@make_vip')->name('admin.users.make-vip');
				Route::get('change-status/{usuario}/{accion}', 'UserController@change_status')->name('admin.users.change-status');
				Route::get('admins', 'UserController@admins')->name('admin.users.admins');
				Route::get('delete/{id}', 'UserController@delete')->name('admin.users.delete');
			});

			Route::group(['prefix' => 'financial'], function(){
				Route::get('services-record', 'ServiceController@record')->name('admin.financial.services-record');
				Route::get('brevers-record', 'UserController@brevers_record')->name('admin.financial.brevers-record');
				Route::post('show-account-status', 'FinancialController@show_account_status')->name('admin.financial.show-account-status');
				Route::get('brever-recharge', 'UserController@brevers_record')->name('admin.financial.brever-recharge');
				Route::post('recharger', 'FinancialController@recharger')->name('admin.financial.recharger');
				Route::post('discount', 'FinancialController@discount')->name('admin.financial.discount');
				Route::get('pending-payments', 'FinancialController@pending_payments')->name('admin.financial.pending-payments');
				Route::get('earnings', 'FinancialController@earnings')->name('admin.financial.earnings');
			});
		});
	});
});

