<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        @if ($order)
            <input type="hidden" id="token" value="{{$order->order_token}}">
            <input type="hidden" id="total" value="{{$order->total}}">
            <div class="row d-flex justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <a id="showPaymentForm" href="{{ route('getCheckout') }}" role="button"
                                class="btn btn-primary px-3 waves-effect waves-light"> {{ __('general.pay') }} </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="showPayForm"></div>

                        @if (isset($success))
                            <div class="alert alert-success text-center">
                                {{ __('general.payed_success') }}
                            </div>
                        @endif


                        @if (isset($fail))
                            <div class="alert alert-danger text-center">
                                {{ __('general.payed_fail') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
        $(document).on('click', '#showPaymentForm', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'get',
                url: "{{ route('getCheckout') }}",
                data: {
                    total: $('#total').val(),
                    order_token: $('#token').val(),
                },
                success: function(data) {
                    console.log(data.content);
                    if (data.status == true) {
                        $('#showPayForm').empty().html(data.content);
                    } else {}
                },
                error: function(reject) {}
            });
        });
    </script>
</body>

</html>
