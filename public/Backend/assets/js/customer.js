// store new record using ajax
$('#add').submit(function (e) {
    e.preventDefault();
    $('.text-danger').addClass('d-none');
    $.ajax({
        method: "POST",
        url: window.location.pathname,
        data: new FormData(this),
        contentType: false,
        processData: false,

        success: function (data, status) {
            console.log(data);
            $("#datatable").html(data);
            $("#add")[0].reset();
            $("#addModal").modal('hide');
            toastr.success(data.message);
            $('#dataTable').DataTable().draw();
        },
        error: function (data) {
            var errors = data.responseJSON;
            if ($.isEmptyObject(errors) == false) {
                $.each(errors.errors, function (key, value) {
                    var errorId = '#' + key + '_error';
                    $(errorId).removeClass('d-none');
                    $(errorId).text(value);
                });
            }
        }
    });
});
$('#cancelAdd').on('click', function(){
    $('#add')[0].reset();
});

// update record using ajax
$('#edit').submit(function (e) {
    e.preventDefault();

    $.ajax({
        url: window.location.pathname + '/update',
        method: 'POST',
        type: "PUT",
        data: new FormData(this),
        contentType: false,
        processData: false,

        success: function (data, status) {
            $("#datatable").html(data);
            $("#edit")[0].reset();
            $("#editModal").modal('hide');
            toastr.success(data.message);
            $('#dataTable').DataTable().draw();
        },
        error: function (data) {
            var errors = data.responseJSON;
            if ($.isEmptyObject(errors) == false) {
                $('.text-danger').addClass('d-none');
                $.each(errors.errors, function (key, value) {
                    var errorId = '#' + key + '_error2';
                    $(errorId).removeClass('d-none');
                    $(errorId).text(value);
                });
            }
        },
    });
});
$('#cancelEdit').on('click', function(){
    $('#edit')[0].reset();
});

// delete record using ajax
$(document).on('click', '#deleteBtn', function (event) {
    if (confirm('Are you sure to delete this record?')) {
        let _token = $("input[name=_token]").val();
        let id = $(this).data("id");
        $.ajax({
            url: window.location.pathname + '/delete',
            type: "DELETE",
            data: {
                _token: _token,
                id: id,
            },
            success: function (data, status) {

                toastr.warning(data.message);
                $('#dataTable').DataTable().draw();
            },
            error: function (data) {

            }
        });
    } else {
        return false;
    }
});

// delete multiple data
$('#multi_delete').on('click', function () {
    selected = $('.items_checkbox:checked')
    if (selected.length < 1) {
        $('#deleteModal').modal('hide');
    } else {
        $('#deleteModal').modal('show');
    }
});
$(document).on('click', '#delete_ok_button', function () {
    var items_ids = [];
    let _token = $("input[name=_token]").val();
    $('.items_checkbox:checked').each(function () {
        items_ids.push($(this).val());
    });
    if (items_ids.length > 0) {
        $.ajax({
            url: window.location.pathname + '/delete-all',
            type: "DELETE",
            data: {
                _token: _token,
                id: items_ids,
            },
            success: function (data, status) {

                $('#selectAll').prop('checked', false);
                $('#deleteModal').modal('hide');
                toastr.warning(data.message);
                $('#dataTable').DataTable().draw();
            },
            error: function (data) {

            }
        });
    } else {
        return false;
    }
});

// active multiple data
$('#active_button').on('click', function () {
    selected = $('.items_checkbox:checked')
    if (selected.length < 1) {
        $('#confirmModal').modal('hide');
    } else {
        $('#confirmModal').modal('show');
    }
});
$(document).on('click', '#ok_button', function () {
    var items_ids = [];

    let _token = $("input[name=_token]").val();
    $('.items_checkbox:checked').each(function () {
        items_ids.push($(this).val());
    });
    if (items_ids.length > 0) {
        $.ajax({
            url: window.location.pathname + '/activeAll',
            method: 'PUT',
            data: {
                _token: _token,
                id: items_ids,
            },
            success: function (data, status) {

                $('#selectAll').prop('checked', false);
                $('#confirmModal').modal('hide')
                toastr.success(data.message);
                $('#dataTable').DataTable().draw();
            },
            error: function (data) {

            }
        });
    } else {
        return false;
    }
});

// disactive multiple data
$('#disactive_button').on('click', function () {
    selected = $('.items_checkbox:checked')
    if (selected.length < 1) {
        $('#disactiveModal').modal('hide');
    } else {
        $('#disactiveModal').modal('show');
    }
});
$(document).on('click', '#disactive_ok_button', function () {
    var items_ids = [];
    let _token = $("input[name=_token]").val();
    $('.items_checkbox:checked').each(function () {
        items_ids.push($(this).val());
    });
    if (items_ids.length > 0) {
        $.ajax({
            url: window.location.pathname + '/disactiveAll',
            method: 'PUT',
            data: {
                _token: _token,
                id: items_ids,
            },
            success: function (data, status) {

                $('#selectAll').prop('checked', false);
                $('#disactiveModal').modal('hide')
                toastr.success(data.message);
                $('#dataTable').DataTable().draw();
            },
            error: function (data) {

            }
        });
    } else {
        return false;
    }
});


// check all boxes
$('#selectAll').click(function () {
    $('.items_checkbox').prop('checked', this.checked);
});

$(document).on("change", "input[class='items_checkbox']", function () {
    if ($(this).prop('checked') == false) {
        $('#selectAll').prop('checked', false);
    }
    let selected = [];
    selected = ($('.items_checkbox:checked'));
    let items = $("input[class='items_checkbox']");

    if (selected.length === items.length) {
        $('#selectAll').prop('checked', true);
    }
});
