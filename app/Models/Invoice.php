<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;
    protected $fillable = [
        'babysitter_id',
        'period_start',
        'period_end',
        'total_amount',
        'status',
    ];
    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_amount' => 'decimal:2',
    ];
    /**
     * Get the babysitter associated with the invoice.
     */
    public function babysitter()
    {
        return $this->belongsTo(User::class, 'babysitter_id');
    }
    /**
     * Get the invoice items associated with the invoice.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
