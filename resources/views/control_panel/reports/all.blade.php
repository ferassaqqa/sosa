@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('title')
    <title>برنامج السنة | التقارير</title>
@endsection
@section('content')
    <style>
        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        }

        .dataTables_paginate {
            display: none;
        }
    </style>

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">التقارير</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">التقارير</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control" onchange="selectDepartment(this)" id="reports_department_id">
                                <option value="0">اختر القسم</option>
                                <option value="قسم الدورات العلمية">قسم الدورات العلمية</option>
                                <option value="قسم تحفيظ السنة النبوية">قسم تحفيظ السنة النبوية</option>
                                <option value="قسم أسانيد السنة النبوية">قسم أسانيد السنة النبوية</option>
                                <option value="الأنشطة الادارية">الأنشطة الادارية</option>
                                <option value="جميع الأقسام">جميع الأقسام</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="analysis_type" onchange="updateTable()">
                                <option value="0">اختر التحليل المناسب</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd"
                                 data-date-autoclose="true" data-provide="datepicker"
                                 data-date-container='#datepicker6'>
                                <input type="text" class="form-control" name="start_date" id="start_date"
                                       onchange="updateTable()" placeholder="تاريخ البداية"/>
                                <input type="text" class="form-control" name="end_date" id="end_date"
                                       onchange="updateTable()" placeholder="تاريخ النهاية"/>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control" onchange="getSubArea(this)" id="report_area_select">
                                <option value="0">اختر المنطقة</option>
                                @foreach($areas as $key => $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="report_sub_area_select" onchange="updateTable()">
                                <option value="0">اختر المنطقة المحلية</option>
                            </select>
                        </div>
                    </div>
                    <hr>


                    <div class="col-md-3">
                        <button type="button" style="width:100%" onclick="updateDateTable()"
                            class="btn btn-primary btn-block">
                            <i class="mdi mdi-magnify" aria-hidden="true"></i>
                            ابحث
                        </button>
                    </div>

                    
                    <div class="row mb-3" id="custom_filters"></div>
                    <div id="tableContainer">

                        <table class="table table-centered   table-nowrap mb-0" id="dataTable" style="font-size: 18px">
                            <thead>
                            <tr>
                                <th scope="col" style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">الكتاب</th>
                                <th scope="col">فئة الخريجين</th>
                                <th scope="col">العدد المطلوب</th>
                                <th scope="col">العدد المنجز</th>
                                <th scope="col">نسبة الانجاز</th>
                                <th scope="col">نسبة الفائض</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($value) && count($value) > 0)
                                @foreach($value as $index => $val)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td style="text-align: justify">{{$val['book_name']}}</td>
                                        <td style="text-align: justify">{{$val['graduated_categories']}}</td>
                                        <td>{{$val['required_num']}}</td>
                                        <td>{{$val['completed_num']}}</td>
                                        <td>{{$val['completed_num_percentage']}} %</td>
                                        <td>{{$val['excess_num_percentage']}} %</td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr style="background-color: #00937C; color: white">
                                <th scope="col" colspan="3">
                                    المجموع
                                </th>
                                <th scope="col">{{$required_num}}</th>
                                <th scope="col">{{$completed_num}}</th>
                                <th scope="col">%{{$completed_num_percentage}}</th>
                                <th scope="col">%{{$excess_num_percentage}}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>

@endsection

@section('script')

    <script>
        function selectDepartment(obj) {
            if (obj.value) {
                switch (obj.value) {
                    case 'قسم الدورات العلمية': {
                        $('#analysis_type').empty().html(
                            '<option value="0">اختر التحليل المناسب</option>' +
                            '<option value="coursePlanProgress">انجاز خطة الدورات</option>' +
                            '<option value="الأكثر إنجازًا">الأكثر إنجازًا</option>' +
                            '<option value="برنامج الصفوة">برنامج الصفوة</option>'
                        );
                    }
                        break;
                    case 'قسم تحفيظ السنة النبوية': {
                        $('#analysis_type').empty().html(
                            '<option value="0">اختر التحليل المناسب</option>' +
                            '<option value="إنجاز خطة الحفظ">إنجاز خطة الحفظ</option>' +
                            '<option value="فئات الحفظ">فئات الحفظ</option>' +
                            '<option value="طلاب الجلسة الواحدة">طلاب الجلسة الواحدة</option>'
                        );
                    }
                        break;
                    case 'قسم أسانيد السنة النبوية': {
                        $('#analysis_type').empty().html(
                            '<option value="0">اختر التحليل المناسب</option>' +
                            '<option value="إنجاز خطة الاسانيد">إنجاز خطة الاسانيد</option>' +
                            '<option value="إحصاءات">إحصاءات</option>'
                        );

                    }
                        break;
                    case 'الأنشطة الادارية': {
                        $('#analysis_type').empty().html(
                            '<option value="0">اختر التحليل المناسب</option>' +
                            '<option value="الاجتماعات">الاجتماعات</option>'
                        );
                    }
                        break;
                    case 'جميع الأقسام': {
                        $('#analysis_type').empty().html(
                            '<option value="0">اختر التحليل المناسب</option>'
                        );
                    }
                        break;
                }
                // updateTable();
            }
        }

        function getSubArea(obj) {
            if (obj.value) {
                $.get('getSubAreas/' + obj.value, function (data) {
                    $('#report_sub_area_select').empty().html(data);
                });
                updateTable();
            }
        }

        function updateTable() {
            var filters = '?department_id=' + $('#reports_department_id').val() + '&analysis_type=' + $('#analysis_type').val()
                + '&start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val()
                + '&sub_area_id=' + $('#report_sub_area_select').val() + '&area_id=' + $('#report_area_select').val();
            $.get('/getAnalysisView' + filters, function (data) {
                $('#tableContainer').empty().html(data.view);
                $('#custom_filters').empty().html(data.filters);
            });
        }
    </script>

@endsection
