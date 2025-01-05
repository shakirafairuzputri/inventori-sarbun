<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBrg extends Model
{
    use HasFactory;

    protected $table = 'kategori_brgs'; // Change this if your table name is different

    // Specify the fillable fields
    protected $fillable = [
        'kategori_brg',
    ];

    // Disable timestamps if your table doesn't have them
    public $timestamps = true;
    public function barang()
    {
    return $this->hasMany(Barang::class, 'kategori_brg_id');
    }
}
