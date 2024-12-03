<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'region_id'];

    protected $keyType = 'string'; 
    public $incrementing = false;

    public function woredas()
    {
        return $this->hasMany(Woreda::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($zone) {
            $zone->id = (string) Str::uuid(); // Generate UUID when creating a new zone
        });
    }
}