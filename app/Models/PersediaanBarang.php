<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersediaanBarang extends Model
{
    use HasFactory;

    protected $table = "persediaan_barangs";

    protected $fillable = [
        'tanggal',
        'barang_id',
        'stok_awal',
        'tambah',
        'kurang',
        'sisa',
        'pegawai_brgm',
        'pegawai_brgk',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
    public function userMasuk()
    {
        return $this->belongsTo(User::class, 'pegawai_brgm');
    }
    public function userKeluar()
    {
        return $this->belongsTo(User::class, 'pegawai_brgk');
    }
}
