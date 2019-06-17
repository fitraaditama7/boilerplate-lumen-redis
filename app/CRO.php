<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CRO extends Model {
    protected $table = 'cro_tab';
    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'umur',
        'tgl_lahir',
        'tgl_gabung',
        'status',
        'created_at',
        'updated_at'
    ];
}
