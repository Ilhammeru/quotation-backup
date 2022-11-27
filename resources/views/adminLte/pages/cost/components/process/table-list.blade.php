<div class="row mt-5">
    <div class="col">
        <p class="title-table-cost">List Process Cost</p>
        <div class="table-responsive">
            <table class="table table-cost">
                <thead>
                    <tr>
                        <th>{{ __('view.no') }}</th>
                        <th>{{ __('view.part_no') }}</th>
                        <th>{{ __('view.part_name') }}</th>
                        <th>{{ __('view.process_group') }}</th>
                        <th>{{ __('view.process_code') }}</th>
                        <th>{{ __('view.process_rate') }}</th>
                        <th>{{ __('view.cycle_time') }}</th>
                        <th>{{ __('view.o/h') }}</th>
                        <th>{{ __('view.total') }}</th>
                        <th>{{ __('view.action') }}</th>
                    </tr>
                </thead>
                <tbody id="body-list-process">
                    <tr class="process-empty-state {{ $type_form != 'edit' ? '' : 'd-none' }}">
                        <td colspan="10" class="text-center">{{ __('view.empty_data') }}</td>
                    </tr>
                    @if ($type_form == 'edit')
                        @foreach ($process as $key => $p)
                            <tr id="delete-process-cost-{{ $key + 1 }}">
                                <td>
                                    {{ $key + 1 }}
                                </td>
                                <td>
                                    {{ $p->part_no }}
                                    <input type="hidden" name="process[{{ $key }}][part_no]" value="{{ $p->part_no }}" />
                                </td>
                                <td>
                                    {{ $p->part_name }}
                                    <input type="hidden" name="process[{{ $key }}][part_name]" value="{{ $p->part_name }}" />
                                </td>
                                <td>
                                    {{ $p->rate->group->name }}
                                    <input type="hidden" name="process[{{ $key }}][group]" value="{{ $p->rate->group->name }}" />
                                </td>
                                <td>
                                    {{ $p->rate->code->name }}
                                    <input type="hidden" name="process[{{ $key }}][code]" value="{{ $p->rate->code->name }}" />
                                </td>
                                <td>
                                    {{ $p->rate->rate }}
                                    <input type="hidden" name="process[{{ $key }}][rate]" value="{{ $p->rate->rate }}" />
                                </td>
                                <td>
                                    {{ $p->cycle_time }}
                                    <input type="hidden" name="process[{{ $key }}][cycle_time]" value="{{ $p->cycle_time }}" />
                                </td>
                                <td>
                                    {{ $p->over_head }}
                                    <input type="hidden" name="process[{{ $key }}][over_head]" value="{{ $p->over_head }}" />
                                </td>
                                <td class="process-total-per-item">
                                    {{ $p->total }}
                                    <input type="hidden" name="process[{{ $key }}][total]" value="{{ $p->total }}" />
                                </td>
                                <td class="text-center">
                                    <button class="times btn btn-sm bg-primary-danger" type="button" onclick="deleteProcessList({{ $key + 1 }}, true, {{ $p->id }})">
                                        &#215;
                                    </button>
                                    <input type="hidden" name="process[{{ $key }}][process_rate_id]" value="{{ $p->rate->id }}" />
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr class="process-total-row d-none">
                        <td colspan="8"><b>{{ __('view.total') }}</b></td>
                        <td class="process-total-item">
                            {{ $type_form == 'edit' ? $data->process_cost : 0 }}
                        </td>
                        <input type="hidden" class="process-total-item-input" value="{{ $type_form == 'edit' ? $data->process_cost : 0 }}">
                        <td class="cell-disabled"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-right">
            <button class="btn btn-sm bg-primary-success" type="button" onclick="addToSummary('process')">&#43; {{ __('view.add_to_summary_cost') }}</button>
        </div>
    </div>
</div>