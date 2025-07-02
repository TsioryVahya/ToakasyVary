<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    protected $fillable = [
        'nom',
        'id_type_client',
        'email',
        'telephone',
        'adresse',
    ];

    // public function typeClient(): BelongsTo
    // {
    //     return $this->belongsTo(TypeClient::class, 'id_type_client');
    // }
}
