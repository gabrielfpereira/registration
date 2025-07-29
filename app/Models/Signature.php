<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    /** @use HasFactory<\Database\Factories\SignatureFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
    ];

    public function registrations(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
