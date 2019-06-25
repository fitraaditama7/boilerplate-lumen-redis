<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
    protected $table = 'tm_vehicle';
    protected $fillable = [
        'jenis_kendaraan',
        'kapasitas_kendaraan',
        'plat_nomor',
        'tahun_pembuatan',
        'nomor_rangka',
        'nomor_lambung',
        'nomor_mesin',
        'stnk',
        'masa_berlaku_stnk',
        'kir',
        'masa_berlaku_kir',
        'base_dc',
        'nomor_kp',
        'masa_berlaku_kp',
        'foto_kendaraan',
        'kepemilikan',
        'is_approved',
        'is_approved'
    ];
}
