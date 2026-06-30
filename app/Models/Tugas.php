<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    protected $fillable = ['id_matkul', 'id_dosen', 'judul', 'deskripsi', 'deadline', 'soal_tugas'];

    public function matakuliah() { return $this->belongsTo(Matakuliah::class, 'id_matkul', 'id_matkul'); }
    public function dosen() { return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen'); }
    public function pengumpulan() { return $this->hasMany(PengumpulanTugas::class, 'id_tugas', 'id_tugas'); }
}