<?php

namespace App\Models;

use App\Events\ProduksiUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiBahan extends Model
{
    use HasFactory;
    
    protected $table = "produksi_bahans";

    protected $fillable = [
        'tanggal',
        'pembelian_id',
        'produksi_baik',
        'produksi_paket',
        'produksi_rusak',
        'user_id',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianBahan::class, 'pembelian_id');
    }
    public function persediaanBahans()
    {
        return $this->hasMany(PersediaanBahan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
