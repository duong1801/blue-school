<?php

use Illuminate\Support\Facades\Route;
use Modules\User\src\Http\Controllers\UserController;

Route::name('admin.')->prefix('admin')->group(function (){
    Route::get('/user',[UserController::class,'index'])->name('user.index');
});
