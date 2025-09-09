<?php

namespace App\Models;

use Laravolt\Indonesia\Models\City;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\District;

class Member extends Model
{
    protected $table = 'members';
    protected $fillable = [
        'nomor',
        'name',
        'jk',
        'city_id',
        'district_id',
        'no_kesehatan_tahap1',
        'no_kesehatan_tahap2',
    ];
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}