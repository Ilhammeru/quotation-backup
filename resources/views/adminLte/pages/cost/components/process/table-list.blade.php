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
                    <tr class="process-empty-state">
                        <td colspan="10" class="text-center">{{ __('view.empty_data') }}</td>
                    </tr>
                    {{-- <tr>
                        <td>1</td>
                        <td>PN-01 456</td>
                        <td>Plastic Seed</td>
                        <td>Resin</td>
                        <td>PP-2/AZ564GL STD COLOR</td>
                        <td>April 18</td>
                        <td>21990</td>
                        <td>21990</td>
                        <td>0,03</td>
                        <td>0,15</td>
                        <td>659,86</td>
                        <td class="text-center">
                            <button class="times btn btn-sm bg-primary-danger">
                                &#215;
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>PN-01 456</td>
                        <td>Plastic Seed</td>
                        <td>Resin</td>
                        <td>PP-2/AZ564GL STD COLOR</td>
                        <td>April 18</td>
                        <td>21990</td>
                        <td>21990</td>
                        <td>0,03</td>
                        <td>0,15</td>
                        <td>659,86</td>
                        <td class="text-center">
                            <button class="times btn btn-sm bg-primary-danger">
                                &#215;
                            </button>
                        </td>
                    </tr> --}}
                    <tr class="process-total-row d-none">
                        <td colspan="8"><b>{{ __('view.total') }}</b></td>
                        <td class="process-total-item">
                            0
                        </td>
                        <input type="hidden" class="process-total-item-input">
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