@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | معلموا الدورات </title>
@endsection
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"> <span style="font-weight: 100">الدورات ></span>  معلموا دورات السنة النبوية</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">معلموا دورات السنة النبوية</li>
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
                        <table class="table table-centered table_bordered">
                            <thead>
                            <tr>
                                <th>عدد المعلمين</th>
                                <th>عدد الدورات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $users_count }}</td>
                                <td>{{ $courses_count }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{--<h4 class="card-title mb-4" style="display: inline-block;">معلموا الدورات</h4>--}}
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-primary create-new-user" onclick="exportMoallemsAsExcelSheet()" style="width:207px;">
                                <i class="mdi mdi-file-excel"></i>
                                استخراج بيانات المعلمين
                            </button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        @if(hasPermissionHelper('اضافة معلم جديد'))
                            <div class="col-md-2">
                                <button class="btn btn-primary create-new-user" onclick="createNewmoallem()" style="width:207px;">
                                    <i class="mdi mdi-plus"></i>
                                    اضافة معلم جديد
                                </button>
                            </div>
                        @endif
                        @if(hasPermissionHelper('فلترة معلمو الدورات'))
                            <div class="col-md-3">
                                <select class="form-control" onchange="getSubAreas(this)" id="areas_select">
                                    <option value="0">اختر المنطقة الكبرى</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="sub_areas_select" onchange="updateDateTable()">
                                    <option value="0">اختر المنطقة المحلية</option>
                                </select>
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
                                <th scope="col">اسم المعلم</th>
                                <th scope="col">رقم الهوية</th>
                                <th scope="col">المشرف العام</th>
                                <th scope="col">المشرف الميداني</th>
                                <th scope="col">المنطقة الكبرى</th>
                                <th scope="col">المنطقة المحلية</th>
                                <th scope="col">عدد الدورات</th>
                                {{--<th scope="col">عدد الدورات القائمة</th>--}}
                                @if(hasPermissionHelper('نسخ بيانات المعلم لمحفظ او شيخ أسانيد') || hasPermissionHelper('تعديل بيانات معلمو الدورات') || hasPermissionHelper('حذف بيانات معلمو الدورات'))
                                    <th scope="col">أدوات</th>
                                @endif
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
                "ajax": "{{ route('moallem.getData') }}",
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
                        @if(hasPermissionHelper('نسخ بيانات المعلم لمحفظ او شيخ أسانيد') || hasPermissionHelper('تعديل بيانات معلمو الدورات') || hasPermissionHelper('حذف بيانات معلمو الدورات'))
                    { "sortable": false, "targets": [8] }
                    @endif
                    // { "sortable": false, "targets": [3] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "id_num" },
                    { "mData": "area_supervisor" },
                    { "mData": "sub_area_supervisor" },
                    { "mData": "area_father_name" },
                    { "mData": "area_name" },
                    { "mData": "CoursesCount" },
                    // { "mData": "runningCoursesCount" },
                        @if(hasPermissionHelper('نسخ بيانات المعلم لمحفظ او شيخ أسانيد') || hasPermissionHelper('تعديل بيانات معلمو الدورات') || hasPermissionHelper('حذف بيانات معلمو الدورات'))
                    { "mData": "tools" }
                    @endif
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
        @if(hasPermissionHelper('اضافة معلم جديد'))
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
                    return fetch('/moallem/create/'+value)
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
        @endif
        function exportMoallemsAsExcelSheet(){
            var areas_select = $('#areas_select').val();
            var sub_areas_select = $('#sub_areas_select').val();
            var search = $('input[type="search"]').val();
            var filters = '?area_id='+areas_select+'&sub_area_id='+sub_areas_select+'&search='+search;
            $.get('exportMoallemsAsExcelSheet/'+filters,function(response){
                if(response.file_link) {
                    window.open(
                        response.file_link, "_blank");
                }
            });
        }
        @if(hasPermissionHelper('فلترة معلمو الدورات'))
        function getSubAreas(obj) {
            if(obj.value != 0) {
                $.get('/getSubAreas/'+obj.value, function (data) {
                    $('#sub_areas_select').empty().html(data);
                });
                updateDateTable();
            }else{
                $('#sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                updateDateTable();
            }
        }
        function updateDateTable() {
            table.ajax.url(
                "/getMoallemData?sub_area_id="+$('#sub_areas_select').val()+'&area_id='+$('#areas_select').val()
            ).load();
        }
        @endif
        @if(hasPermissionHelper('نسخ بيانات المعلم لمحفظ او شيخ أسانيد'))
        function updateRoles(obj,user_id) {
            obj.classList.add('loading');
            obj.disabled= true;
            $.get('/getUserUpdateRolesSelect/'+user_id,function (data) {
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
                        return fetch('/updateUserRoles/'+user_id+'/'+JSON.stringify(checkedValues))
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
        @endif
    </script>

@endsection
