
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">تفاصيل دورة علمية</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-centered">
        <tr>
            <td style="background-color: #DDD;" colspan="2">اسم الضيف للدورة</td>
            <td colspan="2">{{ $course->created_at_user ? $course->created_at_user->name : '' }}</td>
            <td style="background-color: #DDD;" colspan="2">تاريخ الاضافة</td>
            <td colspan="2">{{ $course->created_at_user ? \Carbon\Carbon::parse($course->created_at_user->created_at)->format('Y-m-d') : '' }}</td>
        </tr>
        <tr>
            <td style="background-color: #DDD;" colspan="2">عدد الطلاب</td>
            <td colspan="2">{{ $course->studentsForPermissions->count() }}</td>
            <td style="background-color: #DDD;" colspan="2">فئة الطلاب</td>
            <td colspan="2"><?= $course->book_students_category_string ?></td>
        </tr>
    </table>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
</div>

    <script src="{{asset('control_panel/assets/js/pages/form-advanced.init.js')}}"></script>

