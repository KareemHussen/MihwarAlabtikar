<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /** @use HasFactory<\Database\Factories\InvitationFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'accepted_at',
        'organization_id',
        'role'
    ];

    protected $dates = [
        'accepted_at'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class , 'organization_id');
    }
}
