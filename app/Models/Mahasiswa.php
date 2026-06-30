<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $fillable = ['id_user', 'nama_mahasiswa', 'foto_profil', 'kelas', 'prodi', 'semester', 'email', 'no_hp'];

    public function user() { return $this->belongsTo(User::class, 'id_user', 'id_user'); }
    public function krs() { return $this->hasMany(Krs::class, 'id_mahasiswa', 'id_mahasiswa'); }
    public function khs() { return $this->hasMany(Khs::class, 'id_mahasiswa', 'id_mahasiswa'); }
    public function absen() { return $this->hasMany(Absen::class, 'id_mahasiswa', 'id_mahasiswa'); }
}