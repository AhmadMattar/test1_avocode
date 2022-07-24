@extends('Backend.layouts.master')
@section('content')
    <div class="mt-10">
        @canany(['create_city', 'admin_permission'])
            <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal"
                data-countries="{{ $countries }}">
                {{ __('general.Add') }}
            </a>
        @endcanany

        @canany(['delete_city', 'admin_permission'])
            <button class="btn btn-danger" id="multi_delete">
                {{ __('general.Delete') }}
            </button>
        @endcanany

        @canany(['active_city', 'admin_permission'])
            <button class="btn btn-success" id="active_button">
                {{ __('general.Active') }}
            </button>
        @endcanany

        @canany(['disactive_city', 'admin_permission'])
            <button class="btn btn-dark" id="disactive_button">
                {{ __('general.Disactive') }}
            </button>
        @endcanany

        @include('Backend.cities.create')
        @include('Backend.cities.edit')
    </div>
    @include('Backend.cities.filter.filter')
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.name') }}</th>
                    <th>{{ __('general.country') }}</th>
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
                    url: '{{ route('cities.indexTable') }}',
                    data: function(data) {
                        data.name = $('#searchName').val();
                        data.country_id = $('#searchCountry').val(),
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
                        data: 'name',
                        name: 'name',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'country_id',
                        name: 'country_name',
                        orderable: false,

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
                $('#searchCountry').val('');
                $('#searchStatus').val('');
                $('#dataTable').DataTable().draw();
            });
        });
    </script>

    {{-- update country using ajax --}}
    <script>
        $(document).on('click', '#editBtn', function(event) {
            var data = $(this).data();
            console.log(data);
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
