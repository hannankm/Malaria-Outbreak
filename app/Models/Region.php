<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $keyType = 'string'; 
    public $incrementing = false; 

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($region) {
            $region->id = (string) Str::uuid(); // Generate UUID when creating a new region
        });
    }
}