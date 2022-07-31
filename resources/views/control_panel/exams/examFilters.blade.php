@if (hasPermissionHelper('فلترة مواعيد الاختبارات'))
<div class="row">
    <div class="col-md-4">
        <select id="area_id" onchange="getSubAreas(this);" class="form-control">
            <option value="0"> الكبرى</option>
            @foreach ($areas as $key => $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>

    </div>

    <div class="col-md-4">
        <select id="pending_exams_sub_areas_select" onchange="getSubareaTeacherPlace(this);"
            class="form-control">
            <option value="0">المحلية</option>
        </select>
    </div>

    <div class="col-md-4">
        <select class="form-control select2" id="place_area">
            <option value="0">اختر مكان الدورة</option>
        </select>
    </div>


</div>

<div class=" row" style="margin-top: 15px; ">


    <div class="col-md-4">
        <select id="moallem_id" class="form-control select2">
            <option value="0">المعلم</option>
            @foreach ($moallems as $key => $moallem)
                <option value="{{ $moallem->id }}">{{ $moallem->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <select id="book_id" class="form-control ">
            <option value="0">الكتاب</option>
            @foreach ($books as $key => $book)
                <option value="{{ $book->id }}">{{ $book->name }}</option>
            @endforeach
        </select>
    </div>


    <div class="col-md-4">
        <select id="exam_type" class="form-control ">
            <option value="0">نوع الإختبار</option>
            <option value="App\Models\Course">دورات علمية</option>
            <option value="App\Models\AsaneedCourse">مجالس اسانيد</option>
        </select>
    </div>

</div>


<div class=" row" style="margin-top: 15px; ">

    <div class="col-md-4">
        <div class="input-group" id="datepicker2">
            <input autocomplete="off" type="text" class="form-control" placeholder="من تاريخ"
                name="start_date" value="" id="start_date" data-date-format="yyyy-mm-dd"
                data-date-container='#datepicker2' data-provide="datepicker"
                data-date-autoclose="true">
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="input-group" id="datepicker3">
            <input autocomplete="off" type="text" class="form-control" placeholder="الى تاريخ"
                name="end_date" value="" id="end_date" data-date-format="yyyy-mm-dd"
                data-date-container='#datepicker3' data-provide="datepicker"
                data-date-autoclose="true">
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>

    <div class="col-md-4">
        <button type="button" style="width:100%" onclick="changeExams()"
            class="btn btn-primary btn-block">
            <i class="mdi mdi-magnify" aria-hidden="true"></i>
            ابحث
        </button>
    </div>

</div>

<script>
        function getSubAreas(obj) {
                if (obj.value != 0) {
                    $.get('/getSubAreas/' + obj.value, function(data) {
                        $('#pending_exams_sub_areas_select').empty().html(data);
                    });
                } else {
                    $('#pending_exams_sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                }
            }


            function getSubareaTeacherPlace(obj) {
                if (obj.value != 0) {
                    $.get('/getSubAreaTeachers/' + obj.value, function(data) {
                        $('#moallem_id').empty().html(data[0]);
                        $('#place_area').empty().html(data[1]);
                    });
                } else {
                    $('#moallem_id').empty().html('<option value="0">اختر المعلم</option>');
                    $('#place_area').empty().html('<option value="0">اختر مكان الدورة</option>');
                }
            }

            
</script>



@endif
