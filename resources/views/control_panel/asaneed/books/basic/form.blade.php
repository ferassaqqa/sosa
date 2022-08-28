@csrf
<div class="mb-3 row">
    <label for="name" class="col-md-2 col-form-label">الاسم</label>
    <div class="col-md-4">
        <input class="form-control" type="text" name="name" value="{{old('name',$asaneedBook->name)}}" id="name" >
    </div>
    <label for="author" class="col-md-2 col-form-label">المؤلف</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="author" id="author" value="{{ $asaneedBook->author }}">
    </div>
</div>
<div class="mb-3 row">
    <label for="hours_count" class="col-md-2 col-form-label">عدد الساعات</label>
    <div class="col-md-4">
        <input class="form-control" type="number" min="0" step="1" name="hours_count" value="{{old('hours_count',$asaneedBook->hours_count)}}" id="hours_count" style="direction: rtl;">
    </div>
    <label for="pass_mark" class="col-md-2 col-form-label">علامة النجاح</label>
    <div class="col-md-4">
        <input class="form-control" type="number" min="0" step="1" name="pass_mark" value="{{old('pass_mark',$asaneedBook->pass_mark)}}" id="pass_mark" style="direction: rtl;">
    </div>
</div>
<div class="mb-3 row">
    <label for="book_code" class="col-md-2 col-form-label">رمز الكتاب</label>
    <div class="col-md-4">
        <input class="form-control" type="text" name="book_code" value="{{old('book_code',$asaneedBook->book_code)}}" id="book_code" >
    </div>
    <label for="year" class="col-md-2 col-form-label">السنة</label>
    <div class="col-md-4">
        <select class="form-control" name="year" id="year">
            {!! isset($years) ? $years : (isset($year) ? '<option value="'.$year.'">'.$year.'</option>' : '') !!}
        </select>
    </div>
</div>
{{-- <div class="row mb-3">
    @if(isset($type) && $type == 'خارج الخطة')
        <input type="hidden" class="form-control" name="included_in_plan" value="خارج الخطة">
        <label for="category_id" class="col-md-3 col-form-label">التصنيف</label>
        <div class="col-md-9">
            <select class="form-control" name="category_id" id="category_id">
                {!! $bookCategoriesSelect !!}
            </select>
        </div>
    @else
        <label for="included_in_plan" class="col-md-2 col-form-label">الخطة</label>
        <div class="col-md-4">
            <select class="form-control" name="included_in_plan" id="included_in_plan">
                <option value="">-- إختر --</option>
                <option value="داخل الخطة" @if($asaneedBook->included_in_plan == "داخل الخطة") selected @endif>داخل الخطة</option>
                <option value="خارج الخطة" @if($asaneedBook->included_in_plan == "خارج الخطة") selected @endif>خارج الخطة</option>
            </select>
        </div>
        <label for="category_id" class="col-md-2 col-form-label">التصنيف</label>
        <div class="col-md-4">
            <select class="form-control" name="category_id" id="category_id">
                {!! $bookCategoriesSelect !!}
            </select>
        </div>
    @endif
</div> --}}


<div class="row mb-3">
    <label for="student_category" class="col-md-2 col-form-label">عدد الطلاب المطلوب</label>
    <div class="col-md-4" id="required_students" >
        <input style="direction: rtl;" class="form-control required_students_number" type="number" min="0" step="1" value="{{ $asaneedBook->required_students_number }}" name="required_students_number" >
    </div>

    <label for="category_id" class="col-md-2 col-form-label">التصنيف</label>
    <div class="col-md-4">
        <select class="form-control" name="category_id" id="category_id">
            {!! $bookCategoriesSelect !!}
        </select>
    </div>

</div>


{{-- <div class="row mb-3">
        <label for="student_category" class="col-md-3 col-form-label">فئة الطلاب:</label>
        <div class="col-md-9" id="student_category" style="text-align: center;padding: 8px 0;">
            <span style="color: #2ca02c;" id="student_category_string">{!! $asaneedBook->student_category_string !!}</span>
        </div>
    </div> --}}
    {{-- <div class="row mb-3">
        <label for="student_category" class="col-md-3 col-form-label">فئات الكتاب</label>
        <div class="col-md-9">
            <div class="row" data-cat="1">
                <label class="col-md-6 col-form-label">الابتدائية</label>
                <input class="form-control col-md-6 student_category" type="number" min="0" step="1" value="{{ count($asaneedBook->required_students_number_array_as_array) ? $asaneedBook->required_students_number_array_as_array[0] : 0}}" name="student_category[]" style="width: 47%;direction: rtl;">
            </div>
            <div class="row" data-cat="2">
                <label class="col-md-6 col-form-label">الإعدادية</label>
                <input class="form-control col-md-6 student_category" type="number" min="0" step="1" value="{{ count($asaneedBook->required_students_number_array_as_array) ? $asaneedBook->required_students_number_array_as_array[1] : 0 }}" name="student_category[]" style="width: 47%;direction: rtl;">
            </div>
            <div class="row" data-cat="3">
                <label class="col-md-6 col-form-label">الثانوية</label>
                <input class="form-control col-md-6 student_category" type="number" min="0" step="1" value="{{ count($asaneedBook->required_students_number_array_as_array) ? $asaneedBook->required_students_number_array_as_array[2] : 0 }}" name="student_category[]" style="width: 47%;direction: rtl;">
            </div>
            <div class="row" data-cat="4">
                <label class="col-md-6 col-form-label">الثانوية فما فوق</label>
                <input class="form-control col-md-6 student_category" type="number" min="0" step="1" value="{{ count($asaneedBook->required_students_number_array_as_array) ? $asaneedBook->required_students_number_array_as_array[3] : 0 }}" name="student_category[]" style="width: 47%;direction: rtl;">
            </div>
        </div>
    </div> --}}
<input type="hidden" name="id" value="{{ $asaneedBook->id }}">


<script>
    // $('.student_category').on('change',function(){
    //     var input = $(this);
    //     var student_category = $('#student_category_string').text() ? $('#student_category_string').text().split('-') : [];
    //     var cat = input.closest('div').data('cat');
    //     var category = "";
    //     switch (cat){
    //         case 1: {category = "ابتدائية";}break;
    //         case 2: {category = "اعدادية";}break;
    //         case 3: {category = "ثانوية";}break;
    //         case 4: {category = "ثانوية فما فوق";}break;
    //     }

    //     // console.log(category);
    //     var boolVar = student_category.includes(category);
    //     if(parseInt(input.val())){
    //         if (!boolVar) {
    //             student_category.push(category);
    //         }
    //     }else {
    //         if (boolVar) {
    //             var index = student_category.indexOf(category);
    //             student_category.splice(index,1);
    //         }
    //     }
    //     $('#student_category_string').empty().text(student_category.join("-"));
    // });
</script>
