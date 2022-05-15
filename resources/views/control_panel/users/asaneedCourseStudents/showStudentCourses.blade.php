<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> دورات الأسانيد والإجازات للطالب {{$user->name}}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @php $courses = isset($passed) ? $user->passedAsaneedStudentCourses : ( isset($failed) ? $user->failedAsaneedStudentCourses : $user->studentAsaneedCourses) @endphp
    <table class="table table-responsive">
        <thead>
            <th>المعلم</th>
            <th>الكتاب</th>
            <th>المكان</th>
            <th>العلامة</th>
            <th>تاريخ البداية</th>
        </thead>
        <tbody>
            @foreach($courses as $key => $course)
                <tr>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->book_name }}</td>
                    <td>{{ $course->place_full_name }}</td>
                    <td>{{ $course->pivot->mark ? $course->pivot->mark : 'لم يختبر بعد' }}</td>
                    <td>{{ $course->start_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
</div>
