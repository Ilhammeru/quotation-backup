<div class="row mt-5">
    <div class="col">
        <p class="title-table-cost">List Purchase Cost</p>
        <div class="table-responsive">
            <table class="table table-cost">
                <thead>
                    <tr>
                        <th>{{ __('view.no') }}</th>
                        <th>{{ __('view.part_no') }}</th>
                        <th>{{ __('view.part_name') }}</th>
                        <th>{{ __('view.currency') }}</th>
                        <th>{{ __('view.type_currency') }}</th>
                        <th>{{ __('view.period') }}</th>
                        <th>{{ __('view.value_currency') }}</th>
                        <th>{{ __('view.cost') }}</th>
                        <th>{{ __('view.quantity') }}</th>
                        <th>{{ __('view.o/h') }}</th>
                        <th>{{ __('view.total') }}</th>
                        <th>{{ __('view.action') }}</th>
                    </tr>
                </thead>
                <tbody id="body-list-purchase">
                    <tr class="purchase-empty-state {{ $type_form != 'edit' ? '' : 'd-none' }}">
                        <td colspan="12" class="text-center">{{ __('view.empty_data') }}</td>
                    </tr>
                    @if ($type_form == 'edit')
                        @foreach ($purchases as $key => $p)
                            <tr id="delete-purchase-cost-{{ $key + 1 }}">
                                <td>
                                    {{ $key + 1 }}
                                </td>
                                <td>
                                    {{ $p->part_no }}
                                    <input type="hidden" name="purchase[{{ $key }}][part_no]" value="{{ $p->part_no }}" />
                                </td>
                                <td>
                                    {{ $p->part_name }}
                                    <input type="hidden" name="purchase[{{ $key }}][part_name]" value="{{ $p->part_name }}" />
                                </td>
                                <td>
                                    {{ $p->currencyValue->currency_group_name }}
                                    <input type="hidden" name="purchase[{{ $key }}][currency]" value="{{ $p->currencyValue->currency_group_id }}" />
                                </td>
                                <td>
                                    {{ $p->currencyValue->currency_type_name }}
                                    <input type="hidden" name="purchase[{{ $key }}][currency_type]" value="{{ $p->currencyValue->currency_type_id }}" />
                                </td>
                                <td>
                                    {{ date('M y', strtotime($p->currencyValue->period)) }}
                                    <input type="hidden" name="purchase[{{ $key }}][period]" value="{{ $p->currencyValue->period }}" />
                                </td>
                                <td>
                                    {{ $p->currencyValue->value }}
                                    <input type="hidden" name="purchase[{{ $key }}][value]" value="{{ $p->currencyValue->value }}" />
                                </td>
                                <td>
                                    {{ $p->cost_value }}
                                    <input type="hidden" name="purchase[{{ $key }}][cost]" value="{{ $p->cost_value }}" />
                                </td>
                                <td>
                                    {{ $p->quantity }}
                                    <input type="hidden" name="purchase[{{ $key }}][quantity]" value="{{ $p->quantity }}" />
                                </td>
                                <td>
                                    {{ $p->over_head }}
                                    <input type="hidden" name="purchase[{{ $key }}][over_head]" value="{{ $p->over_head }}" />
                                </td>
                                <td class="purchase-total-per-item">
                                    {{ $p->total }}
                                    <input type="hidden" name="purchase[{{ $key }}][total]" value="{{ $p->total }}" />
                                </td>
                                <td class="text-center">
                                    <button class="times btn btn-sm bg-primary-danger" type="button" onclick="deletePurchaseList({{ $key + 1 }}, true, {{ $p->id }})">
                                        &#215;
                                    </button>
                                    <input type="hidden" name="purchase[{{ $key }}][currency_value_id]" value="{{ $p->currency_value_id }}" />
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr class="purchase-total-row {{ $type_form == 'edit' ? '' : 'd-none' }}">
                        <td colspan="10"><b>{{ __('view.total') }}</b></td>
                        <td class="purchase-total-item">
                            {{ $type_form == 'edit' ? $data->purchase_cost : 0 }}
                        </td>
                        <input type="hidden" class="purchase-total-item-input" value="{{ $type_form == 'edit' ? $data->purchase_cost : 0 }}">
                        <td class="cell-disabled"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-right">
            <button class="btn btn-sm bg-primary-success" type="button" onclick="addToSummary('purchase')">&#43; {{ __('view.add_to_summary_cost') }}</button>
        </div>
    </div>
</div>