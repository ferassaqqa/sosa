<div class="row">
    <div class="col-md-4">
        <select class="form-control" onchange="selectDepartment(this)" id="reports_department_id">
            <option value="0">اختر القسم</option>
            <option value="قسم الدورات العلمية">قسم الدورات العلمية</option>
            {{-- <option value="قسم تحفيظ السنة النبوية">قسم تحفيظ السنة النبوية</option> --}}
            <option value="قسم أسانيد السنة النبوية">قسم أسانيد السنة النبوية</option>
            {{-- <option value="الأنشطة الادارية">الأنشطة الادارية</option> --}}
            {{-- <option value="جميع الأقسام">جميع الأقسام</option> --}}
        </select>

    </div>

    <div class="col-md-4">
        <select class="form-control" id="analysis_type">
            <option value="0">اختر التحليل المناسب</option>
        </select>
    </div>

    <div class="col-md-4" style="display: none">
        <select class="form-control" id="analysis_sub_type">
            <option value="">اختر نوع التحليل </option>
            <option value="teachers">المعلمون</option>
            <option value="mosques">المساجد</option>
            <option value="local_areas">المناطق المحلية</option>

        </select>
    </div>
</div>

<hr>


<div class=" row" style="margin-top: 15px; ">


    <div class="col-md-2">
        <select class="form-control" onchange="getSubArea(this)" id="report_area_select">
            <option value="0">اختر المنطقة</option>
            @foreach ($areas as $key => $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control" id="report_sub_area_select" onchange="getSubAreaTeachers(this)">
            <option value="0">اختر المنطقة المحلية</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control select2" id="teachers_select">
            <option value="0">اختر المعلم</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control select2" id="place_area">
            <option value="0">اختر مكان</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control select2" id="books_select">
            <option value="0">اختر الكتاب</option>
            @foreach ($books as $book)
                <option value="{{ $book->id }}">{{ $book->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <button type="button" style="width:100%" onclick="updateDateTable()" class="btn btn-primary btn-block">
            <i class="mdi mdi-magnify" aria-hidden="true"></i>
            ابحث
        </button>
    </div>


</div>




<div class=" row" style="margin-top: 15px; ">


    <div class="col-md-2" style="display: none">
        <div class="input-group" id="datepicker2">
            <input autocomplete="off" type="text" class="form-control" placeholder="من تاريخ" name="start_date"
                value="" id="start_date" data-date-format="yyyy-mm-dd" data-date-container='#datepicker2'
                data-provide="datepicker" data-date-autoclose="true">
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>

    <div class="col-md-2" style="display: none">
        <div class="input-group" id="datepicker3">
            <input autocomplete="off" type="text" class="form-control" placeholder="الى تاريخ" name="end_date"
                value="" id="end_date" data-date-format="yyyy-mm-dd" data-date-container='#datepicker3'
                data-provide="datepicker" data-date-autoclose="true">
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>




</div>



@section('script')
    <script>
        function selectDepartment(obj) {
            if (obj.value) {
                switch (obj.value) {
                    case 'قسم الدورات العلمية': {
                        $('#analysis_type').empty().html(
                            '<option value="0">اختر التحليل المناسب</option>' +
                            '<option value="coursePlanProgress">انجاز خطة الدورات</option>' +
                            '<option value="mostAccomplished">الأكثر إنجازًا</option>'

                        );

                        // $('#teachers_select').parent().css('display','none');
                        // $('#place_area').parent().css('display','none');


                    }
                    break;
                case 'قسم تحفيظ السنة النبوية': {
                    $('#analysis_type').empty().html(
                        '<option value="0">اختر التحليل المناسب</option>' +
                        '<option value="إنجاز خطة الحفظ">إنجاز خطة الحفظ</option>' +
                        '<option value="فئات الحفظ">فئات الحفظ</option>' +
                        '<option value="طلاب الجلسة الواحدة">طلاب الجلسة الواحدة</option>'
                    );
                }
                break;
                case 'قسم أسانيد السنة النبوية': {
                    $('#analysis_type').empty().html(
                        '<option value="0">اختر التحليل المناسب</option>' +
                        '<option value="إنجاز خطة الاسانيد">إنجاز خطة الاسانيد</option>' +
                        '<option value="إحصاءات">إحصاءات</option>'
                    );

                }
                break;
                case 'الأنشطة الادارية': {
                    $('#analysis_type').empty().html(
                        '<option value="0">اختر التحليل المناسب</option>' +
                        '<option value="الاجتماعات">الاجتماعات</option>'
                    );
                }
                break;
                case 'جميع الأقسام': {
                    $('#analysis_type').empty().html(
                        '<option value="0">اختر التحليل المناسب</option>'
                    );
                }
                break;

                default: {
                    $('#teachers_select').parent().css('display', 'block');
                    $('#place_area').parent().css('display', 'block');
                }
                }
                // updateTable();
            }
        }

        function getSubArea(obj) {
            if (obj.value) {
                $.get('getSubAreas/' + obj.value, function(data) {
                    $('#report_sub_area_select').empty().html(data);
                });

                $.get('/getSubAreaTeachers/' + obj.value, function(data) {
                    $('#teachers_select').empty().html(data[0]);
                });

                // updateTable();
            }
        }



        function getSubAreaTeachers(obj) {
            $.get('/getSubAreaTeachers/' + obj.value, function(data) {
                $('#place_area').empty().html(data[1]);
            });
        }



        function updateDateTable() {
            var filters = '?department_id=' + $('#reports_department_id').val() +
                '&analysis_type=' + $('#analysis_type').val() +
                '&start_date=' + $('#start_date').val() +
                '&end_date=' + $('#end_date').val() +
                '&sub_area_id=' + $('#report_sub_area_select').val() +
                '&teacher_id=' + $('#teachers_select').val() +
                '&place_id=' + $('#place_area').val() +
                '&area_id=' + $('#report_area_select').val() +
                '&analysis_sub_type=' + $('#analysis_sub_type').val() +
                '&book_id=' + $('#books_select').val();

            $('#tableContainer')
                .html(
                    '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                    '   <span class="sr-only">يرجى الانتظار ...</span>' +
                    '</div>'
                );


            $.get('/getAnalysisView' + filters, function(data) {
                $('#tableContainer').empty().html(data.view);
                // $('#custom_filters').empty().html(data.filters);
            });
        }


        $(document).ready(function() {
            $("body").on('change', '#analysis_type', function() {
                if ($(this).find(":selected").val() == 'mostAccomplished') {
                    $('#analysis_sub_type').parent().css('display', 'block');
                } else {
                    $('#analysis_sub_type').parent().css('display', 'none');

                }
            });
        });





        $('.select2').select2({
            dir: "rtl",
            dropdownAutoWidth: true,
        });
    </script>
@endsection
