<?php

use App\Livewire\{Dashboard};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', Dashboard::class)->middleware(['auth'])->name('dashboard');
Route::get('/people', \App\Livewire\People\Index::class)->middleware(['auth'])->name('people');
Route::get('/registration/items', \App\Livewire\Registration\Items::class)->middleware(['auth'])->name('registration.items');
Route::get('/registration/create', \App\Livewire\Registration\Create::class)->middleware(['auth'])->name('registration.create');
Route::get('/registration', \App\Livewire\Registration\Index::class)->middleware(['auth'])->name('registration.index');
Route::get('/registration/suspension-create', \App\Livewire\Registration\SuspensionCreate::class)->middleware(['auth'])->name('registration.suspension-create');

Route::get('/print/{id}', function ($id) {
    return view('pdfs.registration', [
        'registration' => \App\Models\Registration::with(['items', 'user'])->findOrFail($id),
    ]);
})->name('print');

require __DIR__ . '/auth.php';
