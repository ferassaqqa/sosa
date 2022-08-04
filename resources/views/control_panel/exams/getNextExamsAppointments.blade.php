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
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">مواعيد الاختبارات</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">مواعيد الاختبارات</li>
                </ol>
            </div>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-centered table_bordered">
                    <thead>
                        <tr>
                            <th>عدد الدورات</th>
                            <th>عدد الطلاب</th>
                            <th>عدد الطلاب الناجحين</th>
                            <th>عدد الطلاب الراسبين</th>
                            <th>لم يتم ادخال درجاتهم</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr id="main_statistics">    
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">


                    @include('control_panel.exams.examFilters')


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
                "drawCallback": function() {
                        $('#main_statistics').empty().html(table.data().context[0].json['statistics']);
                    },
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


    </script>
@endsection
