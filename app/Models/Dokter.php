<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';

    protected $fillable = [
        'nama_dokter',
        'harga_konsultasi',
        'tahun_pengalaman',
        'kota',
        'spesialisasi',
        'email_dokter',
        'nohp_dokter',
        'status',
        'rating',
        'deskripsi',
        'foto',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}
