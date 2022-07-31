@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | المستخدمين</title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">المستخدمين</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">المستخدمين</li>
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
                <h4 class="card-title mb-4" style="display: inline-block;">المستخدمين</h4>
                <div class="row mb-3">

                    @if (hasPermissionHelper('اضافة مستخدم'))
                    <div class="col-md-3">
                        <button class="btn btn-primary create-new-user" onclick="createNewUser()" style="width: 100%;">
                            <i class="mdi mdi-plus"></i>
                            اضافة
                        </button>
                    </div>
                    @endif
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
                    <div class="col-md-3">
                        <select class="form-control" id="roles_select" onchange="updateDateTable()">
                            <option value="0">اختر دور المستخدم</option>
                            <option value="مدير دائرة">مدير دائرة</option>
                            <option value="مشرف عام">مشرف عام</option>
                            <option value="مشرف ميداني">مشرف ميداني</option>
                            <option value="مشرف جودة">مشرف جودة</option>
                        </select>
                    </div>
                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">الاسم</th>
                                <th scope="col">المنطقة</th>
                                <th scope="col">الادوار</th>
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
        $(document).ready(function() {
            table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('users.getData') }}",
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
                    { "sortable": false, "targets": [2] },
                    { "sortable": false, "targets": [3] },
                    { "sortable": false, "targets": [4] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "supervisorArea" },
                    { "mData": "roles" },
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

        @if(Auth::user()->hasRole('مدير الدائرة'))
    function changePasswordCustomUser(id) {
        Swal.fire({
            title: 'ادخل كلمة المرور',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'تغيير',
            cancelButtonText: 'الغاء',
            showLoaderOnConfirm: true,
            preConfirm: function (value) {
                return fetch('/changePasswordCustomUser/' + value + '/' + id)
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
                            Swal.fire({title: "تعدبل", text: "تم تعديل كلمة المرور بنجاح.", icon: "success"});
                        }
                        // Do something with the response
                    })
                    .catch(function (errors) {
                        // Swal.showValidationMessage(
                        //     'لا يوجد اتصال بالشبكة'
                        // )
                    });
            },
            allowOutsideClick: function () {
                !Swal.isLoading();
            }
        }).then(function (result) {
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
                "/getUsersData?sub_area_id="+$('#sub_areas_select').val()+'&area_id='+$('#areas_select').val()+'&roles_select='+$('#roles_select').val()
            ).load();
        }
        function createNewUser(){
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
                    return fetch('/users/create/'+value)
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
    </script>

@endsection
