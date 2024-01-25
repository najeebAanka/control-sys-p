<?php

use App\Http\Controllers\Dashboard\CompetitionController;
use App\Http\Controllers\Dashboard\CompetitionGroupController;
use App\Http\Controllers\Dashboard\EvaluateCategoryController;
use App\Http\Controllers\Dashboard\SingleCompetitionController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ChampionClassesController;
use App\Http\Controllers\Dashboard\ChampionsClassesController;
use App\Http\Controllers\Dashboard\FoalsClassesController;
use App\Http\Controllers\Dashboard\ClassPrizeController;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

Route::get('get-monitor/{code}', function () {
    return view('monitors.index');
});
Route::get('get-monitor', function () {
    return view('monitors.index');
});

Route::get('get-horse-champoint-rating/{id}', [ChampionClassesController::class, 'getActiveChampionClass']);
Route::get('get-foal-champoint-rating/{id}', [FoalsClassesController::class, 'getActiveChampionClass']);

Route::get('get-monitor-full/{code}', function () {
    return view('monitors.index-full');
});

Route::get('champion-status', function () {
    return view('monitors.champion-status');
});

Route::get('qualifications-top/{class}/{section}', function () {
    return view('dashboard.qualifications-top');
});
Route::get('qualifications-top-printable/{class}/{section}', function () {
    return view('dashboard.qualifications-top-printable');
});

Route::get('horse-results/{id}', function () {
    return view('monitors.horse-results');
});
Route::get('horse-results-print/{id}', function () {
    return view('dashboard.horse-results-print');
});

Route::post('backend/login', 'App\Http\Controllers\Dashboard\AdminController@login');

// Route::get('dashboard/login', function () {
//     return view('dashboard.login');
// })->name('login');

Route::get('dashboard/login', function () {
    if(Auth::check()){
      return redirect('dashboard/competitions');
    }else
    return view('dashboard.login');
})->name('login');

Route::group(
        ['middleware' => 'auth', 'prefix' => 'dashboard/'],
        function () {
            Route::get('logout', 'App\Http\Controllers\Dashboard\AdminController@logout')->name('logout');
            Route::post('backend/reset-password', 'App\Http\Controllers\Dashboard\ResetPasswordController@resetPassword');
        }
);

Route::group(
        ['middleware' => 'auth'],
        function () {

       Route::get('dashboard/classes-prizes/{comp}', function () {
                return view('dashboard.classes-prizes');
            });
    
    
            Route::get('home', function () {
                return redirect('dashboard/competitions');
            });
            Route::get('dashboard', function () {
                return redirect('dashboard/home');
            });
            Route::get('dashboard/home', function () {
                return view('dashboard.home');
            });
            Route::get('dashboard/horses', function () {
                return view('dashboard.horses');
            });
            Route::get('dashboard/registrations', function () {
                return view('dashboard.registrations');
            });
            Route::get('dashboard/points', function () {
                return view('dashboard.points');
            });
            Route::get('dashboard/competitions', function () {
                return view('dashboard.competitions');
            });
            Route::get('dashboard/groups', function () {
                return view('dashboard.groups');
            });
            Route::get('dashboard/categories', function () {
                return view('dashboard.categories');
            });
            Route::get('dashboard/judges', function () {
                return view('dashboard.judges');
            });
            
              Route::get('dashboard/foals-champions/{id}', function () {
                return view('dashboard.foals-champions');
            });
                  

            Route::get('dashboard/reset-password', function () {
                return view('dashboard.reset-password');
            });

            Route::get('dashboard/single-competition/{id}', function () {
                return view('dashboard.single-competition');
            });
            Route::get('dashboard/single-competition-classes/{id}', function () {
                return view('dashboard.single-competition-classes');
            });
            Route::get('dashboard/champion-classes/{id}', function () {
                return view('dashboard.champion-classes');
            });

            Route::get('dashboard/class-ranking/{class}/{section}', function () {
                return view('dashboard.horses-ranking');
            });

            Route::get('dashboard/champion-qualifiers/{cmp_id}', function () {
                return view('dashboard.champion-qualifiers');
            });
            Route::get('dashboard/owners-champion-qualifiers/{cmp_id}', function () {
                return view('dashboard.owners-champion-qualifiers');
            });
            Route::get('dashboard/owners-champion-qualifiers-single/{cmp_id}/{owner}', function () {
                return view('dashboard.owners-champion-qualifiers-single');
            });

            Route::get('dashboard/champion-qualifiers/{id}/{cmp_id}', function () {
                return view('dashboard.champion-qualifiers-single');
            });

            Route::get('dashboard/champion-class-results/{id}/{cmp_id}', function () {
                return view('dashboard.champion-class-results');
            });
               Route::get('dashboard/foals-class-results/{id}', function () {
                return view('dashboard.foals-class-results');
            });
            Route::get('dashboard/champions-classes-management', function () {
                return view('dashboard.champions-classes-management');
            });

            Route::get('dashboard/xls-table', function () {
                return view('dashboard.xls-table');
            });
        }
);


Route::group(
        ['middleware' => 'auth'],
        function () {
            Route::post('backend/class-prizes/add', [ClassPrizeController::class, 'store']);
            Route::post('backend/class-prizes/delete', [ClassPrizeController::class, 'delete']);
            Route::post('backend/class-prizes/update', [ClassPrizeController::class, 'update']);
        });



Route::group(
        ['middleware' => 'auth', 'prefix' => 'backend-crud/v1/'],
        function () {

    
     Route::post('foals-champions/import/load-file',[FoalsClassesController::class, 'readHorses']);
     
       Route::get('foals-champions/fetchClass/{}',[FoalsClassesController::class, 'readHorses']);
     
     
            Route::get('categories/fetchall', [EvaluateCategoryController::class, 'fetchAll']);
            Route::post('categories/store', [EvaluateCategoryController::class, 'store']);
            Route::delete('categories/delete', [EvaluateCategoryController::class, 'delete']);
            Route::post('categories/update', [EvaluateCategoryController::class, 'update']);

            Route::get('users/fetchall', [UserController::class, 'fetchAll']);
            Route::post('users/store', [UserController::class, 'store']);
            Route::delete('users/delete', [UserController::class, 'delete']);
            Route::post('users/update', [UserController::class, 'update']);

            Route::get('champions-classes/fetchall', [ChampionsClassesController::class, 'fetchAll']);
            Route::post('champions-classes/store', [ChampionsClassesController::class, 'store']);
            Route::delete('champions-classes/delete', [ChampionsClassesController::class, 'delete']);
            Route::post('champions-classes/update', [ChampionsClassesController::class, 'update']);

            Route::get('class-horses/fetchForClass/{id}/{section}', [SingleCompetitionController::class, 'fetchClassHorses']);
            Route::get('competitions/single/{id}', [SingleCompetitionController::class, 'fetchSingle']);
            Route::get('competitions/fetchall', [CompetitionController::class, 'fetchAll']);
            Route::post('competitions/store', [CompetitionController::class, 'store']);
            Route::delete('competitions/delete', [CompetitionController::class, 'delete']);
            Route::post('competitions/update', [CompetitionController::class, 'update']);
            Route::get('competitions/update-current-class/{id}/{class_id}/{section}', [SingleCompetitionController::class, 'updateActiveClass']);
            Route::get('competitions/update-current-horse/{id}/{class_id}/{comp_id}', [SingleCompetitionController::class, 'updateActiveHorse']);

            Route::get('competitions/notifyJudges/{class_id}', [SingleCompetitionController::class, 'notifyJudges']);

            Route::get('groups/fetchallclasses/{id}', [CompetitionGroupController::class, 'fetchAllClasses']);
            Route::get('groups/fetchall/{id}', [CompetitionGroupController::class, 'fetchAllThis']);
            Route::get('groups/judges/{class}/{section}', [CompetitionGroupController::class, 'fetchJudgesList']);
             Route::get('champion/judges/{id}', [CompetitionGroupController::class, 'fetchChampionJudgesList']);
            Route::get('groups/fetchall', [CompetitionGroupController::class, 'fetchAll']);
            Route::post('groups/store', [CompetitionGroupController::class, 'store']);
            Route::delete('groups/delete', [CompetitionGroupController::class, 'delete']);
            Route::post('groups/update', [CompetitionGroupController::class, 'update']);

Route::get('groups/judges/{id}', [CompetitionGroupController::class, 'getGroupSections']);

            Route::post('groups/judges/update', [CompetitionGroupController::class, 'updateJudges']);
            
             Route::post('champion/judges/update', [CompetitionGroupController::class, 'updateChampionJudges']);

            Route::get('horses/set-present/{id}', [CompetitionGroupController::class, 'setHorsePresent']);
            Route::get('horses/set-absent/{id}/{action}', [CompetitionGroupController::class, 'setHorseAbsent']);

            //=====================================-=--------------
            //  champion-class-horses/fetchForClass
            Route::get('champion-class-horses/fetchForClass/{id}/{cmp_id}', [ChampionClassesController::class, 'fetchClassHorses']);
            Route::get('competitions/update-current-champion-class/{id}/{class_id}', [ChampionClassesController::class, 'updateActiveClass']);
            
            
                //=====================================-=--------------
            //  champion-class-horses/fetchForClass
            Route::get('foals-champion-class-horses/fetchForClass/{id}', [FoalsClassesController::class, 'fetchClassHorses']);
            Route::get('competitions/update-current-foals-champion-class/{id}/{class_id}', [FoalsClassesController::class, 'updateActiveClass']);
                Route::get('foals-horses/set-present/{id}', [FoalsClassesController::class, 'setHorsePresent']);
            Route::get('foals-horses/set-absent/{id}', [FoalsClassesController::class, 'setHorseAbsent']); 
            
        }
);

//CRUD OPERATIONS
//CATEGORIES --.


// Route::resource('backend/categories', EvaluateCategoryController::class);
