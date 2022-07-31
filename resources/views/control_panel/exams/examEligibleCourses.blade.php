@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | حجز الإختبارات</title>
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">الجودة والاختبارات</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">حجز الإختبارات</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select id="eligible_exams_area_id" onchange="getSubAreas(this)" class="form-control">
                                <option value="0">الكل</option>
                                @foreach($areas as $key => $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="eligible_exams_sub_areas_select" onchange="changeExams()" class="form-control">
                                <option value="0">المحلية</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="moallem_id" onchange="changeExams()" class="form-control">
                                <option value="0">المعلم</option>
                                @foreach($moallems as $key => $moallem)
                                    <option value="{{ $moallem->id }}">{{ $moallem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="book_id" onchange="changeExams()" class="form-control">
                                <option value="0">الكتاب</option>
                                @foreach($books as $key => $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <table class="table table-responsive">
                            <thead>
                                <th>الدورات</th>
                                <th>الطلاب</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $courses->count() }}</td>
                                    <td>{{ $courses->sum('students_count') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable1" style="width: 100%;">
                        <thead>
                            <th>#</th>
                            <th>اسم الكتاب</th>
                            <th>اسم المعلم</th>
                            <th>المنطقة الكبرى</th>
                            <th>المنطقة المحلية</th>
                            <th>المكان</th>
                            <th>عدد الطلاب</th>
                            <th>أدوات</th>
                        </thead>
                        <tbody>

                        </tbody>
                        {{--<tbody id="pendingExams">--}}
                        {{--@php $i=1; @endphp--}}
                        {{--@foreach($courses as $key => $course)--}}
                            {{--<tr>--}}
                                {{--<td>{{ $i }}</td>--}}
                                {{--<td>{{ $course->book_name }}</td>--}}
                                {{--<td>{{ $course->name }}</td>--}}
                                {{--<td>{{ $course->area_father_name }}</td>--}}
                                {{--<td>{{ $course->area_name }}</td>--}}
                                {{--<td>{{ $course->place_name }}</td>--}}
                                {{--<td>{{ $course->students->count() }}</td>--}}
                                {{--<td style="padding: 3px;"><button class="btn btn-success" onclick="examAppointment({{ $course->id }})" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i class="mdi mdi-table"></i></button></td>--}}
                            {{--</tr>--}}
                        {{--@php $i++; @endphp--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    </table>
                </div>
            </div>
        </div>
    </div>

{{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
{{--</div>--}}
@endsection
@section('script')

<script>
    var table = '';
    $(document).ready(function(){
        table = $('#dataTable1').removeAttr('width').DataTable( {
            "processing": true,
            "serverSide": true,
            // "scrollX":true,
            "ajax": "{{ route('exams.getExamEligibleCoursesData') }}",
            language: {
                search: "بحث",
                processing:     "<span style='background-color: #0a9e87;color: #fff;padding: 25px;'>انتظر من فضلك ، جار جلب البيانات ...</span>" ,
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
                {"sortable": false, "targets": [7]}
            ],
        "aoColumns": [
                { "mData": "id" },
                { "mData": "book_name"},
                { "mData": "name" },
                { "mData": "area_father_name" },
                { "mData": "area_name" },
                { "mData": "place_name" },
                { "mData": "students_count" },
                { "mData": "tools" }
            ]
        } );
    });
    function examAppointment(course_id){
        // $('.bs-example-modal-xl').modal('toggle');
        // $('.user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        $.get('/getCourseExamAppointment/'+course_id,function (data) {
            $('#modal_content').empty().html(data);
        });
    }

    function getSubAreas(obj) {
        if(obj.value != 0) {
            $.get('/getSubAreas/'+obj.value, function (data) {
                $('#eligible_exams_sub_areas_select').empty().html(data);
            });
            changeExams();
        }else{
            $('#eligible_exams_sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
            changeExams();
        }
    }
    function changeExams() {
        var filters = '/?area_id='+$('#eligible_exams_area_id').val()+'&sub_area_id='+$('#eligible_exams_sub_areas_select').val()+'&moallem_id='+$('#moallem_id').val()+'&book_id='+$('#book_id').val();//+'&start_date='+$('#start_date').val()+'&end_date='+$('#end_date').val();
        {{--console.log( "{{route('exams.getExamEligibleCoursesData')}}"+filters);--}}
        table.ajax.url(
            "/getExamEligibleCoursesData"+filters
        ).load();
        // $.get('/getFilteredExamEligibleCourses?'+filters,function (data) {
        //     $('#pendingExams').empty().html(data);
        // });
    }
</script>
@endsection
