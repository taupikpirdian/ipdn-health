<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCheckUp extends Model
{
    protected $table = 'medical_check_ups';
    protected $fillable = [
        'nomor',
        'klasifikasi',
        'hal',
        'hal_khusus',
        'nilai',
        'saran',
    ];
}