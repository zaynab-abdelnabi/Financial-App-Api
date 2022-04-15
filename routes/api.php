<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfitGoalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RecurringController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Admins
Route::group(['prefix' => 'admins'], function () {
    Route::get('/', [UserController::class, 'getAll']);
    Route::get('/{id}', [UserController::class, 'getOne']);
    Route::get('/name/{name}', [UserController::class, 'getOneByName']);
    Route::put('/edit/{id}', [UserController::class, 'editUser']);
    Route::put('/password', [UserController::class, 'editAdminPassword']);
    Route::post('/', [UserController::class, 'create']);
    Route::delete('/{id}', [UserController::class, 'delete']);
});

Route::post('/login', [UserController::class, 'authenticate']);
Route::get('/logout', [UserController::class, 'logout']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::group(['prefix' => 'categories'], function () {
        // Route::get('/', [CategoryController::class, 'listall']);
    });
});




//Categories Routes
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'listall']);
    Route::get('/{id}', [CategoryController::class, 'listCategory']);
    Route::delete('/{id}', [CategoryController::class, 'deleteCategory']);
    Route::post('/', [CategoryController::class, 'create']);
    Route::put('/edit/{id}', [CategoryController::class, 'edit']);
    Route::get('/test/{name}', [CategoryController::class, 'getbyname']);
});


//Profit Goal Routes
Route::group(['prefix' => 'ProfitGoals'], function () {
    Route::get('/', [ProfitGoalController::class, 'getProfitGoal']);
    Route::get('/{id}', [ProfitGoalController::class, 'ListProfitGoal']);
    Route::put('/edit/{id}', [ProfitGoalController::class, 'updateProfitGoal']);
});


//Recurring Routes
Route::group(['prefix' => 'recurrings'], function () {
    Route::get('/', [RecurringController::class, 'getAll']);
    Route::delete('/{id}', [RecurringController::class, 'delete']);
});

//Transactions Routes
Route::group(['prefix' => 'transactions'], function () {
    Route::get('/all', [TransactionController::class, "getAll"]);
    Route::get('/income', [TransactionController::class, "getAllIncome"]);
    Route::get('/expense', [TransactionController::class, "getAllExpense"]);
    Route::get('/list', [TransactionController::class, "getPaginationAll"]);
    Route::get('/list/income', [TransactionController::class, "getPaginationincome"]);
    Route::get('/list/expense', [TransactionController::class, "getPaginationExpenses"]);
    Route::get('/latest-transactions', [TransactionController::class, "getLatestTransactions"]);
    Route::get('/date', [TransactionController::class, "getByDate"]);
    Route::get('/incomes', [TransactionController::class, "getIncome"]);
    Route::get('/expenses', [TransactionController::class, "getExpense"]);
    Route::get('/recurring/{id}', [TransactionController::class, "getRecurring"]);
    Route::get('/monthly', [TransactionController::class, "getMonthly"]);
    Route::get('/mobile/monthly', [TransactionController::class, "getMonthlyMobile"]);
    Route::get('/weekly', [TransactionController::class, "getWeekly"]);
    Route::get('/mobile/weekly', [TransactionController::class, "getWeeklyMobile"]);
    Route::get('/yearly', [TransactionController::class, "getYearly"]);
    Route::get('/mobile/yearly', [TransactionController::class, "getYearlyMobile"]);
    Route::get('/records/category/yearly', [TransactionController::class, "getYearCategoryRecords"]);
    Route::get('/records/category/monthly', [TransactionController::class, "getMonthCategoryRecords"]);
    Route::get('/records/category/daily', [TransactionController::class, "getDayCategoryRecords"]);

    Route::get('/{id}', [TransactionController::class, "getById"]);
    Route::post('/create/fixed', [TransactionController::class, "createFixed"]);
    Route::post('create/recurring', [TransactionController::class, "createRecurring"]);
    // Route::post('create/bydate', [TransactionController::class, "getDatesOfInterval"]);
    Route::put('/edit/fixed/{id}', [TransactionController::class, "updateFixed"]);
    Route::put('/edit/recurring/{id}', [TransactionController::class, "updateRecurring"]);
    Route::put('/edit/allrecurring/{id}', [TransactionController::class, "updateAllRecurring"]);
    Route::delete('/{id}', [TransactionController::class, "delete"]);
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'authenticate']);
