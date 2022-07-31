@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | حجز الإختبارات</title>
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        @if(hasPermissionHelper('فلترة طلبات حجز مواعيد الاختبارات'))
                            <div class="col-md-3">
                                <select id="area_id" onchange="getSubAreas(this)" class="form-control">
                                    <option value="0">الكل</option>
                                    @foreach($areas as $key => $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="pending_exams_sub_areas_select" onchange="changeExams()" class="form-control">
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
                            {{--<div class="col-md-3 mt-3 mb-3">--}}
                                {{--<div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>--}}
                                    {{--<input type="text" class="form-control" name="start_date" id="start_date" onchange="changeExams()" placeholder="تاريخ البداية" />--}}
                                    {{--<input type="text" class="form-control" name="end_date" id="end_date" onchange="changeExams()" placeholder="تاريخ النهاية" />--}}
                                {{--</div>--}}
                            {{--</div>--}}
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
                    <div class="row mb-3">
                        <table>
                            <tr style="border: 1px solid #DDD;font-size: x-large;">
                                <td class="text-center-align" style="background-color: #DDD;width: 25%;padding: 25px;">اجمالي عدد الطلاب</td>
                                <td class="text-center-align" id="students_count" style="background-color: #DDD;padding: 25px;">{{ $students }}</td>
                                <td class="text-center-align" style="background-color: #DDD;width: 25%;padding: 25px;">اجمالي عدد الدورات</td>
                                <td class="text-center-align" id="students_count" style="background-color: #DDD;padding: 25px;">{{ $courses }}</td>
                            </tr>
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
                    <table class="table table-responsive table-bordered" id="dataTable1">
                        <thead >
                            <th>#</th>
                            <th>اسم الكتاب</th>
                            <th>اسم المعلم</th>
                            <th>عدد الطلاب</th>
                            <th>مكان الدورة</th>
                            <th>مكان الاختبار</th>
                            <th>المنطقة المحلية</th>
                            <th>ملاحظات</th>
                            <th>أدوات</th>
                        </thead>
                        {{--<tbody id="pendingExams">--}}
                        {{--@php $i=1; @endphp--}}
                        {{--@foreach($exams as $key => $exam)--}}
                            {{--<tr>--}}
                                {{--<td>{{ $i }}</td>--}}
                                {{--{!! $exam->row !!}--}}
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
            "ajax": "{{ route('exams.getPendingExamRequestsData') }}",
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
                { "mData": "students_count" },
                { "mData": "course_place_name" },
                { "mData": "place_name" },
                { "mData": "course_area_name" },
                { "mData": "notes" },
                { "mData": "tools" }
            ]
        } );
    });
    @if(hasPermissionHelper('تأكيد طلبات الحجز'))
    function approveExamAppointment(obj,exam_id){
        $('.bs-example-modal-xl').modal('show');
        $('#user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get('/approveExamAppointment/'+exam_id,function (data) {
            $('#user_modal_content').empty().html(data);
        });
    }
    @endif
    @if(hasPermissionHelper('حذف طلبات مواعيد الاختبارات'))
    function deleteExamAppointment(obj,exam_id){
        var button = $(obj);
        // console.log(button.closest('tr'));
        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        Swal.fire({
            title:"هل انت متأكد",
            text:"لن تتمكن من استرجاع البيانات لاحقاً",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonText:"نعم إحذف البيانات",
            cancelButtonText:"إلغاء",
            confirmButtonClass:"btn btn-success mt-2",
            cancelButtonClass:"btn btn-danger ms-2 mt-2",
            buttonsStyling:!1
        })
            .then(
                function(t){
                    if(t.value) {

                        $.get('/deleteExamAppointment/'+exam_id,function (data) {
                            button.closest('tr').fadeOut(1000);
                            Swal.fire({title: "تم الحذف!", text: "تم حذف البيانات بنجاح.", icon: "error"});
                        });
                    }else{
                        Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                    }
                }
            );
    }
    @endif
    @if(hasPermissionHelper('فلترة طلبات حجز مواعيد الاختبارات'))

        function getSubAreas(obj) {
            if(obj.value != 0) {
                $.get('/getSubAreas/'+obj.value, function (data) {
                    $('#pending_exams_sub_areas_select').empty().html(data);
                });
                changeExams();
            }else{
                $('#pending_exams_sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                changeExams();
            }
        }
        function changeExams() {
            var filters = '?area_id='+$('#area_id').val()+'&sub_area_id='+$('#pending_exams_sub_areas_select').val()+'&moallem_id='+$('#moallem_id').val()+'&book_id='+$('#book_id').val();//+'&start_date='+$('#start_date').val()+'&end_date='+$('#end_date').val();
            table.ajax.url(
                "/getPendingExamRequestsData"+filters
            ).load();
        }
    @endif
</script>
@endsection
