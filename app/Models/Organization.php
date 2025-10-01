<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

    class Organization extends BaseTenant implements TenantWithDatabase
    {
        use HasDatabase, HasDomains , HasFactory;

        protected $table = 'organizations';
        public static function getCustomColumns(): array
        {
            return [
                'id',
                'name',
                'owner_id'
            ];
        }

        public function setPassworddAttribute($value)
        {
            return $this->attributes['password'] = bcrypt($value);
        }

        public function getIncrementing()
        {
            return true;
        }

        public function invitations()
        {
            return $this->hasMany(Invitation::class);
        }

    }
