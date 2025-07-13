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

use Illuminate\Support\Facades\DB;

Route::get('/mongo-test', function () {
    try {
        DB::connection('mongodb')->collection('test_collection')->insert([
            'name' => 'Sagar',
            'message' => 'MongoDB connection successful!',
            'timestamp' => now(),
        ]);

        return 'Inserted test document successfully into MongoDB.';
    } catch (\Exception $e) {
        return 'MongoDB Error: ' . $e->getMessage();
    }
});
