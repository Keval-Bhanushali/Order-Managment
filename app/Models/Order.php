<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = ['customer_id', 'total_amount', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->status)) {
                $order->status = self::STATUS_PENDING;
            }
        });
    }

    public static function getAllowedStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED
        ];
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
