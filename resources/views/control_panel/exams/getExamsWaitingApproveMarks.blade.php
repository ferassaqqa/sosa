@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | مواعيد الاختبارات</title>
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @if(hasPermissionHelper('فلترة اعتماد الاختبارات'))
                            <div class="col-md-3">
                                <select id="area_id" onchange="changeExams(this)" class="form-control">
                                    <option value="0">الكل</option>
                                    @foreach($areas as $key => $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
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
                        <th>الكتاب:</th>
                        <th>مدرس الدورة:</th>
                        <th>المنطقة الكبرى:</th>
                        <th>المنطقة المحلية:</th>
                        <th>مكان الدورة:</th>
                        <th>عدد الطلاب:</th>
                        <th>تاريخ البدء:</th>
                        <th></th>
                        </thead>
                        <tbody id="pendingExams">
                        {{--@php $i=1; @endphp--}}
                        {{--@foreach($exams as $key => $exam)--}}
                        {{--<tr>--}}
                        {{--<td>{{ $i }}</td>--}}
                        {{--{!! $exam->eligible_courses_for_marks_approvement !!}--}}
                        {{--</tr>--}}
                        {{--@php $i++; @endphp--}}
                        {{--@endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--<div class="modal-footer">--}}
{{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
{{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
{{--</div>--}}
@section('script')

    <script>

        var table = '';
        $(document).ready(function(){
            table = $('#dataTable1').removeAttr('width').DataTable( {
                "processing": true,
                "serverSide": true,
                // "scrollX":true,
                "ajax": "{{ route('exams.getExamsWaitingApproveMarksData') }}",
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
        @if(hasPermissionHelper('اعتماد نتائج الاختبارات'))
        function approveEnteredExamMarks(exam_id){
            $('.bs-example-modal-xl').modal('show');
            $('#user_modal_content')
                .html(
                    '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                    '   <span class="sr-only">يرجى الانتظار ...</span>' +
                    '</div>'
                );
            $.get('/approveEnteredExamMarks/'+exam_id,function (data) {
                $('#user_modal_content').empty().html(data);
            });
        }
        @endif
        @if(hasPermissionHelper('فلترة اعتماد الاختبارات'))
        function changeExams(obj) {
            var filters = '?area_id='+$('#area_id').val();
            table.ajax.url(
                "/getExamsWaitingApproveMarksData"+filters
            ).load();
            // $.get('/getFilteredAreaExamsWaitingApproveMarks/'+obj.value,function (data) {
            //     $('#pendingExams').empty().html(data);
            // });
        }
        @endif
    </script>
@endsection
