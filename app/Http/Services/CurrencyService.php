<?php

namespace App\Http\Services;

use App\Models\CurrencyGroup;
use App\Models\CurrencyValue;

class CurrencyService {
    /**
     * Function to get list of currency data
     * @param string type
     * @param string group
     */
    public function list($type, $group)
    {
        try {
            // get id of type and group

        } catch (\Throwable $th) {
            return [
                'error' => true,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Funciton to get id of given type
     * @param string $type
     * @return void
     */
    public function getTypeId($type)
    {
        $types = CurrencyValue::TYPES;
        return collect($types)->filter(function($item) use ($type) {
            return $item == $type;
        })->map(function($i) {
            $var = null;
            if ($i == CurrencyValue::SLIDE_TEXT) {
                $var = CurrencyValue::SLIDE_TYPE;
            } else if ($i == CurrencyValue::NON_SLIDE_TEXT) {
                $var = CurrencyValue::NON_SLIDE_TYPE;
            }
            return $var;
        })->values()[0];
    }

    /**
     * Funciton to get id of given group
     * @param string group
     * @return void
     */
    public function getGroupId($group)
    {
        $groups = CurrencyValue::GROUP;
        $text = collect($groups)->filter(function($item) use ($group) {
            return strtolower($item) == strtolower($group);
        })->values()[0];
        $data = CurrencyGroup::where('name', $text)->first();
        return $data ? $data->id : null;
    }
}