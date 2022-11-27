@php
    [$has_materials, $has_process, $has_purchases] = [false, false, false];    
    if (count($materials) > 0) {
        $has_materials = true;
    }
@endphp

@extends('layouts.base')

@section('content')
    <div style="padding-top: 70px;">
        <div class="card">
            <div class="card-body">
                <form action="" id="form-calculate">

                    {{-- begin::material-cost --}}
                    @include('adminLte.pages.cost.components.material.index', ['has_materials' => $has_materials])
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

                {{-- download excel --}}
                @if ($type_form == 'edit')
                    <div class="text-right">
                        <a href="{{ route('cost.download', $data->id) }}" class="btn btn-sm bg-primary-warning mt-3">{{ __('view.download_excel') }}</a>
                    </div>
                @endif
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
                pcPeriod;

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
            let isValid = false;
            let material = $('#summary_material_cost');
            let process = $('#summary_process_cost');
            let purchase = $('#summary_purchase_cost');
            let totalElem = $('#summary_total_cost');

            if (type == 'material') {
                val = $('.material-total-item-input').val();
                material.val(
                    val != 0 ?
                    parseFloat(val).toFixed(3) :
                    0
                );
                isValid = val != 0 ? true : false;
            } else if (type == 'process') {
                val = $('.process-total-item-input').val();
                process.val(
                    val != 0 ?
                    parseFloat(val).toFixed(3) :
                    0
                );
                isValid = val != 0 ? true : false;
            } else if (type == 'purchase') {
                val = $('.purchase-total-item-input').val();
                purchase.val(
                    val != 0 ?
                    parseFloat(val).toFixed(3) :
                    0
                );
                isValid = val != 0 ? true : false;
            }

            // count all total
            let total = parseFloat(material.val()) +
                parseFloat(process.val()) +
                parseFloat(purchase.val());
            totalElem.val(
                total != 0 ?
                total.toFixed(3) :
                0
            );

            // notify if success
            if (isValid) {
                setNotif(false, 'Successfully added data to Summary');
            }
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
                total = allTotal.reduce(function(a, b) { return a + b; }).toFixed(3);

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
            let childTotalElem = [
                'material-total-item-input',
                'process-total-item-input',
                'purchase-total-item-input',
            ];
            let helper = [
                'summary_material_cost',
                'summary_process_cost',
                'summary_purchase_cost',
            ];

            // manual validation
            if ($('#summary_total_cost').val() == 0) {
                return setNotif(true, 'Please fill all form');
            }
            let err_item = [];
            for (let z = 0; z < childTotalElem.length; z++) {
                let v = $(`.${childTotalElem[z]}`).val();
                let p = $(`#${helper[z]}`).val();
                if (v != p) {
                    return setNotif(true, 'Please check your item again. You still have item that you have not submitted');
                }
            }

            let tbody = document.getElementById('body-list-summary');
            let tr = `<tr id="delete-summary-cost-${tbody.rows.length - 1}">`;

            // define row number
            tr += `<td>${tbody.rows.length - 1}</td>`;

            for (let a = 0; a < elem.length; a++) {
                let itemElem = $(`.td-${elem[a]}`);
                let itemElemField = $(`#td-${elem[a]}_field`);
                let val = $(`#${elem[a]}`).val();

                if (elem[a] != 'summary_total_cost') {
                    if (
                        elem[a] != 'mother_part_no' &&
                        elem[a] != 'mother_part_name'
                    ) {
                        // if (val != 0) {
                        // }
                        itemElem[0].innerHTML = val;
                        itemElemField.val(val);
                    } else {
                        itemElem[0].innerHTML = val;
                        itemElemField.val(val);
                    }
                }

            }

            // show item row
            $('.row-main-summary').removeClass('d-none');

            // hide empty state
            $('.summary-empty-state').addClass('d-none');

            // show total row
            $('.summary-total-row').removeClass('d-none');

            // reset form
            // $('#summary_material_cost').val(0);
            // $('#summary_process_cost').val(0);
            // $('#summary_purchase_cost').val(0);
            // $('#summary_total_cost').val(0);

            // set total item
            let materialCost = parseFloat(
                $('#td-summary_material_cost_field').val() 
            );
            let processCost = parseFloat(
                $('#td-summary_process_cost_field').val()
            );
            let purchaseCost = parseFloat(
                $('#td-summary_purchase_cost_field').val()
            );
            let total = parseFloat(materialCost + processCost + purchaseCost).toFixed(3);
            $('.td-summary_total_cost')[0].innerHTML = total;
            $('#td-summary_total_cost_field').val(total);
            $('.summary-total-item-input').val(total);
            $('.summary-total-item').html(total);
        }

        function submitCost() {
            let url = "{{ route('cost.submit.calculate', ':id') }}";
            url = url.replace(':id', "{{ $data->id }}");

            // validation
            let total = $('.summary-total-item-input').val();
            if (total == 0) {
                return setNotif(true, 'You do not have data on summary cost yet');
            }
            if (
                $('#td-summary_total_cost_field').val() != $('#summary_total_cost').val() ||
                $('.material-total-item-input').val() != $('#summary_material_cost').val() ||
                $('.process-total-item-input').val() != $('#summary_process_cost').val() ||
                $('.purchase-total-item-input').val() != $('#summary_purchase_cost').val()
            ) {
                return setNotif(true, 'Your total is not balance');
            }

            $.ajax({
                type: 'POST',
                url: url,
                data: $('#form-calculate').serialize(),
                beforeSend: function() {
                    setLoading('summary-submit-cost-btn', true);
                },
                success: function(res) {
                    setLoading('summary-submit-cost-btn', false);
                    setNotif(false, res.message);
                    window.location.href = res.url;
                },
                error: function(err) {
                    setLoading('summary-submit-cost-btn', false);
                    return false;
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed to save data');
                }
            })
        }

        function deleteSummaryList(id) {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this data?',
                text: "If you delete this data, all calculate cost data will be deleted too",
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes! Delete it',
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('cost.delete', ':id') }}";
                    url = url.replace(':id', id);
                    $.ajax({
                        type: 'delete',
                        url: url,
                        success: function(res) {
                            setNotif(false, res.message);
                            window.location.href = res.url;
                        },
                        error: function(err) {
                            setNotif(true, err.responseJSON == undefined ? err.responseText : err.responseJSON.message);
                        }
                    })
                }
            })
        }
    </script>
@endpush