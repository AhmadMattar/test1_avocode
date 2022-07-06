@extends('layouts.master')
@section('content')
    <div class="mt-10">
        <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
            {{ __('general.Add') }}
        </a>
        @include('countries.create')
        @include('countries.edit')
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('general.name') }}</th>
                    <th>{{ __('general.code') }}</th>
                    <th>{{ __('general.photo') }}</th>
                    <th>Action</th>
                </tr>
            </thead>

        </table>
    </div>
@stop
@section('script')

    {{-- Yajra dataTable config--}}
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
                        data: 'id',
                        name: 'id',
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

    {{-- store new country using ajax --}}
    <script>
        $('#addCountry').submit(function(e) {
            e.preventDefault();
            $('.text-danger').addClass('d-none');
            $.ajax({
                method: "POST",
                url: "{{ route('countries.store') }}",
                data: new FormData(this),
                contentType: false,
                processData: false,

                success: function(data, status) {
                    $("#datatable").html(data);
                    $("#addCountry")[0].reset();
                    $("#exampleModal").modal('hide');
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

    {{-- update country using ajax --}}
    <script>
        $(document).on('click', '#editBtn', function(event) {
            var data = $(this).data();
            var country_id = $(this).data("id");
            var name_en = $(this).data("name_en");
            var name_ar = $(this).data("name_ar");
            var code = $(this).data("code");

            $('#editModal').modal('show');
            $('#country_id').attr("value", country_id);
            $('#name_en').attr("value", name_en);
            $('#name_ar').attr("value", name_ar);
            $('#code').attr("value", code);
            var cover = $(this).data("cover");
            $('#country-cover').attr("src", cover);
        });

        $('#editCountry').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('countries.update') }}",
                method: 'POST',
                type: "PUT",
                data: new FormData(this),
                contentType: false,
                processData: false,

                success: function(data, status) {
                    $("#datatable").html(data);
                    $("#addCountry")[0].reset();
                    $("#editModal").modal('hide');
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
    </script>

    {{-- delete country using ajax --}}
    <script>
        $(document).on('click', '#deleteBtn', function(event) {
            if (confirm('Are you sure to delete this record?')) {
                let _token = $("input[name=_token]").val();
                let id = $(this).data("id");
                $.ajax({
                    url: "{{ route('countries.destroy') }}",
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
