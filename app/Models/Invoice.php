<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{

    protected $fillable = [
        'client_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'total',
        'status',
        'notes',
    ];


    protected static function booted()
    {
        static::creating(function ($invoice) {
            $invoice->uuid = Str::uuid();
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
