<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Destinasi extends Model
{
    use HasFactory;

    protected $table = 'destinasi';

    public function ratings()
    {
        return $this->hasMany(Review::class, 'id_destinasi');
    }

    public function owner()
    {
        return $this->belongsTo(OwnerBusiness::class, 'id_owner');
    }

    /**
     * Get the user's first name.
     */
    protected function destinationPicture(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::disk('public')->url($value) : null,
        );
    }
}
