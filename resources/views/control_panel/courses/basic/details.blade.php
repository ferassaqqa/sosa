


<style>

    .col-form-label{
        text-align: right;
        font-size: 18px;
    }
    .modal-content{
        border: unset;
    }
    
    .modal-body,.modal-footer{
            padding: 20px 100px 20px 20px;
    }

    .col-md-9{
        text-align: center;
        font-size: 18px;
    }
    </style>




<div class="modal-header " style="background-color: #00937c !important;">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #fff !important;width: 100%;text-align-last: center;">
        تفاصيل دورة علمية
         {{-- ({{$course_number}}) --}}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">


<div class="mb-3 row">
    <label for="book_id" class="col-md-3 col-form-label" >الكتاب:</label>
    <div class="col-md-9" id="book_select">
        {!! $course->book_name !!}       
    </div>
</div>

<div class="mb-3 row">
    <label for="teacher_id" class="col-md-3 col-form-label" >المعلم:</label>
    <div class="col-md-9">
        {!! $course->teacher_name !!}              
    </div>
</div>
<div class="mb-3 row">
    <label for="place_id" class="col-md-3 col-form-label ">عدد الطلاب:</label>
    <div class="col-md-9">
        <p> {{ $course->studentsForPermissions->count() }} </p>
    </div>
</div>
<div class="mb-3 row">
    <label for="start_date" class="col-md-3 col-form-label" > تاريخ بداية الدورة :</label>
    <div class="col-md-9">
        {{ $course->start_date }}
    </div>
</div>



<div class="mb-3 row">
    <label for="place_id" class="col-md-3 col-form-label ">العنوان:</label>
    <div class="col-md-9">
        {!! $course->area_father_name !!} - {!! $course->area_name !!} - {!! $course->place_name !!}           
       
    </div>

</div>



<div class="mb-3 row">
    <label for="place_id" class="col-md-3 col-form-label ">إضافة الدورة بواسطة:</label>
    <div class="col-md-9">
        <p>{{ $course->created_at_user ? $course->created_at_user->name : '' }}</p>
    </div>
</div>

<div class="mb-3 row">
    <label for="place_id" class="col-md-3 col-form-label ">تاريخ الاضافة:</label>
    <div class="col-md-9">
        <p>{{ $course->created_at_user ? \Carbon\Carbon::parse($course->created_at_user->created_at)->format('Y-m-d') : '' }}</p>
    </div>
</div>

<div class="mb-3 row">
    <label for="student_category" class="col-md-3 col-form-label">فئة الطلاب:</label>
    <div class="col-md-9" id="student_category" style="text-align: center;padding: 8px 0;">
        {!! $course->book_students_category_string !!}
    </div>
</div>

<div class="mb-3 row">
    <label for="student_category" class="col-md-3 col-form-label" >عدد الساعات:</label>
    <div class="col-md-9" id="hours_count" style="text-align: center;padding: 8px 0;">
        {!! $course->book_students_hours_count !!}
    </div>
</div>



</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect btn-lg" data-bs-dismiss="modal">إغلاق</button>
</div>


