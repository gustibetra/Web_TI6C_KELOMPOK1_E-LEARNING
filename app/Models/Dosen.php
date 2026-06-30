<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';

    protected $fillable = ['id_user', 'nama_dosen', 'foto_profil', 'email', 'no_hp'];

    public function user() { return $this->belongsTo(User::class, 'id_user', 'id_user'); }
    public function matakuliah() { return $this->hasMany(Matakuliah::class, 'id_dosen', 'id_dosen'); }
    public function materi() { return $this->hasMany(Materi::class, 'id_dosen', 'id_dosen'); }
    public function tugas() { return $this->hasMany(Tugas::class, 'id_dosen', 'id_dosen'); }
    public function uts() { return $this->hasMany(Uts::class, 'id_dosen', 'id_dosen'); }
    public function uas() { return $this->hasMany(Uas::class, 'id_dosen', 'id_dosen'); }
}