<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostMaterial extends Model
{
    use HasFactory;
    protected $table = 'cost_material_details';
    protected $fillable = [
        'cost_id',
        'part_no',
        'part_name',
        'material_rate_id',
        'material_currency_value_id',
        'exchange_rate',
        'usage_part',
        'over_head',
        'total',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function cost():BelongsTo
    {
        return $this->belongsTo(Cost::class, 'cost_id', 'id');
    }

    public function rate():BelongsTo
    {
        return $this->belongsTo(MaterialRate::class, 'material_rate_id', 'id');
    }

    public function currency():BelongsTo
    {
        return $this->belongsTo(CurrencyValue::class, 'material_currency_value_id', 'id');
    }
}
