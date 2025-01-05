<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function returBahans()
    {
        return $this->hasMany(ReturBahan::class, 'user_id');
    }
    public function pembelianBahans()
    {
        return $this->hasMany(PembelianBahan::class, 'user_id');
    }
    public function produksiBahans()
    {
        return $this->hasMany(ProduksiBahan::class, 'user_id');
    }
    public function persediaanBarangs()
    {
        return $this->hasMany(PersediaanBarang::class, 'pegawai_brgm', 'pegawai_brgk');
    }
    public function laporKesalahan()
    {
        return $this->hasMany(LaporKesalahan::class, 'user_id',);
    }
    public function requests()
    {
        return $this->hasMany(Requests::class, 'user_id',);
    }
}
