<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanUas extends Model
{
    protected $table = 'pengumpulan_uas';
    protected $primaryKey = 'id_up_uas';
    protected $fillable = ['id_uas', 'id_mahasiswa', 'link_pengumpulan_uas', 'kritik_dan_saran', 'nilai_uas', 'feedback'];

    public function uas() { return $this->belongsTo(Uas::class, 'id_uas', 'id_uas'); }
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa'); }
}