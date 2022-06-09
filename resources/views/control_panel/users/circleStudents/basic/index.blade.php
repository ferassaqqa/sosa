@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | طلاب حلقات التحفيظ </title>
@endsection
@section('content')
<style>

    .static .table_header {
            color: white;
            background-color: #00937C;
            font-weight: 600;
            font-size: 16px;
        }

        .static td {
            border: 1px solid #e8eaeb !important;
        }

        .static tr {
            background-color: #f1f1f3;
        }

        .static .value {
            font-weight: 600;
            font-size: 16px;
            border: 1px solid #dadcdd;
        }
        .white_space {
            white-space: break-spaces !important;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            width: 100%;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left;
        }

        div.dataTables_filter,
        div.dataTables_length {
            margin-left: 1em;
        }

        div.dataTables_wrapper div.dataTables_processing {
            top: 5%;
        }
        .dataTables_wrapper {
            margin-top: -35px;
        }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">طلاب حلقات تحفيظ السنة النبوية</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">طلاب حلقات التحفيظ</li>
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
                    <table width="100%" class="table table-centered table_bordered static" dir="rtl">
                        <tbody>
                            <tr class="table_header">
                                <td colspan="2">عدد الطلاب الكلي <span id="total_circlestudents_count"></span>
                                </td>
                            </tr>

                            <tr>
                               <td>عدد الطلاب المكفولين</td>
                               <td>عدد الطلاب المتطوعين</td>

                            </tr>

                            <tr class="value">
                                <td id="total_circlestudents_makfool"></td>
                                <td id="total_circlestudents_volunteer"></td>
                            </tr>



                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                {{--<h4 class="card-title mb-4" style="display: inline-block;">طلاب حلقات التحفيظ</h4>--}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <button class="btn btn-primary create-new-user" onclick="createNewCircleStudents()" style="width: 306px;">
                            <i class="mdi mdi-plus"></i>
                            اضافة طالب جديد
                        </button>
                    </div>
                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">اسم الطالب رباعيا</th>
                                <th scope="col">رقم الهوية</th>
                                <th scope="col">اسم المحفظ</th>
                                <th scope="col">نوع الحلقة</th>

                                <th scope="col">الكتاب</th>

                                <th scope="col">المنطقة الكبرى</th>
                                <th scope="col">المنطقة المحلية</th>
                                <th scope="col">المشرف العام</th>


                                <th scope="col">عدد الاحاديث</th>

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
        $(document).ready(function() {
            var table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('circleStudents.getData') }}",
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
                "drawCallback": function() {
                    $('#total_circlestudents_count').empty().html(table.data().context[0].json['total_circlestudents_count']);
                    $('#total_circlestudents_makfool').empty().html(table.data().context[0].json['total_circlestudents_makfool']);
                    $('#total_circlestudents_volunteer').empty().html(table.data().context[0].json['total_circlestudents_volunteer']);
                },
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "id_num" },
                    { "mData": "teacher_name" },
                    { "mData": "contract_type" },
                    { "mData": "books" },

                    { "mData": "area_father_name" },
                    { "mData": "area_name" },

                    { "mData": "area_supervisor" },


                    { "mData": "hadith_count" },
                    { "mData": "tools" }
                ]
            } );
            table.on( 'draw', function () {
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
        function createNewCircleStudents(){
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
                    return fetch('/circleStudents/create/'+value)
                        .then(function(response){
                            return response.json();
                        }).then(function(responseJson) {
                            // console.log(responseJson);
                            if (responseJson.errors){
                                Swal.showValidationMessage(
                                    responseJson.msg
                                );
                                // throw new Error('Something went wrong')
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
        function changeHadithCount(obj){
            var tr = $(obj).closest('tr');
            $(tr.find('td')[4]).empty().html(obj.value);
        }
    </script>

@endsection
