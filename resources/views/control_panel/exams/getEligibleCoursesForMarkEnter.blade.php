@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .swal2-popup{
            width: 1150px;
        }
    </style>
@endsection
@section('title')
    <title>برنامج السنة | ادخال الدرجات </title>
@endsection
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">ادخال الدرجات</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">ادخال الدرجات</li>
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
                    @if(hasPermissionHelper('فلترة ادخال الدرجات'))
                        <div class="row">
                            <div class="col-md-3">
                                <select id="area_id" onchange="getSubAreas(this);" class="form-control">
                                    <option value="0">الكل</option>
                                    @foreach ($areas as $key => $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-3">
                                <select id="pending_exams_sub_areas_select" onchange="getSubareaTeacherPlace(this);"
                                    class="form-control">
                                    <option value="0">المحلية</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="moallem_id" class="form-control select2">
                                    <option value="0">المعلم</option>
                                    @foreach ($moallems as $key => $moallem)
                                        <option value="{{ $moallem->id }}">{{ $moallem->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control select2" id="place_area">
                                    <option value="0">اختر مكان الدورة</option>
                                </select>
                            </div>


                        </div>

                        <div class=" row" style="margin-top: 15px; ">

                            <div class="col-md-3">
                                <select id="book_id" class="form-control ">
                                    <option value="0">الكتاب</option>
                                    @foreach ($books as $key => $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group" id="datepicker2">
                                    <input autocomplete="off" type="text" class="form-control" placeholder="من تاريخ"
                                        name="start_date" value="" id="start_date" data-date-format="yyyy-mm-dd"
                                        data-date-container='#datepicker2' data-provide="datepicker"
                                        data-date-autoclose="true">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group" id="datepicker3">
                                    <input autocomplete="off" type="text" class="form-control" placeholder="الى تاريخ"
                                        name="end_date" value="" id="end_date" data-date-format="yyyy-mm-dd"
                                        data-date-container='#datepicker3' data-provide="datepicker"
                                        data-date-autoclose="true">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <select id="exam_type" class="form-control ">
                                    <option value="0">نوع الإختبار</option>
                                    <option value="App\Models\Course">دورات علمية</option>
                                    <option value="App\Models\AsaneedCourse">مجالس اسانيد</option>
                                </select>
                            </div>



                        </div>
                        <div class=" row" style="margin-top: 15px; ">

                            <div class="col-md-3 offset-md-9" >
                                <button type="button" style="width:100%" onclick="changeExams()"
                                    class="btn btn-primary btn-block">
                                    <i class="mdi mdi-magnify" aria-hidden="true"></i>
                                    ابحث
                                </button>
                            </div>

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="row mb-3 ">
                        @if(hasPermissionHelper('فلترة ادخال الدرجات'))
                            <div class="col-md-3">
                                <select id="marks_area_id" onchange="changeCourses()" class="form-control">
                                    <option value="0">كل المناطق</option>
                                    @foreach($areas as $key => $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="marks_book_id" onchange="changeCourses()" class="form-control">
                                    <option value="0">كل الكتب</option>
                                    @foreach($books as $key => $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div> --}}
                    <div class="">
                        <table class="table table-centered table-nowrap mb-0" id="dataTable1">
                            <thead>
                            <th>#</th>

                            <th>عنوان الدورة\المجلس</th>
                            <th>نوع الإختبار</th>

                            {{-- <th>الكتاب</th> --}}
                            {{-- <th>مدرس الدورة</th> --}}

                            <th>المعلم\الشيخ</th>

                            <th>المنطقة الكبرى</th>
                            <th>المنطقة المحلية</th>
                            <th>مكان الدورة</th>
                            <th>عدد الطلاب</th>
                            <th>تفاصيل الاختبار</th>
                            <th></th>
                            </thead>
                            <tbody></tbody>
                            {{--<tbody id="eligibleCourses">--}}
                            {{--@php $i=1; @endphp--}}
                            {{--@foreach($exams as $key => $exam)--}}
                            {{--<tr>--}}
                            {{--<td>{{ $i }}</td>--}}
                            {{--{!! $exam->eligible_courses_for_mark_enter !!}--}}
                            {{--@php $i++; @endphp--}}
                            {{--</tr>--}}
                            {{--@endforeach--}}
                            {{--</tbody>--}}
                        </table>
                        {{--{{ $exams->links() }}--}}

                        <input type="file" id="excelStudentsImport" style="display: none;">
                        <input type="file" id="excelNewStudentsImport" style="display: none;">
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

$('.select2').select2({
            dir: "rtl",
            dropdownAutoWidth: true,
        });


    var table = '';
    $(document).ready(function(){
        table = $('#dataTable1').removeAttr('width').DataTable( {
            "processing": true,
            "serverSide": true,
            // "scrollX":true,
            "ajax": "{{ route('exams.getEligibleCoursesForMarkEnterData') }}",
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
                {"sortable": false, "targets": [1,2,3,4,5,6,7,8]}
            ],
            "aoColumns": [
                { "mData": "id" },
                { "mData": "course_book_name"},
                {"mData": "exam_type"},
                
                { "mData": "course_name" },
                { "mData": "course_area_father_name" },
                { "mData": "course_area_name" },
                { "mData": "course_place_name" },
                { "mData": "students_count" },
                { "mData": "course_start_date" },
                { "mData": "tools" }
            ]
        } );
    });
    @if(hasPermissionHelper('انهاء الدورة و ادخال الدرجات'))
        var exam__id = 0;
        function enterExamMarks(exam_id){
        exam__id = exam_id;
        Swal.fire(
            {
                title: "يرجى اختيار آلية ادخال الدرجات",
                html:
                "<div style='padding: 5px;'>" +
                "   <button class='btn btn-success' onclick='Swal.clickConfirm();' style='margin-left: 3px;'>عن طريق ملف اكسل</button>&nbsp;" +
                "   <button class='btn btn-danger' onclick='Swal.clickDeny()' data-bs-toggle='modal' data-bs-target='.bs-example-modal-xl'>الادخال اليدوي</button>&nbsp;" +
                "   <button class='btn btn-warning' onclick='clickUploadExcelButton()'>اضافة طلاب جدد مع درجاتهم</button><br>" +
                "   <button class='btn btn-info' onclick='Swal.clickCancel()' style='margin-top: 5px;'>استخراج كشف اسماء الطلاب لمعاودة ادخال الدرجات من خلال الاكسل</button>" +
                "</div>",
                icon: "info",
                showCancelButton: 0,
                showConfirmButton: 0,
            })
            .then(
                function (t) {
                    if (t.isConfirmed) {
                        $('#excelStudentsImport').click();
                    } else if (t.isDenied) {
                        enterExamMarks1(exam_id);
                    } else if (t.dismiss == 'cancel') {
                        exportCourseExamStudentsListAsExcelFile(exam_id);
                    }
                }
            );
        }
        function clickUploadExcelButton() {
            $('#excelNewStudentsImport').click();
        }
        $('#excelStudentsImport').change(function (){
            var fd = new FormData();
            var files = $(this)[0].files;
            fd.append('file',files[0]);
            fd.append('_token','{{ csrf_token() }}');
            $(this).val('');
            $.ajax({
                url: '/importCourseStudentsMarkExcel/'+exam__id,
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.bs-example-modal-xl').modal('show');
                    enterExamMarks1(exam__id);
                },
                error:function (errors) {
                    const entries = Object.entries(errors.responseJSON.errors);
                    var errors_message = document.createElement('div');
                    Swal.fire(entries[0][1][0]);
                    $('div[class="student_excel_import_loading"]').css('display','none');
                }
            });
        });
        $('#excelNewStudentsImport').change(function (){
            Swal.close();
            var fd = new FormData();
            var files = $(this)[0].files;
            fd.append('file',files[0]);
            fd.append('_token','{{ csrf_token() }}');
            $(this).val('');
            $.ajax({
                url: '/importCourseNewStudentsMarkExcel/'+exam__id,
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.bs-example-modal-xl').modal('show');
                    enterExamMarks1(exam__id);
                },
                error:function (errors) {
                    const entries = Object.entries(errors.responseJSON.errors);
                    var errors_message = document.createElement('div');
                    Swal.fire(entries[0][1][0]);
                    $('div[class="student_excel_import_loading"]').css('display','none');
                }
            });
        });
        function exportCourseExamStudentsListAsExcelFile(exam_id){
            $.get('exportCourseExamStudentsListAsExcelFile/'+exam_id,function(response){
                if(response.file_link) {
                    window.open(
                        response.file_link, "_blank");
                }
            });
        }
        function enterExamMarks1(exam_id){
            $('#user_modal_content')
                .html(
                    '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                    '   <span class="sr-only">يرجى الانتظار ...</span>' +
                    '</div>'
                );
            $.get('/getEnterExamMarksForm/'+exam_id,function (data) {
                $('#user_modal_content').empty().html(data);
            });
        }
    @endif
    @if(hasPermissionHelper('فلترة ادخال الدرجات'))


        function changeExams() {

            var filters = '?area_id=' + $('#area_id').val() + '&sub_area_id=' + $('#pending_exams_sub_areas_select')
                .val() +
                '&moallem_id=' + $('#moallem_id').val() + '&book_id=' + $('#book_id').val() + '&start_date=' + $(
                    '#start_date').val() + '&end_date=' + $('#end_date').val() + '&place_area=' + $('#place_area')
                .val()+ '&exam_type=' + $('#exam_type').val();



            table.ajax.url(
                "/getEligibleCoursesForMarkEnterData" + filters
            ).load();
            }

    @endif
</script>

@endsection
