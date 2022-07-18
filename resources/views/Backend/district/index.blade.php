@extends('Backend.layouts.master')
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
        @include('Backend.district.create')
        @include('Backend.district.edit')
    </div>
    @include('Backend.district.filter.filter')
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.name') }}</th>
                    <th>{{ __('general.country') }}</th>
                    <th>{{ __('general.city') }}</th>
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
                    url: '{{ route('district.indexTable') }}',
                    data: function(data) {
                            data.name = $('#searchName').val();
                            data.country_id = $('#search_country_id').val(),
                            data.city_id = $('#search_city_id').val(),
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
                        data: 'city_id',
                        name: 'city_name',
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
            $("#search_country_id").change(function() {
                populateCities();
            });

            function populateCities() {
                let countryIdVal = $('#search_country_id').val() != null ? $('#search_country_id').val() :
                    '{{ old('search_country_id') }}';
                $.get("{{ route('customers.get_cities') }}", {
                    country_id: countryIdVal
                }, function(data) {
                    $('option', $("#search_city_id")).remove();
                    $("#search_city_id").append($('<option></option>').val('').html('{{__('general.select_city')}}'));
                    $.each(data, function(val, text) {
                        let selectedVal = text.id == '{{ old('search_city_id') }}' ? "selected" : "";
                        $("#search_city_id").append($('<option ' + selectedVal + '></option>').val(
                            text
                            .id).html(text.name));
                    });
                }, "json");
            }
            $('#search').on('click', function() {
                $('#dataTable').DataTable().draw();
            });

            $('#reset').on('click', function() {
                $('#searchName').val('');
                $('#search_country_id').val('');
                $('#search_city_id').val('');
                $('#searchStatus').val('');
                $('#dataTable').DataTable().draw();
            });
        });
    </script>

    {{-- update country using ajax --}}
    <script>
        $(function() {
            $(document).on('click', '#editBtn', function(event) {
                var data = $(this).data();

                var district_id = $(this).data("id");
                var city_id = $(this).data("city_id");
                var country_id = $(this).data("country_id");
                var name_en = $(this).data("name_en");
                var name_ar = $(this).data("name_ar");

                $('#editModal').modal('show');

                $('#district_id').attr("value", district_id);
                $("#city_id").attr("value", city_id);
                $('#country_name').attr("value", $(this).data("country_name"));
                $('#name_en').attr("value", name_en);
                $('#name_ar').attr("value", name_ar);

                $('#edit_country_id').val($(this).data("country_id")).trigger('change');
                $('#status').val($(this).data("status")).trigger('change');


                $.get("{{ route('customers.get_cities') }}", {
                    country_id: country_id
                }, function(data) {
                    $('option', $("#edit_city_id")).remove();
                    $.each(data, function(val, text) {
                        let selectedVal = text.id == city_id ? "selected" : "";
                        $("#edit_city_id").append($('<option ' + selectedVal + '></option>')
                            .val(text
                                .id).html(text.name));
                    });
                }, "json");

                $("#edit_country_id").change(function() {
                    populateCities();
                    return false;
                });

                function populateCities() {
                    let countryIdVal = $('#edit_country_id').val() != null ? $('#edit_country_id').val() :
                        '{{ old('country_id') }}';
                    $.get("{{ route('customers.get_cities') }}", {
                        country_id: countryIdVal
                    }, function(data) {
                        $('option', $("#edit_city_id")).remove();
                        $("#edit_city_id").append($('<option></option>').val('').html('{{__('general.select_city')}}'));
                        $.each(data, function(val, text) {
                            let selectedVal = text.id == '{{ old('city_id') }}' ?
                                "selected" : "";
                            $("#edit_city_id").append($('<option ' + selectedVal +
                                '></option>').val(text
                                .id).html(text.name));
                        });
                    }, "json");
                }
            });
        });
    </script>

    {{-- select country and city --}}
    <script>
        $("#country_id").change(function() {
            populateCities();
            return false;
        });

        function populateCities() {
            let countryIdVal = $('#country_id').val() != null ? $('#country_id').val() :
                '{{ old('country_id') }}';
            $.get("{{ route('customers.get_cities') }}", {
                country_id: countryIdVal
            }, function(data) {
                $('option', $("#city_id")).remove();
                $("#city_id").append($('<option></option>').val('').html(' {{ __('general.select_city') }} '));
                $.each(data, function(val, text) {
                    let selectedVal = text.id == '{{ old('city_id') }}' ? "selected" : "";
                    $("#city_id").append($('<option ' + selectedVal + '></option>').val(text
                        .id).html(text.name));
                });
            }, "json");
        }
    </script>

@stop
