@extends('control_panel.master')

@section('title')
    <title>برنامج السنة | إحصائيات النظام </title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">إحصائيات النظام</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">إحصائيات النظام</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-md-12">

    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h2>
                            أولا : الطلاب والدورات
                        </h2>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-md-3">
                        <select id="dashboard_area_id" onchange="getDashboardSubAreas(this)" class="form-control">
                            <option value="0">الكل</option>
                            @foreach($areas as $key => $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-md-3">
                        <select id="dashboard_sub_areas_select" onchange="updateCourseAndStudentsStatisticsInDashboard()" class="form-control">
                            <option value="0">المحلية</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-md-3">
                        <select id="is_accepted" onchange="updateCourseAndStudentsStatisticsInDashboard()" class="form-control">
                            <option value="0">اختر - مجازين - غير مجازين</option>
                            <option value="مجازين">مجازين</option>
                            <option value="غير مجازين">غير مجازين</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-md-3">
                        <select id="dashboard_courses_status" onchange="updateCourseAndStudentsStatisticsInDashboard()" class="form-control">
                            <option value="0">حالة الدورة</option>
                            <option value="انتظار الموافقة">انتظار الموافقة</option>
                            <option value="قائمة">قائمة</option>
                            <option value="معلقة">معلقة</option>
                            <option value="منتهية">منتهية</option>
                            <option value="بانتظار الاعتماد الاداري">بانتظار الاعتماد الاداري</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="courseAndStudentsFilter">
    <!-- end col -->
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3 align-self-center">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                <i class="mdi mdi-refresh"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-1">الدورات</p>
                        <h5 class="mb-3">{{ $courses }}</h5>
                        {{--<p class="text-truncate mb-0"><span class="text-success me-2"> 1.7% <i class="ri-arrow-right-up-line align-bottom ms-1"></i></span> From previous</p>--}}
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->

    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex text-muted">
                    <div class="flex-shrink-0 me-3 align-self-center">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                <i class="mdi mdi-alpha"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-1">المعلمين</p>
                        <h5 class="mb-3">{{ $moallems }}</h5>
                        {{--<p class="text-truncate mb-0"><span class="text-danger me-2"> 0.01% <i class="ri-arrow-right-down-line align-bottom ms-1"></i></span> From previous</p>--}}
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->

    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex text-muted">
                    <div class="flex-shrink-0  me-3 align-self-center">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                <i class="ri-group-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-1">الطلاب</p>
                        <h5 class="mb-3">{{ $course_students_count }}</h5>
                        {{--<p class="text-truncate mb-0"><span class="text-success me-2"> 0.01% <i class="ri-arrow-right-up-line align-bottom ms-1"></i></span> From previous</p>--}}
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    </div>
</div>

@endsection

@section('script')
    <script>
        function getDashboardSubAreas(obj) {
            if(obj.value != 0) {
                $.get('/getSubAreas/'+obj.value, function (data) {
                    $('#dashboard_sub_areas_select').empty().html(data);
                });
                updateCourseAndStudentsStatisticsInDashboard();
            }else{
                $('#dashboard_sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                updateCourseAndStudentsStatisticsInDashboard();
            }
        }
        function updateCourseAndStudentsStatisticsInDashboard() {
            var filters = '?dashboard_area_id='+$('#dashboard_area_id').val()+'&dashboard_sub_areas_id='+$('#dashboard_sub_areas_select').val()+'&is_accepted='+$('#is_accepted').val()+'&status='+$('#dashboard_courses_status').val();
            $.get('/updateCourseAndStudentsStatisticsInDashboard'+filters,function(data){
                $('#courseAndStudentsFilter').empty().html(data);
            });
            // console.log(88);
        }
    </script>
    {{--<script src="{{asset('control_panel/assets/libs/metismenu/metisMenu.min.js')}}"></script>--}}
    {{--<script src="{{asset('control_panel/assets/libs/simplebar/simplebar.min.js')}}"></script>--}}
    {{--<script src="{{asset('control_panel/assets/libs/node-waves/waves.min.js')}}"></script>--}}

    {{--<script src="{{asset('control_panel/assets/libs/apexcharts/apexcharts.min.js')}}"></script>--}}

    {{--<script src="{{asset('control_panel/assets/libs/jqvmap/jquery.vmap.min.js')}}"></script>--}}
    {{--<script src="{{asset('control_panel/assets/libs/jqvmap/maps/jquery.vmap.usa.js')}}"></script>--}}

{{--    <script src="{{asset('control_panel/assets/js/pages/dashboard.init.js')}}"></script>--}}
@endsection