<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostProcessDetail extends Model
{
    use HasFactory;
    protected $table = 'cost_process_details';
    protected $fillable = [
        'cost_id',
        'part_no',
        'part_name',
        'process_rate_id',
        'cycle_time',
        'over_head',
        'total',
    ];

    public function cost():BelongsTo
    {
        return $this->belongsTo(Cost::class, 'cost_id', 'id');
    }

    public function rate():BelongsTo
    {
        return $this->belongsTo(ProcessRate::class, 'process_rate_id', 'id');
    }
}
