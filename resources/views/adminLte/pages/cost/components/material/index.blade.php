<p class="title-section-calculate">
    {{ __('view.material_cost_calculate_title') }} <span>{{ $data->number }}</span> 
</p>
<div class="content-table">
    <div class="header"></div>
    <div class="body">
        @include('adminLte.pages.cost.components.material.form')

        {{-- add to list table --}}
        <div class="row">
            <div class="col">
                <div class="text-right">
                    <button class="btn btn-sm bg-primary-blue border-radius-5" onclick="addMaterialtoList()" type="button">&#43; {{ __('view.add_to_list_table') }}</button>
                </div>
            </div>
        </div>

        {{-- table list material --}}
        @include('adminLte.pages.cost.components.material.table-list')
    </div>
</div>

@push('scripts')
    <script>
        const material_group_field = 'material_group_m_cost';
        const material_spec_field = 'material_spec_m_cost';
        const material_total_cost = 'material_total_m_cost';
        let deleteIdMaterial = [];

        $('#material_group_m_cost').select2();
        $('#material_spec_m_cost').select2();
        $('#material_currency_m_cost').select2();


        // ----------------------------------------------------- MATERIAL COST -------------------------------------------------
        $('#material_period_m_cost').datepicker({
            format: 'mm-yyyy',
            autoclose: true,
            viewMode: 1,
            minViewMode: 1
        }).on('changeDate', function(e) {
            let val = new Date(e.date);
            let month = addZero(val.getMonth() + 1);
            let year = val.getFullYear();
            let full = month + '-' + year;
            clearCostingField('material');
            calculateRate(full, 'material');
        });

        $('#material_spec_m_cost').on('select2:close', function(e) {
            e.preventDefault();
            let val = e.target.value;
            clearCostingField('material');
            calculateRate(null, 'material');
        });

        $('#material_group_m_cost').on('select2:close', function(e) {
            e.preventDefault()
            let val = e.target.value;
            getMaterialSpec(val);
            clearCostingField('material');
            calculateRate(null, 'material');
        });

        $('#material_currency_m_cost').on('select2:close', function(e) {
            e.preventDefault();
            let val = e.target.value;
            clearCostingField('material');
            calculateRate(null, 'material');
        });

        function getExchangeRate(payload) {
            $.ajax({
                type: 'POST',
                url: "{{ route('currency.get-rate') }}",
                data: payload,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    if (res.data) {
                        $('#material_exchange_rate_m_cost').val(res.data.value)
                        $('#calculate_material_currency_id').val(res.data.id);
                    } else {
                        $('#material_exchange_rate_m_cost').val(0)
                        $('#calculate_material_currency_id').val('');
                    }
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function getMaterialRate(payload) {
            $.ajax({
                type: 'POST',
                url: "{{ route('material.get-rate') }}",
                data: payload,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    if (res.data) {
                        $('#material_rate_m_cost').val(res.data.rate);
                        $('#calculate_material_rate_id').val(res.data.id);
                    } else {
                        $('#material_rate_m_cost').val(0);
                        $('#calculate_material_rate_id').val('');
                    }
                },
                error: function(err) {
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed get material rate');
                }
            })
        }

        function getMaterialSpec(groupId) {
            $.ajax({
                type: 'POST',
                url: "{{ route('material.search-spec-by-group') }}",
                data: {
                    group_id: groupId
                },
                beforeSend: function() {
                    
                },
                success: function(res) {
                    buildOption(res.data, 'material_spec_m_cost', true);   
                },
                error: function(err) {
                    setNotif(true, err.responseJSON ? err.responseJSON.message : '');
                }
            })
        }

        function addMaterialtoList() {
            let prefix = 'material_';
            let suffix = '_m_cost';
            let listElem = [
                'part_no',
                'part_name',
                'group',
                'spec',
                'period',
                'rate',
                'exchange_rate',
                'usage_part',
                'over_head',
                'total'
            ];
            
            let tbody = document.getElementById('body-list-material');
            let tr = `<tr id="delete-material-cost-${tbody.rows.length - 1}">`;
            
            // define row number
            tr += `<td>${tbody.rows.length - 1}</td>`;

            let isValid = true;
            let helperMaterialPayload = null;
            let material_rate = $('#calculate_material_rate_id').val();
            let currency_value = $('#calculate_material_currency_id').val();

            for (let a = 0; a < listElem.length; a++) {
                let id = prefix + listElem[a] + suffix;
                let val = $(`#${id}`).val();
                let valForSubmit = $(`#${id}`).val();

                if (val == undefined || val == null || val == 0) {
                    isValid = false;
                    return setNotif(true, 'Please fill all form');
                }

                // custom value
                if (id == material_group_field || id == material_spec_field) {
                    let exp = val.split(' @ ');
                    val = exp[1];
                    valForSubmit = exp[0];
                }

                // custom class for total
                let c = '';
                if (id == material_total_cost) {
                    c += 'material-total-per-item'
                }
                tr += `<td class="${c}">
                    ${val}
                    <input type="hidden" name="material[${tbody.rows.length - 2}][${listElem[a]}]" value="${valForSubmit}" />
                    </td>`;
            }

            tr += `<td class="text-center">
                        <button class="times btn btn-sm bg-primary-danger" type="button" onclick="deleteMaterialList(${tbody.rows.length - 1})">
                            &#215;
                        </button>
                        <input type="hidden" name="material[${tbody.rows.length - 2}][material_rate_id]" value="${material_rate}" />
                        <input type="hidden" name="material[${tbody.rows.length - 2}][material_currency_value_id]" value="${currency_value}" />
                    </td>`;
            tr += '</tr>';

            if (isValid) {
                // append to table
                $(tr).insertBefore($('#body-list-material tr:last'));
                // show total list and set total
                setTotalListandToogle(
                    'material-total-per-item',
                    'material-empty-state',
                    'material-total-row',
                    'material-total-item',
                    'material-total-item-input'
                );
                // clear form
                $('#material_part_no_m_cost').val('');
                $('#material_part_name_m_cost').val('');
                $('#material_currency_m_cost').val(null).trigger('change');
                $('#material_group_m_cost').val(null).trigger('change');
                $('#material_spec_m_cost').val(null).trigger('change');
                $('#material_period_m_cost').val('');
                $('#material_rate_m_cost').val(0);
                $('#material_exchange_rate_m_cost').val(0);
                $('#material_usage_part_m_cost').val(0);
                $('#material_over_head_m_cost').val(0);
                $('#material_total_m_cost').val(0);
            }
        }

        function deleteMaterialList(id, fromEdit = false, uid) {
            $(`#delete-material-cost-${id}`).remove();
            setTotalListandToogle(
                'material-total-per-item',
                'material-empty-state',
                'material-total-row',
                'material-total-item',
                'material-total-item-input'
            );

            if (fromEdit) {
                addToSummary('material');
                deleteIdMaterial.push(uid);
                $('#delete_id_material').val(deleteIdMaterial);
            }
        }

        // ----------------------------------------------------- END MATERIAL COST -------------------------------------------------
    </script>
@endpush