@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | تسجيلات احداث المستخدمين </title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">تسجيلات احداث المستخدمين</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">تسجيلات احداث المستخدمين</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-control" onchange="changeStatus(this)" id="changeStatus">
                            <option value="0">اختر نوع الحدث</option>
                            <option value="created">إضافة</option>
                            <option value="updated">تعديل</option>
                            <option value="deleted">حذف</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                            <input type="text" class="form-control" name="start_date" id="start_date" onchange="updateDateTable()" placeholder="تاريخ البداية" />
                            <input type="text" class="form-control" name="end_date" id="end_date" onchange="updateDateTable()" placeholder="تاريخ النهاية" />
                        </div>
                    </div>
                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">الحدث</th>
                                <th scope="col">المستخدم</th>
                                <th scope="col">نوع العملية</th>
                                <th scope="col">التفاصيل</th>
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

@endsection

@section('script')

    <script>
        var table = '';
        $(document).ready(function(){
            table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('activities.getData') }}",
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
                    { "sortable": false, "targets": [4] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "description" },
                    { "mData": "user" },
                    { "mData": "log_name" },
                    { "mData": "model" },
                    { "mData": "date" },
                    // { "mData": "status" },
                    { "mData": "tools" }
                ]
            } );
        });
        function UndoAction (obj,title,text,button){
            var url = obj.getAttribute('data-url');
            // console.log(url);
            Swal.fire(
                {
                    title:title,
                    text:text,
                    icon:"warning",
                    showCancelButton:!0,
                    confirmButtonText:button,
                    cancelButtonText:"إلغاء",
                    confirmButtonClass:"btn btn-success mt-2",
                    cancelButtonClass:"btn btn-danger ms-2 mt-2",
                    buttonsStyling:!1
                })
                .then(
                    function(t){
                        if(t.value) {
                            $.get(url,function (data) {
                                data.type = data.type == 'success' ? data.type : "error";
                                Swal.fire({title:data.title, text: data.msg, icon: data.type});
                                $('#dataTable').DataTable().ajax.reload();
                            });
                        }else {
                            Swal.fire({title:data.title, text: data.msg, icon: data.type});
                        }
                    }
                );

        }

        function changeStatus(obj) {
            updateDateTable();
        }

        function updateDateTable() {
            table.ajax.url(
                "/activitiesData?status="+$('#changeStatus').val()+'&start_date='+$('#start_date').val()+'&end_date='+$('#end_date').val()
            ).load();
        }
    </script>

@endsection
