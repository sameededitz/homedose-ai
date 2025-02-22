<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable, HasApiTokens, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'last_login',
        'google_id',
        'apple_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['avatar_url']; // Automatically append avatar URL

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk('media')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg']);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        $media = $this->getFirstMedia('avatar');
        return $media ? $media->getUrl() : null;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function activePlan()
    {
        return $this->hasOne(Purchase::class)->where('status', 'active')->where('end_date', '>', now())->latest();
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }
}
