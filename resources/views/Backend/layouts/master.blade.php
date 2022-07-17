<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Sales Admin | CORK - Multipurpose Bootstrap Dashboard Template </title>
    <link rel="icon" type="image/x-icon" href="{{asset('Backend/assets/img/favicon.ico')}}"/>
    <link href="{{asset('Backend/assets/css/loader.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('Backend/assets/js/loader.js')}}"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <link href="{{asset('Backend/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('Backend/assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('Backend/vendor/bootstrap-input-file/css/fileinput.min.css')}}">
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="{{asset('Backend/assets/css/toastr.min.css')}}">

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{asset('Backend/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('Backend/assets/css/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" />
    <link  href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @if (App::isLocal('ar'))
    <style>
        body{
            direction: rtl;
            text-align: right
        }
    </style>
    @endif
    @yield('style')
</head>
<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->
    @include('Backend.partial.navbar')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>
        @include('Backend.partial.sidebar')
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">

                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form class="form-horizontal">
                                    @method('DELETE')
                                    @csrf
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h4 align="center" style="margin:0;">{{__('general.confirm_delete')}}</h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark"
                                            data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                                        <button type="button" class="btn btn-success"
                                            id="delete_ok_button">{{ __('general.Delete') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form class="form-horizontal">
                                    @method('PUT')
                                    @csrf
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h4 align="center" style="margin:0;">{{__('general.confirm_active')}}</h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark"
                                            data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                                        <button type="button" class="btn btn-success" name="ok_button"
                                            id="ok_button">{{ __('general.Active') }}</button>
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
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h4 align="center" style="margin:0;">{{__('general.confirm_disactive')}}</h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark"
                                            data-bs-dismiss="modal">{{ __('general.Close') }}</button>
                                        <button type="button" class="btn btn-success"
                                            id="disactive_ok_button">{{ __('general.Disactive') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @yield('content')
                </div>
            </div>
            @include('Backend.partial.footer')
        </div>
    </div>
    <!-- END MAIN CONTAINER -->
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{asset('Backend/assets/js/libs/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('Backend/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('Backend/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('Backend/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('Backend/assets/js/app.js')}}"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="{{asset('Backend/assets/js/custom.js')}}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{asset('Backend/assets/js/customer.js')}}"></script>
    <script src="{{asset('Backend/plugins/apex/apexcharts.min.js')}}"></script>
    <script src="{{asset('Backend/assets/js/dashboard/dash_1.js')}}"></script>
    <script src="{{asset('Backend/assets/js/toastr.min.js')}}"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="{{asset('Backend/vendor/bootstrap-input-file/js/fileinput.min.js')}}"></script>
    @yield('script')
</body>
</html>
