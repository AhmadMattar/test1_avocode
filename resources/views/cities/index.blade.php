@extends('layouts.master')
@section('content')
    <div class="mt-10">
        <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addCityModal"
            data-countries="{{ $countries }}">
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

    {{-- store new country using ajax --}}
    <script>
        $('#addCity').submit(function(e) {
            e.preventDefault();
            $.ajax({
                method: "POST",
                url: "{{ route('cities.store') }}",
                data: new FormData(this),
                contentType: false,
                processData: false,

                success: function(data, status) {
                    $("#datatable").html(data);
                    $("#addCity")[0].reset();
                    $("#addCityModal").modal('hide');
                    toastr.success('✅ تمت الإضافة بنجاح');
                    $('#dataTable').DataTable().draw();
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        $('.text-danger').addClass('d-none');
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

        $('#editCity').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('cities.update') }}",
                method: 'POST',
                type: "PUT",
                data: new FormData(this),
                contentType: false,
                processData: false,

                success: function(data, status) {
                    $("#datatable").html(data);
                    $("#editModal").modal('hide');
                    toastr.success('✅ تم التعديل بنجاح');
                    $('#dataTable').DataTable().draw();
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        $('.text-danger').addClass('d-none');
                        $.each(errors.errors, function(key, value) {
                            var errorId = '#' + key + '_error';
                            $(errorId).removeClass('d-none');
                            $(errorId).text(value);
                        });
                    }
                },
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
                    url: "{{ route('cities.destroy') }}",
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
            var cities_ids = [];
            if (confirm('Are you sure to delete this record?')) {
                let _token = $("input[name=_token]").val();
                $('.cities_checkbox:checked').each(function() {
                    cities_ids.push($(this).val());
                });
                if (cities_ids.length > 0) {
                    $.ajax({
                        url: "{{ route('cities.deleteAll') }}",
                        type: "DELETE",
                        data: {
                            _token: _token,
                            id: cities_ids,
                        },
                        success: function(data, status) {

                            $('#selectAll').prop('checked',false);
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
            var cities_ids = [];
            let _token = $("input[name=_token]").val();
            $('.cities_checkbox:checked').each(function() {
                cities_ids.push($(this).val());
            });
            if (cities_ids.length > 0) {
                $.ajax({
                    url: "{{ route('cities.ativeAll') }}",
                    method: 'PUT',
                    data: {
                        _token: _token,
                        id: cities_ids,
                    },
                    success: function(data, status) {

                        $('#selectAll').prop('checked',false);
                        $('#confirmModal').modal('hide');
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
            var cities_ids = [];
            let _token = $("input[name=_token]").val();
            $('.cities_checkbox:checked').each(function() {
                cities_ids.push($(this).val());
            });
            if (cities_ids.length > 0) {
                $.ajax({
                    url: "{{ route('cities.disativeAll') }}",
                    method: 'PUT',
                    data: {
                        _token: _token,
                        id: cities_ids,
                    },
                    success: function(data, status) {

                        $('#selectAll').prop('checked',false);
                        $('#disactiveModal').modal('hide');
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
            $('.cities_checkbox').prop('checked', this.checked);
        });
    </script>
@stop
