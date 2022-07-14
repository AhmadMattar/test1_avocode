@extends('layouts.master')
@section('content')
    <div class="mt-10">
        <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addUserModal">
            {{ __('general.Add') }}
        </a>

        <button class="btn btn-danger" id="multi_delete">
            {{ __('general.Delete') }}
        </button>

        <a class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#confirmModal">
            {{__('general.Active')}}
        </a>

        <a class="btn btn-secondry" type="button" data-bs-toggle="modal" data-bs-target="#disactiveModal">
            {{__('general.Disactive')}}
        </a>

        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal">
                        @method('PUT')
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h4 align="center" style="margin:0;">Are you sure you want to Active this data?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" name="ok_button" id="ok_button">OK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="disactiveModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal">
                        @method('PUT')
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h4 align="center" style="margin:0;">Are you sure you want to Disactive this data?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="disactive_ok_button">OK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('users.create')
        @include('users.edit')
    </div>
    <hr>
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
                    url: '{{ route('users.data') }}',
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

                var id = $(this).data("id");
                var first_name = $(this).data("first_name");
                var last_name = $(this).data("last_name");
                var email = $(this).data("email");
                var phone = $(this).data("phone");
                var country_id = $(this).data("country_id");
                var city_id = $(this).data("city_id");
                var city_name = $(this).data("city_name");
                var cover = $(this).data("cover");

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

                        toastr.warning('تم الحذف بنجاح');
                        $('#dataTable').DataTable().draw();
                    },
                    error: function(data) {

                    }
                });
            } else {
                return false;
            }
        });
    </script>

    {{-- delete multi data --}}
    <script>
        $(document).on('click', '#multi_delete', function() {
            var users_ids = [];
            if (confirm('Are you sure to delete this record?')) {
                let _token = $("input[name=_token]").val();
                $('.users_checkbox:checked').each(function() {
                    users_ids.push($(this).val());
                });
                if (users_ids.length > 0) {
                    $.ajax({
                        url: "{{ route('users.deleteAll') }}",
                        type: "DELETE",
                        data: {
                            _token: _token,
                            id: users_ids,
                        },
                        success: function(data, status) {

                            $('#selectAll').prop('checked', false);
                            toastr.warning('تم الحذف بنجاح');
                            $('#dataTable').DataTable().draw();
                        },
                        error: function(data) {

                        }
                    });
                } else {
                    alert("please select at least one record");
                }
            } else {
                return false
            }
        });
    </script>

    {{-- active multi data --}}
    <script>
        $(document).on('click', '#ok_button', function() {
            var users_ids = [];
            let _token = $("input[name=_token]").val();
            $('.users_checkbox:checked').each(function() {
                users_ids.push($(this).val());
            });
            if (users_ids.length > 0) {
                $.ajax({
                    url: "{{ route('users.ativeAll') }}",
                    method: 'PUT',
                    data: {
                        _token: _token,
                        id: users_ids,
                    },
                    success: function(data, status) {

                        $('#selectAll').prop('checked', false);
                        $('#confirmModal').modal('hide')
                        toastr.success('تم التفعيل بنجاح');
                        $('#dataTable').DataTable().draw();
                    },
                    error: function(data) {

                    }
                });
            } else {
                alert("please select at least one record");
            }
        });
    </script>

    {{-- disactive multi data --}}
    <script>
        $(document).on('click', '#disactive_ok_button', function() {
            var users_ids = [];
            let _token = $("input[name=_token]").val();
            $('.users_checkbox:checked').each(function() {
                users_ids.push($(this).val());
            });
            if (users_ids.length > 0) {
                $.ajax({
                    url: "{{ route('users.disativeAll') }}",
                    method: 'PUT',
                    data: {
                        _token: _token,
                        id: users_ids,
                    },
                    success: function(data, status) {

                        $('#selectAll').prop('checked', false);
                        $('#disactiveModal').modal('hide')
                        toastr.success('تم إلغاء التفعيل بنجاح');
                        $('#dataTable').DataTable().draw();
                    },
                    error: function(data) {

                    }
                });
            } else {
                alert("please select at least one record");
            }
        });
    </script>

    {{-- check all boxes --}}
    <script>

        $('#selectAll').click(function() {
            $('.users_checkbox').prop('checked', this.checked);
        });
    </script>
@stop
