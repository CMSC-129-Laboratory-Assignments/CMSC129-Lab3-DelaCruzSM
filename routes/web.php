<?php

use App\Http\Controllers\JournalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/signup', [SignupController::class, 'create'])->name('signup');
    Route::post('/signup', [SignupController::class, 'store']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Dashboard & Journal Entries
    Route::get('/dashboard', [JournalController::class, 'index'])->name('dashboard');
    Route::get('/journals/create', [JournalController::class, 'create'])->name('journals/create');
    Route::post('/journals', [JournalController::class, 'store'])->name('journals/store');

    // ===== AI Chatbot Route =====
    Route::post('/chatbot/send', [App\Http\Controllers\ChatBotController::class, 'chat'])->name('chatbot.send');

    // View, Edit & Delete Entries
    Route::get('/journals/{id}/show', [JournalController::class, 'show'])->name('journals/show'); // NEW ROUTE
    Route::get('/journals/{id}/edit', [JournalController::class, 'edit'])->name('journals/edit');
    Route::put('/journals/{id}', [JournalController::class, 'update'])->name('journals/update');
    Route::delete('/journals/{id}', [JournalController::class, 'destroy'])->name('journals/delete');

    // Profile Page Functionality
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile/update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password/update');

    // Recently Deleted (Trash)
    Route::get('/recently-deleted', [JournalController::class, 'trash'])->name('recently-deleted');
    Route::post('/journals/{id}/restore', [JournalController::class, 'restore'])->name('journals/restore');
    Route::delete('/journals/{id}/force', [JournalController::class, 'forceDelete'])->name('journals/forceDelete');
    Route::delete('/trash/empty', [JournalController::class, 'emptyTrash'])->name('trash/empty');
});

// API endpoint the AI will call
Route::get('/api/users/{id}/journals', function ($id) {
    // Fetch the 20 most recent entries
    $entries = App\Models\Journal::where('user_id', $id)->latest()->take(20)->get();

    // Returns as pure JSON data
    return response()->json($entries);
});
