<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/', 'dashboard')->name('dashboard');

    Volt::route('languages-selection', 'pages.dashboard.languages.index')->name('languages-selection');

    Volt::route('language/{language}', 'pages.dashboard.languages.categories')->name('language');

    Volt::route('words/{language}', 'pages.dashboard.languages.words.multiple-choice')->name('words.multiple-choice');



});



require __DIR__.'/auth.php';
