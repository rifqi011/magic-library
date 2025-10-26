<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;

    protected $fillable = [
        'member_code',
        'name',
        'gender',
        'birth_date',
        'address',
        'phone',
        'email',
        'join_date',
        'status',
        'created_by',
        'image',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
    ];

    protected static function booted()
    {
        static::updating(function ($member) {
            if ($member->isDirty('image') && $member->getOriginal('image')) {
                Storage::disk('public')->delete($member->getOriginal('image'));
            }
        });

        static::deleting(function ($member) {
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getRouteKeyName()
    {
        return 'member_code';
    }
}
