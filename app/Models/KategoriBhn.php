<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBhn extends Model
{
    use HasFactory;
    protected $table = 'kategori_bhns'; // Change this if your table name is different

    // Specify the fillable fields
    protected $fillable = [
        'kategori',
    ];

    // Disable timestamps if your table doesn't have them
    public $timestamps = true;

    public function bahan()
    {
        return $this->hasMany(Bahan::class, 'kategori_id');
    }
    public function persediaan_bahan()
    {
        return $this->hasMany(PersediaanBahan::class, 'kategori_id');
    }

}
