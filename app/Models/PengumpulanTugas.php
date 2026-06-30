<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';
    protected $primaryKey = 'id_up_tugas';
    
    protected $fillable = [
        'id_tugas',
        'id_mahasiswa',
        'link_pengumpulan_tugas',
        'nilai_tugas',
        'feedback',
    ];

    // Pastikan ini ada
    public $incrementing = true;
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function tugas() {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}