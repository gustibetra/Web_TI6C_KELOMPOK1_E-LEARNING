<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $table = 'matakuliah';
    protected $primaryKey = 'id_matkul';

    protected $fillable = ['id_dosen', 'kode_matkul', 'nama_matkul', 'sks', 'semester', 'waktu_matkul'];

    public function dosen() { return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen'); }
    public function materi() { return $this->hasMany(Materi::class, 'id_matkul', 'id_matkul'); }
    public function tugas() { return $this->hasMany(Tugas::class, 'id_matkul', 'id_matkul'); }
    public function uts() { return $this->hasMany(Uts::class, 'id_matkul', 'id_matkul'); }
    public function uas() { return $this->hasMany(Uas::class, 'id_matkul', 'id_matkul'); }
}