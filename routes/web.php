<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RolePermissionController;
use App\Models\Material;
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

// $materials = Material::all();
$materials = [
    [
        'id' => 1,
        'name' => 'Resin'
    ],
    [
        'id' => 2,
        'name' => 'Steel'
    ],
    [
        'id' => 3,
        'name' => 'Urethane'
    ],
    [
        'id' => 4,
        'name' => 'Fabric'
    ],
];

// authentication
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// material
Route::get('/material/download/template', [MaterialController::class, 'downloadTemplate'])->name('material.download-template');
Route::prefix('admin')->middleware('auth')->group(function() use ($materials) {
    Route::get('/material/data/ajax/{type}', [MaterialController::class, 'ajax'])->name('material.ajax');
    Route::post('/material/import', [MaterialController::class, 'import'])->name('material.import');
    Route::post('/material/submit-import', [MaterialController::class, 'submitImport'])->name('material.submit-import');
    foreach ($materials as $material) {
        Route::get('material/' . $material['name'] . '/{type}', [MaterialController::class, 'index'])->name('material.' . $material['name']);
        Route::post('material/{type}/store', [MaterialController::class, 'store'])->name('material.store');
        Route::get('material/{type}/{id}/edit', [MaterialController::class, 'edit'])->name('material.edit');
        Route::put('material/{id}', [MaterialController::class, 'update'])->name('material.update');
        Route::delete('material/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');
    }

    // role and permission
    Route::get('/permissions', [RolePermissionController::class, 'permissions'])->name('setting.permissions');
    Route::get('/permissions/ajax', [RolePermissionController::class, 'ajaxPermission'])->name('setting.permissions.ajax');
    Route::post('/permissions', [RolePermissionController::class, 'storePermission'])->name('setting.permissions.store');
    Route::get('/permissions/{id}', [RolePermissionController::class, 'editPermission'])->name('setting.permissions.edit');
    Route::put('/permissions/{id}', [RolePermissionController::class, 'updatePermission'])->name('setting.permissions.update');
    Route::get('/roles', [RolePermissionController::class, 'roles'])->name('setting.roles');
    Route::get('/roles/ajax', [RolePermissionController::class, 'ajaxRoles'])->name('setting.roles.ajax');
    Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('setting.roles.store');
    Route::get('/roles/{id}', [RolePermissionController::class, 'showRoles'])->name('setting.roles.show');
    Route::put('/roles/{id}', [RolePermissionController::class, 'updateRoles'])->name('setting.roles.update');
    Route::delete('/roles/{id}', [RolePermissionController::class, 'destroyRole'])->name('setting.roles.destroy');
});
