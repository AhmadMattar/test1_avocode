@extends('layouts.master')
@section('content')
    <div class="mt-10">
        <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal"
            data-countries="{{ $countries }}">
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
        @include('cities.create')
        @include('cities.edit')
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
                    <th>Country</th>
                    <th>Status</th>
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
                    url: '{{ route('cities.data') }}',
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
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'country_id',
                        name: 'country_name',
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

            var city_id = $(this).data("id");
            var country_id = $(this).data("country_id");
            var name_en = $(this).data("name_en");
            var name_ar = $(this).data("name_ar");

            $('#editModal').modal('show');

            $("#city_id").attr("value", city_id);
            $('#country_name').attr("value", $(this).data("country_name"));
            $('#edit_country_id').val($(this).data("country_id")).trigger('change');
            $('#status').val($(this).data("status")).trigger('change');
            $('#name_en').attr("value", name_en);
            $('#name_ar').attr("value", name_ar);
        });
    </script>

@stop
