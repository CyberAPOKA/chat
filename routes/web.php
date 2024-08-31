<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precognition;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/get-conversations', [HomeController::class, 'getConversations'])->name('get.conversations');
    Route::get('/load-conversation/{id}', [HomeController::class, 'loadConversation'])->name('load.conversation');
    Route::post('/create-conversation', [HomeController::class, 'createConversation'])->name('create.conversation');

    Route::post('/check-user-phone', [ContactController::class, 'checkUserPhone'])->name('check.user.phone');
    Route::post('/add-contact', [ContactController::class, 'add'])->name('add.contact')->middleware(Precognition::class);
    Route::get('get-contacts', [ContactController::class, 'getContacts'])->name('get.contacts');

    // nova conversa
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::post('/user/update/profile/photo', [UserController::class, 'updateProfilePhoto'])->name('update.profile.photo');
    Route::post('/user/update/name', [UserController::class, 'updateName'])->name('update.name');
});
