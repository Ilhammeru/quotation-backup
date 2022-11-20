<p class="title-section-calculate mt-5">
    {{ __('view.purchase_cost_calculate_title') }} <span>{{ $data->number }}</span> 
</p>
<div class="content-table">
    <div class="header"></div>
    <div class="body">
        @include('adminLte.pages.cost.components.purchase.form')

        {{-- add to list table --}}
        <div class="row">
            <div class="col">
                <div class="text-right">
                    <button class="btn btn-sm bg-primary-blue border-radius-5" onclick="addPurchaseToList()" type="button">&#43; {{ __('view.add_to_list_table') }}</button>
                </div>
            </div>
        </div>

        {{-- table list material --}}
        @include('adminLte.pages.cost.components.purchase.table-list')
    </div>
</div>

@push('scripts')
    <script>
        const purchase_total_cost = 'purchase_total_pc_cost';
        const purchase_type_cost = 'purchase_currency_type_pc_cost';
        const purchase_group_cost = 'purchase_currency_pc_cost';

        $('#purchase_currency_type_pc_cost').select2();
        $('#purchase_currency_pc_cost').select2();

        $('#purchase_period_pc_cost').datepicker({
            format: 'mm-yyyy',
            autoclose: true,
            viewMode: 1,
            minViewMode: 1
        }).on('changeDate', function(e) {
            let val = new Date(e.date);
            let month = addZero(val.getMonth() + 1);
            let year = val.getFullYear();
            let full = month + '-' + year;
            clearCostingField('purchase');
            calculateRate(full, 'purchase');
        });

        $('#purchase_currency_pc_cost').on('select2:close', function(e) {
            e.preventDefault();
            let val = e.target.value;
            clearCostingField('purchase');
            calculateRate(null, 'purchase');
        });

        $('#purchase_currency_type_pc_cost').on('select2:close', function(e) {
            e.preventDefault();
            let val = e.target.value;
            clearCostingField('purchase');
            calculateRate(null, 'purchase');
        });

        function getCurrencyValue(payload) {
            $.ajax({
                type: 'POST',
                url: "{{ route('currency.get-rate-custom') }}",
                data: payload,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    if (res.data) {
                        $('#purchase_value_pc_cost').val(res.data.value);
                        $('#calculate_purchase_rate_id').val(res.data.id);
                    } else {
                        $('#purchase_value_pc_cost').val(0);
                        $('#calculate_purchase_rate_id').val('');
                    }
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function addPurchaseToList() {
            let prefix = 'purchase_';
            let suffix = '_pc_cost';
            let listElem = [
                'part_no',
                'part_name',
                'currency',
                'currency_type',
                'period',
                'value',
                'cost',
                'quantity',
                'over_head',
                'total'
            ];
            
            let tbody = document.getElementById('body-list-purchase');
            let tr = `<tr id="delete-purchase-cost-${tbody.rows.length - 1}">`;
            
            // define row number
            tr += `<td>${tbody.rows.length - 1}</td>`;

            let isValid = true;
            let helperMaterialPayload = null;
            let currency_value = $('#calculate_purchase_rate_id').val();

            for (let a = 0; a < listElem.length; a++) {
                let id = prefix + listElem[a] + suffix;
                let val = $(`#${id}`).val();
                let valForSubmit = $(`#${id}`).val();

                if (val == undefined || val == null || val == 0) {
                    isValid = false;
                    return setNotif(true, 'Please fill all form');
                }

                // custom value
                if (
                    id == purchase_type_cost ||
                    id == purchase_group_cost
                ) {
                    let exp = val.split(' @ ');
                    val = exp[1];
                    valForSubmit = exp[0];
                }

                // custom class for total
                let c = '';
                if (id == purchase_total_cost) {
                    c += 'purchase-total-per-item'
                }
                tr += `<td class="${c}">
                    ${val}
                    <input type="hidden" name="purchase[${tbody.rows.length - 2}][${listElem[a]}]" value="${valForSubmit}" />
                    </td>`;
            }

            tr += `<td class="text-center">
                        <button class="times btn btn-sm bg-primary-danger" type="button" onclick="deletePurchaseList(${tbody.rows.length - 1})">
                            &#215;
                        </button>
                        <input type="hidden" name="purchase[${tbody.rows.length - 2}][currency_value_id]" value="${currency_value}" />
                    </td>`;
            tr += '</tr>';

            if (isValid) {
                // append to table
                $(tr).insertBefore($('#body-list-purchase tr:last'));
                // show total list and set total
                setTotalListandToogle(
                    'purchase-total-per-item',
                    'purchase-empty-state',
                    'purchase-total-row',
                    'purchase-total-item',
                    'purchase-total-item-input'
                );
                // clear form
                $('#purchase_part_no_pc_cost').val('');
                $('#purchase_part_name_pc_cost').val('');
                $('#purchase_currency_pc_cost').val(null).trigger('change');
                $('#purchase_currency_type_pc_cost').val(null).trigger('change');
                $('#purchase_period_pc_cost').val('');
                $('#purchase_value_pc_cost').val(0);
                $('#purchase_cost_pc_cost').val(0);
                $('#purchase_quantity_pc_cost').val(0);
                $('#purchase_over_head_pc_cost').val(0);
                $('#purchase_total_pc_cost').val(0);
            }
        }

        function deletePurchaseList(id, all = false) {
            $(`#delete-purchase-cost-${id}`).remove();
            setTotalListandToogle(
                'purchase-total-per-item',
                'purchase-empty-state',
                'purchase-total-row',
                'purchase-total-item',
                'purchase-total-item-input'
            );
            // update summary component value
            addToSummary('purchase');
        }

        // ----------------------------------------------------- END MATERIAL COST -------------------------------------------------
    </script>
@endpush