<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\AccountStatusChanged;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Auth;

class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
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

    protected $casts = [
        'is_active'=> 'boolean',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();

        // if ($panel->getId() === 'admin' && $roles->contains('admin')) {
        //     return true;
        // } elseif ($panel->getId() === 'merchant' && $roles->contains('merchant')) {
        //     if ($user->is_active === true) {
        //         return true;
        //     }
        //     return redirect('/');
        // } else {
        //     return false;
        // }
        if (
            ($panel->getId() === 'admin' && $roles->contains('admin')) ||
            ($panel->getId() === 'merchant' && $roles->contains('merchant'))
        ) {
            return true;
        }

        return false;


    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }

    // Method untuk mengirim email pemberitahuan
    public function sendAccountActivationEmail()
    {
        // Pastikan untuk mengganti dengan logic pengiriman email yang sesuai
        Mail::to($this->email)->send(new AccountStatusChanged($this));
    }
}
