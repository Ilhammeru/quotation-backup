<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyValue extends Model
{
    use HasFactory;
    protected $table = 'currency_value';
    protected $fillable = ['currency_type_id', 'currency_group_id', 'period', 'value'];
    
    const SLIDE_TYPE = 1;
    const NON_SLIDE_TYPE = 2;
    const IDR = 1;
    const USD = 2;
    const JPY = 3;
    const THB = 4;
    
    const SLIDE_TEXT = 'slide';
    const NON_SLIDE_TEXT = 'non-slide';

    const GROUP = ['idr', 'usd', 'jpy', 'thb'];
    const GROUP_WITH_ID = [
        [
            'id' => CurrencyValue::IDR,
            'name' => 'IDR',
        ],
        [
            'id' => CurrencyValue::USD,
            'name' => 'USD',
        ],
        [
            'id' => CurrencyValue::JPY,
            'name' => 'JPY',
        ],
        [
            'id' => CurrencyValue::THB,
            'name' => 'THB',
        ],
    ];
    const TYPES = ['slide', 'non-slide'];
    const TYPES_WITH_ID = [
        [
            'id' => CurrencyValue::SLIDE_TYPE,
            'name' => 'Slide'
        ],
        [
            'id' => CurrencyValue::NON_SLIDE_TYPE,
            'name' => 'Non Slide'
        ],
    ];

    const KEY_TEMPLATE_1 = 'Template Material Item';
    const KEY_TEMPLATE_2 = "Don't delete this Header or change the column format";
}
