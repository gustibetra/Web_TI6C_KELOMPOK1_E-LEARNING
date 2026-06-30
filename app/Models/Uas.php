<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uas extends Model
{
    protected $table = 'uas';
    protected $primaryKey = 'id_uas';
    protected $fillable = ['id_matkul', 'id_dosen', 'deadline', 'soal_uas'];

    public function matakuliah() { return $this->belongsTo(Matakuliah::class, 'id_matkul', 'id_matkul'); }
    public function dosen() { return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen'); }
    public function pengumpulan() { return $this->hasMany(PengumpulanUas::class, 'id_uas', 'id_uas'); }
}