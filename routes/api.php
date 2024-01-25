<?php

use App\Http\Controllers\Dashboard\EvaluateCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CompetitionsController;
use App\Http\Controllers\Helpers\MonitorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(
    ['prefix' => 'v1/' ,'middleware' => ['localizer']],
    function () {

    
    
      Route::get('horse-results-api/{competition_code}/{number}', [MonitorController::class, 'resultsApi']);
    
    
      Route::get('import', [\App\Http\Controllers\Api\HelperController::class, 'convert']);
         Route::get('import-v2', [CompetitionsController::class, 'buildResults']);
             Route::get('screen-contents', [MonitorController::class, 'current']);
             Route::get('current-horse-eval/{id}', [CompetitionsController::class, 'currentHorseEval']);
    
        Route::post('login', [UsersController::class, 'login']);
        Route::post('login-qr', [UsersController::class, 'loginViaCode']);
       // Route::post('import', [App\Http\Controllers\Api\HelperController::class, 'convert']);
        
    });
    
    
    Route::group(['prefix' => 'v1/', 'middleware' => ['auth:api' ,'localizer']], function () { 
        
        Route::get('logout', [UsersController::class, 'logout']); 
       Route::post('update-profile', [UsersController::class, 'update']);    
        
        
      Route::get('get-older-competitions', [CompetitionsController::class, 'getOldCompetitions']);  
      Route::get('get-competition-by-id/{id}', [CompetitionsController::class, 'getCompetitionById']);  
      Route::get('get-active-competition', [CompetitionsController::class, 'getActiveCompetition']);  
      Route::get('get-competition-class-by-id/{id}', [CompetitionsController::class, 'getClassData']);   //phase 1
        
       //evaluate 
       Route::post('send-evaluation', [CompetitionsController::class, 'saveSingleEvaluation']);  
       Route::post('submit-evaluations', [CompetitionsController::class, 'submitHorseScores']);  
       Route::post('submit-champion-rating', [CompetitionsController::class, 'submitChampionsRating']);  
       Route::post('submit-foal-champion-rating', [CompetitionsController::class, 'submitFoalChampionsRating']);  
      
      
        
    });


