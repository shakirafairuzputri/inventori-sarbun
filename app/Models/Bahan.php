<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    // Specify the table name if it's not the default 'bahans'
    protected $table = 'bahans'; // Change this if your table name is different

    // Specify the fillable fields
    protected $fillable = [
        'nama',
        'kategori_id',
        'satuan',
        'stok',
    ];

    // Disable timestamps if your table doesn't have them
    public $timestamps = true;

    public function kategori()
    {
        return $this->belongsTo(KategoriBhn::class, 'kategori_id');
    }
    public function returBahans()
    {
        return $this->hasMany(ReturBahan::class);
    }
    public function pembelianBahans()
    {
        return $this->hasMany(PembelianBahan::class);
    }
}
