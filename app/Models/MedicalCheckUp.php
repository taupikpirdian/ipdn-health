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
        'created_by',
        'updated_by',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id', 'medical_check_up_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
