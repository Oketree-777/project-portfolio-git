<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $table = 'personal_info';

    protected $fillable = [
        'user_id',
        'title',
        'title_en',
        'first_name', 
        'last_name',
        'first_name_en',
        'last_name_en',
        'age',
        'gender',
        'faculty',
        'major',
        'education_level',
        'study_plan',
        'institution',
        'province',
        'gpa',
        'subject_groups',
        'subject_gpa',
        'national_id',
        'house_number',
        'village_no',
        'road',
        'sub_district',
        'district',
        'province_address',
        'postal_code',
        'phone',
        'major_code',
        'major_name',
        'program',
        'receipt_book_no',
        'receipt_no',
        'amount',
        'documents',
        'verifier_name',
        'recipient_name',
        'photo',
        'portfolio_cover',
        'portfolio_file',
        'portfolio_filename',
        'views',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at'
    ];

    protected $casts = [
        'subject_groups' => 'array',
        'subject_gpa' => 'array',
        'documents' => 'array',
        'gpa' => 'decimal:2',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return $this->title . ' ' . $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get faculty and major
     */
    public function getEducationAttribute()
    {
        return $this->faculty . ' - ' . $this->major;
    }

    /**
     * Get full name in English
     */
    public function getFullNameEnAttribute()
    {
        return ($this->title == 'นาย' ? 'MR.' : 'Miss') . ' ' . $this->first_name_en . ' ' . $this->last_name_en;
    }

    /**
     * Get complete address
     */
    public function getCompleteAddressAttribute()
    {
        $address = [];
        if ($this->house_number) $address[] = 'บ้านเลขที่ ' . $this->house_number;
        if ($this->village_no) $address[] = 'หมู่ ' . $this->village_no;
        if ($this->road) $address[] = 'ถนน ' . $this->road;
        if ($this->sub_district) $address[] = 'ตำบล ' . $this->sub_district;
        if ($this->district) $address[] = 'อำเภอ ' . $this->district;
        if ($this->province_address) $address[] = 'จังหวัด ' . $this->province_address;
        if ($this->postal_code) $address[] = 'รหัสไปรษณีย์ ' . $this->postal_code;
        
        return implode(' ', $address);
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Approval methods
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function approve($userId)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
            'rejection_reason' => null,
            'rejected_by' => null,
            'rejected_at' => null
        ]);
    }

    public function reject($userId, $reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejected_by' => $userId,
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'approved_by' => null,
            'approved_at' => null
        ]);
    }

    public function cancelApproval($userId)
    {
        $this->update([
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'rejected_by' => null,
            'rejected_at' => null,
            'rejection_reason' => null
        ]);
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="badge bg-warning">รอการอนุมัติ</span>';
            case 'approved':
                return '<span class="badge bg-success">อนุมัติแล้ว</span>';
            case 'rejected':
                return '<span class="badge bg-danger">ไม่อนุมัติ</span>';
            default:
                return '<span class="badge bg-secondary">ไม่ทราบสถานะ</span>';
        }
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'รอการอนุมัติ';
            case 'approved':
                return 'อนุมัติแล้ว';
            case 'rejected':
                return 'ไม่อนุมัติ';
            default:
                return 'ไม่ทราบสถานะ';
        }
    }

    /**
     * Increment views
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get formatted views count
     */
    public function getFormattedViewsAttribute()
    {
        $views = $this->views;
        if ($views >= 1000000) {
            return round($views / 1000000, 1) . 'M';
        } elseif ($views >= 1000) {
            return round($views / 1000, 1) . 'K';
        }
        return $views;
    }

    /**
     * Get analytics for this portfolio
     */
    public function analytics()
    {
        return $this->hasMany(Analytics::class, 'portfolio_id');
    }

    /**
     * Get ratings for this portfolio
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        $ratings = $this->ratings;
        if ($ratings->isEmpty()) {
            return 0;
        }
        return round($ratings->avg('rating'), 1);
    }

    /**
     * Get average rating percentage
     */
    public function getAverageRatingPercentageAttribute()
    {
        return round(($this->average_rating / 5) * 100, 1);
    }

    /**
     * Get total ratings count
     */
    public function getTotalRatingsAttribute()
    {
        return $this->ratings->count();
    }

    /**
     * Get rating distribution
     */
    public function getRatingDistributionAttribute()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $this->ratings->where('rating', $i)->count();
        }
        return $distribution;
    }

    /**
     * Get user's rating for this portfolio
     */
    public function getUserRating($userId)
    {
        return $this->ratings()->where('user_id', $userId)->first();
    }
}
