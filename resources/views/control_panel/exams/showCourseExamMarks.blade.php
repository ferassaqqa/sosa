
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> درجات الطلاب في دورة المعلم {{ $course->teacher_name }} في كتاب {{$course->book_name}}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-3">
            {{--<select id="sub_area_id" onchange="changeExams(this)" class="form-control">--}}
                {{--<option value="0">الكل</option>--}}
                {{--@foreach($areas as $key => $area)--}}
                    {{--<option value="{{ $area->id }}">{{ $area->name }}</option>--}}
                {{--@endforeach--}}
            {{--</select>--}}
        </div>
    </div>
    <table class="table table-responsive table-bordered">
        <thead>
            <th>م</th>
            <th>اسم الطالب</th>
            <th>الدرجة</th>
            <th>الحالة</th>
        </thead>
        <tbody>
            @php $i=1; @endphp
            @foreach($course->manyStudents as $student)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $student->user_name }}</td>
                    <td>{{ $student->mark }}</td>
                    <td>{!! $student->estimation !!}</td>
                </tr>
                @php $i++; @endphp
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="approveMarks('{{ $course->id }}')">اعتماد الدرجات</button>
</div>

<script>

    function approveMarks(course_id){

        Swal.fire(
            {
                title:"هل انت متأكد",
                text:"سيتم اعتماد الدورة والدرجات بعد الموافقة",
                icon:"warning",
                showCancelButton:!0,
                confirmButtonText:"نعم اعتماد الدرجات",
                cancelButtonText:"إلغاء",
                confirmButtonClass:"btn btn-success mt-2",
                cancelButtonClass:"btn btn-danger ms-2 mt-2",
                buttonsStyling:!1
            })
            .then(
                function(t) {
                    // console.log(t);
                    if (t.isConfirmed) {
                        $('#user_modal_content')
                            .html(
                                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                                '</div>'
                            );
                        $('.modal').modal('hide');

                        $.get('/approveMarks/'+course_id,function (data) {
                            $('#dataTable').DataTable().ajax.reload();
                            Swal.fire({title: data.title, text: data.msg, icon: data.title});
                        });
                    }else{
                        Swal.fire({title: 'الغاء', text: 'لم يتم اعتماد الدرجات', icon: 'error'});
                    }
                }
            );
    }
</script>

