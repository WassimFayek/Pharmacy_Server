<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PharmacyController;

Route::group(['middleware' => 'api'], function($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);
    //Route::post('/refresh', [AuthController::class, 'refresh']);
   // Route::post('/profile', [AuthController::class, 'profile']);
   Route::post('/add_pharmacy',[PharmacyController::class,'add_pharmacy']);
   Route::post('/add_image',[PharmacyController::class,'add_image']);
   Route::post('/contact_info',[PharmacyController::class,'add_contact_information']);
   Route::get('/list_pharmacies',[PharmacyController::class,'get_pharmacies']);
   Route::post('/delete_pharmacy',[PharmacyController::class,'delete_pharmacy']);
   Route::post('/edit_pharmacy',[PharmacyController::class,'Edit_pharmacy']);
   Route::post('/searchPharmacy',[PharmacyController::class,'searchPharmacy']);
  
   
  

   
   
});