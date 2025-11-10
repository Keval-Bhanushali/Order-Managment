<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'stock'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if ($product->stock < 0) {
                throw new \Exception('Product stock cannot be negative');
            }
        });

        static::updating(function ($product) {
            if ($product->stock < 0) {
                throw new \Exception('Product stock cannot be negative');
            }
        });
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
