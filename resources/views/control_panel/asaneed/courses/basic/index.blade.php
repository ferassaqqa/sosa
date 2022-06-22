@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | قسم الأسانيد والإجازات </title>
@endsection
@section('content')

<style>
        .course_status_option {
        background: white;
        color: black;
    }

    .course_status_select {
        color: white !important;
    }

    .white_space {
        white-space: break-spaces !important;
    }

    .align_td_right {
        text-align: right !important;
    }

    .swal2-modal {
        width: 861px !important;
    }
    div.dataTables_filter,
        div.dataTables_length {
            margin-left: 1em;
        }

    #dataTable_wrapper .dataTables_filter {
        float: left;
    }

    .dataTables_wrapper {
        margin-top: -35px;
    }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">قسم الأسانيد والإجازات</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">قسم الأسانيد والإجازات</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

@if (hasPermissionHelper('فلترة الاسانيد'))

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <div class="row mb-3">

                <div class="col-md-3">
                    <select class="form-control " onchange="getSubAreas(this)" id="areas_select">
                        <option value="0">اختر المنطقة الكبرى</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="col-md-3">
                    <select class="form-control " id="sub_areas_select"
                        onchange="getSubAreaTeachers(this)">
                        <option value="0">اختر المنطقة المحلية</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control " id="teachers_select">
                        <option value="0">اختر الشيخ</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control " id="books_select">
                        <option value="0">اختر الكتاب</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">{{ $book->name }}</option>
                        @endforeach
                    </select>
                </div>

        </div>

        <div class="row mb-3">

            <div class="col-md-3">
                <select class="form-control " id="place_area">
                    <option value="0">اختر مكان المجلس</option>
                </select>
            </div>

            <div class="col-md-3">
                {!! $statuses !!}
            </div>

            <div class="col-md-3">
                <button type="button" onclick="updateDateTable()"
                    class="btn btn-success btn-block" style="background-color:#00937C;width: 100%;">
                    <i class="mdi mdi-magnify" aria-hidden="true"></i>
                    ابحث
                </button>
            </div>

        </div>
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
                <div class="row mb-3">
                    <div class="col-md-3">

                @if (hasPermissionHelper('اضافة دورة سند'))
                    <button type="button" onclick="callApi(this,'user_modal_content_new')"
                        class="btn btn-success btn-block" data-url="{{ route('asaneedCourses.create') }}"
                        style="background-color:#00937C;width: 100%;" data-bs-toggle="modal"
                        data-bs-target=".bs-example-modal-x2">
                        <i class="mdi mdi-plus"></i>
                        اضافة مجلس اسناد
                    </button>
                @endif



                    </div>

                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px; ">
                                    #
                                </th>
                                <th scope="col">الكتاب</th>
                                <th scope="col">اسم الشيخ المجيز رباعياّ</th>
                                <th scope="col">عدد الطلاب</th>

                                <th scope="col">مكان المجلس</th>
                                <th scope="col">المشرف</th>
                                <th scope="col">حالة المجلس </th>

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

<input type="file" id="excelStudentsImport" style="display: none;">


@endsection

@section('script')

    <script>

            $('.select2').select2({
                dir: "rtl",
                dropdownAutoWidth: true,
            });

            var asaneed_id = 0;

            function addExcelAsaneedStudents(asaneedCourse) {
                $('#excelStudentsImport').click();
                asaneed_id = asaneedCourse;
            }

            function getSubAreaTeachers(obj) {
                    // if (obj.value != 0) {
                    //     $.get('/getSubAreaAsaneedTeachers/' + obj.value, function(data) {
                    //         $('#teachers_select').empty().html(data[0]);
                    //         $('#place_area').empty().html(data[1]);
                    //     });

                    // } else {
                    //     $('#teachers_select').empty().html('<option value="0">اختر الشيخ</option>');
                    //     $('#place_area').empty().html('<option value="0">اختر مكان المجلس</option>');

                    // }
                }

        function getSubAreas(obj) {
            if(obj.value != 0) {
                $.get('/getSubAreas/'+obj.value, function (data) {
                    $('#sub_areas_select').empty().html(data);
                });

            $.get('/getSubAreaAsaneedTeachers/' + obj.value, function(data) {
                            $('#teachers_select').empty().html(data[0]);
                            $('#place_area').empty().html(data[1]);
            });
                // updateDateTable();
            }else{
                $('#sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                // updateDateTable();
            }
        }


            function ShowAsaneedStudents(asaneed_id) {
                    $('.bs-example-modal-xl').modal('toggle');
                    $('.user_modal_content')
                        .html(
                            '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                            '   <span class="sr-only">يرجى الانتظار ...</span>' +
                            '</div>'
                        );
                    $.get('/ShowAsaneedCourseStudents/'+asaneed_id,function (data) {
                        $('.bs-example-modal-xl').modal('toggle');
                        $('#user_modal_content').empty().html(data);
                    });
                }


$(document).ready(function() {




            $('#excelStudentsImport').change(function(e) {

                // $('div[class="student_excel_import_loading"]').css('display','block');

                $.get('/showLoadingAsaneedStudents/' + asaneed_id, function(data) {
                    $('.bs-example-modal-xl').modal('show');
                    $('#user_modal_content')
                        .html(data);
                });


                var fd = new FormData();
                var files = $(this)[0].files;
                fd.append('file', files[0]);
                fd.append('_token', '{{ csrf_token() }}');
                $(this).val('');
                $.ajax({
                    url: '/importAsaneedStudentsExcel/' + asaneed_id,
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // console.log(response);
                        if (response.file_link) {
                            window.open(
                                response.file_link, "_blank");
                        }
                        if (response.msg) {
                            Swal.fire(
                                'تم استيراد الملف',
                                response.msg,
                                'success'
                            ).then(function(value) {
                                ShowAsaneedStudents(asaneed_id);
                            });
                        }
                        // $('.bs-example-modal-xl').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // $('div[class="student_excel_import_loading"]').css('display', 'none');
                    },
                    error: function(errors) {
                        const entries = Object.entries(errors.responseJSON.errors);
                        var errors_message = document.createElement('div');
                        Swal.fire(entries[0][1][0]);
                        // $('div[class="student_excel_import_loading"]').css('display', 'none');
                    }
                });
            });
        });

        var table = '';
        $(document).ready(function(){
            table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('asaneedCourses.getData') }}",
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
                    { "sortable": false, "targets": [5] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "book" },
                    { "mData": "teacher_name" },
                    { "mData": "studentCount" },


                    { "mData": "place" },
                    { "mData": "supervisor" },


                    { "mData": "status" },
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
        function excludeStudent(user_id,course_id){
            Swal.close();
            $('.bs-example-modal-xl').modal('show');
            $.get('/excludeAsaneedStudent/'+user_id+'/'+course_id,function(data){
                if(data.type=='danger'){

                }else{
                    // $('.bs-example-modal-xl').modal('show');
                    $('#user_modal_content').html(data);
                }
            });
        }
        function changeCourseStatus(course_id,status) {
            if(status == 'معلقة'){
                Swal.fire({
                    title: 'ادخل سبب التعليق',
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'اضافة',
                    cancelButtonText: 'الغاء',
                    showLoaderOnConfirm: true,
                }).then(function(result){
                    if (result.isConfirmed) {
                        var link = result.value ? '/changeCourseStatus/'+course_id+'/'+status+'/'+result.value : '/changeCourseStatus/'+course_id+'/'+status;
                        $.get(link,function (result) {
                            $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                                { allow_dismiss: true,type:result.type }
                            );
                        });
                    }
                })
            }else {
                $.get('/changeAsaneedCourseStatus/'+course_id+'/'+status,function (result) {
                    $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                        { allow_dismiss: true,type:result.type }
                    );
                });
            }
            // console.log(course_id,status);
        }
        function createNewCourseStudents(course_id){
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
                    return fetch('/asaneedCourseStudents/create/'+value+'/'+course_id)
                        .then(function(response){
                            return response.json();
                        }).then(function(responseJson) {
                            if (responseJson.errors){
                                Swal.showValidationMessage(
                                    responseJson.msg
                                );
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



        function updateDateTable() {
            table.ajax.url(
                "/getAsaneedCoursesData?sub_area_id="+$('#sub_areas_select').val()+'&area_id='+$('#areas_select').val()
                +'&status='+$('#filterCoursesByStatus').val()
                +'&book_id='+$('#books_select').val()
                +'&teacher_id='+$('#teachers_select').val()
                +'&place_area='+$('#place_area').val()



            ).load();
        }

        $('.bs-example-modal-xl').on('hidden.bs.modal', function (e) {
            // updateDateTable();
            $('#dataTable').DataTable().ajax.reload();

            });
    </script>



<script type="module">
    var iteration = 1;
    // Import the functions you need from the SDKs you need
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-analytics.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey: "AIzaSyBSi08DoXXU5rNvK7chgObzLi_l1_807VM",
        authDomain: "sunna-b0909.firebaseapp.com",
        projectId: "sunna-b0909",
        storageBucket: "sunna-b0909.appspot.com",
        messagingSenderId: "141645051731",
        appId: "1:141645051731:web:11c4995110408f7a4993a6",
        measurementId: "G-D7XE883R18"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
    //
    // firebase.initializeApp(firebaseConfig);
    // const messaging = firebase.messaging();

    messaging.onMessage(function(payload) {
        iteration = parseInt($('#students_count').html()); // const noteTitle = payload.notification.title;
        // console.log(iteration,$('#students_count').html());
        // const noteOptions = {
        //     body: payload.notification.body,
        //     icon: payload.notification.icon,
        // };
        iteration++;
        $('#students').append(payload.notification.title);
        $('#students_count').empty().html(iteration);
        // new Notification(noteTitle, noteOptions);
    });
</script>

@endsection
