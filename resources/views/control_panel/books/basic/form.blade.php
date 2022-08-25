@csrf
<div class="mb-3 row">
    <label for="name" class="col-md-2 col-form-label">الاسم</label>
    <div class="col-md-4">
        <input class="form-control" type="text" name="name" value="{{old('name',$book->name)}}" id="name" >
    </div>
    <label for="author" class="col-md-2 col-form-label">المؤلف</label>
    <div class="col-md-4">
        <select class="form-control" name="author_prefix" style="width: 12%;display: inline-block;">
            <option value="أ" @if($book->author_prefix == 'أ') selected @endif>أ</option>
            <option value="د" @if($book->author_prefix == 'د') selected @endif>د</option>
            <option value="م" @if($book->author_prefix == 'م') selected @endif>م</option>
        </select>
        <input type="text" class="form-control" name="author" id="author" value="{{ $book->author }}" style="width: 86%;display: inline-block;">
    </div>
</div>
<div class="mb-3 row">
    <label for="hours_count" class="col-md-2 col-form-label">عدد الساعات</label>
    <div class="col-md-4">
        <input class="form-control" type="number" min="0" step="1" name="hours_count" value="{{old('hours_count',$book->hours_count)}}" id="hours_count" style="direction: rtl;">
    </div>
    <label for="pass_mark" class="col-md-2 col-form-label">علامة النجاح</label>
    <div class="col-md-4">
        <input class="form-control" type="number" min="0" step="1" name="pass_mark" value="{{old('pass_mark',$book->pass_mark)}}" id="pass_mark" style="direction: rtl;">
    </div>
</div>
<div class="mb-3 row">
    <label for="book_code" class="col-md-2 col-form-label">رمز الكتاب</label>
    <div class="col-md-4">
        <input class="form-control" type="text" name="book_code" value="{{old('book_code',$book->book_code)}}" id="book_code" >
    </div>
    <label for="year" class="col-md-2 col-form-label">السنة</label>
    <div class="col-md-4">
        <select class="form-control" name="year" id="year">
            {!! isset($years) ? $years : (isset($year) ? '<option value="'.$year.'">'.$year.'</option>' : '') !!}
        </select>
    </div>
</div>
<div class="row mb-3">
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
                <option value="داخل الخطة" @if($book->included_in_plan == "داخل الخطة") selected @endif>داخل الخطة</option>
                <option value="خارج الخطة" @if($book->included_in_plan == "خارج الخطة") selected @endif>خارج الخطة</option>
            </select>
        </div>
        <label for="category_id" class="col-md-2 col-form-label">التصنيف</label>
        <div class="col-md-4">
            <select class="form-control" name="category_id" id="category_id">
                {!! $bookCategoriesSelect !!}
            </select>
        </div>
    @endif
</div>

<div class="row mb-3">
    <label for="student_category" class="col-md-3 col-form-label">فئة الطلاب:</label>
    <div class="col-md-9" id="student_category" style="text-align: center;padding: 8px 0;">
        <span style="color: #2ca02c;" id="student_category_string">{!! $book->student_category_string !!}</span>
    </div>
</div>
<div class="row mb-3">
    <label for="student_category" class="col-md-3 col-form-label">العدد الإجمالي للأعداد المطلوبة:</label>
    <div class="col-md-9" id="required_students" >
        <div class="col-md-6"></div>
        <div class="col-md-6" style="float: left;width: 47%;direction: rtl; padding-left:2%;">
            <input class="form-control required_students_number" type="number" min="0" step="1" value="{{ $book->required_students_number }}" name="required_students_number" >
        </div>

    </div>
</div>
    <div class="row mb-3">
        <label for="student_category" class="col-md-3 col-form-label">فئات الكتاب</label>
        <div class="col-md-9">
            <div class="row" data-cat="1">
                <label class="col-md-6 col-form-label">الابتدائية ( 7 - 12 )</label>
                <input class="form-control col-md-6 student_category" type="number" min="0" step="1" value="{{ count($book->required_students_number_array_as_array) ? $book->required_students_number_array_as_array[0] : 0}}" name="student_category[]" style="width: 47%;direction: rtl;">
            </div>
            <div class="row" data-cat="2">
                <label class="col-md-6 col-form-label">الإعدادية ( 13 - 15 )</label>
                <input class="form-control col-md-6 student_category" type="number" min="0" step="1" value="{{ count($book->required_students_number_array_as_array) ? $book->required_students_number_array_as_array[1] : 0 }}" name="student_category[]" style="width: 47%;direction: rtl;">
            </div>
            {{--<div class="row" data-cat="3">--}}
                {{--<label class="col-md-6 col-form-label">الثانوية</label>--}}
                {{--<input class="form-control col-md-6 student_category" type="number" min="0" step="1" value="{{ count($book->required_students_number_array_as_array) ? $book->required_students_number_array_as_array[2] : 0 }}" name="student_category[]" style="width: 47%;direction: rtl;">--}}
            {{--</div>--}}
            <div class="row" data-cat="4">
                <label class="col-md-6 col-form-label">الثانوية فما فوق ( 15 فما فوق )</label>
                <input class="form-control col-md-6 student_category" type="number" min="0" step="1" value="{{ count($book->required_students_number_array_as_array) ? $book->required_students_number_array_as_array[2] : 0 }}" name="student_category[]" style="width: 47%;direction: rtl;">
            </div>
        </div>
    </div>
<input type="hidden" name="id" value="{{ $book->id }}">
<input type="hidden" name="department" value="{{ $department }}">


<script>
    $('.student_category').on('change',function(){
        var input = $(this);
        // console.log(input.val());
        var student_category = $('#student_category_string').text() ? $('#student_category_string').text().split('_') : [];
        var student_count = $('#required_students_number').text() ? parseInt($('#student_category_string').text()) : 0;
        var cat = input.closest('div').data('cat');
        var category = "";
        switch (cat){
            case 1: {category = "ابتدائية ( 7 - 12 )";}break;
            case 2: {category = "اعدادية ( 13 - 15 )";}break;
            case 3: {category = "ثانوية فما فوق ( 16 فما فوق )";}break;
            case 4: {category = "ثانوية فما فوق ( 16 فما فوق )";}break;
        }

        var boolVar = student_category.includes(category);
        // console.log(boolVar,student_category,category);
        if(parseInt(input.val())){
            if (!boolVar) {
                student_category.push(category);
            }
        }else {
            if (boolVar) {
                var index = student_category.indexOf(category);
                student_category.splice(index,1);
            }
        }
        var totalStudentsCount = 0;
        $('.student_category').each(function(){
            totalStudentsCount += parseInt($(this).val()); //<==== a catch  in here !! read below
        });
        $('.required_students_number').val(totalStudentsCount);
        $('#student_category_string').empty().text(student_category.join("_"));
    });
</script>