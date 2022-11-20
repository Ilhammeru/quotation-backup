<div class="summary-cost-form">
    <div class="row">
        <div class="col-2">
            <div class="form-group">
                <label for="mother_part_no" class="col-form-label label-grey">{{ __('view.mother_part_no') }}</label>
                <input type="text" value="{{ $data->number }}" class="form-control form-control-sm" id="mother_part_no">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="mother_part_name" class="col-form-label label-grey">{{ __('view.mother_part_name') }}</label>
                <input type="text" value="{{ $data->name }}" class="form-control form-control-sm" id="mother_part_name">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="summary_material_cost" class="col-form-label label-grey">{{ __('view.material_cost_calculate_title') }}</label>
                <input type="text" value="0" class="form-control form-control-sm" id="summary_material_cost">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="summary_process_cost" class="col-form-label label-grey">{{ __('view.process_cost_calculate_title') }}</label>
                <input type="text" value="0" class="form-control form-control-sm" id="summary_process_cost">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="summary_purchase_cost" class="col-form-label label-grey">{{ __('view.purchase_cost_calculate_title') }}</label>
                <input type="text" value="0" class="form-control form-control-sm" id="summary_purchase_cost">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="summary_total_cost" class="col-form-label label-grey">{{ __('view.total') }}</label>
                <input type="number" value="0" class="form-control form-control-sm cost-disabled" readonly id="summary_total_cost">
            </div>
        </div>
    </div>
</div>