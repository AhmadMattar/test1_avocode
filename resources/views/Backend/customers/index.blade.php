@extends('Backend.layouts.master')
@section('content')
    <div class="mt-10">
        @canany(['create_customer', 'admin_permission'])
            <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                {{ __('general.Add') }}
            </a>
        @endcanany

        @canany(['delete_customer', 'admin_permission'])
            <button class="btn btn-danger" id="multi_delete">
                {{ __('general.Delete') }}
            </button>
        @endcanany

        @canany(['active_customer', 'admin_permission'])
            <button class="btn btn-success" id="active_button">
                {{ __('general.Active') }}
            </button>
        @endcanany

        @canany(['disactive_customer', 'admin_permission'])
            <button class="btn btn-dark" id="disactive_button">
                {{ __('general.Disactive') }}
            </button>
        @endcanany

        @include('Backend.customers.create')
        @include('Backend.customers.edit')
    </div>
    @include('Backend.customers.filter.filter')
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.first_name') }}</th>
                    <th>{{ __('general.last_name') }}</th>
                    <th>{{ __('general.email') }}</th>
                    <th>{{ __('general.phone') }}</th>
                    <th>{{ __('general.country') }}</th>
                    <th>{{ __('general.city') }}</th>
                    <th>{{ __('general.Status') }}</th>
                    <th>{{ __('general.photo') }}</th>
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
                    url: '{{ route('customers.indexTable') }}',
                    data: function(data) {
                        data.first_name = $('#searchFirstName').val(),
                            data.last_name = $('#searchLastName').val(),
                            data.country_id = $('#search_country_id').val(),
                            data.city_id = $('#search_city_id').val(),
                            data.email = $('#searchEmail').val(),
                            data.phone = $('#searchPhone').val(),
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
                        data: 'first_name',
                        name: 'first_name',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'last_name',
                        name: 'last_name',
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
                        data: 'phone',
                        name: 'phone',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'country',
                        name: 'country',
                    },
                    {
                        data: 'city',
                        name: 'city',
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

            $("#search_country_id").change(function() {
                populateCities();
                populateDistrict();
                return false;
            });

            $("#search_city_id").change(function() {
                populateDistrict();
                return false;
            });

            function populateCities() {
                let countryIdVal = $('#search_country_id').val() != null ? $('#search_country_id').val() :
                    '{{ old('search_country_id') }}';
                $.get("{{ route('customers.get_cities') }}", {
                    country_id: countryIdVal
                }, function(data) {
                    $('option', $("#search_city_id")).remove();
                    $("#search_city_id").append($('<option></option>').val('').html(
                        '{{ __('general.select_city') }}'));
                    $.each(data, function(val, text) {
                        let selectedVal = text.id == '{{ old('search_city_id') }}' ? "selected" :
                            "";
                        $("#search_city_id").append($('<option ' + selectedVal + '></option>').val(
                            text
                            .id).html(text.name));
                    });
                }, "json");
            }

            function populateDistrict() {
                let cityIdVal = $('#search_city_id').val() != null ? $('#search_city_id').val() :
                    '{{ old('search_city_id') }}';
                $.get("{{ route('customers.get_districts') }}", {
                    city_id: cityIdVal
                }, function(data) {
                    $('option', $("#search_district_id")).remove();
                    $("#search_district_id").append($('<option></option>').val('').html(
                        ' {{ __('general.select_district') }} '));
                    $.each(data, function(val, text) {
                        let selectedVal = text.id == '{{ old('search_district_id') }}' ?
                            "selected" :
                            "";
                        $("#search_district_id").append($('<option ' + selectedVal + '></option>')
                            .val(text
                                .id).html(text.name));
                    });
                }, "json");
            }

            $('#search').on('click', function() {
                $('#dataTable').DataTable().draw();
            });

            $('#reset').on('click', function() {
                $('#searchFirstName').val('');
                $('#searchLastName').val('');
                $('#search_country_id').val('');
                $('#search_city_id').val('');
                $('#search_district_id').val('');
                $('#searchEmail').val('');
                $('#searchPhone').val('');
                $('#searchStatus').val('');
                $('#dataTable').DataTable().draw();
            });
        });
    </script>
    {{-- select country and city and district --}}
    <script>
        $("#country_id").change(function() {
            populateCities();
            populateDistrict();
            return false;
        });

        $("#city_id").change(function() {
            populateDistrict();
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

        function populateDistrict() {
            let cityIdVal = $('#city_id').val() != null ? $('#city_id').val() :
                '{{ old('city_id') }}';
            $.get("{{ route('customers.get_districts') }}", {
                city_id: cityIdVal
            }, function(data) {
                $('option', $("#district_id")).remove();
                $("#district_id").append($('<option></option>').val('').html(
                    ' {{ __('general.select_district') }} '));
                $.each(data, function(val, text) {
                    let selectedVal = text.id == '{{ old('district_id') }}' ? "selected" : "";
                    $("#district_id").append($('<option ' + selectedVal + '></option>').val(text
                        .id).html(text.name));
                });
            }, "json");
        }
    </script>
    {{-- update customer using ajax --}}
    <script>
        $(function() {
            $(document).on('click', '#editBtn', function(event) {
                var data = $(this).data();
                console.log(data);
                var id = $(this).data("id");
                var first_name = $(this).data("first_name");
                var last_name = $(this).data("last_name");
                var email = $(this).data("email");
                var phone = $(this).data("phone");
                var country_id = $(this).data("country_id");
                var city_id = $(this).data("city_id");
                var district_id = $(this).data("district_id");
                var cover = $(this).data("cover");

                // console.log(district_id);

                $('#editModal').modal('show');
                $('#customer_id').attr("value", id);
                $('#first_name').attr("value", first_name);
                $('#last_name').attr("value", last_name);
                $('#email').attr("value", email);
                $('#phone').attr("value", phone);
                $('#edit_country_id').val($(this).data("country_id")).trigger('change');
                $('#status').val($(this).data("status")).trigger('change');

                $.get("{{ route('customers.get_cities') }}", {
                    country_id: country_id
                }, function(data) {
                    $('option', $("#edit_city_id")).remove();
                    $.each(data, function(val, text) {
                        let selectedVal = text.id == city_id ? "selected" : "";
                        $("#edit_city_id").append($('<option ' + selectedVal + '></option>').val(text.id).html(text.name));
                    });
                }, "json");

                $.get("{{ route('customers.get_districts') }}", {
                    city_id: city_id
                }, function(data) {
                    $('option', $("#edit_district_id")).remove();
                    $.each(data, function(val, text) {
                        let selectedVal = text.id == district_id ? "selected" : "";
                        $("#edit_district_id").append($('<option ' + selectedVal +'></option>').val(text.id).html(text.name));
                    });
                }, "json");

                $("#edit_country_id").change(function() {
                    populateCities();
                    populateDistrict();
                    return false;
                });

                $("#edit_city_id").change(function() {
                    populateDistrict();
                    return false;
                });

                function populateCities() {
                    let countryIdVal = $('#edit_country_id').val() != null ? $('#edit_country_id').val() :
                        '{{ old('country_id') }}';
                    $.get("{{ route('customers.get_cities') }}", {
                        country_id: countryIdVal
                    }, function(data) {
                        $('option', $("#edit_city_id")).remove();
                        $("#edit_city_id").append($('<option></option>').val('').html(
                            ' {{ __('general.select_city') }} '));
                        $.each(data, function(val, text) {
                            let selectedVal = text.id == '{{ old('city_id') }}' ?
                                "selected" : "";
                            $("#edit_city_id").append($('<option ' + selectedVal +
                                '></option>').val(text
                                .id).html(text.name));
                        });
                    }, "json");
                }

                function populateDistrict() {
                    let cityIdVal = $('#edit_city_id').val() != null ? $('#edit_city_id').val() :
                        '{{ old('city_id') }}';
                    $.get("{{ route('customers.get_districts') }}", {
                        city_id: cityIdVal
                    }, function(data) {
                        $('option', $("#edit_district_id")).remove();
                        $("#edit_district_id").append($('<option></option>').val('').html(
                            ' {{ __('general.select_district') }} '));
                        $.each(data, function(val, text) {
                            let selectedVal = text.id == '{{ old('district_id') }}' ?
                                "selected" : "";
                            $("#edit_district_id").append($('<option ' + selectedVal +
                                '></option>').val(text
                                .id).html(text.name));
                        });
                    }, "json");
                }

                $('#customer-cover').attr("src", cover);
            });
        });
    </script>
@stop
