@extends('control_panel.master')


@section('style')
    <link href="{{ asset('control_panel/assets/css/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection
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
@section('title')
    <title>برنامج السنة | قسم الدورات العلمية </title>
@endsection
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><span style="font-weight: 100">الدورات ></span> قسم الدورات العلمية </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">قسم الدورات العلمية</li>
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
                    <table class="table table-centered table_bordered">
                        <thead>
                            <tr>
                                <th>عدد المعلمين</th>
                                <th>عدد الطلاب</th>
                                <th>عدد الطلاب الناجحين</th>
                                <th>عدد الطلاب الراسبين</th>
                                <th>عدد الدورات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="main_statistics">
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        @if (hasPermissionHelper('فلترة الدورات العلمية'))
                            <div class="col-md-3">
                                <select class="form-control select2" onchange="getSubAreas(this)" id="areas_select">
                                    <option value="0">اختر المنطقة الكبرى</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-md-3">
                                <select class="form-control select2" id="sub_areas_select"
                                    onchange="getSubAreaTeachers(this)">
                                    <option value="0">اختر المنطقة المحلية</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select class="form-control select2" id="teachers_select">
                                    <option value="0">اختر المعلم</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select class="form-control select2" id="books_select">
                                    <option value="0">اختر الكتاب</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-3">
                            <select class="form-control select2" id="place_area">
                                <option value="0">اختر مكان الدورة</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            {!! $statuses !!}
                        </div>

                        <div class="col-md-3">
                            <button type="button" style="width:100%" onclick="updateDateTable()"
                                class="btn btn-primary btn-block">
                                <i class="mdi mdi-magnify" aria-hidden="true"></i>
                                ابحث
                            </button>
                        </div>

                    </div>
                    @endif

                </div>

            </div>
        </div>




        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">


                    {{-- <div class="row mb-3"> --}}
                        @if (hasPermissionHelper('اضافة دورة علمية'))
                            <div class="col-md-3">
                                <button type="button" onclick="callApi(this,'user_modal_content_new')"
                                    class="btn btn-success btn-block" data-url="{{ route('courses.create') }}"
                                    style="background-color:#00937C;width: 100%;" data-bs-toggle="modal"
                                    data-bs-target=".bs-example-modal-x2">
                                    <i class="mdi mdi-plus"></i>
                                    اضافة دورة علمية
                                </button>
                            </div>
                        @endif


                        <div class="">
                            <table class="table table-centered table-nowrap mb-0" id="dataTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 50px;">
                                            #
                                        </th>
                                        <th scope="col">الكتاب</th>
                                        <th scope="col">اسم المعلم رباعياّ</th>
                                        <th scope="col">عدد الطلاب</th>
                                        <th scope="col">مكان الدورة</th>

                                        <th scope="col">المشرف</th>

                                        {{-- <th scope="col" >المشرف الميداني</th>
                                <th scope="col" >المشرف العام</th> --}}


                                        {{-- <th scope="col">الكبرى</th>
                                <th scope="col">المحلية</th> --}}

                                        <th scope="col">حالة الدورة</th>
                                        @if (hasPermissionHelper('تعديل بيانات الدورات العلمية') || hasPermissionHelper('حذف بيانات الدورات العلمية') || hasPermissionHelper('اضافة طالب جديد - دورات علمية') || hasPermissionHelper('طلاب الدورات'))
                                            <th scope="col">أدوات</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    {{-- </div> --}}
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

            var course_id = 0;

            function addExcelCourseStudents(course) {
                $('#excelStudentsImport').click();
                course_id = course;
            }
            $('#excelStudentsImport').change(function(e) {
                // console.log($(this),course_id);
                // $('div[class="student_excel_import_loading"]').css('display','block');
                $.get('/showLoadingCourseStudents/' + course_id, function(data) {
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
                    url: '/importCourseStudentsExcel/' + course_id,
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
                                ShowCourseStudents(course_id);
                            });
                        }
                        $('.bs-example-modal-xl').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        $('#spinner').remove();
                    },
                    error: function(errors) {
                        const entries = Object.entries(errors.responseJSON.errors);
                        var errors_message = document.createElement('div');
                        Swal.fire(entries[0][1][0]);
                        $('div[class="student_excel_import_loading"]').css('display', 'none');
                    }
                });
            });

            function ShowCourseStudents(course_id) {
                $('.bs-example-modal-xl').modal('show');
                $.get('/ShowCourseStudents/' + course_id, function(data) {
                    $('#user_modal_content')
                        .html(data);
                });
            }

            function exportCourseStudentsMarksExcelSheet(course_id) {
                $.get('exportCourseStudentsMarksExcelSheet/' + course_id, function(response) {
                    if (response.msg) {
                        Swal.fire(
                            'تم استخراج الملف',
                            response.msg,
                            'success'
                        );
                    }
                    if (response.file_link) {
                        window.open(
                            response.file_link, "_blank");
                    }
                });
            }
            var table = '';
            $(document).ready(function() {




                table = $('#dataTable').removeAttr('width').DataTable({
                    "processing": true,
                    "serverSide": true,
                    // "bFilter": false,
                    responsive: true,
                    autoWidth: false,
                    // "scrollX":true,
                    "drawCallback": function() {
                        $('#main_statistics').empty().html(table.data().context[0].json['statistics']);
                        // console.log();
                    },
                    "ajax": "{{ route('courses.getData') }}",
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
                            targets: [1, 2, 3, 4]
                        },
                        // {className: "align_td_right",targets:1},
                        // {"max-width": "5%"},
                        @if (hasPermissionHelper('تعديل بيانات الدورات العلمية') || hasPermissionHelper('حذف بيانات الدورات العلمية'))
                            {
                                "sortable": false,
                                "targets": [4]
                            }
                        @endif
                    ],
                    "aoColumns": [{
                            "mData": "id"
                        },
                        {
                            "mData": "book"
                        },
                        {
                            "mData": "teacher_name"
                        },
                        {
                            "mData": "studentCount"
                        },
                        {
                            "mData": "place"
                        },
                        {
                            "mData": "supervisor"
                        },

                        // { "mData": "sub_area_supervisor" },
                        // { "mData": "area_supervisor" },
                        // { "mData": "father_area_name" },
                        // { "mData": "area_name" },

                        {
                            "mData": "status"
                        },
                        @if (hasPermissionHelper('تعديل بيانات الدورات العلمية') || hasPermissionHelper('حذف بيانات الدورات العلمية') || hasPermissionHelper('اضافة طالب جديد - دورات علمية') || hasPermissionHelper('طلاب الدورات'))
                            {
                                "mData": "tools"
                            }
                        @endif
                    ]
                });
            });
            @if (hasPermissionHelper('استثناء طالب خارج الخطة'))
                function excludeStudent(user_id, course_id) {
                    // Swal.close();
                    // $('.bs-example-modal-xl').modal('show');
                    $.get('/excludeStudent/' + user_id + '/' + course_id, function(data) {
                        // console.log(data);
                        if (data.type == 'danger') {

                        } else {
                            // $('.bs-example-modal-xl').modal('show');
                            // $('#user_modal_content').html(data);
                            Swal.showValidationMessage(
                                data.msg
                            );
                            $('#id_num').val('').focus().removeClass('swal2-inputerror');
                            $('#dataTable').DataTable().ajax.reload();
                        }
                    });
                }
            @endif
            @if (hasPermissionHelper('تغيير حالة الدورات العلمية'))
                function changeCourseStatus(course_id, obj) {

                    var status = obj.value;
                    switch (status) {
                        case 'قائمة': {
                            $(obj).css('background-color', '#51aaf2');
                        }
                        break;
                    case 'انتظار الموافقة': {
                        $(obj).css('background-color', '#3cb6ab');
                    }
                    break;
                    }
                    if (status == 'معلقة') {
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
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                var link = result.value ? '/changeCourseStatus/' + course_id + '/' + status + '/' +
                                    result.value : '/changeCourseStatus/' + course_id + '/' + status;
                                $.get(link, function(result) {
                                    $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title +
                                        ' </strong> | ' + result.msg, {
                                            allow_dismiss: true,
                                            type: result.type
                                        }
                                    );
                                    $('#dataTable').DataTable().ajax.reload();
                                });
                            }
                        })
                    } else {
                        $.get('/changeCourseStatus/' + course_id + '/' + status, function(result) {
                            $('#dataTable').DataTable().ajax.reload();
                            $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title + ' </strong> | ' + result
                                .msg, {
                                    allow_dismiss: true,
                                    type: result.type
                                });
                        });
                    }
                    // console.log(course_id,status);
                }
            @endif
            @if (hasPermissionHelper('اضافة طالب جديد - دورات علمية'))
                function createNewCourseStudents(course_id) {
                    Swal.fire({
                        customClass: 'swal-wide',
                        title: 'ادخل رقم الهوية',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off',
                            id: 'id_num'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'اضافة',
                        cancelButtonText: 'الغاء',
                        showLoaderOnConfirm: true,
                        preConfirm: function(value) {
                            return fetch('/courseStudents/create/' + value + '/' + course_id)
                                .then(function(response) {
                                    return response.json();
                                }).then(function(responseJson) {
                                    if (responseJson.errors) {
                                        Swal.showValidationMessage(
                                            responseJson.msg
                                        );
                                    } else if (responseJson.view) {
                                        Swal.close();
                                        $('.bs-example-modal-xl').modal('show');
                                        $('#user_modal_content').html(responseJson.view);
                                    } else {
                                        Swal.showValidationMessage(
                                            responseJson.msg
                                        );
                                        $('#dataTable').DataTable().ajax.reload();
                                        $('#id_num').val('').focus().removeClass('swal2-inputerror');
                                        // if($('#swal2-validation-message')){
                                        //     $('#swal2-validation-message').removeClass('swal2-validation-message');
                                        //     $('#swal2-validation-message').css('align-content','flex-end')
                                        // }
                                    }
                                    // Do something with the response
                                })
                                .catch(function(errors) {
                                    // Swal.showValidationMessage(
                                    //     'لا يوجد اتصال بالشبكة'
                                    // )
                                });
                        },
                        allowOutsideClick: function() {
                            !Swal.isLoading();
                        }
                    }).then(function(result) {
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
            @if (hasPermissionHelper('فلترة الدورات العلمية'))




                function getSubAreas(obj) {

                    $('#teachers_select').empty().html('<option value="0">اختر المعلم</option>');
                    $('#place_area').empty().html('<option value="0">اختر مكان الدورة</option>');

                    if (obj.value != 0) {
                        $.get('/getSubAreas/' + obj.value, function(data) {
                            $('#sub_areas_select').empty().html(data);
                        });
                        $.get('/getSubAreaTeachers/' + obj.value, function(data) {
                            $('#teachers_select').empty().html(data[0]);
                            // $('#place_area').empty().html(data[1]);
                        });
                        // updateDateTable();
                    } else {
                        $('#sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                        $('#teachers_select').empty().html('<option value="0">اختر المعلم</option>');
                        // updateDateTable();
                    }
                }

                function getSubAreaTeachers(obj) {
                    $.get('/getSubAreaTeachers/' + obj.value, function(data) {
                            // $('#teachers_select').empty().html(data[0]);
                            $('#place_area').empty().html(data[1]);
                        });
                    // if (obj.value != 0) {
                    //     $.get('/getSubAreaTeachers/' + obj.value, function(data) {
                    //         $('#teachers_select').empty().html(data[0]);
                    //         $('#place_area').empty().html(data[0]);
                    //     });
                    //     // updateDateTable();
                    // } else {

                    //     $('#place_area').empty().html('<option value="0">اختر مكان الدورة</option>');
                    //     // updateDateTable();
                    // }
                }



                function updateDateTable() {
                    table.ajax.url(
                        "/getCoursesData?teacher_id=" + $('#teachers_select').val() + "&book_id=" + $('#books_select')
                        .val() + "&sub_area_id=" + $('#sub_areas_select').val() + '&area_id=' + $('#areas_select').val() +
                        '&status=' + $('#filterCoursesByStatus').val() + '&place_area=' + $('#place_area').val()
                    ).load();
                }
            @endif
            function deleteCourse(obj) {
                var url = obj.getAttribute('data-url');
                var alert = obj.getAttribute('data-alert');
                // console.log(url);
                Swal.fire({
                        title: "حذف بيانات الدورة",
                        text: alert,
                        icon: "warning",
                        showCancelButton: !0,
                        confirmButtonText: "نعم إحذف",
                        cancelButtonText: "رجوع",
                        confirmButtonClass: "btn btn-success mt-2",
                        cancelButtonClass: "btn btn-danger ms-2 mt-2",
                        buttonsStyling: !1
                    })
                    .then(
                        function(t) {
                            if (t.value) {
                                $.ajax({
                                    url: url,
                                    type: 'POST',
                                    data: {
                                        _method: 'DELETE',
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(result) {
                                        $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title +
                                            ' </strong> | ' + result.msg, {
                                                allow_dismiss: true,
                                                type: result.type
                                            }
                                        );
                                        result.type = result.type == 'danger' ? 'error' : result.type;
                                        Swal.fire({
                                            title: result.title,
                                            text: result.msg,
                                            icon: result.type
                                        });
                                        if (result.type == 'success') {
                                            $('#dataTable').DataTable().ajax.reload();
                                            $('.modal').modal('hide');
                                        }
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "لم يتم الحذف!",
                                    text: "البيانات لم تحذف.",
                                    icon: "error"
                                });
                            }
                        }
                    );

            }
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
