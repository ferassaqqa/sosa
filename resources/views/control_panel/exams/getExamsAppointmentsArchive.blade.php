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
                    <div class="row mb-3">
                        @if(hasPermissionHelper('فلترة ارشيف مواعيد الاختبارات'))
                            <div class="col-md-3">
                                <select id="area_id" onchange="getSubAreas(this)" class="form-control">
                                    <option value="0">الكل</option>
                                    @foreach($areas as $key => $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="sub_areas_select" onchange="getMoallemsList(this)" class="form-control">
                                    <option value="0">المحلية</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="moallem_id" onclick="changeExams()" class="form-control">
                                    <option value="0">اختر المعلم</option>
                                </select>
                            </div>
                            <div class="col-md-3">
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
                            <th>نوع الدورة</th>
                            <th>عدد الطلاب</th>
                            <th>عدد الطلاب المجازين</th>
                            <th>المعلم\المحفظ</th>
                            <th>رقم المعلم</th>
                            <th>المنطقة الكبرى</th>
                            <th>المنطقة المحلية</th>
                            <th>مكان الاختبار</th>
                            <th>مشرف الجودة</th>
                            <th>الموعد</th>
                            <th></th>
                        </thead>
                        <tbody id="pendingExams">
                        {{--@php $i=1; @endphp--}}
                        {{--@foreach($exams as $key => $exam)--}}
                            {{--<tr>--}}
                                {{--<td>{{ $i }}</td>--}}
                                {{--{!! $exam->exam_archive_row !!}--}}
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
            "ajax": "{{ route('exams.getExamsAppointmentsArchiveData') }}",
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
                {"sortable": false, "targets": [1,2,3,4,5,6,7,8,9,10,11]}
            ],
            "aoColumns": [
                { "mData": "id" },
                { "mData": "course_book_name"},
                { "mData": "students_count"},
                { "mData": "passed_students_count"},
                { "mData": "course_name" },
                { "mData": "teacher_mobile" },
                { "mData": "course_area_father_name" },
                { "mData": "course_area_name" },
                { "mData": "place_name" },
                { "mData": "quality_supervisors_string" },
                { "mData": "date" },
                { "mData": "tools" }
            ]
        } );
    });

    function getSubAreas(obj) {
        if(obj.value != 0) {
            $.get('/getSubAreas/'+obj.value, function (data) {
                $('#sub_areas_select').empty().html(data);
            });
            changeExams();
        }else{
            $('#sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
            $('#moallem_id').empty().html('<option value="0">اختر المعلم</option>');
            changeExams();
        }
    }
    function getMoallemsList(obj) {
        if(obj.value != 0) {
            $.get('/getMoallemsList/'+obj.value, function (data) {
                $('#moallem_id').empty().html(data);
            });
            changeExams();
        }else{
            $('#moallem_id').empty().html('<option value="0">اختر المنطقة المحلية</option>');
            changeExams();
        }
    }
    @if(hasPermissionHelper('فلترة ارشيف مواعيد الاختبارات'))
        function changeExams() {
            var filters = '/?area_id='+$('#area_id').val()+'&sub_area_id='+$('#sub_areas_select').val()+'&moallem_id='+$('#moallem_id').val()+'&book_id='+$('#book_id').val();//+'&start_date='+$('#start_date').val()+'&end_date='+$('#end_date').val();
            table.ajax.url(
                "/getExamsAppointmentsArchiveData"+filters
            ).load();
        }
    @endif
</script>
@endsection
