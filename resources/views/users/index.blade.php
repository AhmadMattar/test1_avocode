@extends('layouts.master')
@section('content')
    <div class="mt-10">
        <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addUserModal">
            {{ __('general.Add') }}
        </a>
        @include('users.create')
        @include('users.edit')
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('general.first_name') }}</th>
                    <th>{{ __('general.last_name') }}</th>
                    <th>{{ __('general.email') }}</th>
                    <th>{{ __('general.phone') }}</th>
                    <th>{{ __('general.country') }}</th>
                    <th>{{ __('general.city') }}</th>
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
                    url: '{{ route('users.data') }}',
                },
                columns: [{
                        data: 'id',
                        name: 'id',
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

    {{-- file input --}}
    <script>
        $(function() {
            $("#countryImage").fileinput({
                theme: "fas",
                maxFileCount: 1,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false
            });
        });
    </script>

    {{-- store new user using ajax --}}
    <script>
        $(function() {
            populateCities();

            $("#country_id").change(function() {
                populateCities();
                return false;
            });


            function populateCities() {
                let countryIdVal = $('#country_id').val() != null ? $('#country_id').val() :
                    '{{ old('country_id') }}';
                $.get("{{ route('users.get_cities') }}", {
                    country_id: countryIdVal
                }, function(data) {
                    $('option', $("#city_id")).remove();
                    $("#city_id").append($('<option></option>').val('').html(' --- '));
                    $.each(data, function(val, text) {
                        let selectedVal = text.id == '{{ old('city_id') }}' ? "selected" : "";
                        $("#city_id").append($('<option ' + selectedVal + '></option>').val(text
                            .id).html(text.name));
                    });
                }, "json");
            }
        });
        $('#addUser').submit(function(e) {
            e.preventDefault();
            $('.text-danger').addClass('d-none');
            $.ajax({
                method: "POST",
                url: "{{ route('users.store') }}",
                data: new FormData(this),
                contentType: false,
                processData: false,

                success: function(data, status) {
                    $("#datatable").html(data);
                    $("#addUser")[0].reset();
                    $("#addUserModal").modal('hide');
                    toastr.success('✅ تمت الإضافة بنجاح');
                    $('#dataTable').DataTable().draw();
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(data);
                    if ($.isEmptyObject(errors) == false) {
                        $.each(errors.errors, function(key, value) {
                            var errorId = '#' + key + '_error';
                            $(errorId).removeClass('d-none');
                            $(errorId).text(value);
                        });
                    }
                }
            });
        });
    </script>

    {{-- update user using ajax --}}
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
                var city_name = $(this).data("city_name");
                var cover = $(this).data("cover");
                console.log(country_id);
                console.log(city_id);
                console.log(city_name);

                $('#editUserModal').modal('show');
                $('#user_id').attr("value", id);
                $('#first_name').attr("value", first_name);
                $('#last_name').attr("value", last_name);
                $('#email').attr("value", email);
                $('#phone').attr("value", phone);
                $('#edit_country_id').val($(this).data("country_id")).trigger('change');
                $('#status').val($(this).data("status")).trigger('change');

                $.get("{{ route('users.get_cities') }}", {
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
                    $.get("{{ route('users.get_cities') }}", {
                        country_id: countryIdVal
                    }, function(data) {
                        $('option', $("#edit_city_id")).remove();
                        $("#edit_city_id").append($('<option></option>').val('').html(' --- '));
                        $.each(data, function(val, text) {
                            let selectedVal = text.id == '{{ old('city_id') }}' ?
                                "selected" : "";
                            $("#edit_city_id").append($('<option ' + selectedVal +
                                '></option>').val(text
                                .id).html(text.name));
                        });
                    }, "json");
                }

                $('#user-cover').attr("src", cover);
            });


            $('#editUser').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('users.update') }}",
                    method: 'POST',
                    type: "PUT",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,

                    success: function(data, status) {
                        $("#datatable").html(data);
                        $("#editUserModal").modal('hide');
                        toastr.success('✅ تم التعديل بنجاح');
                        $('#dataTable').DataTable().draw();
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        if ($.isEmptyObject(errors) == false) {
                            $('.text-danger').addClass('d-none');
                            $.each(errors.errors, function(key, value) {
                                var errorId = '#' + key + '_error2';
                                $(errorId).removeClass('d-none');
                                $(errorId).text(value);
                            });
                        }
                    },
                });
            });
        });
    </script>

    {{-- delete country using ajax --}}
    <script>
        $(document).on('click', '#deleteBtn', function(event) {
            if (confirm('Are you sure to delete this record?')) {
                let _token = $("input[name=_token]").val();
                let id = $(this).data("id");
                $.ajax({
                    url: "{{ route('users.destroy') }}",
                    type: "DELETE",
                    data: {
                        _token: _token,
                        id: id,
                    },
                    success: function(data, status) {
                        console.log(data);
                        toastr.warning('تم الحذف بنجاح');
                        $('#dataTable').DataTable().draw();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            } else {
                return false;
            }
        });
    </script>

@stop
