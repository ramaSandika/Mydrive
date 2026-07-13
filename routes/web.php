<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\DriveManager;

// Mengarahkan halaman utama langsung ke komponen Livewire DriveManager
Route::get('/', DriveManager::class);