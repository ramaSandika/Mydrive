<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    // Mengizinkan pengisian data massal untuk kolom ini
    protected $fillable = ['name', 'parent_id'];

    // Relasi: Sebuah folder bisa memiliki banyak sub-folder
    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relasi: Sebuah folder bisa memiliki satu "induk" (folder di atasnya)
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    // Relasi: Sebuah folder bisa memiliki banyak file di dalamnya
    public function files()
    {
        return $this->hasMany(File::class);
    }
}