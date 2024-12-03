<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Woreda extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'zone_id'];

    protected $keyType = 'string';
    public $incrementing = false;

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($woreda) {
            $woreda->id = (string) Str::uuid(); 
        });
    }
}
