<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostPurchase extends Model
{
    use HasFactory;

    protected $table = 'cost_purchase_details';
    protected $fillable = [
        'cost_id',
        'currency',
        'currency_type',
        'currency_value_id',
        'part_name',
        'part_no',
        'over_head',
        'quantity',
        'total',
        'cost_value'
    ];

    public function cost():BelongsTo
    {
        return $this->belongsTo(Cost::class, 'cost_id', 'id');
    }

    public function currency():BelongsTo
    {
        return $this->belongsTo(CurrencyValue::class, 'currency_value_id', 'id');
    }

    public function currencyValue():BelongsTo
    {
        return $this->belongsTo(CurrencyValue::class, 'currency_value_id', 'id');
    }
}
