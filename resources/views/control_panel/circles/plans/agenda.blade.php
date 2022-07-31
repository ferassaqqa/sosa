
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> أجندة الخطة السنوية - تحفيظ السنة النبوية - للعام {{ $year }}  </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <button type="button" class="btn btn-primary" onclick="addNewSemester()" style="width: 160px;">اضافة فصل جديد</button>
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center">
                <thead>
                    <tr>
                        <td>الفصل</td>
                        <td>الاشهر</td>
                        <td>شهر الاختبار</td>
                        <td>حذف</td>
                    </tr>
                </thead>
                <tbody id="agendaTBody">
                    @foreach($agendas as $key => $agenda)
                        <tr>
                            <td>{{ $agenda->semester }}</td>
                            <td>{{ $agenda->months_string }}</td>
                            <td>{{ $agenda->exam_month }}</td>
                            <td><button type="button" class="btn btn-danger" onclick="deleteSemester('{{ $agenda->id }}')"><i class="mdi mdi-trash-can"></i></button> </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function addNewSemester() {
        $('#user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get('/getAddNewCirclePlanAgendaSemester/{{ $year }}',function(data){
             $('#user_modal_content').empty().html(data);
            // console.log(data);
        });
    }
    function deleteSemester(agenda_id) {

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
                function(t) {
                    if (t.value) {
                        $.get('/deleteCirclePlanAgendaSemester/'+agenda_id,function(data){
                            if(!data.errors){
                                $('#user_modal_content')
                                    .html(
                                        '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                                        '   <span class="sr-only">يرجى الانتظار ...</span>' +
                                        '</div>'
                                    );
                                $.get('{{ route('circlePlans.agenda',['year'=>$year]) }}',function(data){
                                    $('#user_modal_content').empty().html(data);
                                    // console.log(data);
                                });
                            }
                            // console.log(data);
                        });
                    }else {
                        Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                    }
                }
            );
    }
</script>


