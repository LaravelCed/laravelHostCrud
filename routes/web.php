<?php

use App\Http\Controllers\indexController;
use App\Models\TblRecord;
use App\Models\TblSignature;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $readTblRecord = TblRecord::all();
    $readTblSignature = TblSignature::all();
    return view('index', ['readTblRecord'=>$readTblRecord, 'readTblSignature'=>$readTblSignature]);
});

Route::get('/edit/{id}', function ($id) {
    $readTblRecord = TblRecord::all();
    $readTblSignature = TblSignature::all();
    $checkTblRecord = TblRecord::where('id',$id)->first();
    return view('edit', ['readTblRecord'=>$readTblRecord, 'readTblSignature'=>$readTblSignature, 'id'=>$id, 'checkTblRecord'=>$checkTblRecord]);
});

Route::post('/addTask', [indexController::class, 'addTask']);

Route::get('/deleteTask/{id}', [indexController::class, 'deleteTask']);

Route::post('/addSignature', [indexController::class, 'addSignature']);

Route::post('/editTask/{id}', [indexController::class, 'editTask']);

Route::post('/addSignatureToPDF', [indexController::class, 'addSignatureToPDF']);