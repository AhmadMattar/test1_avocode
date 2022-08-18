@extends('Backend.layouts.master')
@section('content')
    <div class="mt-10">
        @canany(['create_product', 'admin_permission'])
            <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                {{ __('general.Add') }}
            </a>
        @endcanany

        @canany(['delete_product', 'admin_permission'])
            <button class="btn btn-danger" id="multi_delete">
                {{ __('general.Delete') }}
            </button>
        @endcanany

        @include('Backend.products.create')
        @include('Backend.products.edit')
    </div>

    @include('Backend.products.filter.filter')
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.name') }}</th>
                    <th>{{ __('general.price') }}</th>
                    <th>{{ __('general.quantity') }}</th>
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
                    url: '{{ route('products.indexTable') }}',
                    data: function(data) {
                            data.name = $('#searchName').val(),
                            data.price = $('#searchPrice').val(),
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
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
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
            $('#search').on('click', function() {
                $('#dataTable').DataTable().draw();
            });

            $('#reset').on('click', function() {
                $('#searchName').val('');
                $('#searchPrice').val('');
                $('#dataTable').DataTable().draw();
            });

        });

        var firstUpload = new FileUploadWithPreview('cover')
        var secondUpload = new FileUploadWithPreview('images')
    </script>


    {{-- update country using ajax --}}
    <script>
        $(document).on('click', '#editBtn', function(event) {
            var data = $(this).data();
            console.log(data);
            var product_id = $(this).data("id");
            var name_en = $(this).data("name_en");
            var name_ar = $(this).data("name_ar");
            var price = $(this).data("price");
            var quantity = $(this).data("quantity");
            var cover = $(this).data("cover");

            $('#editModal').modal('show');
            $('#product_id').attr("value", product_id);
            $('#name_en').attr("value", name_en);
            $('#name_ar').attr("value", name_ar);
            $('#edit_price').attr("value", price);
            $('#edit_quantity').attr("value", quantity);
            $('.dropify-render img:first-child').attr('src', cover)

            let images = [];
            var imgs = $(this).data('images') + ''
            if (imgs.indexOf(",") >= 0) {
                images = ($(this).data('images').split(','))
                images = images.filter(item => item);
            }

            $('#edit_images_container').children().remove();
            for (let i = 0; i < images.length; i++) {
                $('#edit_images_container').append(
                    '<button type="button" class="btn-close" id="remove-images_'+i+'"></button>',
                    '<img width="110" height="100" id="product-images_'+i+'" src=' +images[i] +'>',
                    '<input type="text" class="form-control" name="removed_images[]" id="product_removed_images_'+i+'" hidden>'
                );
            }

            for (let i = 0; i < images.length; i++) {
                $('#remove-images_'+i).on('click', function(){
                    image_name = $('#product-images_'+i).attr('src')
                    $('#product-images_'+i).hide()
                    $('#remove-images_'+i).remove()
                    $('#product_removed_images_'+i).attr('value', image_name.substring(47));
                });
            }

        });
    </script>
@stop
