<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersediaanBahan extends Model
{
    use HasFactory;

    protected $table = "persediaan_bahans";

    protected $fillable = [
        'tanggal',
        'produksi_id',
        'stok_awal',
        'stok_siang',
        'cek_fisik',
        'selisih',
        'stok_akhir',
        'tambahan_sore',  
        'keterangan',
    ];

    public function produksi()
    {
        return $this->belongsTo(ProduksiBahan::class, 'produksi_id');
    }
    
}
