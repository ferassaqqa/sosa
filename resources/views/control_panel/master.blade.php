<!doctype html>
<html lang="en" style="direction: rtl;">


<head>

    <meta charset="utf-8"/>
    {{--<meta content="width=device-width, initial-scale=1" name="viewport" />--}}
    @yield('title');
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('control_panel/assets/images/favicon.ico')}}">

    <link href="{{asset('control_panel/assets/libs/jqvmap/jqvmap.min.css')}}" rel="stylesheet"/>

    <link href="{{asset('control_panel/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('control_panel/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{asset('control_panel/assets/libs/spectrum-colorpicker2/spectrum.min.css')}}" rel="stylesheet"
          type="text/css">
    <link href="{{asset('control_panel/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}"
          rel="stylesheet">

    <!-- Sweet Alert-->
    <link href="{{asset('control_panel/assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Bootstrap Css -->
    <link href="{{asset('control_panel/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="{{asset('control_panel/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="{{asset('control_panel/assets/css/app-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('control_panel/assets/css/white-font.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('control_panel/assets/css/animate.min.css')}}" rel="stylesheet" type="text/css"/>
    {{--    <link href="{{asset('control_panel/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />--}}

    @yield('style')
<!-- Sweet Alert-->
    {{--<link href="{{asset('control_panel/assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<!-- Bootstrap Css -->--}}
    {{--<link href="{{asset('control_panel/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--    <link href="{{asset('control_panel/assets/css/dark.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<!-- Icons Css -->--}}
    {{--<link href="{{asset('control_panel/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<!-- App Css-->--}}
    {{--<link href="{{asset('control_panel/assets/css/app-rtl.min.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<link href="{{asset('control_panel/assets/css/white-font.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<link href="{{asset('control_panel/assets/css/animate.min.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<link href="{{asset('control_panel/clockpicker.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<link href="{{asset('control_panel/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<link rel="stylesheet" href="assets/vendor/animate.css/animate.min.css">--}}
    {{--<link href="{{asset('control_panel/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">--}}
    <style>
        .table td, th {
            text-align: center;
            vertical-align: middle;
        }
        @media only screen and (max-width: 1400px) {
            #dataTable{
                display: inline-block;
            }
            #dataTable1{
                display: inline-block;
            }
        }
        #dataTable {
            overflow-x: scroll;
            max-width: 100% !important;
        }
        #dataTable1 {
            overflow-x: scroll;
            max-width: 100% !important;
        }

        .alert-success {
            width: 17%;
        }

        .center-align {
            text-align-last: center;
        }

        .left-align {
            text-align-last: left;
        }

        .right-align {
            text-align-last: right;
        }

        .text-center-align {
            text-align: center;
        }

        .text-left-align {
            text-align: left;
        }

        .text-right-align {
            text-align: right;
        }

        .swal2-actions {
            z-index: 0;
        }

        .align-right {
            text-align: right;
        }

        .swal-wide {
            width: 50% !important;
        }

        .modal-xl {
            max-width: 1650px;
        }

        .modal-x2 {
            max-width: 1000px;
        }

        /* Absolute Center Spinner */
        .student_excel_import_loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        thead {
            background-color: #00937C;
            color: #fff;
        }

        /* Transparent Overlay */
        .student_excel_import_loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

            background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
        }

        /* :not(:required) hides these rules from IE9 and below */
        .student_excel_import_loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .student_excel_import_loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 150ms infinite linear;
            -moz-animation: spinner 150ms infinite linear;
            -ms-animation: spinner 150ms infinite linear;
            -o-animation: spinner 150ms infinite linear;
            animation: spinner 150ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        .app-search:hover {
            background-color: #0bb197 !important;
            transition: all 0.5s ease;

        }

        .app-search:hover .btn-hover {
            color: white !important;
        }

        /*.btn-hover:hover{*/
        /*    color: white !important;*/

        /*}*/

    </style>
</head>

{{--<body data-sidebar="dark" data-topbar="dark">--}}
<body>

<!-- <body data-layout="horizontal" data-topbar="dark"> -->

<!-- Begin page -->
<div id="layout-wrapper">
    <header style="background-color: #00937c !important;" id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box text-center">
                    {{--                    <a href="/" class="logo logo-dark">--}}
                    {{--                                <span class="logo-sm">--}}
                    {{--                                    <img src="{{asset('control_panel/lo.PNG')}}" style="margin-top: 11px;"--}}
                    {{--                                         alt="logo-sm-dark" height="70">--}}
                    {{--                                </span>--}}
                    {{--                        <span class="logo-lg">--}}
                    {{--                            السنة النبوية--}}
                    {{--                                    <img src="{{asset('control_panel/lo.PNG')}}" alt="logo-dark" height="70">--}}
                    {{--                                </span>--}}
                    {{--                    </a>--}}

                    {{--                    <a href="/" class="logo logo-light">--}}
                    {{--                                <span class="logo-sm">--}}
                    {{--                                    <img src="{{asset('control_panel/assets/images/logo-sm.png')}}" alt="logo-sm-light"--}}
                    {{--                                         height="22">--}}
                    {{--                                </span>--}}
                    {{--                        <span class="logo-lg">--}}
                    {{--                                    <img src="{{asset('control_panel/assets/images/logo-light.png')}}" alt="logo-light"--}}
                    {{--                                         height="24">--}}
                    {{--                                </span>--}}
                    {{--                    </a>--}}
                </div>

{{--                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect"--}}
{{--                        id="vertical-menu-btn">--}}
{{--                    <i class="ri-menu-2-line align-middle text-white"></i>--}}
{{--                </button>--}}

                @if(hasPermissionHelper('قسم تحفيظ السنة النبوية'))
                    <div class="app-search d-block d-lg-block"
                         style="border-left: 1px solid #7cb7ae; border-right: 1px solid #7cb7ae;    ">
                        <div class="dropdown d-inline-block user-dropdown">
                            <button style="border: none !important;" type="button" class="btn btn-hover text-white"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                قسم تحفيظ السنة النبوية
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="{{route('circles.index')}}">
                                    الحلقات والتقارير
                                </a>
                                <!-- item-->
                                <a class="dropdown-item" href="{{route('mohafez.index')}}">
                                    قائمة المحفظين
                                </a>
                                <!-- item-->
                                <a class="dropdown-item" href="{{route('circleStudents.index')}}">
                                    طلاب الحلقات
                                </a>
                                <!-- item-->
                                <a class="dropdown-item" href="{{route('circleBooks.index')}}">
                                    كتب الحلقات
                                </a>
                                <!-- item-->
                                <a class="dropdown-item" href="{{route('circlePlans.index')}}">
                                    الخطط السنوية لكتب الحلقات
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                @if(hasPermissionHelper('قسم الدورات العلمية'))
                    <div class="app-search d-block d-lg-block" style="border-left: 1px solid #7cb7ae;">
                        <div class="dropdown d-inline-block user-dropdown">
                            <button style="border: none !important;" type="button" class="btn btn-hover text-white"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                قسم الدورات العلمية
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @if(hasPermissionHelper('الدورات العلمية'))
                                    <a class="dropdown-item" href="{{route('courses.index')}}">
                                        الدورات العلمية
                                        <span
                                            style="    position: relative;
                                                right: 20px;
                                                padding: 1px 5px;
                                                border-radius: 100px;
                                                color: black;"
                                        >{{count(\App\Models\Course::get())}}</span>

                                    </a>
                                @endif
                            <!-- item-->

                                @if(hasPermissionHelper('معلمو الدورات'))
                                    <a class="dropdown-item" href="{{route('moallem.index')}}">
                                        معلمو الدورات
                                        <span
                                            style="    position: relative;
                                                right: 20px;
                                                padding: 1px 5px;
                                                border-radius: 100px;
                                                color: black;"
                                        >{{\App\Models\User::department(2)->count()}}</span>
                                    </a>
                                @endif
                            <!-- item-->

                                @if(hasPermissionHelper('طلاب الدورات'))
                                    <a class="dropdown-item" href="{{route('courseStudents.index')}}">
                                        طلاب الدورات
                                        <span
                                            style="    position: relative;
                                                right: 20px;
                                                padding: 1px 5px;
                                                border-radius: 100px;
                                                color: black;"
                                        >{{count(\App\Models\CourseStudent::whereHas('course')->get())}}</span>
                                    </a>
                                @endif
                            <!-- item-->
                                @if(hasPermissionHelper('كتب الدورات'))
                                    <a class="dropdown-item" href="{{route('books.index',['department'=>2])}}">
                                        كتب الدورات
                                        <span
                                            style="    position: relative;
                                                right: 20px;
                                                padding: 1px 5px;
                                                border-radius: 100px;
                                                color: black;"
                                        >{{count(\App\Models\Book::get())}}</span>
                                    </a>
                                @endif
                            <!-- item-->
                                @if(hasPermissionHelper('تصنيفات كتب الدورات'))
                                    <a class="dropdown-item" href="{{route('CourseBookCategory.index')}}">
                                        تصنيفات كتب الدورات
                                    </a>
                                @endif
                            <!-- item-->
                                @if(hasPermissionHelper('خطة الدورات'))
                                    <a class="dropdown-item" href="{{route('plans.index',['department'=>2])}}">
                                        الخطة
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if(hasPermissionHelper('قسم الأسانيد والإجازات'))
                <div class="app-search d-block d-lg-block" style="border-left: 1px solid #7cb7ae;">
                    <div class="dropdown d-inline-block user-dropdown">
                        <button style="border: none !important;" type="button" class="btn btn-hover text-white"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            قسم الأسانيد والإجازات
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">

                            @if (hasPermissionHelper('قسم الأسانيد والإجازات'))

                            <a class="dropdown-item" href="{{route('asaneedCourses.index')}}">
                                دورات الأسانيد
                                <span
                                    style="    position: relative;
                                            right: 20px;
                                            padding: 1px 5px;
                                            border-radius: 100px;
                                            color: black;"
                                >{{\App\Models\AsaneedCourse::count()}}</span>
                            </a>

                            @endif

                            <!-- item-->
                            @if (hasPermissionHelper('شيوخ الاسانيد'))

                            <a class="dropdown-item" href="{{route('asaneedMoallem.index')}}">
                                شيوخ الأسانيد
                                <span
                                    style="    position: relative;
                                            right: 20px;
                                            padding: 1px 5px;
                                            border-radius: 100px;
                                            color: black;"
                                >{{ \App\Models\User::department(7)->count()}}</span>

                            </a>

                            @endif

                            <!-- item-->
                            @if (hasPermissionHelper('طلاب الأسانيد والإجازات'))

                            <a class="dropdown-item" href="{{route('asaneedCourseStudents.index')}}">
                                طلاب دورات الاسانيد

                                <span
                                    style="    position: relative;
                                            right: 20px;
                                            padding: 1px 5px;
                                            border-radius: 100px;
                                            color: black;"
                                >{{ \App\Models\AsaneedCourseStudent::count()}}</span>
                            </a>
                            @endif

                            <!-- item-->
                            @if (hasPermissionHelper('كتب دورات الاسانيد'))

                            <a class="dropdown-item" href="{{route('asaneedBooks.index')}}">
                                كتب دورات الأسانيد
                                <span
                                    style="    position: relative;
                                            right: 20px;
                                            padding: 1px 5px;
                                            border-radius: 100px;
                                            color: black;"
                                >{{ \App\Models\AsaneedBook::count()}}</span>
                            </a>
                            @endif


                            @if (hasPermissionHelper('تصنيفات كتب الاسانيد'))

                            <!-- item-->
                            <a class="dropdown-item" href="{{route('asaneedBookCategories.index')}}">
                                تصنيفات كتب الأسانيد
                            </a>

                            @endif


                            @if (hasPermissionHelper('خطة دورات الاسانيد'))

                            <!-- item-->
                            <a class="dropdown-item" href="{{route('asaneedPlans.index')}}">
                                خطة الأسانيد
                            </a>

                            @endif

                        </div>
                    </div>
                </div>
            @endif
                @if(hasPermissionHelper('قسم الجودة والإختبارات'))
                    <div class="app-search d-block d-lg-block" style=" border-left: 1px solid #7cb7ae;">
                        <div class="dropdown d-inline-block user-dropdown">
                            <button style="border: none !important;" type="button" class="btn btn-hover text-white"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                قسم الجودة والإختبارات
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                            @if(hasPermissionHelper('حجز موعد اختبار'))
                                <!-- item-->
                                    <a class="dropdown-item" onclick="selectExamType()" style="cursor: pointer; display: none;">
                                        حجز موعد اختبار
                                    </a>
                            @endif
                            @if(hasPermissionHelper('طلبات حجز مواعيد الاختبارات'))
                                <!-- item-->
                                    <a class="dropdown-item" onclick="getPendingExamRequests()"
                                       style="cursor: pointer;  display: none;">
                                        طلبات حجز مواعيد الاختبارات
                                    </a>
                                @endif
                                @if(hasPermissionHelper('مواعيد الاختبارات'))
                                    <a class="dropdown-item" onclick="getNextExamsAppointments()"
                                       style="cursor: pointer;">
                                        طلبات حجز ومواعيد الاختبارات
                                    </a>
                                @endif
                                @if(hasPermissionHelper('ادخال الدرجات'))
                                    <a class="dropdown-item" onclick="addExamMarks()" style="cursor: pointer;">
                                        ادخال الدرجات
                                    </a>
                                @endif
                                @if(hasPermissionHelper('اعتماد الدرجات'))
                                    <a class="dropdown-item" onclick="getExamsWaitingApproveMarks()"
                                       style="cursor: pointer;">
                                        اعتماد الدرجات
                                    </a>
                                @endif
                                @if(hasPermissionHelper('ارشيف مواعيد الاختبارات'))
                                    <a class="dropdown-item" onclick="getExamsAppointmentsArchive()"
                                       style="cursor: pointer;">
                                        ارشيف الدورات المنتهية والدرجات
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if(hasPermissionHelper('التقارير والتقييمات'))
                    <div class="app-search d-block d-lg-block" style=" border-left: 1px solid #7cb7ae;">
                        <div class="dropdown d-inline-block user-dropdown">
                            <button style="border: none !important;" type="button" class="btn btn-hover text-white"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                التقارير والتقييمات
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="{{route('reports.all')}}">
                                    التقارير
                                </a>
                                <!-- item-->
                                <a class="dropdown-item" href="{{route('moallem.index')}}">
                                    التقييمات
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex">

                <div class="dropdown d-inline-block d-lg-none ms-2">
                    <button type="button" class="btn header-item noti-icon waves-effect"
                            id="page-header-search-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ri-search-line"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                         aria-labelledby="page-header-search-dropdown">

                        <form class="p-3">
                            <div class="mb-3 m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="dropdown d-none d-sm-inline-block">
                    {{--<button type="button" class="btn header-item waves-effect"--}}
                    {{--data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{--<img class="" src="{{asset('control_panel/assets/images/flags/us.jpg')}}" alt="Header Language" height="16">--}}
                    {{--</button>--}}
                    {{--<div class="dropdown-menu dropdown-menu-end">--}}

                    {{--<!-- item-->--}}
                    {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                    {{--<img src="{{asset('control_panel/assets/images/flags/spain.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>--}}
                    {{--</a>--}}

                    {{--<!-- item-->--}}
                    {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                    {{--<img src="{{asset('control_panel/assets/images/flags/germany.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>--}}
                    {{--</a>--}}

                    {{--<!-- item-->--}}
                    {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                    {{--<img src="{{asset('control_panel/assets/images/flags/italy.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>--}}
                    {{--</a>--}}

                    {{--<!-- item-->--}}
                    {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                    {{--<img src="{{asset('control_panel/assets/images/flags/russia.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                </div>

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    {{--<button type="button" class="btn header-item noti-icon waves-effect"--}}
                    {{--data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{--<i class="ri-apps-2-line"></i>--}}
                    {{--</button>--}}
                    {{--<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">--}}
                    {{--<div class="px-lg-2">--}}
                    {{--<div class="row g-0">--}}
                    {{--<div class="col">--}}
                    {{--<a class="dropdown-icon-item" href="#">--}}
                    {{--<img src="{{asset('control_panel/assets/images/brands/github.png')}}" alt="Github">--}}
                    {{--<span>GitHub</span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="col">--}}
                    {{--<a class="dropdown-icon-item" href="#">--}}
                    {{--<img src="{{asset('control_panel/assets/images/brands/bitbucket.png')}}" alt="bitbucket">--}}
                    {{--<span>Bitbucket</span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="col">--}}
                    {{--<a class="dropdown-icon-item" href="#">--}}
                    {{--<img src="{{asset('control_panel/assets/images/brands/dribbble.png')}}" alt="dribbble">--}}
                    {{--<span>Dribbble</span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="row g-0">--}}
                    {{--<div class="col">--}}
                    {{--<a class="dropdown-icon-item" href="#">--}}
                    {{--<img src="{{asset('control_panel/assets/images/brands/dropbox.png')}}" alt="dropbox">--}}
                    {{--<span>Dropbox</span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="col">--}}
                    {{--<a class="dropdown-icon-item" href="#">--}}
                    {{--<img src="{{asset('control_panel/assets/images/brands/mail_chimp.png')}}" alt="mail_chimp">--}}
                    {{--<span>Mail Chimp</span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="col">--}}
                    {{--<a class="dropdown-icon-item" href="#">--}}
                    {{--<img src="{{asset('control_panel/assets/images/brands/slack.png')}}" alt="slack">--}}
                    {{--<span>Slack</span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                        <i class="ri-fullscreen-line text-white"></i>
                    </button>
                </div>
                @if(0)
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect"
                                id="page-header-notifications-dropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-notification-3-line"></i>
                            <span class="noti-dot"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                             aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0"> Notifications </h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#!" class="small"> View All</a>
                                    </div>
                                </div>
                            </div>
                            <div data-simplebar style="max-height: 230px;">
                                <a href="#" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                        <i class="ri-shopping-cart-line"></i>
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Your order is placed</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="{{asset('control_panel/assets/images/users/avatar-3.jpg')}}"
                                                 class="rounded-circle avatar-xs" alt="user-pic">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">James Lemire</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">It will seem like simplified English.</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                                        <i class="ri-checkbox-circle-line"></i>
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Your item is shipped</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <a href="#" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="{{asset('control_panel/assets/images/users/avatar-4.jpg')}}"
                                                 class="rounded-circle avatar-xs" alt="user-pic">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Salena Layfield</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2 border-top">
                                <div class="d-grid">
                                    <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                        <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="dropdown d-inline-block user-dropdown">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{--<img class="rounded-circle header-profile-user" src="{{asset('control_panel/assets/images/users/avatar-2.jpg')}}"--}}
                        {{--alt="Header Avatar">--}}
                        <div class="avatar-xs" style="display: inline-block;">
                            <span class=" text-white avatar-title rounded-circle bg-soft-primary text-success">
                                @guest()
                                @else
                                    {{ \Illuminate\Support\Str::substr(\Illuminate\Support\Facades\Auth::user()->name, 0, 2) }}
                                @endguest
                            </span>
                        </div>
                        @guest()
                        @else
{{--                            <span class="text-white d-none d-xl-inline-block ms-1">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>--}}
                        @endguest
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        @if(0)
                            <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                            <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle me-1"></i> My
                                Wallet</a>
                            <a class="dropdown-item d-block" href="#"><span
                                    class="badge bg-success float-end mt-1">11</span><i
                                    class="ri-settings-2-line align-middle me-1"></i> Settings</a>
                            <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock
                                screen</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="changePassword()">
                                <i class="ri-lock-unlock-line align-middle me-1"></i>
                                تغيير كلمة المرور
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#"
                           onclick="event.preventDefault();
                                    $('#logOutForm').submit();">
                            <i class="ri-shut-down-line align-middle me-1 text-danger"></i>
                            خروج
                        </a>
                        <form method="POST" action="{{ route('logout') }}" id="logOutForm">
                            @csrf
                        </form>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    {{--<button type="button" class="btn header-item noti-icon waves-effect" onclick="switchMode()">--}}
                    {{--<i class="mdi mdi-theme-light-dark" style="color: #f3c682;"></i>--}}
                    {{--<i class="mdi mdi-theme-light-dark" style="color: #7b7a78;"></i>--}}
                    {{--</button>--}}
                </div>

            </div>
        </div>
    </header>

    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu" style="background-color: #00937c !important;">
        <div style="display: flex;
                    justify-content: center;
                    align-items: center;
                    ">
            <img src="{{asset('control_panel/lo.PNG')}}" height="150px" alt="شعار دار القران الكريم والسنة">
        </div>
        <div style="display: flex;
                    justify-content: center;
                    align-items: center;
                    ">
            @guest()
            @else
                <h6 class="text-white">{{ \Illuminate\Support\Facades\Auth::user()->name }}</h6>
            @endguest
        </div>

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title text-white">القائمة</li>

                    <li>
                        <a href="{{route('dashboard.index')}}" class="waves-effect">
                            <i class="mdi mdi-home-variant-outline text-white" style="color: white !important;"></i>
                            <span class="text-white">الرئيسية</span>
                        </a>
                    </li>
                    @if(hasPermissionHelper('المستخدمين'))
                        <li>
                            <a href="{{route('users.index')}}" class="waves-effect">
                                <i class="mdi mdi-account-group text-white"></i>
                                <span
                                    class="badge rounded-pill bg-primary float-end">{{\App\Models\User::count()}}</span>
                                <span class="text-white">المستخدمين</span>
                            </a>
                        </li>
                    @endif
                    @if(hasPermissionHelper('فئات الكتب'))
                        <li>
                            <a href="{{route('bookCategory.index')}}">
                                <i class="mdi mdi-chart-bubble text-white"></i>
                                <span
                                    class="badge  rounded-pill bg-primary float-end">{{\App\Models\BookCategory::count()}}</span>
                                <span class="text-white">فئات الكتب</span>

                            </a>
                        </li>
                    @endif
                    @if(hasPermissionHelper('اعدادات النظام'))
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-settings-4-fill text-white"></i>
                                <span class="text-white">إعدادات النظام</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a style="color: white !important;" href="javascript: void(0);" class="has-arrow">المستخدمين</a>
                                    <ul class="sub-menu" aria-expanded="true">
                                        <li>
                                            <a style="color: white !important;" class="text-white"
                                               href="{{route('roles.index')}}">
                                                ادوار المستخدمين
                                            </a>
                                        </li>
                                        <li>
                                            <a style="color: white !important;" class="text-white"
                                               href="{{route('activities')}}">
                                                أحداث المستخدمين
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="javascript: void(0);" class="has-arrow" style="color: white !important;">المناطق</a>
                                    <ul class="sub-menu" aria-expanded="true">
                                        <li>
                                            <a style="color: white !important;" href="{{route('areas.index')}}">
                                                المناطق
                                            </a>
                                        </li>
                                        <li>
                                            <a style="color: white !important;" href="{{route('places.index')}}">
                                                الأماكن (مساجد)
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->


    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                @yield('content')

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <footer class="footer">
            {{--<div class="container-fluid">--}}
            {{--<div class="row">--}}
            {{--<div class="col-sm-6">--}}
            {{--<script>document.write(new Date().getFullYear())</script> © Upzet.--}}
            {{--</div>--}}
            {{--<div class="col-sm-6">--}}
            {{--<div class="text-sm-end d-none d-sm-block">--}}
            {{--Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesdesign--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
        </footer>

    </div>
    <!-- end main content-->

</div>
<div class="student_excel_import_loading" style="display: none;">Loading&#8230;</div>

<div class="res-modal modal fade res-restore-modal" style="" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-center">
        <div class="modal-content" id="restore_modal_content">
            <div class="spinner-border text-success" role="status" style="margin:25px auto;">
                <span class="sr-only">Loading...</span>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="bs-modal modal fade bs-example-modal-x2" role="dialog" aria-labelledby="myExtraLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-x2  modal-dialog-scrollable">
        <div class="modal-content" id="user_modal_content_new">

            <div class="spinner-border text-success" role="status" style="margin:25px auto;">
                <span class="sr-only">Loading...</span>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="bs-modal modal fade bs-example-modal-xl" role="dialog" aria-labelledby="myExtraLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl  modal-dialog-scrollable">
        <div class="modal-content" id="user_modal_content">

            <div class="spinner-border text-success" role="status" style="margin:25px auto;">
                <span class="sr-only">Loading...</span>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="bs-modal modal fade bs-example-modal-center" style="" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-center">
        <div class="modal-content" id="modal_content">

            <div class="spinner-border text-success" role="status" style="margin:25px auto;">
                <span class="sr-only">Loading...</span>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- END layout-wrapper -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>
<input type="file" id="excelFile" style="display: none;" onchange="loadFile(event)">
<a href="#!" style="display: none;" id="downloadableLink"></a>


<!-- JAVASCRIPT -->
<script src="{{asset('control_panel/assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/node-waves/waves.min.js')}}"></script>

<script src="{{asset('control_panel/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>

<!-- plugins -->
<script src="{{asset('control_panel/assets/libs/select2/js/select2.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/spectrum-colorpicker2/spectrum.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
<script
    src="{{asset('control_panel/assets/libs/admin-resources/bootstrap-filestyle/bootstrap-filestyle.min.js')}}"></script>
<script src="{{asset('control_panel/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

<!-- init js -->
@yield('script')

<script src="{{asset('control_panel/assets/js/app.js')}}"></script>

<script src="{{asset('control_panel/assets/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('control_panel/assets/js/bootstrap-notify.min.js')}}"></script>

<!-- JAVASCRIPT -->
{{--<script src="{{asset('control_panel/assets/libs/jquery/jquery.min.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/assets/libs/metismenu/metisMenu.min.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/assets/libs/node-waves/waves.min.js')}}"></script>--}}

{{--<script src="{{asset('control_panel/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>--}}

{{--<script src="{{asset('control_panel/assets/libs/select2/js/select2.min.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/assets/js/pages/form-advanced.init.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/assets/js/app.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/assets/js/jquery.dataTables.min.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/assets/js/bootstrap-notify.min.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/clockpicker.js')}}"></script>--}}
{{--<script src="{{asset('control_panel/bootstrap-datepicker.min.js')}}"></script>--}}


<script>
    $(document).ajaxStart(function() {
        // show loader on start
        console.log('ajax_start');
    }).ajaxSuccess(function() {
        // hide loader on success
        console.log('ajax_success');
        // $("#loader").css("display","none");
    });
    $('.bs-modal').on('hidden.bs.modal', function () {
        $('.modal-content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
    });
    $('.res-modal').on('hidden.bs.modal', function () {
        $('#restore_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
    });
    $('.call-modal').on('click', function (e) {
        var url = $(this).data('url');
        $.get(url, function (data) {
            $('#modal_content').html(data)
        });
    });
    $('.call-user-modal').unbind('click').on('click', function (e) {
        var url = $(this).data('url');
        $.get(url, function (data) {
            $('#user_modal_content').html(data)
        });
    });

    function callUserModal(obj) {
        Swal.close();
        var url = obj.getAttribute('data-url');
        $.get(url, function (data) {
            $('#user_modal_content').html(data);
        });
    }
    @if(0)
    function changePassword() {
        Swal.fire({
            title: 'ادخل كلمة المرور',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'تغيير',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true,
            preConfirm: function (value) {
                return fetch('/changePassword/' + value)
                    .then(function (response) {
                        return response.json();
                    }).then(function (responseJson) {
                        // console.log(responseJson);
                        if (responseJson.errors) {
                            Swal.showValidationMessage(
                                responseJson.msg
                            );
                            // throw new Error('Something went wrong')
                        } else {
                            Swal.close();
                            Swal.fire({title: "تعدبل", text: "تم تعديل كلمة المرور بنجاح.", icon: "success"});
                        }
                        // Do something with the response
                    })
                    .catch(function (errors) {
                        // Swal.showValidationMessage(
                        //     'لا يوجد اتصال بالشبكة'
                        // )
                    });
            },
            allowOutsideClick: function () {
                !Swal.isLoading();
            }
        }).then(function (result) {
            // console.log(result);
            if (result.isConfirmed) {
                // if(result.value.errors == 0) {
                // $('.bs-example-modal-xl').modal('show');
                // $('#user_modal_content').html(result.value.view);
                // }
            }
        })
    }
    @endif
    function checkAll(obj) {
        var status = obj.checked;
        if (status) {
            $('input[name="id[]"]').attr('checked', true);
        } else {

            $('input[name="id[]"]').removeAttr('checked');
        }
    }

    $('.check-all').click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    function callApi(obj, id) {
        // $('#'+id).empty();
        Swal.close();
        var url = obj.getAttribute('data-url');
        $.get(url, function (data) {
            $('#' + id).html(data)
        });
    }

    function deleteAllItems(obj) {
        var url = obj.getAttribute('data-url');
        // console.log(url);
        Swal.fire(
            {
                title: "هل انت متأكد",
                text: "لن تتمكن من استرجاع البيانات لاحقاً",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "نعم إحذف البيانات",
                cancelButtonText: "إلغاء",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1
            })
            .then(
                function (t) {
                    if (t.value) {
                        $.get(url, function (result) {
                            $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title + ' </strong> | ' + result.msg,
                                {allow_dismiss: true, type: result.type}
                            );
                            Swal.fire({title: "تم الحذف!", text: "تم حذف الملف بنجاح.", icon: "success"});
                            if (result.type == 'success') {
                                $('#dataTable').DataTable().ajax.reload();
                            }

                        });
                    } else {
                        Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                    }
                }
            );

    }

    function deleteItem(obj) {
        var url = obj.getAttribute('data-url');
        // console.log(url);
        Swal.fire(
            {
                title: "هل انت متأكد",
                text: "لن تتمكن من استرجاع البيانات لاحقا",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "نعم إحذف البيانات",
                cancelButtonText: "إلغاء",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1
            })
            .then(
                function (t) {
                    if (t.value) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {_method: 'DELETE', _token: '{{csrf_token()}}'},
                            success: function (result) {
                                $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title + ' </strong> | ' + result.msg,
                                    {allow_dismiss: true, type: result.type}
                                );
                                result.type = result.type == 'danger' ? 'error' : result.type;
                                Swal.fire({title: result.title, text: result.msg, icon: result.type});
                                if (result.type == 'success') {
                                    $('#dataTable').DataTable().ajax.reload();
                                    $('.modal').modal('hide');
                                }
                            }
                        });
                    } else {
                        Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                    }
                }
            );

    }

    function deleteSelected(obj) {
        var url = obj.getAttribute('data-url');
        // console.log(url);
        var values = $("input[name='id[]']")
            .map(function () {
                if ($(this).prop('checked')) return $(this).val();
            }).get();
        if (values.length) {
            Swal.fire({
                customClass: 'swal-wide',
                title: "هل انت متأكد",
                text: "لن تتمكن من استرجاع البيانات لاحقاً",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "نعم إحذف البيانات",
                cancelButtonText: "إلغاء",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1
            })
                .then(
                    function (t) {
                        if (t.value) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {_method: 'DELETE', _token: '{{csrf_token()}}', ids: values},
                                success: function (result) {
                                    $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title + ' </strong> | ' + result.msg,
                                        {allow_dismiss: true, type: result.type}
                                    );
                                    Swal.fire({title: "تم الحذف!", text: "تم حذف الملف بنجاح.", icon: "success"});
                                    if (result.type == 'success') {
                                        setTimeout(function () {
                                            $('#dataTable').DataTable().ajax.reload();
                                        }, 1100)
                                    }
                                }
                            });
                        } else {
                            Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                        }
                    }
                );
        } else {
            $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>تنبيه !</strong> | يرجى تحديد بيانات لحذفها',
                {allow_dismiss: true, type: 'danger'}
            );
        }
    }

    function restoreSelected(obj) {
        var url = obj.getAttribute('data-url');
        // console.log(url);
        var values = $("input[name='id[]']")
            .map(function () {
                if ($(this).prop('checked')) return $(this).val();
            }).get();
        if (values.length) {
            Swal.fire({
                customClass: 'swal-wide',
                title: "هل انت متأكد",
                text: "سيتم استرجاع كافة البيانات المحددةً",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "نعم استرجع البيانات",
                cancelButtonText: "إلغاء",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1
            })
                .then(
                    function (t) {
                        if (t.value) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {_token: '{{csrf_token()}}', ids: values},
                                success: function (result) {
                                    $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title + ' </strong> | ' + result.msg,
                                        {allow_dismiss: true, type: result.type}
                                    );
                                    Swal.fire({
                                        title: "تم الاسترجاع!",
                                        text: "تم استرجاع البيانات بنجاح.",
                                        icon: "success"
                                    });
                                    if (result.type == 'success') {
                                        setTimeout(function () {
                                            $('#dataTable').DataTable().ajax.reload();
                                        }, 1100)
                                    }
                                }
                            });
                        } else {
                            Swal.fire({title: "لم يتم الاسترجاع!", text: "البيانات لم تسترجع.", icon: "error"});
                        }
                    }
                );
        } else {
            $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>تنبيه !</strong> | يرجى تحديد بيانات لحذفها',
                {allow_dismiss: true, type: 'danger'}
            );
        }
    }

    function restoreItem(obj) {
        var url = obj.getAttribute('data-url');
        Swal.fire(
            {
                title: "هل انت متأكد",
                text: "سيتم استرجاع البياناتً",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "نعم استرجع البيانات",
                cancelButtonText: "إلغاء",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1
            })
            .then(
                function (t) {
                    if (t.value) {
                        $.get(url, function (result) {
                            $('.modal').modal('hide');
                            $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title + ' </strong> | ' + result.msg,
                                {allow_dismiss: true, type: result.type}
                            );
                            Swal.fire({title: "تم الاسترجاع!", text: "تم استرجاع البيانات بنجاح.", icon: "success"});
                            if (result.type == 'success') {
                                $('#dataTable').DataTable().ajax.reload();
                            }
                        });
                    } else {
                        Swal.fire({title: "لم يتم الاسترجاع!", text: "البيانات لم تسترجع.", icon: "error"});
                    }
                }
            );

    }
    @if(hasPermissionHelper('حجز موعد اختبار'))
    function selectExamType() {
        Swal.fire(
            {
                title: "حجز موعد اختبار",
                html: "<div style='padding: 5px;'>" +
                    "<button class='btn btn-success' onclick='Swal.clickConfirm();'  style='margin-left: 3px;'>حلقات السنة النبوية</button>" +
                    "<button class='btn btn-danger' onclick='Swal.clickDeny()'>الدورات العلمية</button>" +
                    "</div>",
                icon: "info",
                showCancelButton: 0,
                showConfirmButton: 0,
            })
            .then(
                function (t) {
                    if (t.isConfirmed) {
                        console.log('حلقات');

                    } else if (t.isDenied) {
                        console.log('دورات');
                        getEligibleCourses();
                    }
                }
            );

    }

    function getEligibleCourses() {
        window.open('/examEligibleCourses', '_blank');
        // $('.bs-example-modal-center').modal('hide');
        // $('.bs-example-modal-xl').modal('hide');
        // $('#modal-content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        // $.get('/examEligibleCourses', function (result) {
        //     $('.bs-example-modal-xl').modal('show');
        //     $('#user_modal_content').html(result);
        // });
    }
    @endif
    @if(hasPermissionHelper('ادخال الدرجات'))
    function addExamMarks() {
        Swal.fire(
            {
                title: "ادخال درجات الاختبارات",
                html:
                    "<div style='padding: 5px;'>" +
                    "   <button class='btn btn-success' onclick='Swal.clickConfirm();'>حلقات السنة النبوية</button>" +
                    "   <button class='btn btn-danger' onclick='Swal.clickDeny()'>الدورات العلمية</button>" +
                    "</div>",
                icon: "info",
                showCancelButton: 0,
                showConfirmButton: 0,
            })
            .then(
                function (t) {
                    if (t.isConfirmed) {

                    } else if (t.isDenied) {
                        getEligibleCoursesForMarkEnter();
                    }
                }
            );

    }

    function getEligibleCoursesForMarkEnter() {
        window.open('/getEligibleCoursesForMarkEnter', '_blank');
        // $('.bs-example-modal-xl').modal('show');
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        // $.get('/getEligibleCoursesForMarkEnter', function (result) {
        //     $('#user_modal_content').html(result);
        // });
    }
    @endif
    @if(hasPermissionHelper('طلبات حجز مواعيد الاختبارات'))
    function getPendingExamRequests() {
        window.open('/getPendingExamRequests', '_blank');
        // $('.bs-example-modal-xl').modal('show');
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        // $.get('/getPendingExamRequests', function (data) {
        //     $('#user_modal_content').html(data);
        //     // console.log(data);
        // });
    }
    @endif
    @if(hasPermissionHelper('مواعيد الاختبارات'))
    function getNextExamsAppointments() {
        window.open('/getNextExamsAppointments', '_blank');
        // $('.bs-example-modal-xl').modal('show');
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        // $.get('/getNextExamsAppointments', function (data) {
        //     $('#user_modal_content').html(data);
        //     // console.log(data);
        // });
    }
    @endif
    @if(hasPermissionHelper('اعتماد الدرجات'))
    function getExamsWaitingApproveMarks() {
        window.open('/getExamsWaitingApproveMarks', '_blank');
        // $('.bs-example-modal-xl').modal('show');
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        // $.get('/getExamsWaitingApproveMarks', function (data) {
        //     $('#user_modal_content').html(data);
        //     // console.log(data);
        // });
    }
    @endif
    @if(hasPermissionHelper('ارشيف مواعيد الاختبارات'))
    function getExamsAppointmentsArchive() {
        window.open('/getExamsAppointmentsArchive', '_blank');
        // $('.bs-example-modal-xl').modal('show');
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        // $.get('/getExamsAppointmentsArchive', function (data) {
        //     $('#user_modal_content').html(data);
        //     // console.log(data);
        // });
    }
    @endif

    function examsDeptManagerApprovement(exam_id, obj) {
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        $.get('/examsDeptManagerApprovement/' + exam_id, function (data) {
            if (!data.errors) {
                if (data.status == 2) {
                    $(obj).text('اعتماد رئيس قسم الاختبارات');
                    Swal.fire({
                        position: "top-right", icon: "success", title: data.msg, showConfirmButton: !1, timer: 2500
                    })
                } else if (data.status == 3) {
                    $(obj).text('تراجع اعتماد رئيس قسم الاختبارات');
                    Swal.fire({
                        position: "top-right", icon: "success", title: data.msg, showConfirmButton: !1, timer: 2500
                    })
                }
            }
            // console.log(data);
        });
    }

    function qualityDeptManagerApprovement(exam_id, obj) {
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        $.get('/qualityDeptManagerApprovement/' + exam_id, function (data) {
            if (!data.errors) {
                if (data.status == 3) {
                    $(obj).text('اعتماد مدير دائرة التخطيط والجودة');
                    Swal.fire({
                        position: "top-right", icon: "success", title: data.msg, showConfirmButton: !1, timer: 2500
                    })
                } else if (data.status == 4) {
                    $(obj).text('تراجع اعتماد مدير دائرة التخطيط والجودة');
                    Swal.fire({
                        position: "top-right", icon: "success", title: data.msg, showConfirmButton: !1, timer: 2500
                    })
                }
            }
            // console.log(data);
        });
    }

    function sunnaManagerApprovement(exam_id, obj) {
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        $.get('/sunnaManagerApprovement/' + exam_id, function (data) {
            if (!data.errors) {
                if (data.status == 4) {
                    $(obj).text('اعتماد مدير الدائرة');
                    Swal.fire({
                        position: "top-right", icon: "success", title: data.msg, showConfirmButton: !1, timer: 2500
                    })
                } else if (data.status == 5) {
                    $(obj).text('تراجع اعتماد مدير الدائرة');
                    Swal.fire({
                        position: "top-right", icon: "success", title: data.msg, showConfirmButton: !1, timer: 2500
                    })
                }
            }
            // console.log(data);
        });
    }

    $('.export-excel').on('click', function (e) {
        var url = $(this).data('url');
        var isDeleted = $(this).data('is-deleted');
        var search = document.querySelector('input[type="search"]');
        if (search) {
            search = search.value;
        }
        Swal.fire({
            title: "تصدير اكسل",
            text: "اختر البيانات المراد تصديرها",
            icon: "question",
            showCancelButton: !0,
            showCloseButton: true,
            confirmButtonText: "الكل",
            cancelButtonText: "الصفحة الحالية",
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonClass: "btn btn-info ms-2 mt-2",
            buttonsStyling: !1
        })
            .then(
                function (t) {
                    console.log(t, t.isDenied);
                    if (t.isConfirmed) {
                        window.open(url + '?search=' + search + '&isDeleted=' + isDeleted + '&page=all', '_blank');
                    } else if (t.isDismissed && t.dismiss == 'cancel') {
                        var values = $("input[name='id[]']")
                            .map(function () {
                                return $(this).val();
                            }).get();
                        window.open(url + '?page=current&ids=' + values, '_blank');
                    }
                }
            );
    });
    $('.import-excel').on('click', function (e) {
        document.getElementById('excelFile').click();
    });
    var loadFile = function (event) {
        var url = $('.import-excel').data('url');
        var formData = new FormData();
        formData.append('file', event.target.files[0]);
        formData.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                // console.log(data);
                document.getElementById('downloadableLink').setAttribute('href', data.file_link);
                document.getElementById('downloadableLink').click();
            },
            error: function (errors) {
                Swal.fire(
                    'خطأ !',
                    errors.responseJSON.errors.file[0],
                    'error'
                )
            }
        });
        event.path[0].value = '';
        $('#dataTable').DataTable().ajax.reload();
    };

    // console.log(document.styleSheets);
</script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script>
    const firebaseConfig = {
        apiKey: "AIzaSyBSi08DoXXU5rNvK7chgObzLi_l1_807VM",
        authDomain: "sunna-b0909.firebaseapp.com",
        projectId: "sunna-b0909",
        storageBucket: "sunna-b0909.appspot.com",
        messagingSenderId: "141645051731",
        appId: "1:141645051731:web:11c4995110408f7a4993a6",
        measurementId: "G-D7XE883R18"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    (function(){
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                // console.log(token);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                $.ajax({
                    url: '{{ route("save-token") }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Chat Token Error'+ err);
                    },
                });

            }).catch(function (err) {
            console.log('User Chat Token Error'+ err);
        });
    })();


</script>
</body>

</html>
