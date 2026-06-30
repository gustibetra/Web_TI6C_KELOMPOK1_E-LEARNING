<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $table = 'krs';
    protected $primaryKey = 'id_krs';
    public $timestamps = false; // tabel krs tidak punya timestamps
    protected $fillable = ['id_mahasiswa', 'id_matkul', 'semester'];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa'); }
    public function matakuliah() { return $this->belongsTo(Matakuliah::class, 'id_matkul', 'id_matkul'); }
}