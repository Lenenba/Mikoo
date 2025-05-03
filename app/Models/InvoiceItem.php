<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'work_id',
        'description',
        'hours',
        'unit_price',
        'amount',
    ];

    protected $casts = [
        'hours' => 'integer',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];
    /**
     * Get the invoice associated with the invoice item.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    /**
     * Get the work associated with the invoice item.
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
