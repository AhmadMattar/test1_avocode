@extends('layouts.master')
@section('content')
    <div class="mt-10">
        <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addCityModal" data-countries="{{$countries}}">
            {{ __('general.Add') }}
        </a>
        @include('cities.create')
        @include('cities.edit')
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('general.name') }}</th>
                    <th>Country</th>
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
                    url: '{{ route('cities.data') }}',
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
