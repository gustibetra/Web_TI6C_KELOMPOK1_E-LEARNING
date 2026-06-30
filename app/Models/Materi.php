<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    protected $fillable = ['id_matkul', 'id_dosen', 'file_path', 'deksripsi'];

    public function matakuliah() { return $this->belongsTo(Matakuliah::class, 'id_matkul', 'id_matkul'); }
    public function dosen() { return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen'); }
}