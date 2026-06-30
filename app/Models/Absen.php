<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'id_absen';
    protected $fillable = ['id_mahasiswa', 'id_matkul', 'tanggal_absen', 'foto_pash', 'status'];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa'); }
    public function matakuliah() { return $this->belongsTo(Matakuliah::class, 'id_matkul', 'id_matkul'); }
}