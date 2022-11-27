<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cost extends Model
{
    use HasFactory;
    protected $table = 'cost';
    protected $fillable = [
        'name', 'number', 'total_cost', 'material_cost',
        'process_cost', 'purchase_cost'
    ];

    public function materials():HasMany
    {
        return $this->hasMany(CostMaterial::class, 'cost_id', 'id');
    }

    public function process():HasMany
    {
        return $this->hasMany(CostProcessDetail::class, 'cost_id', 'id');
    }

    public function purchases():HasMany
    {
        return $this->hasMany(CostPurchase::class, 'cost_id', 'id');
    }
}
