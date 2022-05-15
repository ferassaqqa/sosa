<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> طلاب دورة كتاب - {{ $course->book_name }} - المعلم {{ $course->teacher_name }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-responsive table-bordered">
        <thead>
            <th>اسم الكتاب</th>
            <th>عدد الطلاب</th>
            <th>فئات الطلاب</th>
        </thead>
        <tbody>
            <tr>
                <td>{{ $course->book_name }}</td>
                <td>{{ $course->students->count() }}</td>
                <td>{!! $course->book_students_category_string !!}</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-responsive">
        <thead>
            <th>م</th>
            <th>اسم الطالب</th>
            <th>رقم الهوية</th>
            <th>تاريخ الميلاد</th>
            <th>مكان الميلاد</th>
            <th>الفئة العمرية للطالب</th>
            <th>ضمن الفئة العمرية</th>
            @if(hasPermissionHelper('حذف طالب من دورة علمية'))
                <th>حذف</th>
            @endif
        </thead>
        <tbody>
            @php $i=1; @endphp
            @foreach($users as $key => $user)
                @if($user)
                    <tr>
                        <td>{{ $i }}</td>
                        <td style="text-align:right;">{{ $user->name }}</td>
                        <td>{{ $user->id_num }}</td>
                        <td>{{ $user->dob }}</td>
                        <td>{{ $user->pob }}</td>
                        <td>{{ $user->student_category }}</td>
                        <td>{!! in_array($user->student_category,$course->student_categories) ? '<i class="mdi mdi-checkbox-marked-circle-outline" style="color:green"></i>' : '<i class="mdi mdi-close-circle-outline" style="color:red"></i>' !!}</td>
                        @if(hasPermissionHelper('حذف طالب من دورة علمية'))
                            <td>{!! $user->deleteCourseStudent($course->id) !!}</td>
                        @endif
                    </tr>
                    @php $i++; @endphp
                @endif
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
</div>

<script>
    function deleteCourseStudent (obj){
        var url = obj.getAttribute('data-url');
        // console.log(url);
        var deleteButton = $(obj);
        // console.log(deleteButton.closest('tr'));
        Swal.fire(
            {
                title:"هل انت متأكد",
                text:"لن تتمكن من استرجاع البيانات لاحقا",
                icon:"warning",
                showCancelButton:!0,
                confirmButtonText:"نعم إحذف البيانات",
                cancelButtonText:"إلغاء",
                confirmButtonClass:"btn btn-success mt-2",
                cancelButtonClass:"btn btn-danger ms-2 mt-2",
                buttonsStyling:!1
            })
            .then(
                function(t){
                    if(t.value) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {_method:'DELETE',_token:'{{csrf_token()}}'},
                            success: function (result) {
                                $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                                    { allow_dismiss: true,type:result.type }
                                );
                                result.type = result.type == 'danger' ? 'error' : result.type;
                                Swal.fire({title: result.title, text: result.msg, icon: result.type});
                                if(result.type == 'success') {
                                    $('#dataTable').DataTable().ajax.reload();
                                    deleteButton.closest('tr').fadeOut(750);
                                    // $('.modal').modal('hide');
                                }
                            }
                        });
                    }else {
                        Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                    }
                }
            );

    }
    function showCourseStudents(course_id) {
        $('.bs-example-modal-xl').modal('toggle');
        $('.user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get('/ShowCourseStudents/'+course_id,function (data) {
            $('.bs-example-modal-xl').modal('toggle');
            $('#user_modal_content').empty().html(data);
        });
    }
</script>