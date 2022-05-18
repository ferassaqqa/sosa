@extends('control_panel.master')


@section('style')
    <link href="{{ asset('control_panel/assets/css/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | طلاب الدورات العلمية </title>
@endsection
@section('content')


    <style>
        .static .table_header {
            color: white;
            background-color: #00937C;
            font-weight: 600;
        }

        .static td {
            border: 1px solid #f8f9fa;
        }

        .static .value {
            font-weight: 600;
            border: 1px solid #f8f9fa;

        }

    </style>


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"> <span style="font-weight: 100">الدورات ></span> طلاب الدورات العلمية</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">طلاب الدورات العلمية</li>
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




                        <table  width="100%" class="table table-centered table_bordered static" dir="rtl">
                            <tbody>
                                <tr class="table_header">
                                    <td colspan="3">عدد الطلاب الكلي <div id="students_count"></div>
                                    </td>
                                    <td colspan="6">إجمالي عدد الطلاب الناجحين <div id="students_count_success"></div>
                                    </td>
                                    <td colspan="1">إجمالي عدد الشهادات <div  id="students_count_certificate">(-)</div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>عدد الطلاب الناجحين</td>
                                    <td>عدد الطلاب الراسبين</td>
                                    <td>عدد الطلاب قيد الانتظار</td>


                                    <td>ممتاز <div>(100.09)</div>
                                    </td>
                                    <td>جيد جدا مرتفع <div>(89.85)</div>
                                    </td>
                                    <td>جيد جدا <div>(84.80)</div>
                                    </td>
                                    <td>جيد مرتفع <div>(79.75)</div>
                                    </td>
                                    <td>جيد <div>(74.07)</div>
                                    </td>
                                    <td>مقبول <div>(69.06)</div>
                                    </td>

                                    <td>عدد الدورات</td>
                                </tr>

                                <tr class="value">
                                    <td id="passed_students_count"></td>
                                    <td id="failed_students_count"></td>
                                    <td id="awaiting_students_count"></td>


                                    <td id="students_100"></td>
                                    <td id="students_89"></td>
                                    <td id="students_84"></td>
                                    <td id="students_79"></td>
                                    <td id="students_74"></td>
                                    <td id="students_69"></td>

                                    <td id="training_course_count"></td>


                                </tr>

                            </tbody>
                        </table>







                    </div>
                    @if (hasPermissionHelper('فلترة طلاب الدورات'))
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-control" onchange="getTeacherCourseBooks(this)" id="teachers_select"
                                    style="width: 88%;">
                                    <option value="0">اختر المعلم</option>
                                    @if (isset($moallems))
                                        @foreach ($moallems as $moallem)
                                            <option value="{{ $moallem->id }}">{{ $moallem->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" onchange="getBookCoursePlaces(this)" id="books_select">
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" onchange="updateDateTable(this)" id="places_select">
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="">
                        <table class="table table-centered table-nowrap mb-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th scope="col">اسم الطالب رباعياّ</th>
                                    <th scope="col">رقم الهوية</th>
                                    @if (hasPermissionHelper('الدورات المجاز فيها'))
                                        <th scope="col">الدورات المجاز فيها</th>
                                    @endif
                                    @if (hasPermissionHelper('الدورات الغير مجاز فيها'))
                                        <th scope="col">الدورات الغير المجاز فيها</th>
                                    @endif
                                    @if (hasPermissionHelper('جميع الدورات'))
                                        <th scope="col">جميع الدورات</th>
                                    @endif
                                    {{-- <th scope="col">أدوات</th> --}}
                                    {{-- <th scope="col"> --}}
                                    {{-- <div class="form-check mb-2"> --}}
                                    {{-- <input class="form-check-input check-all" type="checkbox"> --}}
                                    {{-- </div> --}}
                                    {{-- </th> --}}
                                </tr>
                            </thead>
                            <tbody>
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
        var table = '';
        $(document).ready(function() {
            table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,

                "drawCallback":function(){
                    $('#students_count').empty().html(table.data().context[0].json['students_count']);
                    $('#students_count_success').empty().html(table.data().context[0].json['students_count_success']);
                    $('#students_count_certificate').empty().html(table.data().context[0].json['students_count_certificate']);
                    $('#passed_students_count').empty().html(table.data().context[0].json['passed_students_count']);
                    $('#failed_students_count').empty().html(table.data().context[0].json['failed_students_count']);
                    $('#awaiting_students_count').empty().html(table.data().context[0].json['awaiting_students_count']);
                    $('#students_100').empty().html(table.data().context[0].json['students_100']);
                    $('#students_89').empty().html(table.data().context[0].json['students_89']);
                    $('#students_84').empty().html(table.data().context[0].json['students_84']);
                    $('#students_79').empty().html(table.data().context[0].json['students_79']);
                    $('#students_74').empty().html(table.data().context[0].json['students_74']);
                    $('#students_69').empty().html(table.data().context[0].json['students_69']);
                    $('#training_course_count').empty().html(table.data().context[0].json['training_course_count']);
                } ,

                "ajax": "{{ route('courseStudents.getData') }}",
                language: {
                    search: "بحث",
                    processing: "جاري معالجة البيانات",
                    lengthMenu: "عدد _MENU_ الصفوف",
                    info: "من _START_ الى _END_ من أصل _TOTAL_ صفحة",
                    infoEmpty: "لا يوجد بيانات",
                    loadingRecords: "يتم تحميل البيانات",
                    zeroRecords: "<p style='text-align: center'>لا يوجد بيانات</p>",
                    emptyTable: "<p style='text-align: center'>لا يوجد بيانات</p>",
                    paginate: {
                        first: "الأول",
                        previous: "السابق",
                        next: "التالي",
                        last: "الأخير"
                    },
                    aria: {
                        sortAscending: ": ترتيب تصاعدي",
                        sortDescending: ": ترتيب تنازلي"
                    }
                },
                "columnDefs": [
                    @if (hasPermissionHelper('الدورات المجاز فيها') || hasPermissionHelper('الدورات الغير مجاز فيها') || hasPermissionHelper('جميع الدورات'))
                        // { "sortable": false, "targets": [2,3,4] },
                    @endif
                ],
                "aoColumns": [{
                        "mData": "id"
                    },
                    {
                        "mData": "name"
                    },
                    {
                        "mData": "id_num"
                    },
                    @if (hasPermissionHelper('الدورات المجاز فيها'))
                        {
                            "mData": "passedCourses"
                        },
                    @endif
                    @if (hasPermissionHelper('الدورات الغير مجاز فيها'))
                        {
                            "mData": "failedCourses"
                        },
                    @endif
                    @if (hasPermissionHelper('جميع الدورات'))
                        {
                            "mData": "courses"
                        }
                    @endif
                    // { "mData": "select" }
                ]
            });
        });
        @if (hasPermissionHelper('فلترة طلاب الدورات'))
            function getTeacherCourseBooks(obj) {
                $.get('/getTeacherCourseBooks/' + obj.value, function(data) {
                    updateDateTable();
                    $('#places_select').empty();
                    $('#books_select').empty().html(data);
                });
            }

            function getBookCoursePlaces(obj) {
                $.get('/getBookCoursePlaces/' + obj.value + '/' + $('#teachers_select').val(), function(data) {
                    updateDateTable();
                    $('#places_select').empty().html(data);
                });
            }

            function updateDateTable() {
                table.ajax.url(
                    "/getCourseStudentsData?teacher_id=" + $('#teachers_select').val() + '&book_id=' + $(
                        '#books_select').val() + '&place_id=' + $('#places_select').val()
                ).load();
            }
        @endif
    </script>
@endsection
