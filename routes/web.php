<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\CollectionUpdateController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CDController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\NewspaperController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Redirect the home route to posts
Route::redirect('/', 'posts');

// Posts Routes
Route::resource('posts', PostController::class);

// User Posts Route
Route::get('/{user}/posts', [DashboardController::class, 'userPosts'])->name('posts.user');

// Routes for authenticated users
Route::middleware('auth')->group(function () {
    // User Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Email Verification Notice route
    Route::get('/email/verify', [AuthController::class, 'verifyEmailNotice'])->name('verification.notice');

    // Email Verification Handler route
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmailHandler'])->middleware('signed')->name('verification.verify');

    // Resending the Verification Email route
    Route::post('/email/verification-notification', [AuthController::class, 'verifyEmailResend'])->middleware('throttle:6,1')->name('verification.send');
});

// Routes for guest users
Route::middleware('guest')->group(function () {
    // Register Routes
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login Routes
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Reset Password Routes
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'passwordEmail']);
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'passwordReset'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'passwordUpdate'])->name('password.update');
});

// Admin and Librarian Routes

Route::group(['middleware' => 'auth:sanctum'], function () {

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::post('/books/{book}/approve', [AdminController::class, 'approveBook'])->name('admin.books.approve');
        Route::post('/books/{book}/reject', [AdminController::class, 'rejectBook'])->name('admin.books.reject');
        Route::get('/books', [AdminController::class, 'showBooks']);
        Route::post('/books', [AdminController::class, 'updateBookStatus'])->name('admin.books.status');
        
        // Librarian Management Routes
        Route::get('/librarians', [AdminController::class, 'index']); // List of librarians
        Route::get('/librarians/create', [AdminController::class, 'createLibrarian']); // Form to add librarian
        Route::post('/librarians', [AdminController::class, 'storeLibrarian']); // Add librarian
        Route::delete('/librarians/{id}', [AdminController::class, 'destroyLibrarian']); // Remove librarian
        
        // Collection Updates Management Routes
        Route::get('/collection-updates', [CollectionUpdateController::class, 'index']); // View collection updates
        Route::post('/collection-updates/{id}', [AdminController::class, 'updateCollectionStatus']); // Approve/Reject update
        
    });

    // Librarian routes
    Route::prefix('librarian')->group(function () {
        
        // Library Inventory Routes
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
        Route::get('/cds', [CDController::class, 'index'])->name('cds.index');
        Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
        Route::get('/journals/create', [JournalController::class, 'create'])->name('journals.create');
        Route::get('/newspapers', [NewspaperController::class, 'index'])->name('newspapers.index');
        Route::get('/newspapers/create', [NewspaperController::class, 'create'])->name('newspapers.create');
        Route::post('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/cds/create', [CDController::class, 'create'])->name('cds.create');
        
        // Collection Updates Request Routes
        Route::post('/collection-updates', [LibrarianController::class, 'requestCollectionUpdate']); // Request collection update
        
    });

    // Book Routes (Accessible by both Admin and Librarian)
    Route::resource('books', BookController::class);
});

// Student and Lecturer Routes
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::post('/students/borrow', [StudentController::class, 'borrow'])->name('students.borrow');
Route::get('/lecturers', [LecturerController::class, 'index'])->name('lecturers.index');
Route::post('/lecturers/borrow', [LecturerController::class, 'borrow'])->name('lecturers.borrow');

// Email Verification Routes (Handled manually)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard'); // Redirect to your desired location after verification
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
