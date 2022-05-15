<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">تفاصيل دورة علمية</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
{{-- <div class="modal-body">
    <table class="table table-centered">
        <tr>
            <td style="background-color: #DDD;" colspan="2">اسم المضيف للدورة</td>
            <td colspan="2">{{ $course->created_at_user ? $course->created_at_user->name : '' }}</td>
            <td style="background-color: #DDD;" colspan="2">تاريخ الاضافة</td>
            <td colspan="2">
                {{ $course->created_at_user ? \Carbon\Carbon::parse($course->created_at_user->created_at)->format('Y-m-d') : '' }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #DDD;" colspan="2">عدد الطلاب</td>
            <td colspan="2">{{ $course->studentsForPermissions->count() }}</td>
            <td style="background-color: #DDD;" colspan="2">فئة الطلاب</td>
            <td colspan="2"></td>
        </tr>
    </table>
</div> --}}

<div class="modal-body">

    <div class="mb-3 row">
        <label for="place_id" class="col-md-2 col-form-label ">اسم المضيف للدورة:</label>
        <div class="col-md-9">
            <p>{{ $course->created_at_user ? $course->created_at_user->name : '' }}</p>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="place_id" class="col-md-2 col-form-label ">تاريخ الاضافة:</label>
        <div class="col-md-9">
            <p>{{ $course->created_at_user ? \Carbon\Carbon::parse($course->created_at_user->created_at)->format('Y-m-d') : '' }}</p>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="place_id" class="col-md-2 col-form-label ">عدد الطلاب:</label>
        <div class="col-md-9">
            <p> {{ $course->studentsForPermissions->count() }} </p>
        </div>
    </div>

    {{-- <div class="mb-3 row">
        <label for="place_id" class="col-md-2 col-form-label ">فئة الطلاب:</label>
        <div class="col-md-9">
            <p></p>
        </div>
    </div> --}}


    <div class="mb-3 row">
        <label for="start_date" class="col-md-2 col-form-label">بداية الدورة:</label>
        <div class="col-md-9">
            <p>{{ $course->start_date }}</p>
        </div>
    </div>


    <div class="mb-3 row">
        <label for="area_id" class="col-md-2 col-form-label">المنطقة الكبرى:</label>
        <div class="col-md-3">
            <p></p>

        </div>

        <label for="sub_area_id" class="col-md-2 col-form-label">المحلية:</label>
        <div class="col-md-4">
            <p></p>

        </div>
    </div>

    <div class="mb-3 row">
        <label for="place_id" class="col-md-2 col-form-label ">المسجد:</label>
        <div class="col-md-9">
            <p></p>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="teacher_id" class="col-md-2 col-form-label">المعلم:</label>
        <div class="col-md-9">
            <p></p>

        </div>
    </div>

    <div class="mb-3 row">
        <label for="book_id" class="col-md-2 col-form-label">الكتاب:</label>
        <div class="col-md-9" id="book_select">
            <p></p>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="student_category" class="col-md-2 col-form-label">فئة الطلاب:</label>
        <div class="col-md-9" id="student_category" style="text-align: center;padding: 8px 0;">
            <p><?= $course->book_students_category_string ?></p>

        </div>
    </div>

    <div class="mb-3 row">
        <label for="student_category" class="col-md-2 col-form-label">عدد الساعات:</label>
        <div class="col-md-9" id="hours_count" style="text-align: center;padding: 8px 0;">
            <p></p>


        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect btn-lg" data-bs-dismiss="modal">إلغاء</button>
    {{-- <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button> --}}
</div>
