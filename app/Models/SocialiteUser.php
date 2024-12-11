<?php

namespace App\Models;

use DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser as FilamentSocialiteUserContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Illuminate\Database\Eloquent\Model;

class SocialiteUser extends Model implements FilamentSocialiteUserContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider',
        'provider_id',
        'user_id',
    ];
    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getUser(): Authenticatable
    {
        return $this->user;
    }

    public static function findForProvider(string $provider, SocialiteUserContract $oauthUser): ?self
    {
        return self::where('provider', $provider)
            ->where('provider_id', $oauthUser->getId())
            ->first();
    }

    public static function createForProvider(
        string $provider,
        SocialiteUserContract $oauthUser,
        Authenticatable $user
    ): self {
        return self::create([
            'provider' => $provider,
            'provider_id' => $oauthUser->getId(),
            'user_id' => $user->id,
            // Anda dapat menambahkan kolom tambahan seperti access_token, refresh_token, dll.
        ]);
    }
}