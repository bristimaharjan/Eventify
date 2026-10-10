<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VendorKyc extends Model
{
    use HasFactory;

    protected $table = 'vendor_kycs';

    protected $fillable = [
        'user_id',
        'business_name',
        'pan_vat_number',
        'document_type',
        'document_front',
        'document_back',
        'company_registration_doc',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        'reviewed_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isNotSubmitted(): bool
    {
        return $this->status === 'not_submitted';
    }

    /**
     * Get URL for document front
     */
    public function getDocumentFrontUrlAttribute(): ?string
    {
        return $this->document_front
            ? route('vendor.kyc.document', ['kyc' => $this->id, 'type' => 'front'])
            : null;
    }

    /**
     * Get URL for document back
     */
    public function getDocumentBackUrlAttribute(): ?string
    {
        return $this->document_back
            ? route('vendor.kyc.document', ['kyc' => $this->id, 'type' => 'back'])
            : null;
    }

    /**
     * Get URL for company registration document
     */
    public function getCompanyRegistrationDocUrlAttribute(): ?string
    {
        return $this->company_registration_doc
            ? route('vendor.kyc.document', ['kyc' => $this->id, 'type' => 'company'])
            : null;
    }

    /**
     * Check if a given file path is a PDF
     */
    public static function isPdf(?string $path): bool
    {
        if (!$path) return false;
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
    }

    /**
     * Check if a given file path is an image
     */
    public static function isImageFile(?string $path): bool
    {
        if (!$path) return false;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    }
}
