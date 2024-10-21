<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/logout', 'App\Http\Controllers\Logoutcontroller@logout');
    Route::post('/force-logout', 'App\Http\Controllers\Logoutcontroller@forceLogout');
    Route::post('/destroy', [App\Http\Controllers\Logoutcontroller::class, 'destroy']);
    Route::post('/createdoc', 'App\Http\Controllers\TransactionController@createdoc');
    Route::post('/send-whatsapp', [App\Http\Controllers\WhatsappController::class, 'sendWhatsAppMessage']);

    Route::get('/email/{email}', 'App\Http\Controllfindnumdoccreatedocers\UserController@email');
    Route::get('/changep', 'App\Http\Controllers\UserController@changep');
    Route::get('/config', 'App\Http\Controllers\UserController@config');
    Route::get('/serial/{id}', 'App\Http\Controllers\TransactionController@serial');
    Route::get('/account/{id}', 'App\Http\Controllers\TransactionController@account');
    Route::get('/account2/{id}', 'App\Http\Controllers\TransactionController@account2');
    Route::get('/way/{id}', 'App\Http\Controllers\TransactionController@way');
    Route::get('/available/{id}', 'App\Http\Controllers\TransactionController@available');
    Route::get('/code/{id}', 'App\Http\Controllers\UserController@code');
    Route::get('/location/{id}', 'App\Http\Controllers\UserController@location');
    Route::get('/town/{id}', 'App\Http\Controllers\UserController@town');
    Route::get('/doc/{id}', 'App\Http\Controllers\UserController@doc');
    Route::get('/description/{id}', 'App\Http\Controllers\TransactionController@description');
    Route::get('/getway/{id}', 'App\Http\Controllers\TransactionController@getway');
    Route::get('/get_bank/{id}', 'App\Http\Controllers\CurrencyBankController@get_bank');
    Route::get('/get_currencybank/{id}', 'App\Http\Controllers\CurrencyBankController@get_currencybank');
    Route::get('/typedoc2/{id}', 'App\Http\Controllers\TransactionController@typedoc2');
    Route::get('/bank2/{id}', 'App\Http\Controllers\TransactionController@bank2');
    Route::get('/findfavorite/{id}', 'App\Http\Controllers\TransactionController@findfavorite');
    Route::get('/findpayer/{id}', 'App\Http\Controllers\TransactionController@findpayer');
    Route::get('/findnumcuenta/{numcuenta}', 'App\Http\Controllers\TransactionController@findnumcuenta');
    Route::get('/findnumdoc/{numdoc}', 'App\Http\Controllers\TransactionController@findnumdoc');
    Route::get('/findnombre/{nombre}', 'App\Http\Controllers\TransactionController@findnombre');
    Route::get('/findnombrepayer', 'App\Http\Controllers\TransactionController@findnombrepayer');
    Route::get('/daily', 'App\Http\Controllers\TransactionController@daily');
    Route::get('/historybuy', 'App\Http\Controllers\BuyController@historybuy');
    Route::get('/delete_payer/{id}', 'App\Http\Controllers\TransactionController@delete_payer');
    Route::get('/proccess', 'App\Http\Controllers\TransferController@proccess');
    Route::get('/ret_history/{desde}/{hasta}', 'App\Http\Controllers\HistoryController@ret_history');
    Route::get('/ret_admhistory/{desde}/{hasta}/{reporte}/{userid}', 'App\Http\Controllers\HistoryController@ret_admhistory');

    Route::resource('/user', 'App\Http\Controllers\UserController');
    Route::resource('/license', 'App\Http\Controllers\LicenseController');
    Route::resource('/bank', 'App\Http\Controllers\BankController');
    Route::resource('/currencybank', 'App\Http\Controllers\CurrencyBankController');
    Route::resource('/country', 'App\Http\Controllers\CountryController');
    Route::resource('/currency', 'App\Http\Controllers\CurrencyController');
    Route::resource('/conversion', 'App\Http\Controllers\ConversionController');
    Route::resource('/waytopay', 'App\Http\Controllers\WayToPayController');
    Route::resource('/transaction', 'App\Http\Controllers\TransactionController');
    Route::resource('/typedoc', 'App\Http\Controllers\TypeDocController');
    Route::resource('/resend_image', 'App\Http\Controllers\ResendImageController');
    Route::resource('/history', 'App\Http\Controllers\HistoryController');
    Route::resource('/sys_status', 'App\Http\Controllers\AppStatusController');
    Route::resource('/canawilbank', 'App\Http\Controllers\CanawilBankController');
    Route::resource('/transfer', 'App\Http\Controllers\TransferController');
    Route::resource('/buy', 'App\Http\Controllers\BuyController');
    Route::resource('/profit', 'App\Http\Controllers\ProfitController');
});

Route::post('/login2', 'App\Http\Controllers\UserController@login');

Route::resource('/userforgot', 'App\Http\Controllers\UserController');
Route::resource('/newpassword', 'App\Http\Controllers\UserController');
Route::resource('/savepassword', 'App\Http\Controllers\UserController');
Route::resource('/passworduser', 'App\Http\Controllers\UserController');
Route::get('/forgotp', 'App\Http\Controllers\UserController@forgotp');
Route::get('/user_password/{xemail}/{xtoken}', 'App\Http\Controllers\UserController@user_password');
Route::get('/code2/{id}', 'App\Http\Controllers\UserController@code');
Route::get('/location2/{id}', 'App\Http\Controllers\UserController@location');
Route::get('/town2/{id}', 'App\Http\Controllers\UserController@town');
Route::get('/doc2/{id}', 'App\Http\Controllers\UserController@doc');
Route::get('/v1/whatsapp/{token}', 'App\Http\Controllers\WhatsappController@whatsapp');
