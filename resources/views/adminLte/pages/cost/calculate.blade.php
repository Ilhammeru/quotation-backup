@extends('layouts.base')

@section('content')
    <div style="padding-top: 70px;">
        <div class="card">
            <div class="card-body">
                <form action="" id="form-calculate">
                    {{-- begin::material-cost --}}
                    @include('adminLte.pages.cost.components.material.index')
                    {{-- end::material-cost --}}

                    {{-- begin::process-cost --}}
                    @include('adminLte.pages.cost.components.process.index')
                    {{-- end::process-cost --}}

                    {{-- begin::purchase-cost --}}
                    @include('adminLte.pages.cost.components.purchase.index')
                    {{-- end::purchase-cost --}}
    
                    {{-- begin::sumarry cost --}}
                    <p class="title-section-calculate" style="margin-top: 18px;">
                        {{ __('view.summary_cost_calculate_title') }} <span>{{ $data->number }}</span> 
                    </p>
                    <div class="content-table">
                        <div class="header"></div>
                        <div class="body">
                            @include('adminLte.pages.cost.components.summary.form')
    
                            {{-- add to list table --}}
                            <div class="row">
                                <div class="col">
                                    <div class="text-right">
                                        <button class="btn btn-sm bg-primary-blue border-radius-5" onclick="addSummaryToList()" type="button">&#43; {{ __('view.add_to_list_table') }}</button>
                                    </div>
                                </div>
                            </div>
    
                            {{-- table list material --}}
                            @include('adminLte.pages.cost.components.summary.table-list')
                        </div>
                    </div>
                    {{-- end::sumarry cost --}}
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // detect refresh page
        // window.onbeforeunload = function () {
        //     return 'You have unsaved work, it will be lost if you leave this page.';
        // }
        
        function calculateRate(dateVal = null, type) {
            let materialGroup, materialSpec, currency, period,
                processGroup, processCode, pcCurrency, pcCurrencyType,
                pcPeriod

            if (type == 'material') {

                materialGroup = $('#material_group_m_cost').val();
                materialSpec = $('#material_spec_m_cost').val();
                currency = $('#material_currency_m_cost').val();
                period;
                if (dateVal == null) {
                    period = $('#material_period_m_cost').val();
                } else {
                    period = dateVal;
                }

                if (
                    materialGroup &&
                    materialSpec &&
                    period
                ) {
                    getMaterialRate({
                        group: materialGroup,
                        spec: materialSpec,
                        period: period
                    });
                } 

                if (
                    currency &&
                    period
                ) {
                    getExchangeRate({
                        currency: currency,
                        period: period
                    });
                }
            } else if (type == 'process') {

                processGroup = $('#process_group_p_cost').val();
                processCode = $('#process_code_p_cost').val();

                if (
                    processCode &&
                    processGroup
                ) {
                    getProcessRate({
                        process_code: processCode,
                        process_group: processGroup
                    });
                }

            } else if (type == 'purchase') {

                pcCurrency = $('#purchase_currency_pc_cost').val();
                pcCurrencyType = $('#purchase_currency_type_pc_cost').val();
                if (dateVal == null) {
                    pcPeriod = $('#purchase_period_pc_cost').val();
                } else {
                    pcPeriod = dateVal;
                }
                
                if (
                    pcCurrency,
                    pcCurrencyType,
                    pcPeriod
                ) {
                    getCurrencyValue({
                        currency_group: pcCurrency,
                        currency_type: pcCurrencyType,
                        period: pcPeriod,
                        diff: ' @ '
                    });
                }

            }
        }

        function clearCostingField(type) {
            if (type == 'material') {
                $('#material_usage_part_m_cost').val(0);
                $('#material_over_head_m_cost').val(0);
                $('#material_total_m_cost').val(0);
            } else if (type == 'process') {
                $('#process_cycle_time_p_cost').val(0);
                $('#process_over_head_p_cost').val(0);
                $('#process_total_p_cost').val(0);
            } else if (type == 'purchase') {
                $('#purchase_cost_pc_cost').val(0);
                $('#purchase_quantity_pc_cost').val(0);
                $('#purchase_over_head_pc_cost').val(0);
                $('#purchase_total_pc_cost').val(0);
            }
        }

        function getTotal(type) {
            if (type == 'material') {

                let exchangeRate = $('#material_exchange_rate_m_cost').val();
                let usage = $('#material_usage_part_m_cost').val();
                let overHead = $('#material_over_head_m_cost').val();
                if (exchangeRate && usage && overHead) {
                    let total = (parseFloat((parseFloat(exchangeRate) * parseFloat(usage))) + parseFloat(overHead));
                    $('#material_total_m_cost').val(total.toFixed(3));
                }

            } else if (type == 'process') {

                let rate = parseFloat(
                    $('#process_rate_p_cost').val()
                );
                let cycleTime = parseFloat(
                    $('#process_cycle_time_p_cost').val()
                );
                let overHead = parseFloat(
                    $('#process_over_head_p_cost').val()
                );
                let total = (parseFloat(rate * cycleTime) + overHead);
                $('#process_total_p_cost').val(total.toFixed(3));

            } else if (type == 'purchase') {

                let currencyValue = parseFloat(
                    $('#purchase_value_pc_cost').val()
                );
                let cost = parseFloat(
                    $('#purchase_cost_pc_cost').val()
                );
                let qty = parseFloat(
                    $('#purchase_quantity_pc_cost').val()
                );
                let overHead = parseFloat(
                    $('#purchase_over_head_pc_cost').val()
                );
                let total = parseFloat(
                    (parseFloat(currencyValue * cost * qty) + overHead)
                );
                $('#purchase_total_pc_cost').val(total.toFixed(3));

            }
        }

        function addToSummary(type) {
            let val = 0;
            let form = null;
            let material = $('#summary_material_cost');
            let process = $('#summary_process_cost');
            let purchase = $('#summary_purchase_cost');
            let totalElem = $('#summary_total_cost');

            if (type == 'material') {
                val = $('.material-total-item-input').val();
                material.val(parseFloat(val));
            } else if (type == 'process') {
                val = $('.process-total-item-input').val();
                process.val(parseFloat(val));
            } else if (type == 'purchase') {
                val = $('.purchase-total-item-input').val();
                purchase.val(parseFloat(val));
            }

            // count all total
            let total = parseFloat(material.val()) +
                parseFloat(process.val()) +
                parseFloat(purchase.val());
            totalElem.val(total.toFixed(3));
        }

        function setTotalListandToogle(
            classTotalItemAll,
            classEmptyState,
            classTotalRow,
            classTotalItem,
            classTotalItemField
        ) {
            let allTotalElem = $(`.${classTotalItemAll}`);
            let allTotal = [];
            let total = 0;

            if (allTotalElem.length > 0) {
                // remove empty state
                $(`.${classEmptyState}`).addClass('d-none');

                for(let y = 0; y < allTotalElem.length; y++) {
                    allTotal.push(parseFloat(allTotalElem[y].innerHTML));
                }
                total = allTotal.reduce(function(a, b) { return a + b; });

                $(`.${classTotalRow}`).removeClass('d-none');
                $(`.${classTotalItem}`).html(total);
                $(`.${classTotalItemField}`).val(total);
            } else {
                // remove empty state
                $(`.${classEmptyState}`).removeClass('d-none');

                $(`.${classTotalItem}`).html(total);
                $(`.${classTotalItemField}`).val(0);
                $(`.${classTotalRow}`).addClass('d-none');
            }
        }

        function buildOption(data, elem, needToCustom = false) {
            let option = `<option value="" selected disabled>-- {{ __('view.choose') }} --</option>`;

            for (let a = 0; a < data.length; a++) {
                if (needToCustom) {
                    option += `<option value="${data[a].id} @ ${data[a].name}">${data[a].name}</option>`;
                } else {
                    option += `<option value="${data[a].id}">${data[a].name}</option>`;
                }
            }

            $(`#${elem}`).html(option);
        }

        function addSummaryToList() {
            let elem = [
                'mother_part_no',
                'mother_part_name',
                'summary_material_cost',
                'summary_process_cost',
                'summary_purchase_cost',
                'summary_total_cost'
            ];

            // manual validation
            if ($('#summary_total_cost').val() == 0) {
                return setNotif(true, 'Please fill all form');
            }

            let tbody = document.getElementById('body-list-summary');
            let tr = `<tr id="delete-summary-cost-${tbody.rows.length - 1}">`;

            // define row number
            tr += `<td>${tbody.rows.length - 1}</td>`;

            for (let a = 0; a < elem.length; a++) {
                let val = $(`#${elem[a]}`).val();

                let c = '';
                if (elem[a] == 'summary_total_cost') {
                    c += 'summary-total-per-item'
                }

                tr += `<td class="${c}">
                    ${val}
                    <input type="hidden" name="summary[${tbody.rows.length - 2}][${elem[a]}]" value="${val}" />
                    </td>`;
            }
            tr += `<td class="text-center">
                        <button class="times btn btn-sm bg-primary-danger" type="button" onclick="deleteSummaryList(${tbody.rows.length - 1})">
                            &#215;
                        </button>
                    </td>`;
            tr += '</tr>';

            // append to table
            $(tr).insertBefore($('#body-list-summary tr:last'));

            // hide empty state
            $('.summary-empty-state').addClass('d-none');

            // show total row
            $('.summary-total-row').removeClass('d-none');

            // reset form
            $('#summary_material_cost').val(0);
            $('#summary_process_cost').val(0);
            $('#summary_purchase_cost').val(0);
            $('#summary_total_cost').val(0);

            // set total item
            let allElemTotal = $('.summary-total-per-item');
            let allTotal = [];
            for (let b = 0; b < allElemTotal.length; b++) {
                allTotal.push(parseFloat(allElemTotal[b].innerHTML));
            }
            let total = allTotal.reduce(function(a, b) { return a + b; }).toFixed(3);
            console.log('total',total);
            $('.summary-total-item-input').val(total);
            $('.summary-total-item').html(total);
        }

        function submitCost() {
            let url = "{{ route('cost.submit.calculate', ':id') }}";
            url = url.replace(':id', "{{ $data->id }}");
            $.ajax({
                type: 'POST',
                url: url,
                data: $('#form-calculate').serialize(),
                beforeSend: function() {
                    
                },
                success: function(res) {
                    console.log('res',res)
                },
                error: function(err) {
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed to save data');
                }
            })
        }
    </script>
@endpush