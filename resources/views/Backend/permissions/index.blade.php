@extends('Backend.layouts.master')
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/vendor/selectPicker/css/picker.css') }}">
@stop
@section('content')
    <div class="mt-10">
        <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
            {{ __('general.Add') }}
        </a>

        <button class="btn btn-danger" id="multi_delete">
            {{ __('general.Delete') }}
        </button>

        @include('Backend.permissions.create')
        @include('Backend.permissions.edit')
    </div>
    @include('Backend.permissions.filter.filter')
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.name') }}</th>
                    <th>{{ __('general.Action') }}</th>
                </tr>
            </thead>

        </table>
    </div>
@stop
@section('script')
    <script type="text/javascript" src="{{ asset('backend/vendor/selectPicker/js/picker.js') }}"></script>
    {{-- <script>
        $(document).ready(function() {
            $('#role_id').picker({
                search: true
            });
            $('#edit_role_id').picker({
                search: true
            });
        });
    </script> --}}

    {{-- Yajra dataTable config --}}
    <script>
        $(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ route('permissions.indexTable') }}',
                    data: function(data){
                        data.name = $('#searchName').val(),
                        data.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            $('#search').on('click', function() {
                $('#dataTable').DataTable().draw();
            });

            $('#reset').on('click', function() {
                $('#searchName').val('');
                $('#dataTable').DataTable().draw();
            });
        });
    </script>

    {{-- update permission using ajax --}}
    <script>
        $(document).on('click', '#editBtn', function(event) {
            var data = $(this).data();
            console.log(data);
            var permission = $(this).data("id");
            var name = $(this).data("name");

            $('#editModal').modal('show');

            $("#permission").attr("value", permission);
            $('#name').attr("value", name);

            var roles_ids = $(this).data('roles') + ''
            if (roles_ids.indexOf(",") >= 0) {
                roles = ($(this).data('roles').split(','))
                roles = roles.filter(item => item);
                console.log(roles);
            }

            $("#edit_role_id").val(roles).trigger('change');
        });
    </script>

@stop
