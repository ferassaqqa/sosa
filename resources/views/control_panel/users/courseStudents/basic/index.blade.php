@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | طلاب الدورات العلمية </title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">طلاب الدورات العلمية</h4>

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
                    <table class="table table-centered table_bordered">
                        <thead>
                        <tr>
                            <th>عدد الطلاب الكلي</th>
                            {{--<th>عدد الطلاب المجازين</th>--}}
                            {{--<th>عدد الطلاب الغير مجازين</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $students_count }}</td>
{{--                            <td>{{ $passed_students_count }}</td>--}}
{{--                            <td>{{ $failed_students_count }}</td>--}}
                        </tr>
                        </tbody>
                    </table>
                </div>
                @if(hasPermissionHelper('فلترة طلاب الدورات'))
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" onchange="getTeacherCourseBooks(this)" id="teachers_select" style="width: 88%;">
                                <option value="0">اختر المعلم</option>
                                @if(isset($moallems))
                                    @foreach($moallems as $moallem)
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
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">اسم الطالب رباعياّ</th>
                                <th scope="col">رقم الهوية</th>
                                @if(hasPermissionHelper('الدورات المجاز فيها'))
                                    <th scope="col">الدورات المجاز فيها</th>
                                @endif
                                @if(hasPermissionHelper('الدورات الغير مجاز فيها'))
                                    <th scope="col">الدورات الغير المجاز فيها</th>
                                @endif
                                @if(hasPermissionHelper('جميع الدورات'))
                                    <th scope="col">جميع الدورات</th>
                                @endif
                                {{--<th scope="col">أدوات</th>--}}
                                {{--<th scope="col">--}}
                                    {{--<div class="form-check mb-2">--}}
                                        {{--<input class="form-check-input check-all" type="checkbox">--}}
                                    {{--</div>--}}
                                {{--</th>--}}
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
            table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('courseStudents.getData') }}",
                language: {
                    search: "بحث",
                    processing:     "جاري معالجة البيانات" ,
                    lengthMenu:    "عدد _MENU_ الصفوف",
                    info:           "من _START_ الى _END_ من أصل _TOTAL_ صفحة",
                    infoEmpty: "لا يوجد بيانات",
                    loadingRecords: "يتم تحميل البيانات",
                    zeroRecords:    "<p style='text-align: center'>لا يوجد بيانات</p>",
                    emptyTable:     "<p style='text-align: center'>لا يوجد بيانات</p>",
                    paginate: {
                        first:      "الأول",
                        previous:   "السابق",
                        next:       "التالي",
                        last:       "الأخير"
                    },
                    aria: {
                        sortAscending:  ": ترتيب تصاعدي",
                        sortDescending: ": ترتيب تنازلي"
                    }
                },
                "columnDefs": [
                        @if(hasPermissionHelper('الدورات المجاز فيها')||hasPermissionHelper('الدورات الغير مجاز فيها')||hasPermissionHelper('جميع الدورات'))
                            // { "sortable": false, "targets": [2,3,4] },
                        @endif
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "id_num" },
                        @if(hasPermissionHelper('الدورات المجاز فيها'))
                            { "mData": "passedCourses" },
                        @endif
                        @if(hasPermissionHelper('الدورات الغير مجاز فيها'))
                            { "mData": "failedCourses" },
                        @endif
                        @if(hasPermissionHelper('جميع الدورات'))
                            { "mData": "courses" }
                        @endif
                    // { "mData": "select" }
                ]
            } );
        });
        @if(hasPermissionHelper('فلترة طلاب الدورات'))
            function getTeacherCourseBooks(obj){
                $.get('/getTeacherCourseBooks/'+obj.value,function(data){
                    updateDateTable();
                    $('#places_select').empty();
                    $('#books_select').empty().html(data);
                });
            }
            function getBookCoursePlaces(obj){
                $.get('/getBookCoursePlaces/'+obj.value+'/'+$('#teachers_select').val(),function(data){
                    updateDateTable();
                    $('#places_select').empty().html(data);
                });
            }
            function updateDateTable(){
            table.ajax.url(
                "/getCourseStudentsData?teacher_id="+$('#teachers_select').val()+'&book_id='+$('#books_select').val()+'&place_id='+$('#places_select').val()
            ).load();
        }
        @endif
    </script>

@endsection