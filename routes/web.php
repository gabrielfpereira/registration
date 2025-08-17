<?php

use Illuminate\Support\Facades\Route;
use Spatie\LaravelPdf\Facades\Pdf;


Route::get('/', function () {
    //return view('welcome');
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', \App\Livewire\Dashboard::class)->middleware(['auth'])->name('dashboard');
Route::get('/people', \App\Livewire\People\Index::class)->middleware(['auth'])->name('people');
Route::get('/registration/items', \App\Livewire\Registration\Items::class)->middleware(['auth'])->name('registration.items');
Route::get('/registration/create', \App\Livewire\Registration\Create::class)->middleware(['auth'])->name('registration.create');
Route::get('/registration/list', \App\Livewire\Registration\Index::class)->middleware(['auth'])->name('registration.index');
Route::get('/registration/suspension-create', \App\Livewire\Registration\SuspensionCreate::class)->middleware(['auth'])->name('registration.suspension-create');

Route::get('/registration/signature', \App\Livewire\Registration\Signature::class)->middleware(['auth'])->name('registration.signature');
Route::get('/profile', \App\Livewire\Profile\Index::class)->middleware(['auth'])->name('profile.index');

//Route::get('/print/{id}', function ($id) {
  //  return view('pdfs.registration', [
  //      'registration' => \App\Models\Registration::with(['items', 'user'])->findOrFail($id),
  //  ]);
//})->name('print');

Route::get('/print/{id}', function ($id) {
    $registration = \App\Models\Registration::with('items')->findOrFail($id);

        $fileName = $registration->student_name . '-' . $registration->class_number . '-' . now()->format('dmY') . '.pdf';
    return Pdf::view('pdfs.registration', ['registration' => $registration])
        ->format('a4')
        ->download("{$fileName}.pdf");
})->name('print');

require __DIR__ . '/auth.php';
