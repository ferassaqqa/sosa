<!doctype html>
<html lang="en" style="direction: rtl;">


<head>

    <meta charset="utf-8" />
    <title>تسجيل الدخول</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('control_panel/assets/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('control_panel/assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('control_panel/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('control_panel/assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('control_panel/assets/css/login-font.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="bg-pattern">
    <div class="bg-overlay"></div>
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-6 col-md-8">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="">
                                <div class="text-center">
                                    <a href="/" class="">
                                        {{-- <img src="{{ asset('control_panel/assets/images/logo-dark.png')}}" alt="" height="24" class="auth-logo logo-dark mx-auto"> --}}
                                        {{-- <img src="{{ asset('control_panel/assets/images/logo-light.png')}}" alt="" height="24" class="auth-logo logo-light mx-auto"> --}}
                                    </a>
                                </div>
                                <!-- end row -->
                                {{-- <h4 class="font-size-18 text-muted mt-2 text-center">Welcome Back !</h4> --}}
                                <p class="mb-5 text-center">تسجيل الدخول الى برنامج السنة النبوية</p>
                                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-4">
                                                <label class="form-label" for="id_num">رقم الهوية</label>
                                                <input type="number"
                                                    class="form-control @error('id_num') is-invalid @enderror"
                                                    name="id_num" id="id_num" value="{{ old('id_num') }}"
                                                    style="direction: rtl;">
                                                @error('id_num')
                                                    <div class="invalid-feedback" style="display: block;">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label" for="password">كلمة المرور</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror">
                                                @error('password')
                                                    <div class="invalid-feedback" style="display: block;">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customControlInline">
                                                        <label class="form-label form-check-label"
                                                            for="customControlInline">تذكرني</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid mt-4">
                                                <button class="btn btn-primary waves-effect waves-light"
                                                    type="submit">دخول</button>
                                            </div>
                                            <div class="d-grid mt-4">
                                                <p>اذا واجهتك مشكله في تسجيل الدخول يرجى الضغط على الزر التالي
                                                    <a href="#" class="btn  btn-light  btn-sm col-3 logOutBtn"
                                                        type="button">اضغط هنا</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('logout') }}" id="logOutForm">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end Account pages -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('control_panel/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('control_panel/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('control_panel/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('control_panel/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('control_panel/assets/libs/node-waves/waves.min.js') }}"></script>

    <script src="{{ asset('control_panel/assets/js/app.js') }}"></script>

    <script>
                   $( document ).ready(function() {
                        $('.logOutBtn').on('click',function(e){
                            e.preventDefault();

                            // alert('vvdfsds');
                                 $('#logOutForm').submit();
                        });

                    });

    </script>

</body>

</html>
