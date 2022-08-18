@extends('Backend.layouts.master')
@section('content')
    <div class="mt-10">
        @canany(['create_coupoun', 'admin_permission'])
            <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                {{ __('general.Add') }}
            </a>
        @endcanany

        @canany(['delete_coupoun', 'admin_permission'])
            <button class="btn btn-danger" id="multi_delete">
                {{ __('general.Delete') }}
            </button>
        @endcanany

        @canany(['active_coupoun', 'admin_permission'])
            <button class="btn btn-success" id="active_button">
                {{ __('general.Active') }}
            </button>
        @endcanany

        @canany(['disactive_coupoun', 'admin_permission'])
            <button class="btn btn-dark" id="disactive_button">
                {{ __('general.Disactive') }}
            </button>
        @endcanany

        @include('Backend.coupouns.create')
        @include('Backend.coupouns.edit')
    </div>
    @include('Backend.coupouns.filter.filter')
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.code') }}</th>
                    <th>{{ __('general.type') }}</th>
                    <th>{{ __('general.value') }}</th>
                    <th>{{ __('general.use_times') }}</th>
                    <th>{{ __('general.Status') }}</th>
                    <th>{{ __('general.Action') }}</th>
                </tr>
            </thead>

        </table>
    </div>
@stop
@section('script')

    {{-- Yajra dataTable config --}}
    <script>
        $(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ route('coupouns.indexTable') }}',
                    data: function(data) {
                            data.code = $('#searchCode').val();
                            data.status = $('#searchStatus').val(),
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
                        data: 'code',
                        name: 'code',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'type',
                        name: 'type',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'value',
                        name: 'value',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'use_times',
                        name: 'use_times',
                        orderable: false,
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
                $('#searchCode').val('');
                $('#searchStatus').val('');
                $('#dataTable').DataTable().draw();
            });

            $('#code').keyup(function(){
                this.value = this.value.toUpperCase();
            });
        });
    </script>

    {{-- update coupoun using ajax --}}
    <script>
        $(function() {
            $(document).on('click', '#editBtn', function(event) {
                var data = $(this).data();

                console.log(data);
                var coupouns_id = $(this).data("id");
                var code = $(this).data("code");
                var value = $(this).data("value");

                $('#editModal').modal('show');

                $('#coupouns_id').attr("value", coupouns_id);
                $('#edit_code').attr("value", code);
                $('#value').attr("value", value);

                $('#type').val($(this).data("type")).trigger('change');
                $('#status').val($(this).data("status")).trigger('change');

                $('#edit_code').keyup(function(){
                    this.value = this.value.toUpperCase();
                });
            });
        });
    </script>
@stop
