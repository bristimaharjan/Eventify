<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function chirps()
    {
        return $this->hasMany(Chirp::class);
    }

    public function savedEvents()
    {
        return $this->belongsToMany(Event::class, 'saved_events', 'user_id', 'event_id')->withTimestamps();
    }

    public function emailOtps()
    {
        return $this->hasMany(EmailOtp::class);
    }

    public function latestEmailOtp()
    {
        return $this->hasOne(EmailOtp::class)->latestOfMany();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'user_id');
    }

    public function vendorInquiries()
    {
        return $this->hasMany(Inquiry::class, 'vendor_id');
    }

    public function kyc()
    {
        return $this->hasOne(VendorKyc::class, 'user_id');
    }

    /**
     * Check if KYC is approved (non-vendors always return true).
     */
    public function isKycApproved(): bool
    {
        if ($this->role !== 'vendor') {
            return true;
        }

        return $this->kyc !== null && $this->kyc->status === 'approved';
    }

    /**
     * Get the current KYC status string.
     */
    public function kycStatus(): string
    {
        if ($this->role !== 'vendor') {
            return 'not_required';
        }

        return $this->kyc ? $this->kyc->status : 'not_submitted';
    }

    public function scopeRoles($query, array $roles)
    {
        return $query->whereIn('role', $roles);
    }

    /**
     * Get the resolved public URL for the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (!empty($this->profile_photo)) {
            if (file_exists(public_path('uploads/profile_photos/' . $this->profile_photo))) {
                return asset('uploads/profile_photos/' . $this->profile_photo);
            }
            if (file_exists(public_path('storage/' . $this->profile_photo))) {
                return asset('storage/' . $this->profile_photo);
            }
            if (str_starts_with($this->profile_photo, 'http://') || str_starts_with($this->profile_photo, 'https://')) {
                return $this->profile_photo;
            }
            if (file_exists(public_path($this->profile_photo))) {
                return asset($this->profile_photo);
            }
        }

        if (!empty($this->profile_image)) {
            return $this->profile_image;
        }

        if (file_exists(public_path('uploads/avatar.jpg'))) {
            return asset('uploads/avatar.jpg');
        }

        return null;
    }
}
