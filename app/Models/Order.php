<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'status',
        'user_id',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_APPROVED = 'approved';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_APPROVED,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
