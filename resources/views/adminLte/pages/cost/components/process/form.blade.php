<div class="material-cost-form">
    {{-- hidden form --}}
    <input type="hidden" id="calculate_process_rate_id">

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="process_part_no_p_cost" class="col-form-label label-grey">{{ __('view.child_part_no') }}</label>
                <input type="text" class="form-control form-control-sm" id="process_part_no_p_cost">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="process_part_name_p_cost" class="col-form-label label-grey">{{ __('view.child_part_name') }}</label>
                <input type="text" class="form-control form-control-sm" id="process_part_name_p_cost">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="process_group_p_cost" class="col-form-label label-grey">{{ __('view.process_group') }}</label>
                <select id="process_group_p_cost" class="form-control form-control-sm">
                    <option value="" selected disabled>-- {{ __('view.choose') }} --</option>
                    @foreach ($process_groups as $item)
                        <option value="{{ $item->id }} @ {{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="process_code_p_cost" class="col-form-label label-grey">{{ __('view.process_code') }}</label>
                <select name="" id="process_code_p_cost" class="form-control form-control-sm">
                    <option value="" selected disabled>-- {{ __('view.choose') }} --</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="process_rate_p_cost" class="col-form-label label-grey">{{ __('view.process_rate') }}</label>
                <input type="number" class="form-control form-control-sm cost-disabled" value="0" id="process_rate_p_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="process_cycle_time_p_cost" class="col-form-label label-grey">{{ __('view.cycle_time') }}</label>
                <input type="number" class="form-control form-control-sm" oninput="getTotal('process')" value="0" id="process_cycle_time_p_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="process_over_head_p_cost" class="col-form-label label-grey">{{ __('view.over_head') }}</label>
                <input type="number" class="form-control form-control-sm" oninput="getTotal('process')" value="0" id="process_over_head_p_cost">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="process_total_p_cost" class="col-form-label label-grey">{{ __('view.total') }}</label>
                <input type="number" class="form-control form-control-sm cost-disabled" oninput="getTotal('process')" value="0" id="process_total_p_cost">
            </div>
        </div>
    </div>
</div>