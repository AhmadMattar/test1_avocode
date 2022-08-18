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

        @include('Backend.roles.create')
        @include('Backend.roles.edit')
    </div>

    @include('Backend.roles.filter.filter')
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
    {{-- Yajra dataTable config --}}
    <script>
        $(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ route('roles.indexTable') }}',
                    data: function(data) {
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

    {{-- update country using ajax --}}
    <script>
        $(document).on('click', '#editBtn', function(event) {
            var data = $(this).data();
            console.log(data);
            var role_id = $(this).data("id");
            var name = $(this).data("name");
            // var permision_id = $(this).data("permission_id")

            // console.log(permision_id);
            $('#editModal').modal('show');

            $("#role_id").attr("value", role_id);
            $('#name').attr("value", name);

            var perm_ids = $(this).data('permission_id') + ''
            if (perm_ids.indexOf(",") >= 0) {
                permissions = ($(this).data('permission_id').split(','))
                permissions = permissions.filter(item => item);
                console.log(permissions);
            }

            $("#edit_permission_id").val(permissions).trigger('change');
        });
    </script>

@stop
