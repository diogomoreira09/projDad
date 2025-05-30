<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'nickname',
        'photo_filename',
        'type',
        'blocked',
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

    public function games_player1(): HasMany
    {
        return $this->hasMany(Game::class, 'player1_id')
            ->orderBy('id', 'asc');
    }

    public function games_player2(): HasMany
    {
        return $this->hasMany(Game::class, 'player2_id')
            ->orderBy('id', 'asc');
    }

    public function games() : HasMany
    {
        return $this->games_player1()->union($this->games_player2()->toBase())
            ->orderBy('id', 'asc');
    }

    public function games_won(): HasMany
    {
        return $this->hasMany(Game::class, 'winner_id')
            ->orderBy('id', 'asc');
    }
}
