<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'file_path'
    ];
    public function getFileSize()
    {
        // Ensure $this->file_path begins with '/public/';
        return Storage::size($this->file_path);
    }
}
