<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturBahan extends Model
{
    use HasFactory;

    protected $table = "retur_bahans";

    protected $fillable = [
        'tanggal',
        'bahan_id',
        'kategori',
        'satuan',
        'retur_baik',
        'retur_rusak',
        'user_id',
        'jenis_kerusakan',
        'status',
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // atau Pegawai::class jika menggunakan pegawai
    }
    public function pembelianBahans()
    {
        return $this->hasMany(PembelianBahan::class);
    }

}
