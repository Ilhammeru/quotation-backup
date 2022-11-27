<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyGroup extends Model
{
    use HasFactory;
    protected $table = 'currency_group';
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
}
