@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | ارشيف الدورات المنتهية والدرجات</title>
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ارشيف الدورات المنتهية والدرجات</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">ارشيف الدورات المنتهية والدرجات</li>
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
                        @if(hasPermissionHelper('فلترة ارشيف مواعيد الاختبارات'))
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
                            <th>عنوان الدورة\المجلس</th>   
                            <th>نوع الإختبار</th>
                            <th>عدد الطلاب</th>
                            <th>عدد الطلاب المجازين</th>
                            <th>المعلم\الشيخ</th>                            
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
                {
                        "mData": "exam_type"
                    },
                { "mData": "students_count"},
                { "mData": "passed_students_count"},
                { "mData": "course_name" },
                { "mData": "teacher_mobile" },
                { "mData": "course_area_father_name" },
                { "mData": "course_area_name" },
                { "mData": "place_name" },
                { "mData": "quality_supervisors_string" },
                { "mData": "course_start_date" },
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
                    "/getExamsAppointmentsArchiveData" + filters
                ).load();
            }


    @endif
</script>
@endsection
