<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    protected $fillable = ['nidn', 'npm', 'password'];
    public $timestamps = false;

    // Relasi
    public function dosen() { return $this->hasOne(Dosen::class, 'id_user', 'id_user'); }
    public function mahasiswa() { return $this->hasOne(Mahasiswa::class, 'id_user', 'id_user'); }

    // Helper: cek role berdasarkan data yang terisi
    public function getRoleAttribute()
    {
        if (!empty($this->nidn)) return 'dosen';
        if (!empty($this->npm)) return 'mahasiswa';
        return 'guest';
    }
}