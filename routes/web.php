<?php

use App\Http\Controllers\User\ResepController;
use App\Models\ResepSigna;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::resource('resep', ResepController::class);
    Route::get('api/resep/get-obat', [ResepController::class,'getObat']);
Route::get('api/resep/get-signa', [ResepController::class, 'getSigna']);
Route::get('api/resep/detail/{id}', [ResepController::class, 'detailResep']);
Route::get('api/resep/cetak/{id}', [ResepController::class, 'cetakResep']);
    Route::get('/cekpdf',function(){
        $data['items'] = ResepSigna::where('resep_id', 32)->whereHas('resep', function ($q) {
    $q->where('user_id', auth()->user()->id);
})->latest()->with('signa')->get();

        return view('user.resep.pdf',$data);
    });


    Route::get('api/resep/get-obat/{id}', [ResepController::class, 'getObatId']);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
