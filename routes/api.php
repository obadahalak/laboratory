<?php

// use App\Models\Section;
use App\Models\Account;
use App\Models\Patient;
use App\Models\Section;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PatieonAnalys;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\homeController;
use App\Http\Controllers\AnalyzController;
use App\Http\Controllers\AntibioticController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\LabAccount\billController;
use App\Http\Controllers\LabAccount\labsController;
use App\Http\Controllers\LabAccount\staffController;
use App\Http\Controllers\LabAccount\storeController;
use App\Http\Controllers\Doctor\AuthDoctroController;
use App\Http\Controllers\LabAccount\resultController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\LabAccount\PatientController;
use App\Http\Controllers\LabAccount\SectionController;
use App\Http\Controllers\LabAccount\supplierController;
use App\Http\Controllers\LabAccount\accountingController;
use App\Http\Controllers\LabAccount\LabAccountController;
use App\Http\Controllers\LabAccount\sendPatientController;
use App\Http\Controllers\LabAccount\DcotorAccountController;
use App\Http\Controllers\LabAccount\Settings\genderController;
use App\Http\Controllers\LabAccount\Settings\companyController;
use App\Http\Controllers\LabAccount\Settings\jobTitleController;
use App\Http\Controllers\LabAccount\Settings\SendMethodController;
use App\Http\Controllers\LabAccount\Settings\payemntMethodContoller;
use App\Http\Controllers\LabAccount\Settings\specializationController;
use App\Http\Controllers\LabAccount\Settings\TestUnitAndMethodController;


Route::get('/setup', function () {

    Artisan::call('migrat:fresh --seed');
    Artisan::call('passport:install');
});

///auth labs endpoints//////////
Route::prefix('lab-scope')->group(function () {


    Route::controller(LabAccountController::class)->group(function () {

        Route::get('auth-lab', 'accountAuth');


        Route::middleware('auth:lab')->group(function () {

            Route::get('account', 'account');
            Route::get('account-logout', 'logout');

            Route::post('updateLab', 'updateLab');
            Route::post('priceStatus','updatePriceStatus');
            Route::get('getPriceStatus','priceStatus');
        });

    });
    /// end auth labs endpoints//////////


    ////////////////  # DcotorAccoun Routes # ///////////////////////
    Route::controller(DcotorAccountController::class)->middleware('auth:lab')->group(function () {

        Route::post('createDoctor', 'storeDoctor');

        Route::get('myDoctors', 'Docotors');

        Route::get('doctor/{id}', 'show');

        Route::post('doctor-update', 'update');

        Route::delete('doctor-delete', 'destroy');

        Route::get('doctor_reports','reports');
    });
    ////////////////  # end of DcotorAccoun Routes # ///////////////////////

    ////////////////  # accountings Routes # ///////////////////////
    Route::controller(accountingController::class)->middleware('auth:lab')->group(function () {
        Route::get('/accounting-export', 'exports');
        Route::get('/accounting-rev', 'outcome');
        Route::post('/accounting-store', 'store');
        Route::get('/accounting-show/{id}', 'show');
        Route::put('/accounting-update/{id}', 'update');
        Route::delete('/accounting-delete', 'destroy');
    });
    ////////////////  # end of accounting Routes # ///////////////////////


    ////////////////  # Analyz Routes # ///////////////////////
    Route::controller(AnalyzController::class)->middleware('auth:lab')->group(function () {

        Route::get('patientAnalysis', 'patientAnalysis');

        Route::get('getAnlalys-Id', 'anlalysId');

        Route::get('mainAnalysId', 'mainAnalysId');

        Route::get('get-AnalysisFromSection', 'getAnalysis');

        Route::post('create-analyzForSection', 'analyzForSection')->name('analyzForSection');

        Route::get('get_inti_from_section', 'get_antibiotic_from_section');

        Route::get('filterAnalysis', 'sectionsWithAnalysisFilter');

        Route::get('filterPatients', 'patientAnalysisFilter');


        Route::delete('delete-Analys', 'deleteAnalys');
    });

    ////////////////  # end of Analyz Routes # ///////////////////////


    /////////// # start section Routes /////////////////
    Route::controller(SectionController::class)->middleware('auth:lab')->group(function () {

        Route::post('moveUpSection','moveUp');
        Route::post('moveDownSection','moveDown');
        Route::get('get-sections', 'sections')->name('getSectionForAnalysis');
        Route::get('SectionsForPatient', 'SectionsForPatient');
        Route::post('create-section', 'store');
        Route::get('mainSection', 'mainSectionId');
        Route::put('update-mainSection', 'updateMainSection');
        Route::post('update-MainAnalys', 'updateMainAnalys')->name('updateMainAnalys');
        Route::delete('delete-MainSection', 'deleteMainSection');
        Route::delete('delete-MainAnalys', 'deleteMainAnalys');
    });
    ////////// # end section Routes /////////
    Route::controller(AntibioticController::class)->middleware('auth:lab')->group(function () {

        Route::put('update-intrputik', 'update');

        Route::delete('update-intrputik', 'destroy');

        Route::post('create-Inti-forAnalys', 'createIntiAnalys');

        Route::post('create-Inti', 'createInti');
    });


    ////////////////  # Patien Routes # ///////////////////////
    Route::controller(PatientController::class)->middleware('auth:lab')->group(function () {

        Route::get('getPrice','getPrice');
        Route::post('patient-store', 'storePatient');

        Route::get('list-patients', 'listPatient');

        Route::put('patient-update', 'update');

        Route::get('get-patients', 'index');

        Route::get('patient', 'getPatient')->name('getPatient');

        Route::delete('/patient-delete', 'destroy');
    });
    ////////////////  # end of Patien Routes # ///////////////////////

    Route::controller(resultController::class)->middleware('auth:lab')->group(function () {


        Route::get('get_result_data', 'get_date');

        Route::post('add-result',  'addResult');

        Route::put('update-result',  'updatedResult');
    });

    ////////////////  # store Routes # ///////////////////////
    Route::controller(storeController::class)->middleware('auth:lab')->group(function () {
        Route::get('/store-inside', 'get_inside');
        Route::get('/store-outside', 'get_outside');
        Route::post('/store-store', 'store');
        Route::get('/store-show/{id}', 'show');
        Route::post('/store-update/{id}', 'update');
        Route::post('/quantity-update/{id}', 'update_quantity');
        Route::delete('/store-delete', 'destroy');
        Route::delete('/outside-delete', 'deleteOutside');
        Route::get('/get_filter_data', 'get_filter_data');
    });
    ////////////////  # end of store Routes # ///////////////////////



    ////////////////  # staff Routes # ///////////////////////
    Route::controller(staffController::class)->middleware('auth:lab')->group(function () {
        Route::get('/staff', 'index');
        Route::post('/staff-store', 'store');
        Route::get('/staff-show/{id}', 'show');
        Route::post('/staff-update/{stuffId}', 'update');
        Route::delete('/staff-delete/{id}', 'destroy');
    });
    ////////////////  # end of staff Routes # ///////////////////////



    ////////////////  # supplier Routes # ///////////////////////
    Route::controller(supplierController::class)->middleware('auth:lab')->group(function () {
        Route::get('/suppliers', 'index');
        Route::post('/supplier-store', 'store');
        Route::get('/supplier-show/{id}', 'show');
        Route::post('/supplier-update', 'update');
        Route::delete('/supplier-delete', 'destroy');
    });
    ////////////////  # end of supplier Routes # ///////////////////////


    ////////////////  # bill Routes # ///////////////////////
    Route::controller(billController::class)->middleware('auth:lab')->group(function () {
        Route::get('/bills', 'get_bills');
        Route::post('/bill-store', 'addBillToOffice');
        Route::get('/pays', 'get_pays');
        Route::post('/pay-store', 'addPay');
        Route::delete('/bill-delete', 'deleteBill');
        Route::delete('/pay-delete', 'deletePay');
    });
    ////////////////  # end of bill Routes # ///////////////////////



    ////////////////  # labs Routes # ///////////////////////
    Route::controller(labsController::class)->middleware('auth:lab')->group(function () {
        Route::get('/labs', 'index');
        Route::post('/lab-store', 'store');
        Route::get('/lab-show/{id}', 'show');
        Route::post('/lab-search', 'searchCode');
        Route::post('/lab-update', 'update');
        Route::delete('/lab-delete', 'destroy');
    });
    ////////////////  # end of labs Routes # ///////////////////////


    ////////////////  # specialization Routes # ///////////////////////
    Route::controller(specializationController::class)->middleware('auth:lab')->group(function () {
        Route::get('/specializations', 'index');
        Route::post('/specialization-store', 'store');
        Route::get('/specialization-show/{id}', 'show');
        Route::post('/specialization-update/{id}', 'update');
        Route::delete('/specialization-delete/{id}', 'destroy');
    });
    ////////////////  # end of specialization Routes # ///////////////////////



    ////////////////  # job title Routes # ///////////////////////
    Route::controller(jobTitleController::class)->middleware('auth:lab')->group(function () {
        Route::get('/job-titles', 'index');
        Route::post('/job-title-store', 'store');
        Route::get('/job-title-show/{id}', 'show');
        Route::post('/job-title-update/{id}', 'update');
        Route::delete('/job-title-delete/{id}', 'destroy');
    });
    ////////////////  # end of job title Routes # ///////////////////////


    ////////////////  # send patint Routes # ///////////////////////
    Route::controller(sendPatientController::class)->middleware('auth:lab')->group(function () {

        Route::get('/getRecivedAnalys', 'getRecivedAnalys');

        Route::post('/send-petient', 'sendPetient');
        Route::get('/getPatients', 'getPatientData');
        Route::get('/allAccounts', 'allAccounts');
        Route::get('/all-patient', 'indexPatient');
        Route::get('/patients-SendingAndResived', 'patientsSendingAndResived')->name('getPatients');
        Route::get('/recevedPateitns', 'recevedPateitns')->name('recevedPateitns');
        Route::get('/getDataForSending', 'get_analysis_pateint');
    });
    ////////////////  # end of send patint Routes # ///////////////////////



    ///  start genders Routes //////
    Route::controller(genderController::class)->middleware('auth:lab')->group(function () {

        Route::get('genders', 'index');
        Route::post('create-gender', 'store');
        Route::put('gender-update', 'update');
        Route::delete('gender-delete', 'delete');
    });
    ///  end genders Routes //////


    ///  start company Routes //////
    Route::controller(companyController::class)->middleware('auth:lab')->group(function () {

        Route::get('companies', 'index');
        Route::post('create-company', 'store');
        Route::put('company-update', 'update');
        Route::delete('company-delete', 'delete');
    });

    ///  end  company Routes //////




    ///  start payemntMethod Routes //////
    Route::controller(payemntMethodContoller::class)->middleware('auth:lab')->group(function () {

        Route::get('paymentMethods', 'index');
        Route::post('create-paymentMethods', 'store');
        Route::put('paymentMethods-update', 'update');
        Route::delete('paymentMethods-delete', 'delete');
    });

    ///  end  payemntMethod Routes //////



    ///  start sendMethod Routes //////
    Route::controller(SendMethodController::class)->middleware('auth:lab')->group(function () {

        Route::get('sendMethods', 'index');
        Route::post('create-sendMethod', 'store');
        Route::put('sendMethod-update', 'update');
        Route::delete('sendMethod-delete', 'delete');
    });
    ///  end sendMethod Routes //////

    //////// home /////

    Route::get('home', [homeController::class, 'numbers'])->middleware('auth:lab');

    //// end home /////

    Route::controller(TestUnitAndMethodController::class)->middleware('auth:lab')->group(function () {
        /// testUnits endpoints
        Route::get('get-TestUnits', 'getTestUnits');
        Route::post('create-TestUnit', 'createTestUnit');
        Route::put('update-TestUnit', 'updateTestUnit');
        Route::delete('delete-TestUnit', 'deleteTestUnit');



        /// testMethods endpoints
        Route::get('get-TestMethods', 'testMethods');
        Route::post('create-TestMethod', 'createTestMethod');
        Route::put('update-TestMethod', 'updateTestMethod');
        Route::delete('delete-TestMethod', 'deleteTestMethod');
        // end testMethods endpoints

        /// end  testUnits endpoints

        Route::get('getTupes', 'getTupes');
    });
});



///auth Doctor endpoints//////////


Route::prefix('doctor-scope')->group(function () {

    Route::controller(AuthDoctroController::class)->group(function () {

        Route::get('doctor-login', 'doctorAuth');

        Route::middleware('auth:doctor')->group(function () {

            Route::get('my-analysies', 'index');
            Route::get('my-analysies', 'index');

            Route::put('update-profile', 'updatePassword');
            Route::get('doctor-profile', 'doctorProfile');
        });
    });
});




//// end auth Doctor  Endpoints
