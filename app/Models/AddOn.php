<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    use HasFactory;

    protected $table = 'add_on';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_tambahan',
        'harga'
    ];
}
