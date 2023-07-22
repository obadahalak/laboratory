

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntibioticController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\LabAccount\staffController;
use App\Http\Controllers\Doctor\AuthDoctroController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\LabAccount\LabAccountController;
use App\Http\Controllers\Admin\AdminSectionAnalizController;
use App\Http\Controllers\LabAccount\DcotorAccountController;


///////////  start  admin endpoints ////////////

Route::prefix('admin-scope')->group(function () {


    /// login admin endpoint ///
    Route::controller(AdminAuthController::class)->group(function () {


        Route::get('auth-admin', 'auth');
        Route::put('auth-profile', 'profileAdmin');
        Route::get('get-admin', 'getProfile')->middleware('auth:admin');
        Route::get('logout-admin', 'logout')->middleware('auth:admin');


    });
    //// end Endpoints


    ///Management account endpoints  ////

    Route::controller(AccountController::class)->middleware('auth:admin')->group(function () {

        Route::post('/account', 'create');

        Route::get('/accounts', 'accounts');

        Route::get('/get-account','account');

        Route::put('update-account','update');
    });

    //// end Endpoints


    //Settings Admin endpoints

    Route::controller(AdminSettingController::class)->middleware('auth:admin')->group(function () {

        /// type of tupes //
        Route::get('getTupes', 'getTupes');
        Route::post('typeOftupe', 'addTypeOfTupe');
        Route::delete('deleteTupe', 'deleteTupe');
        Route::post('updateTupe', 'updateTupe')->name('updateTupe');
        ///end Tupes

        /// testMethods endpoints
        Route::get('get-TestMethods', 'testMethods');
        Route::post('create-TestMethod', 'createTestMethod');
        Route::put('update-TestMethod', 'updateTestMethod');
        Route::delete('delete-TestMethod', 'deleteTestMethod');
        // end testMethods endpoints

        /// testUnits endpoints
        Route::get('get-TestUnits', 'getTestUnits');
        Route::post('create-TestUnit', 'createTestUnit');
        Route::put('update-TestUnit', 'updateTestUnit');
        Route::delete('delete-TestUnit', 'deleteTestUnit');

        /// end  testUnits endpoints


        /// genders endpoints
        Route::get('get-genders', 'indexGender');
        Route::post('create-gender', 'storeGender');
        Route::put('update-gender', 'UpdateGender');
        Route::delete('delete-gender', 'DeleteGender');

        /// end  gender endpoints




    });


    /////// sections-analys ////
    Route::controller(AdminSectionAnalizController::class)->middleware('auth:admin')->group(function () {


        Route::post('moveUpSection','moveUp')->name('adminMoveup');
        Route::post('moveDownSection','moveDown')->name('adminMovedown');

        Route::get('sections', 'sections');
        Route::get('getAnalys', 'getAnalys');

        Route::post('create-section', 'createSections');

        Route::post('create-analyzSection', 'analyzForSection')->name('analyzForSectionAdmin');


        Route::get('mainSection', 'mainSectionId');

        Route::get('mainAnalysId', 'mainAnalysId');

        Route::get('getAnlalys-Id', 'anlalysId');


        Route::put('update-mainSection', 'updateMainSection'); //done

        Route::post('update-MainAnalys', 'updateMainAnalys')->name('adminUpdateMainAnalys'); //done


        Route::post('create-Inti', 'createInti');
        Route::get('get_inti_from_section', 'get_inti_from_section');

        Route::post('create-Inti-forAnalys', 'createIntiAnalys');


        Route::delete('delete-Analys','deleteAnalys');

        Route::delete('delete-MainSection','deleteMainSection');
        Route::delete('delete-MainAnalys','deleteMainAnalys');
    });

    Route::controller(AntibioticController::class)->middleware('auth:admin')->group(function () {
        Route::put('update-intrputik', 'update');
        Route::delete('update-intrputik', 'destroy');
    });
    //// end Endpoints
});

///////////////// end  admin endpoints////////////////
