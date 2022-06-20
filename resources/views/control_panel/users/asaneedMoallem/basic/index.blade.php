@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | مشايخ الاسناد </title>
@endsection
@section('content')

<style>


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
            margin-top: -40px;
        }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">مشايخ الاسانيد والإجازات</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">مشايخ الاسانيد والإجازات</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->


@if (hasPermissionHelper('فلترة بيانات شيوخ الاسانيد'))

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
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
                    <input type="text" class="form-control" name="id_num" id="id_num" placeholder="رقم الهوية"/>
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
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                {{--<h4 class="card-title mb-4" style="display: inline-block;">مشايخ الاسناد</h4>--}}
                <div class="row mb-3">

                    @if (hasPermissionHelper('إضافة شيخ جديد'))

                    <div class="col-md-2">
                        <button class="btn btn-primary create-new-user" onclick="createNewmoallem()" style="width:207px;">
                            <i class="mdi mdi-plus"></i>
                            اضافة شيخ جديد
                        </button>
                    </div>
                    @endif

                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">اسم الشيخ</th>
                                <th scope="col">رقم الهوية</th>
                                <th scope="col">رقم الجوال</th>


                                <th scope="col">المشرف العام</th>
                                <th scope="col">المشرف الميداني</th>
                                <th scope="col">المنطقة الكبرى</th>
                                <th scope="col">المنطقة المحلية</th>
                                <th scope="col">عدد المجالس </th>
                                {{--<th scope="col">عدد الدورات القائمة</th>--}}
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
                "ajax": "{{ route('asaneedMoallem.getData') }}",
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
                "columnDefs": [
                    { "sortable": false, "targets": [7] }
                    // { "sortable": false, "targets": [3] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "id_num" },
                    { "mData": "mobile" },

                    { "mData": "area_supervisor" },
                    { "mData": "sub_area_supervisor" },
                    { "mData": "area_father_name" },
                    { "mData": "area_name" },
                    { "mData": "CoursesCount" },
                    // { "mData": "runningCoursesCount" },
                    { "mData": "tools" }
                    // { "mData": "select" }
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
        function createNewmoallem(){
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
                    return fetch('/asaneedMoallem/create/'+value)
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
                                // console.log(responseJson.teachers_roles_select);
                                if (responseJson.teachers_roles_select) {

                                    Swal.fire({
                                        title: '<strong>ادخل صلاحيات المستخدم</strong>',
                                        icon: 'info',
                                        html:responseJson.view,
                                        showCloseButton: true,
                                        focusConfirm: false,
                                        confirmButtonAriaLabel: '',
                                        showCancelButton: true,
                                        confirmButtonText: 'اضافة',
                                        cancelButtonText: 'الغاء',
                                        showLoaderOnConfirm: true,
                                        preConfirm: function(value){
                                            var checkedValues = [];
                                            $("input:checkbox[name='userSelectRoles[]']:checked").each(function(){
                                                checkedValues.push($(this).val());
                                            });
                                            // console.log(checkedValues);
                                            return fetch('/updateAsaneedUserRoles/'+responseJson.user_id+'/'+JSON.stringify(checkedValues))
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
                                                        Swal.fire(responseJson.msg,responseJson.title,responseJson.type);
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
                                    });
                                }else {
                                    $('.bs-example-modal-xl').modal('show');
                                    $('#user_modal_content').html(responseJson.view);
                                }
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
                "/getAsaneedMoallemData?sub_area_id="+$('#sub_areas_select').val()+'&area_id='+$('#areas_select').val()
                +'&id_num='+$('#id_num').val()
            ).load();
        }
        function updateRoles(obj,user_id) {
            obj.classList.add('loading');
            obj.disabled= true;
            $.get('/getAsaneedUserUpdateRolesSelect/'+user_id,function (data) {
                obj.classList.remove('loading');
                obj.disabled= false;
                Swal.fire({
                    title: '<strong>ادخل صلاحيات المستخدم</strong>',
                    icon: 'info',
                    html:data,
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonAriaLabel: '',
                    showCancelButton: true,
                    confirmButtonText: 'اضافة',
                    cancelButtonText: 'الغاء',
                    showLoaderOnConfirm: true,
                    preConfirm: function(value){
                        var checkedValues = [];
                        $("input:checkbox[name='userSelectRoles[]']:checked").each(function(){
                            checkedValues.push($(this).val());
                        });
                        // console.log(checkedValues);
                        return fetch('/updateAsaneedUserRoles/'+user_id+'/'+JSON.stringify(checkedValues))
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
                                    Swal.fire(responseJson.msg,responseJson.title,responseJson.type);
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
                });
            });
        }
    </script>

@endsection
