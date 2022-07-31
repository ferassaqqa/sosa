@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | محفظو الحلقات </title>
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
            <h4 class="mb-sm-0">محفظو الحلقات</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">محفظو الحلقات</li>
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
                                <td colspan="2">عدد المحفظين الكلي <span id="total_mohafez_count"></span>
                                </td>
                                <td colspan="2">عدد الحلقات الكلي <span id="total_circle_count"></span>
                                </td>
                                <td colspan="2">عدد الطلاب الكلي <span id="total_circlestudents_count"></span>
                                </td>
                            </tr>

                            <tr>
                               <td>عدد المحفظين المكفولين</td>
                               <td>عدد المحفظين المتطوعين</td>

                               <td>عدد الحلقات المكفولة</td>
                               <td>عدد الحلقات المتطوعة </td>

                               <td>عدد الطلاب المكفولين</td>
                               <td>عدد الطلاب المتطوعين</td>

                            </tr>

                            <tr class="value">

                                <td id="mohafez_makfool"></td>
                                <td id="mohafez_volunteer"></td>

                                <td id="circle_makfool"></td>
                                <td id="circle_volunteer"></td>

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
                <div class="row mb-3">

                <div class="col-md-3" >
                    <select class="form-control" onchange="getSubAreas(this)" id="areas_select">
                        <option value="0">اختر المنطقة الكبرى</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="sub_areas_select">
                        <option value="0">اختر المنطقة المحلية</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control" id="circle_type_select">
                        <option value="">نوع الحلقة</option>

                        <option value="مكفول">مكفول</option>
                        <option value="متطوع">متطوع</option>

                    </select>
                </div>

                <div class="col-md-3">
                    <button type="button" style="width:100%" onclick="updateDateTable()" class="btn btn-primary btn-block">
                        <i class="mdi mdi-magnify" aria-hidden="true"></i>
                        ابحث
                    </button>
                </div>

                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-2">
                    <button class="btn btn-primary  create-new-user btn-block" onclick="createNewMohafez()" style="width: 307px;">
                        <i class="mdi mdi-plus"></i>
                        اضافة محفظ جديد
                    </button>
                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">إسم المحفظ رباعيًا</th>
                                <th scope="col">رقم الهوية</th>
                                <th scope="col">رقم الجوال</th>
                                <th scope="col">نوع الحلقة</th>



                                <th scope="col">عدد الطلاب</th>
                                <th scope="col">المنطقة الكبرى</th>
                                <th scope="col">المنطقة المحلية</th>

                                <th scope="col">المشرف العام</th>
                                <th scope="col">المشرف الميداني</th>



                                <th scope="col">التقارير</th>
                                <th scope="col">أدوات</th>
                                {{--<th scope="col">--}}
                                    {{--<div class="form-check mb-2">--}}
                                        {{--<input class="form-check-input check-all"  type="checkbox">--}}
                                    {{--</div>--}}
                                {{--</th>--}}
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
            $(document).ready(function() {
            table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('mohafez.getData') }}",
                language: {
                    search: "",
                    searchPlaceholder: "بحث سريع",
                    processing: "<span style='background-color: #0a9e87;color: #fff;padding: 25px;'>انتظر من فضلك ، جار جلب البيانات ...</span>",
                    lengthMenu: " _MENU_ ",
                    info: "من _START_ الى _END_ من أصل _TOTAL_ صفحة",
                    infoEmpty: "لا يوجد بيانات",
                    loadingRecords: "يتم تحميل البيانات",
                    zeroRecords: "<p style='text-align: center'>لا يوجد بيانات</p>",
                    emptyTable: "<p style='text-align: center'>لا يوجد بيانات</p>",
                    paginate: {
                        first: "الأول",
                        previous: "السابق",
                        next: "التالي",
                        last: "الأخير"
                    },
                    aria: {
                        sortAscending: ": ترتيب تصاعدي",
                        sortDescending: ": ترتيب تنازلي"
                    }
                },
                   "columnDefs": [{
                    className: "white_space",
                    "sortable": false,
                    "targets": [1,2,3,4,5]
                }],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "id_num" },
                    { "mData": "mobile" },
                    { "mData": "contract_type" },

                    { "mData": "studentCount" },
                    { "mData": "area_father_name" },
                    { "mData": "area_name" },


                    { "mData": "area_supervisor" },
                    { "mData": "sub_area_supervisor" },


                    { "mData": "circleReports" },
                    { "mData": "tools" }
                    // { "mData": "select" }
                ],
                "drawCallback": function() {
                    $('#total_mohafez_count').empty().html(table.data().context[0].json['total_mohafez_count']);
                    $('#mohafez_makfool').empty().html(table.data().context[0].json['mohafez_makfool']);
                    $('#mohafez_volunteer').empty().html(table.data().context[0].json['mohafez_volunteer']);

                    $('#total_circle_count').empty().html(table.data().context[0].json['total_circle_count']);
                    $('#circle_makfool').empty().html(table.data().context[0].json['circle_makfool']);
                    $('#circle_volunteer').empty().html(table.data().context[0].json['circle_volunteer']);

                    $('#total_circlestudents_count').empty().html(table.data().context[0].json['total_circlestudents_count']);
                    $('#total_circlestudents_makfool').empty().html(table.data().context[0].json['total_circlestudents_makfool']);
                    $('#total_circlestudents_volunteer').empty().html(table.data().context[0].json['total_circlestudents_volunteer']);
                },
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
        function createNewMohafez(){
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
                    return fetch('/mohafez/create/'+value)
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
        function getSubAreas(obj) {
            if(obj.value != 0) {
                $.get('/getSubAreas/'+obj.value, function (data) {
                    $('#sub_areas_select').empty().html(data);
                });
                // updateDateTable();
            }else{
                $('#sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                // updateDateTable();
            }
        }
        function updateDateTable() {
            table.ajax.url(
                "/getMohafezData?sub_area_id="+$('#sub_areas_select').val()+'&area_id='+$('#areas_select').val()+ '&circle_type=' + $('#circle_type_select').val()
            ).load();
        }
        function letEnterLateReports(user_id) {
            Swal.fire({
                customClass: 'swal-wide',
                title: 'اخر تاريخ لادخال التقارير الشهرية',
                html :
                '<div class="input-group" id="datepicker1">' +
                '   <input type="text" class="form-control"' +
                '       data-date-format="yyyy-mm-dd" data-date-container="#datepicker1" data-provide="datepicker"'+
                '       data-date-autoclose="true" id="reportEntranceDateLimit">'+
                '   <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>'+
                '</div>',
                showCancelButton: true,
                confirmButtonText: 'اضافة',
                cancelButtonText: 'الغاء'
            }).then(function(result){
                // console.log(result);
                if (result.isConfirmed) {
                    let reportEntranceDateLimit = $('#reportEntranceDateLimit').val();
                    $.get('/letEnterLateReports/'+user_id+'/'+reportEntranceDateLimit,function (data) {
                        Swal.fire(data.title,data.msg,data.type);
                    });
                    // if(result.value.errors == 0) {
                    // $('.bs-example-modal-xl').modal('show');
                    // $('#user_modal_content').html(result.value.view);
                    // }
                }
            })
        }
    </script>

@endsection
