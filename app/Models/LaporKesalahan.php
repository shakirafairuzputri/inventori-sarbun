<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporKesalahan extends Model
{
    use HasFactory;
    protected $table = "lapor_kesalahans";

    protected $fillable = [
        'user_id',
        'tanggal',
        'kategori',
        'keterangan',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // atau Pegawai::class jika menggunakan pegawai
    }
}
