<?php

use App\Http\Controllers\admin\AddAccountController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AdminTransactionHistoryUserController;
use App\Http\Controllers\admin\ViewUserController;
use App\Http\Controllers\petugas\ApprovalSavingController;
use App\Http\Controllers\petugas\AprrovalPaymentUserController;
use App\Http\Controllers\petugas\OfficeIncomeController;
use App\Http\Controllers\petugas\OfficeIncomeJSONController;
use App\Http\Controllers\petugas\PetugasController;
use App\Http\Controllers\petugas\TransactionHistoryUserController;
use App\Http\Controllers\supervisor\ManageApprovalUserController;
use App\Http\Controllers\supervisor\ManagePengajuanPinjamanUserController;
use App\Http\Controllers\supervisor\SPVTransactionHistoryUserController;
use App\Http\Controllers\supervisor\SupervisorController;
use App\Http\Controllers\user\BorrowerProfileController;
use App\Http\Controllers\user\LoanApplicationController;
use App\Http\Controllers\user\LoanPaymentController;
use App\Http\Controllers\user\SavingController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\ViewSavingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role->name === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role->name === 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        } elseif ($user->role->name === 'petugas') {
            return redirect()->route('petugas.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
    return redirect()->route('login');
})->name('home');

Auth::routes(['middleware' => ['redirectIfAuthenticated']]);


Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('approval-payments', AprrovalPaymentUserController::class);
    Route::resource('approval-savings', ApprovalSavingController::class);
    Route::resource('accounts', AddAccountController::class);
    Route::resource('transaction-history-admin', AdminTransactionHistoryUserController::class);
    Route::resource('view-users', ViewUserController::class);
});

Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/home', [UserController::class, 'index'])->name('home');

    Route::resource('borrower-profiles', BorrowerProfileController::class);
    Route::resource('loan-applications', LoanApplicationController::class)->except('show');
    Route::get('/loan-applications/print', [LoanApplicationController::class, 'printActiveLoans'])->name('loan-applications.print');
    Route::resource('my-savings', ViewSavingController::class);
});

Route::middleware(['auth', 'role.petugas'])->group(function () {
    Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.dashboard');

    Route::resource('loan-payments', LoanPaymentController::class);
    Route::resource('office-incomes', OfficeIncomeController::class);
    Route::resource('office-incomes-json', OfficeIncomeJSONController::class);
    Route::get('get-user-loans/{userId}', [LoanPaymentController::class, 'getUserLoans'])->name('admin.get-user-loans');
    Route::resource('savings', SavingController::class);
    Route::post('/savings-json', [SavingController::class, 'jsonStore'])->name('savings.storejson');
    Route::resource('transaction-history', TransactionHistoryUserController::class);
});

Route::middleware(['auth', 'role.supervisor'])->group(function () {
    Route::get('/supervisor', [SupervisorController::class, 'index'])->name('supervisor.dashboard');

    Route::resource('manage-approval-users', ManageApprovalUserController::class);
    Route::resource('transaction-history-spv', SPVTransactionHistoryUserController::class);
    Route::resource('pengajuan-pinjaman', ManagePengajuanPinjamanUserController::class);
});
