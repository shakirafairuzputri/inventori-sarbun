<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahan extends Model
{
    use HasFactory;
    protected $table = "pembelian_bahans";

    protected $fillable = [
        'tanggal',
        'bahan_id',
        'pembelian',
        'tambahan_sore',
        'user_id',
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
    
    public function produksiBahans()
    {
        return $this->hasMany(ProduksiBahan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // atau Pegawai::class jika menggunakan pegawai
    }
}
