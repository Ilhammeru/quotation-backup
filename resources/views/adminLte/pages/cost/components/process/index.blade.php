<p class="title-section-calculate mt-5">
    {{ __('view.process_cost_calculate_title') }} <span>{{ $data->number }}</span> 
</p>
<div class="content-table">
    <div class="header"></div>
    <div class="body">
        @include('adminLte.pages.cost.components.process.form')

        {{-- add to list table --}}
        <div class="row">
            <div class="col">
                <div class="text-right">
                    <button class="btn btn-sm bg-primary-blue border-radius-5" onclick="addProcessToList()" type="button">&#43; {{ __('view.add_to_list_table') }}</button>
                </div>
            </div>
        </div>

        {{-- table list material --}}
        @include('adminLte.pages.cost.components.process.table-list')
    </div>
</div>

@push('scripts')
    <script>
        const process_total_cost = 'process_total_p_cost';
        const process_code_cost = 'process_code_p_cost';
        const process_group_cost = 'process_group_p_cost';

        $('#process_group_p_cost').select2();
        $('#process_code_p_cost').select2();

        // ----------------------------------------------------- MATERIAL COST -------------------------------------------------

        $('#process_code_p_cost').on('select2:close', function(e) {
            e.preventDefault();
            let val = e.target.value;
            clearCostingField('process');
            calculateRate(null, 'process');
        });

        $('#process_group_p_cost').on('select2:close', function(e) {
            e.preventDefault()
            let val = e.target.value;
            getProcessCode(val);
            clearCostingField('process');
            calculateRate(null, 'process');
        });

        function getProcessRate(payload) {
            $.ajax({
                type: 'POST',
                url: "{{ route('process.get-rate') }}",
                data: payload,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    if (res.data) {
                        $('#process_rate_p_cost').val(res.data.rate);
                        $('#calculate_process_rate_id').val(res.data.id);
                    } else {
                        $('#process_rate_p_cost').val(0);
                        $('#calculate_process_rate_id').val('');
                    }
                },
                error: function(err) {
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed to get process rate');
                }
            })
        }

        function getProcessCode(groupId) {
            $.ajax({
                type: 'POST',
                url: "{{ route('process.search-code-by-group') }}",
                data: {
                    group_id: groupId
                },
                beforeSend: function() {
                    
                },
                success: function(res) {
                    buildOption(res.data, 'process_code_p_cost', true);   
                },
                error: function(err) {
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Failed to get process code');
                }
            })
        }

        function addProcessToList() {
            let prefix = 'process_';
            let suffix = '_p_cost';
            let listElem = [
                'part_no',
                'part_name',
                'group',
                'code',
                'rate',
                'cycle_time',
                'over_head',
                'total'
            ];
            
            let tbody = document.getElementById('body-list-process');
            let tr = `<tr id="delete-process-cost-${tbody.rows.length - 1}">`;
            
            // define row number
            tr += `<td>${tbody.rows.length - 1}</td>`;

            let isValid = true;
            let helperMaterialPayload = null;
            let process_rate = $('#calculate_process_rate_id').val();

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
                    id == process_code_cost ||
                    id == process_group_cost
                ) {
                    let exp = val.split(' @ ');
                    val = exp[1];
                    valForSubmit = exp[0];
                }

                // custom class for total
                let c = '';
                if (id == process_total_cost) {
                    c += 'process-total-per-item'
                }
                tr += `<td class="${c}">
                    ${val}
                    <input type="hidden" name="process[${tbody.rows.length - 2}][${listElem[a]}]" value="${valForSubmit}" />
                    </td>`;
            }

            tr += `<td class="text-center">
                        <button class="times btn btn-sm bg-primary-danger" type="button" onclick="deleteProcessList(${tbody.rows.length - 1})">
                            &#215;
                        </button>
                        <input type="hidden" name="process[${tbody.rows.length - 2}][process_rate_id]" value="${process_rate}" />
                    </td>`;
            tr += '</tr>';

            if (isValid) {
                // append to table
                $(tr).insertBefore($('#body-list-process tr:last'));
                // show total list and set total
                setTotalListandToogle(
                    'process-total-per-item',
                    'process-empty-state',
                    'process-total-row',
                    'process-total-item',
                    'process-total-item-input'
                );
                // clear form
                $('#process_part_no_p_cost').val('');
                $('#process_part_name_p_cost').val('');
                $('#process_group_p_cost').val(null).trigger('change');
                $('#process_code_p_cost').val(null).trigger('change');
                $('#process_rate_p_cost').val(0);
                $('#process_cycle_time_p_cost').val(0);
                $('#process_over_head_p_cost').val(0);
                $('#process_total_p_cost').val(0);
            }
        }

        function deleteProcessList(id, all = false) {
            $(`#delete-process-cost-${id}`).remove();
            setTotalListandToogle(
                'process-total-per-item',
                'process-empty-state',
                'process-total-row',
                'process-total-item',
                'process-total-item-input'
            );
        }

        // ----------------------------------------------------- END MATERIAL COST -------------------------------------------------
    </script>
@endpush