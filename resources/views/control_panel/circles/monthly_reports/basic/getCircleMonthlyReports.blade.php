<link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
<style>
    .swal2-actions{
        z-index: 0 !important;
    }

    .table_label {
        color: #343a40;
        /* font-weight: 600; */
        background-color: #ced4da !important;
    }

    .dataTables_length , .dataTables_info , .dataTables_paginate  { display: none; }

</style>

<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">  التقارير الشهرية لحلقة {{ $circle->teacher_name }}  </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">


                <table border="1" width="100%" class="ui compact selectable striped celled table data-table nomargin" dir="rtl" style="margin-top: 0px;" data-time="">
                    <tbody>
                        <tr>
                            <td class="dark-th normal-bg table_label">اسم المحفظ</td>
                            <td class="white-bg print-white" style="background-color: #fff;">{{ $circle->teacher_name }}</td>

                            <td class="dark-th normal-bg table_label">رقم الهوية</td>
                            <td class="white-bg print-white" style="background-color: #fff;">{{ $teacher->id_num }}</td>

                            <td class="dark-th normal-bg table_label">رقم الجوال</td>
                            <td class="white-bg print-white" style="background-color: #fff;">{{ $circle->teacher_mobile }}</td>

                            <td class="dark-th normal-bg table_label">نوع الحلقة</td>
                            <td class="white-bg print-white" style="background-color: #fff;">{{ $circle->teacher->userExtraData->contract_type }}</td>
                        </tr>

                        <tr>

                            <td class="dark-th normal-bg table_label">عدد الطلاب</td>
                            <td class="white-bg print-white" style="background-color: #fff;">{{ $circle->teacher->students->count() }}</td>

                            <td class="dark-th normal-bg table_label">المسجد</td>
                            <td class="white-bg print-white" style="background-color: #fff;">{{ $circle->place_name }}</td>

                            <td class="dark-th normal-bg table_label">المنطقة الكبرى والمحلية</td>
                            <td class="white-bg print-white" style="background-color: #fff;">{{ $circle->place->area_father_name}} - {{ $circle->sub_area_name }}</td>

                            <td class="dark-th normal-bg table_label">المشرف العام</td>
                            <td class="white-bg print-white" style="background-color: #fff;">{{ areaSupervisor($circle->area_father_id) }}</td>
                        </tr>


                    </tbody>
                </table>





                {{--<h4 class="card-title mb-4" style="display: inline-block;"> </h4>--}}
                <div class="dropdown d-inline-block user-dropdown mb-3">
                    <button class="btn btn-primary"  onclick="createCircleMonthlyReports({{$circle->id}})">
                        <i class="mdi mdi-plus"></i>
                        اضافة
                    </button>
                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable1">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">التاريخ</th>
                                <th scope="col">حالة التسليم</th>
                                <th scope="col">التسليم بواسطة</th>
                                <th scope="col">حالة الاعتماد</th>
                                <th scope="col">معتمد بواسطة</th>


                                <th scope="col">أدوات</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($circleMonthlyReports as $key => $report)



                        <tr>
                        <td>{{$key+1}}</td>
                        <td> <a href="#!" data-url="{{ route('circleMonthlyReports.updateCircleMonthlyReports',$report->id) }}" onclick="showReport(this)"> {{  $report->date}} </a></td>
                        <td>
                            {!!  ($report->is_delivered)? '<i  style="color:green; font-size: 25px;" class="mdi mdi-check-bold "></i>' : '<i style="color:red" class="mdi mdi-block-helper "></i>' !!}
                        </td>
                        <td>
                            {{ ($report->is_delivered)?$report->delivered->name.' '.$report->delivered_at:'' }}
                        </td>
                        <td>
                            {!! ($report->is_approved)?'<i  style="color:green;  font-size: 25px;" class="mdi mdi-check-all "></i>' : '<i style="color:red" class="mdi mdi-block-helper "></i>' !!}
                        </td>
                        <td>
                            {{ ($report->is_approved)?$report->approved->name.' '.$report->approved_at:'' }}
                        </td>


                        <td>
                            <button type="button" class="btn btn-danger" data-url="{{route('circleMonthlyReports.deleteCircleMonthlyReport',$report->id)}}" onclick="deleteCircleMonthlyReport(this)"><i class="mdi mdi-trash-can"></i></button>
                            <button type="button" class="btn btn-primary" data-url="{{route('circleMonthlyReports.makeReportApproved',$report->id)}}" onclick="approveCircleMonthlyReport(this)"><i class="mdi mdi-check-decagram"></i></button>

                        </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
</div>
<script src="{{asset('control_panel/assets/js/pages/form-advanced.init.js')}}"></script>

<script>

function createCircleMonthlyReports($circle_id) {


    // Swal.fire(
    //     {
    //         title:"هل أنت متأكد من تسليم التقرير",
    //         text:"لن تتمكن من تعديل البيانات لاحقاً",
    //         icon:"warning",
    //         showCancelButton:!0,
    //         confirmButtonText:"نعم تسليم التقرير",
    //         cancelButtonText:"إلغاء",
    //         confirmButtonClass:"btn btn-success mt-2",
    //         cancelButtonClass:"btn btn-danger ms-2 mt-2",
    //         buttonsStyling:!1
    //     })
    //     .then(
    //         function(t){
    //             if(t.value) {
    //                 if(typeof $circle_id !='undefined') {

                        $.get('createCircleMonthlyReports/' + $circle_id , function (data) {
                            // if(data.type == 'danger') {
                            //     Swal.fire('خطأ !',data.msg,'error');
                            // }else {
                            //     $(this).parent().parent().remove();
                            //     getCircleMonthlyReports();
                            // }
                        });
                //     }else{
                //         $(this).parent().parent().remove();
                //     }
                //     Swal.fire({title: "تم تسليم التقرير", text: "التقرير تم تسليمه", icon: "success"});
                // }else {
                //     Swal.fire({title: "لم تتم العملية!", text: " ", icon: "error"});
                // }
            // }
        // );

}

function approveCircleMonthlyReport  (obj){
        var url = obj.getAttribute('data-url');
        // console.log(url);
        Swal.fire(
            {
                title:"هل انت متأكد",
                text:"لن تتمكن من استرجاع البيانات لاحقا، سيتم إعتماد التقرير",
                icon:"warning",
                showCancelButton:!0,
                confirmButtonText:"نعم إعتمد البيانات",
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
                            type: 'get',
                            // data: {_method:'POST',_token:'{{csrf_token()}}'},
                            success: function (result) {
                                $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                                    { allow_dismiss: true,type:result.type }
                                );
                                Swal.fire({title: "تم الإعتماد!", text: "تم إعتماد التقرير بنجاح.", icon: "success"});
                                if(result.type == 'success') {
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
                            }
                        });
                    }else {
                        Swal.fire({title: "لم يتم الاعتماد!", text: "البيانات لم تعتمد.", icon: "error"});
                    }
                }
            );

    }



    $(document).ready(function() {
        // var table1 = $('#dataTable1').DataTable( {
        //     "processing": true,
        //     "serverSide": true,
        //     // "bPaginate": false,
        //     "bFilter": false,
        //     // "bInfo": false,
        //     "ajax": "{{ route('circleMonthlyReports.getCircleMonthlyReportsData',$circle->id) }}",

        //     "aoColumns": [
        //         { "mData": "id" },
        //         { "mData": "date" },
        //         { "mData": "date" },
        //         { "mData": "date" },
        //         { "mData": "tools" }
        //     ]
        // } );

    });
    // function createCircleMonthlyReports() {
    //     @if($teacher->current_circle)
    //         Swal.fire({
    //             customClass: 'swal-wide',
    //             title: 'ادخل شهر التقرير الشهري',
    //             html: '<div class="input-group" id="datepicker1">'+
    //             '                    <input type="text" class="form-control" placeholder="تاريخ التقرير"'+
    //             '                           name="date" id="date"'+
    //             '                           data-date-format="dd-mm-yyyy" data-date-container="#datepicker1" data-provide="datepicker"'+
    //             '                           data-date-autoclose="true">'+
    //             '                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>'+
    //             '                </div>',
    //             inputPlaceholder: 'ادخل شهر التقرير الشهري',
    //             showCancelButton:true,
    //             showCloseButton:true,
    //             confirmButtonText: 'اضافة',
    //             cancelButtonText: 'الغاء',
    //             showLoaderOnConfirm: true,
    //             preConfirm: function(value){
    //                 var date = document.getElementById('date');
    //                 if(date && date.value != '') {
    //                     $('.bs-example-modal-xl').modal('hide');
    //                     $('.user_modal_content')
    //                         .html(
    //                             '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
    //                             '   <span class="sr-only">يرجى الانتظار ...</span>' +
    //                             '</div>'
    //                         );
    //                     return fetch('/createCircleMonthlyReports/{{$circle->id}}/' + date.value)
    //                         .then(function (response) {
    //                             return response.json();
    //                         }).then(function (responseJson) {
    //                             // console.log(responseJson);
    //                             if (responseJson.errors) {
    //                                 Swal.showValidationMessage(
    //                                     responseJson.msg
    //                                 );
    //                                 // throw new Error('Something went wrong')
    //                             } else {
    //                                 Swal.close();
    //                                 // console.log(responseJson,responseJson.view);
    //                                 $('.bs-example-modal-xl').modal('show');
    //                                 $('#user_modal_content').html(responseJson.view);
    //                             }
    //                             // Do something with the response
    //                         })
    //                         .catch(function (errors) {
    //                             // Swal.showValidationMessage(
    //                             //     'لا يوجد اتصال بالشبكة'
    //                             // )
    //                         });
    //                 }else{
    //                     Swal.showValidationMessage(
    //                         'ادخل تاريخ شهر التقرير'
    //                     );
    //                 }
    //             },
    //             allowOutsideClick: function(){!Swal.isLoading();}
    //         }).then(function(result){
    //             // console.log(result);
    //             if (result.isConfirmed) {
    //                 // if(result.value.errors == 0) {
    //                 // $('.bs-example-modal-xl').modal('show');
    //                 // $('#user_modal_content').html(result.value.view);
    //                 // }
    //             }
    //         });
    //     @else
    //         Swal.fire('عذرا ! لا يوجد حلقات قائمة للمحفظ .','لا يوجد حلقات','error');
    //     @endif
    // }
    function deleteCircleMonthlyReport (obj){
        var url = obj.getAttribute('data-url');
        // console.log(url);
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
                                Swal.fire({title: "تم الحذف!", text: "تم حذف الملف بنجاح.", icon: "success"});
                                if(result.type == 'success') {
                                    $('#dataTable1').DataTable().ajax.reload();
                                    $('#dataTable').DataTable().ajax.reload();
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
    function showReport(obj) {
        var link = $(obj).data('url');
        $('#user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get(link,function(data){
            $('#user_modal_content').empty().html(data);
            // console.log(data);
        });
    }

</script>

