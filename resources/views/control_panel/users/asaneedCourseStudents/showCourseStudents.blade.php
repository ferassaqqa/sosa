<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> طلاب دورة كتاب - {{ $asaneedCourse->book_name }} - المعلم {{ $asaneedCourse->teacher_name }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-responsive">
        <thead>
            <th>اسم الطالب</th>
            <th>رقم الهوية</th>
            <th>تاريخ الميلاد</th>
            <th>مكان الميلاد</th>
            <th>ضمن الفئة العمرية</th>
            <th>حذف</th>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->id_num }}</td>
                    <td>{{ $user->dob }}</td>
                    <td>{{ $user->pob }}</td>
                    <td>{!! in_array($user->student_category,$asaneedCourse->student_categories) ? '<i class="mdi mdi-checkbox-marked-circle-outline" style="color:green"></i>' : '<i class="mdi mdi-close-circle-outline" style="color:red"></i>' !!}</td>
                    <td>{!! $user->deleteAsaneedCourseStudent() !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
</div>
