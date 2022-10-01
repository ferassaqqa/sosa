
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> كشف اختبار دورة {!! '<span style="color: #2ca02c;">'.$course->book_name.'</span> للمعلم <span style="color: #2ca02c;"> '. $course->teacher_name .'</span>' !!} - دائرة السنة النبوية - دار القرآن الكريم والسنة </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-responsive table-bordered">
        <thead>
        <th>المنطقة الكبرى:</th>
        <th>المنطقة المحلية:</th>
        <th>مكان الدورة:</th>
        <th>نوع الدورة:</th>
        <th>بداية الدورة:</th>
        <th>نهاية الدورة:</th>
        <th>معلم الدورة:</th>
        <th>فئة الطلاب:</th>
        </thead>
        <tbody>
            <tr>
                <td>{{ $course->area_father_name }}</td>
                <td>{{ $course->area_name }}</td>
                <td>{{ $course->place_name }}</td>
                <td>{{ $course->course_type }}</td>
                <td>{{ $course->start_date }}</td>
                <td>{{ $course->exam_date }}</td>
                <td>{{ $course->teacher_name }}</td>
                <td>{!! $course->book_students_category_string !!}</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-responsive table-bordered">
        <thead>
            <th>#</th>
            <th>الاسم رباعي:</th>
            <th>تاريخ الميلاد:</th>
            <th>مكان الميلاد:</th>
            <th>الدرجة:</th>
            <th>التقدير:</th>
        </thead>
        <tbody>
            @php $i=1; @endphp
            @foreach($students as $key => $student)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->dob }}</td>
                    <td>{{ $student->pob }}</td>
                    <td>
                        <input form="form" type="number" min="0" max="100" oninput="changeMarkEstimation(this,'{{ $student->id }}')" step="1" name="mark[{{$student->id}}]" class="form-control" value="{{ $student->pivot->mark }}" style="direction: rtl;">
                    </td>
                    <td id="student_estimation_{{$student->id}}" style="max-width: 71px;">{!! markEstimation($student->pivot->mark) !!}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>
    </table>
    @if ($course->exam->examable_type == 'App\Models\Course')
    <form action="{{ route('courseExam.enterMarks',$course->id) }}" method="POST" id="form">
        @csrf
    </form>
    @endif
    @if ($course->exam->examable_type == 'App\Models\AsaneedCourse')
    <form action="{{ route('asaneedExam.enterMarks',$course->id) }}" method="POST" id="form">
        @csrf
    </form>
    @endif

</div>

<div class="modal-footer">
    {{--<button type="button" class="btn btn-info waves-effect" onclick="getEligibleCoursesForMarkEnter()">رجوع</button>--}}
    <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>
</div>

<script>
    $('.examAppointment').on('click',function(){
        var course_id = $(this).data('course-id');
        $('.bs-example-modal-xl').modal('toggle');
        $('.user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get('getCourseExamAppointment/'+course_id,function (data) {
            $('#modal_content').empty().html(data);
        });
    });
    function changeMarkEstimation(obj,student_id) {
        var mark = obj.value;
        var student_estimation = document.getElementById('student_estimation_'+student_id);
        if(60<=mark && mark<70){
            student_estimation.innerHTML =  '<span style="color:#b3b300">متوسط</span>';
        }else if(70<=mark && mark<75){
            student_estimation.innerHTML =  '<span style="color:lawngreen">جيد</span>';
        }else if(75<=mark && mark<80){
            student_estimation.innerHTML =  '<span style="color:lightgreen">جيد مرتفع</span>';
        }else if(80<=mark && mark<85){
            student_estimation.innerHTML =  '<span style="color:forestgreen">جيد جدا</span>';
        }else if(85<=mark && mark<90){
            student_estimation.innerHTML =  '<span style="color:green">جيد جدا مرتفع</span>';
        }else if(90<=mark && mark<=100){
            student_estimation.innerHTML =  '<span style="color:darkgreen">ممتاز</span>';
        }else{
            student_estimation.innerHTML =  '<span style="color:red">لا يجاز</span>';
        }
    }
</script>
<script>
    $('#form').submit(function(event){
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        $.ajax({
            url:$(this).attr('action'),
            type:$(this).attr('method'),
            data:$(this).serialize(),
            success:function(result){
                $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                    { allow_dismiss: true,type:result.type }
                );
                $('#dataTable1').DataTable().ajax.reload();
                document.querySelector('button[data-bs-dismiss="modal"]').click();
                // getEligibleCoursesForMarkEnter();
            },
            error:function (errors) {
                const entries = Object.entries(errors.responseJSON.errors);
                // console.log(entries);
                var errors_message = document.createElement('div');
                for(let x of entries){
                    let tag_name = x[0].split('.');
                    // console.log('input[name="'+tag_name[0]+'['+tag_name[1]+']"]');
                    if(document.querySelector('input[name="'+tag_name[0]+'['+tag_name[1]+']"]')) {
                        document.querySelector('input[name="'+tag_name[0]+'['+tag_name[1]+']"]').focus();
                        errors_message = document.createElement('div');
                        errors_message.classList.add('invalid-feedback');
                        errors_message.classList.add('show');
                        document.querySelector('input[name="'+tag_name[0]+'['+tag_name[1]+']"]').classList.add('is-invalid');
                        errors_message.innerHTML = x[1][0];
                        document.querySelector('input[name="'+tag_name[0]+'['+tag_name[1]+']"]').parentElement.appendChild(errors_message);
                    }
                }
            }

        });
        return false;
    });
</script>
