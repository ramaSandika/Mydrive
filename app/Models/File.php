<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'folder_id', 'minio_path', 'size'];

    // Relasi: Sebuah file pasti berada di dalam satu folder
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}