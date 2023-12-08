<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'reservation_id',
        'customer_id',
        'user_id',
        'total',
        'is_paid',
        'paid_at',
    ];


    public function orderDetail(): HasOne
    {
        return $this->hasOne(OrderDetail::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function waiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
