<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> دورات الطالب {{$user->name}}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @php $courses = isset($passed) ? $user->passedStudentCourses : ( isset($failed) ? $user->failedStudentCourses : $user->studentCourses) @endphp
    <table class="table table-responsive">
        <thead>


            <th>اسم الدورة</th>
            <th>اسم المعلم</th>
            <th>العنوان</th>
            <th>المشرف الميداني</th>
            <th>المشرف العام</th>



            <th>عدد الساعات</th>
            <th>الدرجة</th>
            <th>التقدير</th>
            <th>استلام الشهادة</th>
            <th>أدوات</th>






        </thead>
        <tbody>
            @foreach($courses as $key => $course)
                <tr>
                    <td>{{ $course->book_name }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->place_full_name }}</td>
                    <td>{{ $course->sub_area_supervisor_name }}</td>
                    <td>{{ $course->area_supervisor_name }}</td>

                    <td>{{ $course->book->hours_count}}</td>

                    <td>{{ $course->exam ? ($course->exam->status == 5 ? ($course->pivot->mark ? $course->pivot->mark : 'لم يتم رصد الدرجات') : 'انتظار اعتماد الدرجات' ): 'لم يختبر بعد' }}</td>
                    <td>{!! $course->exam->status == 5 ? ($course->pivot->mark ? markEstimationText($course->pivot->mark) : '-'):'-'  !!}</td>

                    <td>{{ $course->status == 'منتهية' ? 'تم الاستلام' : 'لم يتم الاستلام'}}</td>
                    <td>{{ $course->status == 'منتهية' ? 'طباعة شهادة' : '-' }}</td>


                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">


    <button class="btn btn-primary btn-lg" onclick="exportStudentCoursesAsExcelSheet()" style="width:207px;">
        <i class="mdi mdi-file-excel"></i>
        تصدير excel
    </button>

    <a target="_blank" class="btn btn-primary  btn-lg" href="{{ url('/exportStudentCoursesAsPDF') }}?user_id={{$user->id}}" style="width:207px;">
        <i class="mdi mdi-file-pdf"></i>
        تصدير pdf
    </a>

    <a target="_blank"  href="{{ url('/exportStudentCoursesAsPDF') }}?user_id={{$user->id}}" class="btn btn-primary btn-lg" onclick="" style="width:207px;">
        <i class="mdi mdi-print"></i>
        طباعة
    </a>

</div>



<script>

        function exportStudentCoursesAsExcelSheet(){

            var filters = '?user_id='+{{$user->id}}
                $.get('exportStudentCoursesAsExcelSheet/'+filters,function(response){
                    if(response.file_link) {
                        window.open(
                            response.file_link, "_blank");
                    }
                });
    }


</script>
