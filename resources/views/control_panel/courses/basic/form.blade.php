@csrf
<div class="mb-3 row">
    <label for="start_date" class="col-md-2 col-form-label" style="text-align: center;font-size: 18px;">بداية الدورة</label>
    <div class="col-md-9">
        <div class="input-group" id="datepicker1">
            <input type="text" class="form-control" placeholder="تاريخ بداية الدورة"
                   name="start_date" value="{{old('start_date',$course->start_date )}}" id="start_date"
                   data-date-format="yyyy-mm-dd" data-date-container='#datepicker1' data-provide="datepicker"
                   data-date-autoclose="true">
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>
</div>


<div class="mb-3 row">
    <label for="included_in_plan" class="col-md-2 col-form-label" style="text-align: center;font-size: 18px;">المنطقة الكبرى:</label>
    <div class="col-md-3">
        <select class="form-control" name="area_id">
            <option value="">-- تحديد --</option>
            @foreach($areas as $key => $area)
                <option value="{{ $area->id }}" @if($area->id == $course->area_father_id) selected @endif>{{ $area->name }}</option>
            @endforeach
        </select>
    </div>

    <label for="included_in_plan" class="col-md-2 col-form-label" style="text-align: center;font-size: 18px;">المحلية:</label>
    <div class="col-md-4">
        <select class="form-control" name="sub_area_id" id="sub_area_id">
            @if(isset($sub_areas))
                @foreach($sub_areas as $key => $area)
                    <option value="{{ $area->id }}" @if($area->id == $course->area_id) selected @endif>{{ $area->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="included_in_plan" class="col-md-2 col-form-label" style="text-align: center;font-size: 18px;">المسجد:</label>
    <div class="col-md-9">
        <select class="form-control" name="place_id" id="place_id">
            @if(isset($places))
                @foreach($places as $key => $place)
                    <option value="{{ $place->id }}" @if($place->id == $course->place_id) selected @endif>{{ $place->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    {{--<label for="included_in_plan" class="col-md-2 col-form-label" style="text-align: center;">النوع:</label>--}}
    {{--<div class="col-md-4">--}}
        {{--<select class="form-control" name="included_in_plan" id="included_in_plan" onchange="planChanged(this)">--}}
            {{--<option value="0">-- تحديد --</option>--}}
            {{--<option value="داخل الخطة" @if($course->included_in_plan == 'داخل الخطة') selected @endif>داخل الخطة</option>--}}
            {{--<option value="خارج الخطة" @if($course->included_in_plan == 'خارج الخطة') selected @endif>خارج الخطة</option>--}}
        {{--</select>--}}
    {{--</div>--}}
</div>

<div class="mb-3 row">
    <label for="teacher_id" class="col-md-2 col-form-label" style="text-align: center;font-size: 18px;">المعلم:</label>
    <div class="col-md-9">
        <select class="form-control" name="teacher_id" id="teacher_id">
            <option value="">-- تحديد --</option>
            @if(isset($teachers)) {!! $teachers !!} @endif
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="book_id" class="col-md-2 col-form-label" style="text-align: center;font-size: 18px;">الكتاب:</label>
    <div class="col-md-9" id="book_select">
        <select class="form-control" name="book_id" id="book_id">
            <option value="">-- تحديد --</option>
            {!! isset($books) ? $books : '' !!}
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="student_category" class="col-md-2 col-form-label" style="text-align: center;font-size: 18px;">فئة الطلاب:</label>
    <div class="col-md-9" id="student_category" style="text-align: center;padding: 8px 0;">
        {!! $course->book_students_category_string !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="student_category" class="col-md-2 col-form-label" style="text-align: center;font-size: 18px;">عدد الساعات:</label>
    <div class="col-md-9" id="hours_count" style="text-align: center;padding: 8px 0;">
        {!! $course->book_students_hours_count !!}
        {{--<input type="number" min="0" step="1" name="hours" class="form-control" value="{{ old('hours',$course->hours) }}" style="direction: rtl;">--}}
    </div>
</div>
{{--<div class="mb-3 row">--}}
    {{--<div class="col-md-3"></div>--}}
    {{--<div class="col-md-9">--}}
        {{--<div class="form-check form-check-inline">--}}
            {{--<input class="form-check-input" type="radio" name="course_type" id="course_type1" value="إختبار" @if($course->course_type == 'إختبار') checked @endif>--}}
            {{--<label class="form-check-label" for="course_type1">--}}
                {{--إختبار--}}
            {{--</label>--}}
        {{--</div>--}}
        {{--<div class="form-check form-check-inline">--}}
            {{--<input class="form-check-input" type="radio" name="course_type" id="course_type2" value="حضور" @if($course->course_type == 'حضور') checked @endif>--}}
            {{--<label class="form-check-label" for="course_type2">--}}
                {{--حضور--}}
            {{--</label>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
<input type="hidden" name="id" value="{{ $course->id }}">
<script>

    $(function () {
        $("#place_id").select2({
            dir: "rtl",
        });
        $("#teacher_id").select2({
            dir: "rtl",
        });
    });

    $('select[name="area_id"]').on('change', function() {
        // console.log($(this).val(),$(this)[0][$(this)[0].selectedIndex]);
        var area_id = $(this).val();
        $.get('/getSubAreas/'+area_id,function(data){
            $('#sub_area_id').empty().html(data);
        });
    });
    $('#sub_area_id').on('change', function() {
        var sub_area_id = $(this).val();
        $.get('/getSubAreaPlaces/'+sub_area_id,function(data){
            $('#place_id').empty().html(data);
        });
    });
    $('#place_id').on('change', function() {
        var place_id = $(this).val();
        $.get('/getPlaceTeachers/'+place_id+'/{{ $course->teacher_id ? $course->teacher_id : 0 }}',function(data){
            $('#teacher_id').empty().html(data);
        });
    });
    $('#book_id').on('change', function() {
        var book_id = $(this).val();
        $.get('/getBookStudentCategory/'+book_id,function(data){
            $('#student_category').empty().html(data[1]);
            $('#hours_count').empty().html(data[0]);
        });
    });
    $('#start_date').on('change',function() {
        var start_date = document.getElementById('start_date');
        if(start_date){
            if(start_date.value!=''){
                $.get('/getYearBooksForNewCourse/'+start_date.value,function(data){
                    $('#book_select').empty().html(data);
                    $('#book_id').on('change', function() {
                        var book_id = $(this).val();
                        $.get('/getBookStudentCategory/'+book_id,function(data){
                            $('#student_category').empty().html(data[1]);
                            $('#hours_count').empty().html(data[0]);
                        });
                    });
                });
            }else{

            }
        }
    });
    function addNewCourseBook() {
        var start_date = document.getElementById('start_date');
        if(start_date){
            if(start_date.value!=''){
                $('.modal').modal('hide');
                $.get('/createOutOfPlanBook/'+start_date.value,function(data){
                    // $('#book_select').empty().html(data);
                    $('.bs-example-modal-xl').modal('show');
                    $('#user_modal_content').html(data);
                });
            }else{

            }
        }
        
    }

</script>