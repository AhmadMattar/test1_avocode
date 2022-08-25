@extends('Backend.layouts.master')
@section('content')
    <div class="mt-10">
        @canany(['create_order', 'admin_permission'])
            <a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                {{ __('general.Add') }}
            </a>
        @endcanany

        @canany(['delete_order', 'admin_permission'])
            <button class="btn btn-danger" id="multi_delete">
                {{ __('general.Delete') }}
            </button>
        @endcanany

        @include('Backend.orders.show')
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
            <thead>
                <tr>
                    <th style="text-align: right">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('general.name') }}</th>
                    <th>{{ __('general.total') }}</th>
                    <th>{{ __('general.coupoun') }}</th>
                    <th>{{ __('general.type') }}</th>
                    <th>{{ __('general.value') }}</th>
                    <th>{{ __('general.payment_status') }}</th>
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
                    url: '{{ route('orders.indexTable') }}',
                    data: function(data) {
                        data.name = $('#searchName').val(),
                        data.search = $('input[type="search"]').val()
                    },
                },
                columns: [
                    {
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
                        data: 'total',
                        name: 'total',
                    },
                    {
                        data: 'coupoun',
                        name: 'coupoun',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'type',
                        name: 'type',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'value',
                        name: 'value',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        orderable: false,
                        searchable: true,
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
                $('#dataTable').DataTable().draw();
            });

        });
    </script>

    {{-- update coupoun using ajax --}}
    <script>
        $(function() {
            $(document).on('click', '#editBtn', function(event) {
                var data = $(this).data();

                console.log(data);
                var orders_id = $(this).data("id");
                var code = $(this).data("code");
                var value = $(this).data("value");

                $('#editModal').modal('show');

                $('#orders_id').attr("value", orders_id);
                $('#edit_code').attr("value", code);
                $('#value').attr("value", value);

                $('#type').val($(this).data("type")).trigger('change');
                $('#status').val($(this).data("status")).trigger('change');

            });

            $(document).on('click', '#showBtn', function(event) {
                var data = $(this).data();
                console.log(data);

                var customer_name = $(this).data("name");
                var created_at = $(this).data("created_at");
                var coupoun_code = $(this).data("coupoun_code");
                var coupoun_type = $(this).data("coupoun_type");
                var coupoun_value = $(this).data("coupoun_value");
                var payment_status = $(this).data("payment_status");
                var total = $(this).data("total");

                $('#customer_name').text(customer_name);
                $('#created_at').text(created_at);
                $('#coupoun_code').text(coupoun_code);
                $('#coupoun_type').text(coupoun_type);
                $('#coupoun_value').text(coupoun_value);
                if(payment_status != ""){
                    $('#payment_status').attr('class', 'text-success');
                    $('#payment_status').text("{{__('general.complete')}}");
                }else{
                    $('#payment_status').attr('class', 'text-danger');
                    $('#payment_status').text("{{__('general.not_complete')}}");
                }
                $('#total').text(total);

                let productNames = [];
                var names = $(this).data('product_names') + ''
                if (names.indexOf(",") >= 0) {
                    productNames = ($(this).data('product_names').split(','))
                    productNames = productNames.filter(item => item);
                }

                let productPrices = [];
                var prices = $(this).data('product_prices') + ''
                if (prices.indexOf(",") >= 0) {
                    productPrices = ($(this).data('product_prices').split(','))
                    productPrices = productPrices.filter(item => item);
                }

                let productQuantities = [];
                var quantities = $(this).data('product_quantities') + ''
                if (quantities.indexOf(",") >= 0) {
                    productQuantities = ($(this).data('product_quantities').split(','))
                    productQuantities = productQuantities.filter(item => item);
                }

                let productCovers = [];
                var covers = $(this).data('product_covers') + ''
                if (covers.indexOf(",") >= 0) {
                    productCovers = ($(this).data('product_covers').split(','))
                    productCovers = productCovers.filter(item => item);
                }


                $('#products_details').children().remove();
                for (let i = 0; i < productPrices.length; i++) {
                    $('#products_details').append(
                        '<tr><th>@lang('general.product_name')</th><td>'+ productNames[i] +'</td></tr>',
                        '<tr><th>@lang('general.product_price')</th><td>'+ productPrices[i] +'</td></tr>',
                        '<tr><th>@lang('general.product_quantity')</th><td>'+ productQuantities[i] +'</td></tr>',
                        '<tr><th>@lang('general.product_cover')</th><td><img width="110" height="100" src=' +productCovers[i] +'></td></tr>',
                    );
                }
            });

        });
    </script>
@stop
