@extends('Backend.layouts.master')
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/vendor/selectPicker/css/picker.css') }}">
@stop
@section('content')
    <div class="mt-10">
        @can('admin_permission')
            <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                {{ __('general.Add') }}
            </a>

            <button class="btn btn-danger" id="multi_delete">
                {{ __('general.Delete') }}
            </button>

            <button class="btn btn-success" id="active_button">
                {{ __('general.Active') }}
            </button>

            <button class="btn btn-dark" id="disactive_button">
                {{ __('general.Disactive') }}
            </button>
        @endcan
        @include('Backend.users.create')
        @include('Backend.users.edit')
    </div>
    @include('Backend.users.filter.filter')
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.name') }}</th>
                    <th>{{ __('general.email') }}</th>
                    <th>{{ __('general.Status') }}</th>
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
            $('#permission').picker({
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
                    url: '{{ route('users.indexTable') }}',
                    data: function(data) {
                        data.name = $('#searchName').val(),
                            data.email = $('#searchEmail').val(),
                            data.status = $('#searchStatus').val(),
                            data.search = $('input[type="search"]').val()
                    },
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
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'status',
                        name: 'status',
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
                $('#searchEmail').val('');
                $('#searchStatus').val('');
                $('#dataTable').DataTable().draw();
            });
        });
    </script>

    {{-- update user using ajax --}}
    <script>
        $(function() {
            $(document).on('click', '#editBtn', function(event) {
                var data = $(this).data();
                var id = $(this).data("id");
                var name = $(this).data("name");
                var email = $(this).data("email");

                $('#editModal').modal('show');
                $('#user_id').attr("value", id);
                $('#name').attr("value", name);
                $('#email').attr("value", email);
                $('#status').val($(this).data("status")).trigger('change');

                var perm_ids = $(this).data('permissions') + ''
                if (perm_ids.indexOf(",") >= 0) {
                    permissions = ($(this).data('permissions').split(','))
                    permissions = permissions.filter(item => item);
                    console.log(permissions);
                }

                $('#edit_permission_id').val(permissions).trigger('change');
            });
        });
    </script>
@stop
