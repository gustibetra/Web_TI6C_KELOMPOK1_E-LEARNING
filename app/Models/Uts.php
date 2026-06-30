<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uts extends Model
{
    protected $table = 'uts';
    protected $primaryKey = 'id_uts';
    
    protected $fillable = [
        'id_matkul',
        'id_dosen',
        'deadline',
        'soal_uts',
    ];

    // Pastikan ini ada
    public $incrementing = true;
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function matakuliah() {
        return $this->belongsTo(Matakuliah::class, 'id_matkul', 'id_matkul');
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }
}