@extends('layouts.base')

@section('pageTitle')
    {!! $pageTitle !!}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-item bg-primary-blue d-flex align-items-center" type="button" onclick="openModal('create')">+ {{ __('view.create_permission') }}</button>
            </div>

            <div class="table-responsive">
                <table class="table {{ dt_table_class() }}" id="table-permissions">
                    <thead class="{{ dt_head_class() }}">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- begin::modal --}}
    <div class="modal modal-q fade" id="modalAddPermission"  data-keyboard="false" tabindex="-1" aria-labelledby="modalAddPermissionLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content p-0 bg-transparent border-none">
            <div class="modal-body p-0 m-0">
                <div class="modal-custom-header">
                    <p class="title">+ Add Data</p>
                </div>
                {{-- begin::form --}}
                <form id="form-material">
                    <div class="body">
                        @include('adminLte.pages.setting.permissions.form')
                    </div>
                    <div class="footer d-flex align-items-end justify-content-end">
                        <button class="btn btn-sm bg-primary-success" type="button" onclick="save()" id="btn-submit">{{ __('view.submit') }}</button>
                        <button class="btn btn-sm bg-primary-danger ml-2" type="button" onclick="closeModal('create')">{{ __('view.close') }}</button>
                    </div>
                </form>
                {{-- end::form --}}
                {{-- <button type="button" class="btn btn-primary">{{ __('view.submit') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('view.close') }}</button> --}}
            </div>
        </div>
        </div>
    </div>
    {{-- end::modal --}}
@endsection

@push('scripts')
    <script>
        let dt_route = "{{ route('setting.permissions.ajax') }}";
        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    width: '5%' 
                },
                {data: 'name', name: 'name', width: '90%'},
                {data: 'action', name: 'action', width: '5%'},
        ];
        let dt = setDataTable('table-permissions', columns, dt_route);

        function openModal(type) {
            if (type == 'create') {
                $('#modalAddPermission').modal('show');
            }
        }
    </script>
@endpush