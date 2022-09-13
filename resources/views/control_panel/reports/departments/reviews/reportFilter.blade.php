


<div class=" row" >


    <div class="col-md-3" >
        <select class="form-control" id="analysis_type">
            <option value=""> اختر اقسام الادارة </option>
            <option value="all">جميع الاقسام</option>
            <option value="courses">قسم الدورات العلمية</option>
            <option value="asaneed"> قسم الاسانيد و الاجازات</option>

        </select>
    </div>

    <div class="col-md-3">
        <select class="form-control" id="report_area_select">
            <option value="0">اختر المنطقة</option>
            @foreach ($areas as $key => $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
    </div>


{{-- <style>
    #datepicker1{z-index: 99 !important};

</style> --}}

    <div class="col-md-3" >

        <input name="report_date" id="report_date" type="date" class="form-control" date-format="yyyy-mm-dd"/>


        {{-- <select class="form-control" id="analysis_sub_type">
            <option value=""> اختر نوع التقييم </option>
            <option>التقييم السنوي</option>
            <option value="teachers">تقييم الفصل الاول</option>
            <option value="mosques">تقييم الفصل الثاني</option>
            <option value="local_areas"> تقييم الفصل الثالث</option>

        </select> --}}
    </div>





    <div class="col-md-3">
        <button type="button" style="width:100%" onclick="updateDateTable()" class="btn btn-primary btn-block">
            <i class="mdi mdi-magnify" aria-hidden="true"></i>
            ابحث
        </button>
    </div>


</div>







@section('script')
    <script>





        function updateDateTable() {
            var filters = '?analysis_type=' + $('#analysis_type').val() +
                '&area_id=' + $('#report_area_select').val() +
                '&report_date=' + $('#report_date').val();

            $('#tableContainer')
                .html(
                    '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                    '   <span class="sr-only">يرجى الانتظار ...</span>' +
                    '</div>'
                );


            $.get('/getReviewsAnalysisView' + filters, function(data) {
                $('#tableContainer').empty().html(data.view);
                // $('#custom_filters').empty().html(data.filters);
            });
        }



        $('.select2').select2({
            dir: "rtl",
            dropdownAutoWidth: true,
        });
    </script>
@endsection
