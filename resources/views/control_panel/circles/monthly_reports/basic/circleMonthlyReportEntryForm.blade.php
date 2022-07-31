<style>
    .pointer{
        cursor: pointer;
    }
    .dark-td{
        background-color: rgba(0,0,50,.02)!important;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> تقرير حلقة - {{ $circle->teacher_name }}  - بتاريخ - <span>{{ $circleMonthlyReport ? $circleMonthlyReport->date : ''}} </span></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <table class="table table-responsive table-bordered">
        <tr>
            <td class="dark-td">اسم المحفظ رباعيًا</td>
            <td>{{ $circle->teacher_name }}</td>
            <td class="dark-td">رقم الجوال</td>
            <td>{{ $circle->teacher_mobile }}</td>
            <td class="dark-td">التاريخ</td>
            <td>{{ $circleMonthlyReport->date }}</td>
        </tr>
        <tr>
            <td class="dark-td">المسجد</td>
            <td>{{ $circle->place_name }}</td>
            <td class="dark-td">الفرع</td>
            <td>{{ $circle->sub_area_name }}</td>
            <td class="dark-td">المشرف العام</td>
            <td>{{ areaSupervisor($circle->area_father_id) }}</td>
        </tr>
    </table>

    <table class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th rowspan="3">م</th>
                <th rowspan="3">الاسم رباعيا</th>
                <th rowspan="3">اسم الكتاب</th>
                <th colspan="4">رقم الحديث</th>
                <th colspan="2">الفئة</th>
                <th rowspan="3">مجموع الحفظ الحالي</th>
                <th rowspan="3">نسبة إنجاز خطة الطالب %</th>
            </tr>
            <tr>
                <th colspan="2">الحفظ السابق</th>
                <th colspan="2">الحفظ الحالي</th>
                <th rowspan="2">السابقة</th>
                <th rowspan="2">الحالية</th>
            </tr>
            <tr>
                <th>من</th>
                <th>الى</th>
                <th>من</th>
                <th>الى</th>
            </tr>
        </thead>
        <tbody>
            @foreach($circleMonthlyReport->circleMonthlyReportStudents as $key => $circleMonthlyReportStudent)
                {!! $circleMonthlyReportStudent->row !!}
            @endforeach
        </tbody>
    </table>

</div>

<div class="modal-footer">

    <button type="button" class="btn btn-secondary waves-effect" onclick="getCircleMonthlyReports()">رجوع</button>
    <button type="button" class="btn btn-primary waves-effect waves-light make-report-delivered"  onclick="makeReportDelivered({{$circleMonthlyReport->id}})">حفظ</button>
</div>

<script>
// $( document ).ready(function() {
//     $('button').on('click', ".make-report-delivered", function() {





// });
// });





    function makeReportDelivered($report_id){


    Swal.fire(
        {
            title:"هل أنت متأكد من تسليم التقرير",
            text:"لن تتمكن من تعديل البيانات لاحقاً",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonText:"نعم تسليم التقرير",
            cancelButtonText:"إلغاء",
            confirmButtonClass:"btn btn-success mt-2",
            cancelButtonClass:"btn btn-danger ms-2 mt-2",
            buttonsStyling:!1
        })
        .then(
            function(t){
                if(t.value) {
                    if(typeof $report_id !='undefined') {

                        $.get('makeReportDelivered/' + $report_id , function (data) {
                            if(data.type == 'danger') {
                                Swal.fire('خطأ !',data.msg,'error');
                            }else {
                                $(this).parent().parent().remove();
                                getCircleMonthlyReports();
                            }
                        });
                    }else{
                        $(this).parent().parent().remove();
                    }
                    Swal.fire({title: "تم تسليم التقرير", text: "التقرير تم تسليمه", icon: "success"});
                }else {
                    Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                }
            }
        );

        // $('#user_modal_content')
        //     .html(
        //         '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
        //         '   <span class="sr-only">يرجى الانتظار ...</span>' +
        //         '</div>'
        //     );
        // $.get('{{ route('circleMonthlyReports.getCircleMonthlyReports',$circle->id) }}',function(data){
        //     // $('.bs-example-modal-xl').modal('show');
        //     $('#user_modal_content').html(data);
        //     $('#dataTable').DataTable().ajax.reload();
        // });
    }


    function getCircleMonthlyReports() {
        // $('.bs-example-modal-xl').modal('hide');
        $('#user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get('{{ route('circleMonthlyReports.getCircleMonthlyReports',$circle->id) }}',function(data){
            // $('.bs-example-modal-xl').modal('show');
            $('#user_modal_content').html(data);
            $('#dataTable').DataTable().ajax.reload();
        });
    }
    function changeCurrentToValue(obj,report_student_id) {

        var row = $(obj).closest('tr');
        var current_storage = row.find('.current_storage');
        var current_category = row.find('.current_category');
        var current_percentage = row.find('.current_percentage');
        $.get('/changeCurrentToValue/'+obj.value+'/'+report_student_id,function(data){
            current_storage.empty().html(data[1]);
            current_category.empty().html(data[0]);
            current_percentage.empty().html(data[2]);
        });

    }
</script>
