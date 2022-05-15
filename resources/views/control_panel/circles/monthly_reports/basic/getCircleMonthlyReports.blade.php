<link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
<style>
    .swal2-actions{
        z-index: 0 !important;
    }
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
                {{--<h4 class="card-title mb-4" style="display: inline-block;"> </h4>--}}
                <div class="dropdown d-inline-block user-dropdown mb-3">
                    <button class="btn btn-primary"  onclick="createCircleMonthlyReports()">
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
                                <th scope="col">أدوات</th>
                            </tr>
                        </thead>
                        <tbody>
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
    $(document).ready(function() {
        var table1 = $('#dataTable1').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('circleMonthlyReports.getCircleMonthlyReportsData',$circle->id) }}",
            language: {
                search: "بحث",
                processing:     "جاري معالجة البيانات" ,
                lengthMenu:    "عدد _MENU_ الصفوف",
                info:           "من _START_ الى _END_ من أصل _TOTAL_ صفحة",
                infoEmpty: "لا يوجد بيانات",
                loadingRecords: "يتم تحميل البيانات",
                zeroRecords:    "<p style='text-align: center'>لا يوجد بيانات</p>",
                emptyTable:     "<p style='text-align: center'>لا يوجد بيانات</p>",
                paginate: {
                    first:      "الأول",
                    previous:   "السابق",
                    next:       "التالي",
                    last:       "الأخير"
                },
                aria: {
                    sortAscending:  ": ترتيب تصاعدي",
                    sortDescending: ": ترتيب تنازلي"
                }
            },
            "columnDefs": [
                { "sortable": false, "targets": [2] }
            ],
            "aoColumns": [
                { "mData": "id" },
                { "mData": "date" },
                { "mData": "tools" }
            ]
        } );
        table1.on( 'draw', function () {
            var elements = $('.ellipsis').nextAll();
            if(elements.length == 1){
                var elements = $('.ellipsis').prevAll();
                elements[1].before(elements[4]);
                elements[3].after(elements[0]);
                elements[2].after(elements[1]);
                elements[2].before(elements[3]);
            }else if(elements.length == 5){
                elements[1].before(elements[4]);
                elements[3].after(elements[0]);
                elements[2].after(elements[1]);
                elements[2].before(elements[3]);
            }else{
                // var paginate_buttons = $('.paginate_button');
                // if(paginate_buttons.length > 3) {
                //     paginate_buttons.css('cursor', 'pointer');
                //     paginate_buttons[5].after(paginate_buttons[4]);
                //     paginate_buttons[4].after(paginate_buttons[3]);
                //     paginate_buttons[3].after(paginate_buttons[2]);
                //     paginate_buttons[2].after(paginate_buttons[1]);
                // }

            }
        } );
    });
    function createCircleMonthlyReports() {
        @if($teacher->current_circle)
            Swal.fire({
                customClass: 'swal-wide',
                title: 'ادخل شهر التقرير الشهري',
                html: '<div class="input-group" id="datepicker1">'+
                '                    <input type="text" class="form-control" placeholder="تاريخ التقرير"'+
                '                           name="date" id="date"'+
                '                           data-date-format="dd-mm-yyyy" data-date-container="#datepicker1" data-provide="datepicker"'+
                '                           data-date-autoclose="true">'+
                '                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>'+
                '                </div>',
                inputPlaceholder: 'ادخل شهر التقرير الشهري',
                showCancelButton:true,
                showCloseButton:true,
                confirmButtonText: 'اضافة',
                cancelButtonText: 'الغاء',
                showLoaderOnConfirm: true,
                preConfirm: function(value){
                    var date = document.getElementById('date');
                    if(date && date.value != '') {
                        $('.bs-example-modal-xl').modal('hide');
                        $('.user_modal_content')
                            .html(
                                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                                '</div>'
                            );
                        return fetch('/createCircleMonthlyReports/{{$circle->id}}/' + date.value)
                            .then(function (response) {
                                return response.json();
                            }).then(function (responseJson) {
                                // console.log(responseJson);
                                if (responseJson.errors) {
                                    Swal.showValidationMessage(
                                        responseJson.msg
                                    );
                                    // throw new Error('Something went wrong')
                                } else {
                                    Swal.close();
                                    // console.log(responseJson,responseJson.view);
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
                    }else{
                        Swal.showValidationMessage(
                            'ادخل تاريخ شهر التقرير'
                        );
                    }
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
            });
        @else
            Swal.fire('عذرا ! لا يوجد حلقات قائمة للمحفظ .','لا يوجد حلقات','error');
        @endif
    }
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

