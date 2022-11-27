<div class="row mt-5">
    <div class="col">
        <p class="title-table-cost">List Material Cost</p>
        <div class="table-responsive">
            <table class="table table-cost">
                <thead>
                    <tr>
                        <th>{{ __('view.no') }}</th>
                        <th>{{ __('view.part_no') }}</th>
                        <th>{{ __('view.part_name') }}</th>
                        <th>{{ __('view.material_group') }}</th>
                        <th>{{ __('view.spec') }}</th>
                        <th>{{ __('view.period') }}</th>
                        <th>{{ __('view.material_rate') }}</th>
                        <th>{{ __('view.exchange_rate') }}</th>
                        <th>{{ __('view.usage_part') }}</th>
                        <th>{{ __('view.o/h') }}</th>
                        <th>{{ __('view.total') }}</th>
                        <th>{{ __('view.action') }}</th>
                    </tr>
                </thead>
                <tbody id="body-list-material">
                    <tr class="material-empty-state {{ $has_materials ? 'd-none' : '' }}">
                        <td colspan="12" class="text-center">{{ __('view.empty_data') }}</td>
                    </tr>
                    @if ($type_form == 'edit')
                        @foreach ($materials as $key => $material)
                        <tr id="delete-material-cost-{{ $key + 1 }}">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ $material->part_no }}
                                <input type="hidden" name="material[{{ $key }}][part_no]" value="{{ $material->part_no }}" />
                            </td>
                            <td>
                                {{ $material->part_name }}
                                <input type="hidden" name="material[{{ $key }}][part_name]" value="{{ $material->part_name }}" />
                            </td>
                            <td>
                                {{ $material->rate->material->name }}
                                <input type="hidden" name="material[{{ $key }}][group]" value="{{ $material->rate->id }}" />
                            </td>
                            <td>
                                {{ $material->rate->materialSpec->specification }}
                                <input type="hidden" name="material[{{ $key }}][spec]" value="{{ $material->rate->material_spec_id }}" />
                            </td>
                            <td>
                                {{ date('M y', strtotime($material->rate->period)) }}
                                <input type="hidden" name="material[{{ $key }}][period]" value="{{ $material->rate->period }}" />
                            </td>
                            <td>
                                {{ $material->rate->rate }}
                                <input type="hidden" name="material[{{ $key }}][rate]" value="{{ $material->rate->rate }}" />
                            </td>
                            <td>
                                {{ $material->exchange_rate }}
                                <input type="hidden" name="material[{{ $key }}][exchange_rate]" value="{{ $material->exchange_rate }}" />
                            </td>
                            <td>
                                {{ $material->usage_part }}
                                <input type="hidden" name="material[{{ $key }}][usage_part]" value="{{ $material->usage_part }}" />
                            </td>
                            <td>
                                {{ $material->over_head }}
                                <input type="hidden" name="material[{{ $key }}][over_head]" value="{{ $material->over_head }}" />
                            </td>
                            <td class="material-total-per-item">
                                {{ $material->total }}
                                <input type="hidden" name="material[{{ $key }}][total]" value="{{ $material->total }}" />
                            </td>
                            <td class="text-center">
                                <button class="times btn btn-sm bg-primary-danger"
                                    type="button"
                                    onclick="deleteMaterialList({{ $key + 1 }}, true, {{ $material->id }})">
                                    &#215;
                                </button>
                                <input type="hidden" name="material[{{ $key }}][material_rate_id]" value="{{ $material->material_rate_id }}" />
                                <input type="hidden" name="material[{{ $key }}][material_currency_value_id]" value="{{ $material->material_currency_value_id }}" />
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    <tr class="material-total-row {{ !$has_materials ? 'd-none' : '' }}">
                        <td colspan="10"><b>{{ __('view.total') }}</b></td>
                        <td class="material-total-item">
                            {{ $type_form == 'edit' ? $data->material_cost : 0 }}
                        </td>
                        <input type="hidden" class="material-total-item-input" value="{{ $type_form == 'edit' ? $data->material_cost : 0 }}">
                        <td class="cell-disabled"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-right">
            <button class="btn btn-sm bg-primary-success" type="button" onclick="addToSummary('material')">&#43; {{ __('view.add_to_summary_cost') }}</button>
        </div>
    </div>
</div>