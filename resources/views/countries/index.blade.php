@extends('layouts.master')
@section('content')
    <div class="mt-10">
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
        @include('countries.create')
        @include('countries.edit')
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.name') }}</th>
                    <th>{{ __('general.code') }}</th>
                    <th>Status</th>
                    <th>{{ __('general.photo') }}</th>
                    <th>Action</th>
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
                searching: true,
                ajax: {
                    url: '{{ route('countries.data') }}',
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
                        data: 'code',
                        name: 'code',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'cover',
                        name: 'cover',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
    {{-- update country using ajax --}}
    <script>
        $(document).on('click', '#editBtn', function(event) {
            var data = $(this).data();
            var country_id = $(this).data("id");
            var name_en = $(this).data("name_en");
            var name_ar = $(this).data("name_ar");
            var code = $(this).data("code");
            var status = $(this).data("status");
            var cover = $(this).data("cover");

            $('#editModal').modal('show');
            $('#country_id').attr("value", country_id);
            $('#name_en').attr("value", name_en);
            $('#name_ar').attr("value", name_ar);
            $('#code').attr("value", code);
            $('#status').val($(this).data("status")).trigger('change');

            $('#country-cover').attr("src", cover);
        });
    </script>
@stop
