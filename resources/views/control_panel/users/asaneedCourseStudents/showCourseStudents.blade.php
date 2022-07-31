<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> طلاب دورة كتاب - {{ $asaneedCourse->book_name }} - المعلم {{ $asaneedCourse->teacher_name }}</h5>

    @if (hasPermissionHelper('اضافة طالب جديد الأسانيد والإجازات'))
    <button style="margin-right: 15px;" type="button" class="btn btn-info" title="اضافة طالب"  onclick="createNewCourseStudents({{$asaneedCourse->id}})"><i class="mdi mdi-account-plus"></i>اضافة طالب جديد</button>
    @endif

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
@if (hasPermissionHelper('حذف طالب الأسانيد والإجازات'))

            <th>حذف</th>
@endif

        </thead>
        <tbody>
            @foreach($users as $key => $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->id_num }}</td>
                    <td>{{ $user->dob }}</td>
                    <td>{{ $user->pob }}</td>
                    <td>{!! in_array($user->student_category,$asaneedCourse->student_categories) ? '<i class="mdi mdi-checkbox-marked-circle-outline" style="color:green"></i>' : '<i class="mdi mdi-close-circle-outline" style="color:red"></i>' !!}</td>
@if (hasPermissionHelper('حذف طالب الأسانيد والإجازات'))

                    <td>{!! $user->deleteAsaneedCourseStudent($user->id, $asaneedCourse->id) !!}</td>
@endif

                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}

</div>


<script>

function createNewCourseStudents(course_id){
            Swal.fire({
                customClass: 'swal-wide',
                title: 'ادخل رقم الهوية',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'اضافة',
                cancelButtonText: 'الغاء',
                showLoaderOnConfirm: true,
                preConfirm: function(value){
                    return fetch('/asaneedCourseStudents/create/'+value+'/'+course_id)
                        .then(function(response){
                            return response.json();
                        }).then(function(responseJson) {
                            if (responseJson.errors){
                                Swal.showValidationMessage(
                                    responseJson.msg
                                );
                            }else{
                                Swal.close();
                                $('.bs-example-modal-xl').modal('show');
                                $('#user_modal_content').html(responseJson.view);
                            }
                            // Do something with the response
                        })
                        .catch(function (errors) {
                            // Swal.showValidationMessage(
                            //     'لا يوجد اتصال بالشبكة'
                            // )
                        });
                },
                allowOutsideClick: function(){!Swal.isLoading();}
            }).then(function(result){
                // console.log(result);
                if (result.isConfirmed) {
                    // if(result.value.errors == 0) {
                    // $('.bs-example-modal-xl').modal('show');
                    // $('#user_modal_content').html(result.value.view);
                    // }
                }
            })
        }

</script>
