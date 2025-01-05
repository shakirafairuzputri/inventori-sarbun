<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barangs'; // Change this if your table name is different

    // Specify the fillable fields
    protected $fillable = [
        'nama_brg',
        'kelompok',
        'kategori_brg_id',
        'satuan_brg_id',
        'stok_brg',
    ];

    // Disable timestamps if your table doesn't have them
    public $timestamps = true;
    public function kategori_brg()
    {
        return $this->belongsTo(KategoriBrg::class, 'kategori_brg_id');
    }
    public function satuan_brg()
    {
        return $this->belongsTo(Satuan::class, 'satuan_brg_id');
    }
    public function persediaan_barangs()
    {
        return $this->hasMany(PersediaanBarang::class);
    }
}
