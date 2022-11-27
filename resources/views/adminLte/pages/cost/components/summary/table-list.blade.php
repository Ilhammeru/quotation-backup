<div class="row mt-5">
    <div class="col">
        <p class="title-table-cost">List Summary Cost</p>
        <div class="table-responsive">
            <table class="table table-cost">
                <thead>
                    <tr>
                        <th>{{ __('view.no') }}</th>
                        <th>{{ __('view.part_no') }}</th>
                        <th>{{ __('view.part_name') }}</th>
                        <th>{{ __('view.material_cost_calculate_title') }}</th>
                        <th>{{ __('view.process_cost_calculate_title') }}</th>
                        <th>{{ __('view.purchase_cost_calculate_title') }}</th>
                        <th>{{ __('view.total') }}</th>
                        <th>{{ __('view.action') }}</th>
                    </tr>
                </thead>
                <tbody id="body-list-summary">
                    <tr class="summary-empty-state {{ $type_form != 'edit' ? '' : 'd-none' }}">
                        <td colspan="8" class="text-center">{{ __('view.empty_data') }}</td>
                    </tr>
                    <tr class="row-main-summary {{ $type_form == 'edit' ? '' : 'd-none' }}">
                        <td>1</td>
                        <td>
                            <span class="td-mother_part_no">{{ $type_form == 'edit' ? $data->number : '' }}</span>
                            <input type="hidden" name="summary[mother_part_no]" id="td-mother_part_no_field" value="{{ $type_form == 'edit' ? $data->number : '' }}">
                        </td>
                        <td>
                            <span class="td-mother_part_name">{{ $type_form == 'edit' ? $data->name : '' }}</span>
                            <input type="hidden" name="summary[mother_part_name]" id="td-mother_part_name_field" value="{{ $type_form == 'edit' ? $data->name : '' }}">
                        </td>
                        <td>
                            <span class="td-summary_material_cost">{{ $type_form == 'edit' ? $data->material_cost : 0 }}</span>
                            <input type="hidden" name="summary[material]" id="td-summary_material_cost_field" value="{{ $type_form == 'edit' ? $data->material_cost : 0 }}">
                        </td>
                        <td>
                            <span class="td-summary_process_cost">{{ $type_form == 'edit' ? $data->process_cost : 0 }}</span>
                            <input type="hidden" name="summary[process]" id="td-summary_process_cost_field" value="{{ $type_form == 'edit' ? $data->process_cost : 0 }}">
                        </td>
                        <td>
                            <span class="td-summary_purchase_cost">{{ $type_form == 'edit' ? $data->purchase_cost : 0 }}</span>
                            <input type="hidden" name="summary[purchase]" id="td-summary_purchase_cost_field" value="{{ $type_form == 'edit' ? $data->purchase_cost : 0 }}">
                        </td>
                        <td>
                            <span class="td-summary_total_cost">{{ $type_form == 'edit' ? $data->total_cost : 0 }}</span> 
                            <input type="hidden" name="summary[total]" id="td-summary_total_cost_field" value="{{ $type_form == 'edit' ? $data->total_cost : 0 }}">
                        </td>
                        <td class="text-center">
                            @if ($type_form == 'edit')
                                <button class="times btn btn-sm bg-primary-danger" type="button" onclick="deleteSummaryList({{ $data->id }})">
                                    &#215;
                                </button>
                            @endif
                        </td>
                    </tr>
                    <tr class="summary-total-row {{ $type_form == 'edit' ? '' : 'd-none' }}">
                        <td colspan="6"><b>{{ __('view.total') }}</b></td>
                        <td class="summary-total-item">
                            {{ $type_form == 'edit' ? $data->total_cost : 0 }}
                        </td>
                        <input type="hidden" class="summary-total-item-input" value="{{ $type_form == 'edit' ? $data->total_cost : 0 }}">
                        <td class="cell-disabled"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-right">
            <button class="btn btn-sm bg-primary-success" type="button" onclick="submitCost()"  id="summary-submit-cost-btn">{{ __('view.submit') }}</button>
        </div>
    </div>
</div>