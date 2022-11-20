<div class="material-cost-form">
    {{-- hidden form --}}
    <input type="hidden" id="calculate_material_rate_id">
    <input type="hidden" id="calculate_material_currency_id">

    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="material_part_no_m_cost" class="col-form-label label-grey">{{ __('view.child_part_no') }}</label>
                <input type="text" class="form-control form-control-sm" id="material_part_no_m_cost">
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label for="material_part_name_m_cost" class="col-form-label label-grey">{{ __('view.child_part_name') }}</label>
                <input type="text" class="form-control form-control-sm" id="material_part_name_m_cost">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="material_currency_m_cost" class="col-form-label label-grey">{{ __('view.currency') }}</label>
                <select id="material_currency_m_cost" class="form-control form-control-sm">
                    <option value="" selected disabled>-- {{ __('view.choose') }} --</option>
                    @foreach ($currency as $item)
                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="material_group_m_cost" class="col-form-label label-grey">{{ __('view.material_group') }}</label>
                <select id="material_group_m_cost" class="form-control form-control-sm">
                    <option value="" selected disabled>-- {{ __('view.choose') }} --</option>
                    @foreach ($material_groups as $group)
                        <option value="{{ $group->id }} @ {{ $group->name }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="material_spec_m_cost" class="col-form-label label-grey">{{ __('view.spec') }}</label>
                <select id="material_spec_m_cost" class="form-control form-control-sm">
                    <option value="" selected disabled>-- {{ __('view.choose') }} --</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-1">
            <div class="form-group">
                <label for="material_period_m_cost" class="col-form-label label-grey">{{ __('view.period') }}</label>
                <input type="text" class="form-control form-control-sm" id="material_period_m_cost">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="material_rate_m_cost" class="col-form-label label-grey">{{ __('view.material_rate') }}</label>
                <input type="number" oninput="getTotal('material')" class="form-control form-control-sm cost-disabled" value="0" id="material_rate_m_cost">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="material_exchange_rate_m_cost" class="col-form-label label-grey">{{ __('view.exchange_rate') }}</label>
                <input type="number" oninput="getTotal('material')" class="form-control form-control-sm cost-disabled" value="0" id="material_exchange_rate_m_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="material_usage_part_m_cost" class="col-form-label label-grey">{{ __('view.usage_part') }}</label>
                <input type="number" oninput="getTotal('material')" class="form-control form-control-sm" value="0" id="material_usage_part_m_cost">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="material_over_head_m_cost" class="col-form-label label-grey">{{ __('view.over_head') }}</label>
                <input type="number" oninput="getTotal('material')" class="form-control form-control-sm" value="0" id="material_over_head_m_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="material_total_m_cost" class="col-form-label label-grey">{{ __('view.total') }}</label>
                <input type="number" class="form-control form-control-sm cost-disabled" value="0" id="material_total_m_cost">
            </div>
        </div>
    </div>
</div>