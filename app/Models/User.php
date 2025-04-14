<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\SearchPattern;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory, 
        Notifiable,
        HasUuid,
        SearchPattern;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'last_name',
        'role_id',
        'status',
        'metadata',
        'email',
        'password',
        'metadata'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'password' => 'hashed',
        'metadata' => 'json'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function scopeRole($query, $value)
    {
        if (!$value) {
            return $query;
        }

        $rolesUuids = Str::of($value)->explode(',');

        $roles = Role::whereIn('uuid', $rolesUuids)
            ->get()
            ->pluck('id')
            ->toArray();

        $query->whereIn('role_id',$roles);

    }

}
