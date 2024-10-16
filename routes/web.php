<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/', 'dashboard')->name('dashboard');

    Volt::route('languages-selection', 'pages.dashboard.languages.index')->name('languages-selection');

    Volt::route('language/{language}', 'pages.dashboard.languages.modules')->name('language');

    Volt::route('words/{language}/set/{set}', 'pages.dashboard.languages.words.multiple-choice')->name('words.multiple-choice');

    Volt::route('words/{language}/set', 'pages.dashboard.languages.words.set')->name('words.sets');

});



require __DIR__.'/auth.php';
