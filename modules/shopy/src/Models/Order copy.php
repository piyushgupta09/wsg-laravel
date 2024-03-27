<?php

namespace Fpaipl\Shopy\Models;

use App\Models\User;
use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Authy\Models\Address;
use Fpaipl\Panel\Traits\BelongsToUser;
use Fpaipl\Shopy\Models\Payment;
use Fpaipl\Shopy\Models\Delivery;
use Spatie\Activitylog\LogOptions;
use Fpaipl\Shopy\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use Authx, SoftDeletes, BelongsToUser, LogsActivity;

    protected $fillable = [
        'oid', 
        'user_id', 
        'status', 
        'total',
        'amount',
        'tax',
        'tags',
        'billing_address'
    ];

    const STATUS =['pending', 'processing', 'completed', 'cancelled'];

    protected $attributes = [
        'status' => self::STATUS[0],
    ];

    public function getRouteKeyName()
    {
        return 'oid';
    }

    public function getSid() {
        return $this->oid;
    }

    public function computeApprovedPayment(){
        return $this->orderPayments()->where('status', Payment::STATUS[1])->sum('amount');
    }

    public function computePendingPayment(){
        return $this->orderPayments()->where('status', Payment::STATUS[0])->sum('amount');
    }

    public function computeApprovedDelivery(){
        return $this->orderDeliveries()->where('status', Delivery::STATUS[1])->sum('amount');
    }

    public function computePendingDelivery(){
        return $this->orderDeliveries()->where('status', Delivery::STATUS[0])->sum('amount');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS[0]);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS[1]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS[2]);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS[3]);
    }

    /**
    * Relationship
    */

    public function approvedPayments()
    {
        return $this->orderPayments()->where('status', Payment::STATUS[1])->sum('amount');
    }

    // we consider it as approved for few purpose to avoide confusion and unnessary refund requests
    public function unapprovedPayments()
    {
        return $this->orderPayments()->where('status', Payment::STATUS[0])->sum('amount');
    }

    public function rejectedPayments()
    {
        return $this->orderPayments()->where('status', Payment::STATUS[3])->sum('amount');
    }

    public function payableAmount()
    {
        return $this->total - $this->approvedPayments() - $this->unapprovedPayments();
    }

    public function deliveryStarted() {
        return $this->orderDeliveries()->where('status', Delivery::STATUS[1])->exists();
    }

    public function deliveryCompleted() {
        return $this->orderDeliveries()->where('status', Delivery::STATUS[2])->exists();
    }

    public function paymentStatus() {
        if($this->unapprovedPayments() > 0) {
            // User has made atleast one payment, which is not approved yet by admin
            // or user has made payment also approved but is not equal to total amount
            return 'in-process';
        } else if($this->approvedPayments() == $this->total) {
            // User has made payment and is approved by admin and is equal to total amount
            return 'paid';
        } else {
            // User has not made any payment yet
            return 'unpaid';
        }
    }

    public function paymentStatusImage() {
        switch ($this->paymentStatus()) {
            case 'unpaid': return asset('storage/assets/status/payment-0.png');
            case 'in-process': return asset('storage/assets/status/payment-1.png');
            case 'paid': return asset('storage/assets/status/payment-2.png');
        }
    }

    public function deliveryStatus() {
        if($this->deliveryStarted()) {
            // Atleast one delivery has status updated as shipped
            return 'in-process';
        } else if($this->deliveryCompleted()) {
            // Atleast one delivery has status updated as delivered
            return 'delivered';
        } else {
            // No delivery has status updated as shipped or delivered, so all are pending
            return 'pending';
        }
    }

    public function deliveryStatusImage() {
        switch ($this->deliveryStatus()) {
            case 'pending': return asset('storage/assets/status/delivery-0.png');
            case 'in-process': return asset('storage/assets/status/delivery-1.png');
            case 'delivered': return asset('storage/assets/status/delivery-2.png');
        }
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orderProducts(){
        return $this->hasMany(OrderProduct::class);
    }

    public function billingAddress(){
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function orderDeliveries(){
        return $this->hasMany(Delivery::class);
    }

    public function orderPayments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDatetimeString($value)
    {
        if ($value == 'created_at') {
            return  $this->$value->diffForHumans();
        } else {
            return $this->$value->format('d-m-Y h:i:s A');
        }
        
    }
    // Logging

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('model_log');
    }
}
