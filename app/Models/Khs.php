<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khs extends Model
{
    protected $table = 'khs';
    protected $primaryKey = 'id_khs';
    protected $fillable = ['id_mahasiswa', 'id_matkul', 'nilai_akhir', 'grade', 'ipk'];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa'); }
    public function matakuliah() { return $this->belongsTo(Matakuliah::class, 'id_matkul', 'id_matkul'); }
}