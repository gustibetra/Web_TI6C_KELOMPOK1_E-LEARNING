<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanUts extends Model
{
    protected $table = 'pengumpulan_uts';
    protected $primaryKey = 'id_up_uts';
    protected $fillable = ['id_uts', 'id_mahasiswa', 'link_pengumpulan_uts', 'nilai_uts', 'feedback'];

    public function uts() { return $this->belongsTo(Uts::class, 'id_uts', 'id_uts'); }
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa'); }
}