
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
            @foreach($course->students as $key => $student)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->dob }}</td>
                        <td>{{ $student->pob }}</td>
                        <td>
                            {{ $student->pivot->mark }}
                        </td>
                        <td style="max-width: 71px;">{!! markEstimation($student->pivot->mark) !!}</td>
                    </tr>
                @php $i++; @endphp
                @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer" style="display: block;">
    <div class="row mb-3">
        <div class="col-md-3">
            {{--<button type="button" class="btn btn-primary waves-effect" onclick="getExamsWaitingApproveMarks()" style="width: 100%;">رجوع</button>--}}
        </div>
        <div class="col-md-3">
            @if($exam->status > 3)
                تم اعتماد رئيس قسم الاختبارات
            @else
                <button type="button" class="btn btn-success waves-effect" onclick="examsDeptManagerApprovement('{{ $exam->id }}',this)" style="width: 100%;">@if($exam->status == 3) تراجع اعتماد رئيس قسم الاختبارات @elseif($exam->status == 2) اعتماد رئيس قسم الاختبارات @endif</button>
            @endif
        </div>
        <div class="col-md-3">
            @if($exam->status >= 3)
                @if($exam->status > 4)
                    تم اعتماد مدير دائرة التخطيط والجودة
                @else
                    <button type="button" class="btn btn-info waves-effect" onclick="qualityDeptManagerApprovement('{{ $exam->id }}',this)" style="width: 100%;">@if($exam->status == 4) تراجع اعتماد مدير دائرة التخطيط والجودة @elseif($exam->status == 3) اعتماد مدير دائرة التخطيط والجودة @endif</button>
                @endif
            @else
                 <span style="margin-top: 5px;">بانتظار اعتماد رئيس قسم الاختبارات</span>
            @endif
        </div>
        <div class="col-md-3">
            @if($exam->status >= 4)
                <button type="button" class="btn btn-danger waves-effect" onclick="sunnaManagerApprovement('{{ $exam->id }}',this)" style="width: 100%;">@if($exam->status == 5) تراجع اعتماد مدير الدائرة @elseif($exam->status == 4) اعتماد مدير الدائرة @endif</button>
            @else
                بانتظار اعتماد مدير دائرة التخطيط والجودة
            @endif
        </div>
    </div>
</div>

