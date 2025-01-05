<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;
    protected $table = 'satuans'; // Change this if your table name is different

    // Specify the fillable fields
    protected $fillable = [
        'satuan_brg',
    ];

    // Disable timestamps if your table doesn't have them
    public $timestamps = true;
    public function barang()
    {
    return $this->hasMany(Barang::class, 'satuan_brg_id');
    }
}
