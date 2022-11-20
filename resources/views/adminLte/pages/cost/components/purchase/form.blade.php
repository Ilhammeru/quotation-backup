<div class="purchase-cost-form">
    {{-- hidden form --}}
    <input type="hidden" id="calculate_purchase_rate_id">

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="purchase_part_no_pc_cost" class="col-form-label label-grey">{{ __('view.child_part_no') }}</label>
                <input type="text" class="form-control form-control-sm" id="purchase_part_no_pc_cost">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="purchase_part_name_pc_cost" class="col-form-label label-grey">{{ __('view.child_part_name') }}</label>
                <input type="text" class="form-control form-control-sm" id="purchase_part_name_pc_cost">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="purchase_currency_pc_cost" class="col-form-label label-grey">{{ __('view.qurrency') }}</label>
                <select id="purchase_currency_pc_cost" class="form-control form-control-sm">
                    <option value="" selected disabled>-- {{ __('view.choose') }} --</option>
                    @foreach ($currency_group as $item)
                        <option value="{{ $item['id'] }} @ {{ $item['name'] }}">{{ $item['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="purchase_currency_type_pc_cost" class="col-form-label label-grey">{{ __('view.type_currency') }}</label>
                <select name="" id="purchase_currency_type_pc_cost" class="form-control form-control-sm">
                    <option value="" selected disabled>-- {{ __('view.choose') }} --</option>
                    @foreach ($currency_types as $item)
                        <option value="{{ $item['id'] }} @ {{ $item['name'] }}">{{ $item['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="purchase_period_pc_cost" class="col-form-label label-grey">{{ __('view.period') }}</label>
                <input type="text" class="form-control form-control-sm" id="purchase_period_pc_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="purchase_value_pc_cost" class="col-form-label label-grey">{{ __('view.value_currency') }}</label>
                <input type="number" class="form-control form-control-sm cost-disabled" readonly value="0" id="purchase_value_pc_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="purchase_cost_pc_cost" class="col-form-label label-grey">{{ __('view.cost') }}</label>
                <input type="number" oninput="getTotal('purchase')" class="form-control form-control-sm" value="0" id="purchase_cost_pc_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="purchase_quantity_pc_cost" class="col-form-label label-grey">{{ __('view.quantity') }}</label>
                <input type="number" oninput="getTotal('purchase')" class="form-control form-control-sm" value="0" id="purchase_quantity_pc_cost">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="purchase_over_head_pc_cost" class="col-form-label label-grey">{{ __('view.over_head') }}</label>
                <input type="number" oninput="getTotal('purchase')" class="form-control form-control-sm" value="0" id="purchase_over_head_pc_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="purchase_total_pc_cost" class="col-form-label label-grey">{{ __('view.total') }}</label>
                <input type="number" oninput="getTotal('purchase')" class="form-control form-control-sm cost-disabled" readonly value="0" id="purchase_total_pc_cost">
            </div>
        </div>
    </div>
</div>