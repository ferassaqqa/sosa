@extends('control_panel.master')

@section('style')
    <link href="{{ asset('control_panel/assets/css/datatable.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .dataTables_filter {
            float: left !important;
            margin-left: 45px;
        }
    </style>
@endsection
@section('title')
    <title>برنامج السنة | مواعيد الاختبارات</title>
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if (hasPermissionHelper('فلترة مواعيد الاختبارات'))
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
                    <table class="table table-responsive" id="dataTable1">
                        <thead>
                            <th>#</th>
                            <th>عنوان الدورة\المجلس</th>
                            <th>نوع الإختبار</th>
                            <th>عدد الطلاب</th>
                            <th>المعلم\الشيخ</th>
                            <th>رقم الهاتف</th>
                            <th>العنوان</th>
                            {{-- <th>المنطقة الكبرى</th>
                            <th>المنطقة المحلية</th> --}}
                            <th>مشرف الجودة</th>
                            <th>مكان الاختبار</th>

                            <th>تفاصيل الاختبار</th>
                            <th>أدوات</th>
                        </thead>
                        <tbody id="pendingExams">
                            {{-- @php $i=1; @endphp --}}
                            {{-- @foreach ($exams as $key => $exam) --}}
                            {{-- <tr> --}}
                            {{-- <td>{{ $i }}</td> --}}
                            {{-- {!! $exam->next_exam_row !!} --}}
                            {{-- </tr> --}}
                            {{-- @php $i++; @endphp --}}
                            {{-- @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection



{{-- <div class="modal-footer"> --}}
{{-- <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button> --}}
{{-- <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button> --}}
{{-- </div> --}}
@section('script')
    <script>
        $('.select2').select2({
            dir: "rtl",
            dropdownAutoWidth: true,
        });


        var table = '';
        $(document).ready(function() {
            table = $('#dataTable1').removeAttr('width').DataTable({
                "processing": true,
                "serverSide": true,
                // "scrollX":true,
                "ajax": "{{ route('exams.getNextExamsAppointmentsData') }}",
                language: {
                    search: "",
                    searchPlaceholder: 'بحث سريع',
                    processing: "جاري معالجة البيانات",
                    lengthMenu: " _MENU_ ",
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

                "columnDefs": [{
                    "sortable": false,
                    "targets": [1, 2, 3, 4, 5, 6, 7, 8]
                }],

                "aoColumns": [{
                        "mData": "id"
                    },
                    {
                        "mData": "course_book_name"
                    },
                    {
                        "mData": "exam_type"
                    },
                    {
                        "mData": "students_count"
                    },
                    {
                        "mData": "course_name"
                    },
                    {
                        "mData": "teacher_mobile"
                    },

                    {
                        "mData": "area"
                    },
                    {
                        "mData": "quality_supervisors_string"
                    },
                    {
                        "mData": "place_name"
                    },

                    {
                        "mData": "date"
                    },
                    {
                        "mData": "tools"
                    }
                ]
            });
        });

        @if (hasPermissionHelper('تعديل طلبات الحجز'))
            function approveExamAppointment(obj, exam_id) {
                $('.bs-example-modal-x2').modal('show');
                $('#user_modal_content_new')
                    .html(
                        '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                        '   <span class="sr-only">يرجى الانتظار ...</span>' +
                        '</div>'
                    );
                $.get('/approveExamAppointment/' + exam_id, function(data) {
                    $('#user_modal_content_new').empty().html(data);
                });
            }
        @endif

        @if (hasPermissionHelper('حذف طلبات مواعيد الاختبارات'))
            function deleteExamAppointment(obj, exam_id) {
                var button = $(obj);
                // console.log(button.closest('tr'));
                // $('#user_modal_content')
                //     .html(
                //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
                //         '</div>'
                //     );
                Swal.fire({
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
                        function(t) {
                            if (t.value) {

                                $.get('/deleteExamAppointment/' + exam_id, function(data) {
                                    button.closest('tr').fadeOut(1000);
                                    Swal.fire({
                                        title: "تم الحذف!",
                                        text: "تم حذف البيانات بنجاح.",
                                        icon: "error"
                                    });
                                });
                            } else {
                                Swal.fire({
                                    title: "لم يتم الحذف!",
                                    text: "البيانات لم تحذف.",
                                    icon: "error"
                                });
                            }
                        }
                    );
            }
        @endif

        function examAppointment(course_id) {
            $.get('/getCourseExamAppointment/' + course_id, function(data) {
                $('#modal_content').empty().html(data);
            });
        }

        @if (hasPermissionHelper('فلترة طلبات حجز مواعيد الاختبارات'))

            // function getSubAreas(obj) {
            //     if (obj.value != 0) {
            //         $.get('/getSubAreas/' + obj.value, function(data) {
            //             $('#pending_exams_sub_areas_select').empty().html(data);
            //         });
            //         changeExams();
            //     } else {
            //         $('#pending_exams_sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
            //         changeExams();
            //     }
            // }

            function getSubAreas(obj) {
                if (obj.value != 0) {
                    $.get('/getSubAreas/' + obj.value, function(data) {
                        $('#pending_exams_sub_areas_select').empty().html(data);
                    });
                    // changeExams();
                } else {
                    $('#pending_exams_sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                    // changeExams();
                }
            }


            function getSubareaTeacherPlace(obj) {
                if (obj.value != 0) {
                    $.get('/getSubAreaTeachers/' + obj.value, function(data) {
                        $('#moallem_id').empty().html(data[0]);
                        $('#place_area').empty().html(data[1]);
                    });
                    // changeExams();
                } else {
                    $('#moallem_id').empty().html('<option value="0">اختر المعلم</option>');
                    $('#place_area').empty().html('<option value="0">اختر مكان الدورة</option>');
                    // changeExams();
                }
            }

            function changeExams() {

                var filters = '?area_id=' + $('#area_id').val() + '&sub_area_id=' + $('#pending_exams_sub_areas_select')
                    .val() +
                    '&moallem_id=' + $('#moallem_id').val() + '&book_id=' + $('#book_id').val() + '&start_date=' + $(
                        '#start_date').val() + '&end_date=' + $('#end_date').val() + '&place_area=' + $('#place_area')
                    .val()+ '&exam_type=' + $('#exam_type').val();



                table.ajax.url(
                    "/getNextExamsAppointmentsData" + filters
                ).load();
            }

            // function changeExams() {
            //     var filters = '?area_id=' + $('#area_id').val() + '&sub_area_id=' + $('#pending_exams_sub_areas_select')
            //         .val() + '&moallem_id=' + $('#moallem_id').val() + '&book_id=' + $('#book_id')
            //         .val(); //+'&start_date='+$('#start_date').val()+'&end_date='+$('#end_date').val();
            //     table.ajax.url(
            //         "/getPendingExamRequestsData" + filters
            //     ).load();
            // }
        @endif
    </script>
@endsection
