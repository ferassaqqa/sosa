@extends('control_panel.master')


@section('style')
    <link href="{{ asset('control_panel/assets/css/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | طلاب الأسانيد والإجازات </title>
@endsection
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">طلاب الأسانيد والإجازات</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">طلاب الأسانيد والإجازات</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="col-lg-12">

        @if (hasPermissionHelper('فلترة طلاب الأسانيد والإجازات'))


        <div class="card">
            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">
                        <div class="form-group" style="padding-bottom: 20px;">
                            <select class="form-control " onchange="getSubAreas(this)" id="areas_select">
                                <option value="0">اختر المنطقة الكبرى</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control " id="sub_areas_select">
                                <option value="0">اختر المنطقة المحلية</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-4">
                        <div class="form-group" style="padding-bottom: 20px;">
                            <select class="form-control select2" id="teachers_select" onchange="getTeacherCourseBooks(this)"
                                id="teachers_select">
                                <option value="0">اختر المعلم</option>
                                @if (isset($moallems))
                                    @foreach ($moallems as $moallem)
                                        <option value="{{ $moallem->id }}">{{ $moallem->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control " onchange="getBookCoursePlaces(this)" id="books_select">
                                <option value="0">اختر كتاب الدورة</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control " id="places_select">
                                <option value="0">اختر مكان الدورة</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button type="button" style="width:100%" onclick="updateDateTable()"
                            class="btn btn-primary btn-block">
                            <i class="mdi mdi-magnify" aria-hidden="true"></i>
                            ابحث
                        </button>
                    </div>




                </div>


                {{-- <div class="row mb-3">

                <div class="col-md-3">
                    <select class="form-control" onchange="getTeacherCourseBooks(this)" id="teachers_select">
                        <option value="0">اختر المعلم</option>
                        @if (isset($moallems))
                            @foreach ($moallems as $moallem)
                                <option value="{{ $moallem->id }}">{{ $moallem->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" onchange="getBookCoursePlaces(this)" id="books_select">
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control"  id="places_select">
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="button" style="width:100%" onclick="updateDateTable()" class="btn btn-primary btn-block">
                        <i class="mdi mdi-magnify" aria-hidden="true"></i>
                        ابحث
                    </button>
                </div>

            </div> --}}


            </div>
        </div>

        @endif
    </div>



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="">
                        <table class="table table-centered table-nowrap mb-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th scope="col">اسم الطالب رباعياّ</th>

                                    <th scope="col">رقم الهوية</th>
                                    <th scope="col">الكبرى</th>
                                    <th scope="col">المحلية</th>
                                    <th scope="col">المشرف الميداني</th>
                                    <th scope="col">المشرف العام</th>


                                    <th scope="col">الدورات المجاز فيها</th>
                                    <th scope="col">الدورات الغير المجاز فيها</th>
                                    <th scope="col">جميع الدورات</th>
                                    <th scope="col">أدوات</th>
                                    {{-- <th scope="col"> --}}
                                    {{-- <div class="form-check mb-2"> --}}
                                    {{-- <input class="form-check-input check-all" type="checkbox"> --}}
                                    {{-- </div> --}}
                                    {{-- </th> --}}
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
            table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('asaneedCourseStudents.getData') }}",
                language: {
                    search: "",
                    searchPlaceholder: "بحث سريع",
                    processing: "<span style='background-color: #0a9e87;color: #fff;padding: 25px;'>انتظر من فضلك ، جار جلب البيانات ...</span>",
                    lengthMenu: "عدد _MENU_ الصفوف",
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
                        "sortable": false,
                        "targets": [2]
                    },
                    {
                        "sortable": false,
                        "targets": [3]
                    },
                    {
                        "sortable": false,
                        "targets": [4]
                    },
                ],
                "aoColumns": [{
                        "mData": "id"
                    },
                    {
                        "mData": "name"
                    },

                    {
                        "mData": "id_num"
                    },

                    {
                        "mData": "area_father_name"
                    },
                    {
                        "mData": "area_name"
                    },
                    {
                        "mData": "sub_area_supervisor"
                    },
                    {
                        "mData": "area_supervisor"
                    },


                    {
                        "mData": "passedCourses"
                    },
                    {
                        "mData": "failedCourses"
                    },
                    {
                        "mData": "courses"
                    },
                    {
                        "mData": "tools"
                    }
                ]
            });
            table.on('draw', function() {
                var elements = $('.ellipsis').nextAll();
                if (elements.length == 1) {
                    var elements = $('.ellipsis').prevAll();
                    elements[1].before(elements[4]);
                    elements[3].after(elements[0]);
                    elements[2].after(elements[1]);
                    elements[2].before(elements[3]);
                } else if (elements.length == 5) {
                    elements[1].before(elements[4]);
                    elements[3].after(elements[0]);
                    elements[2].after(elements[1]);
                    elements[2].before(elements[3]);
                } else {
                    // var paginate_buttons = $('.paginate_button');
                    // if(paginate_buttons.length > 3) {
                    //     paginate_buttons.css('cursor', 'pointer');
                    //     paginate_buttons[5].after(paginate_buttons[4]);
                    //     paginate_buttons[4].after(paginate_buttons[3]);
                    //     paginate_buttons[3].after(paginate_buttons[2]);
                    //     paginate_buttons[2].after(paginate_buttons[1]);
                    // }

                }
            });
        });

        function createNewCourseStudents() {
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
                preConfirm: function(value) {
                    return fetch('/asaneedCourseStudents/create/' + value)
                        .then(function(response) {
                            return response.json();
                        }).then(function(responseJson) {
                            // console.log(responseJson);
                            if (responseJson.errors) {
                                Swal.showValidationMessage(
                                    responseJson.msg
                                );
                                // throw new Error('Something went wrong')
                            } else {
                                Swal.close();
                                $('.bs-example-modal-xl').modal('show');
                                $('#user_modal_content').html(responseJson.view);
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

        function getTeacherCourseBooks(obj) {
            $.get('/getTeacherAsaneedCourseBooks/' + obj.value, function(data) {
                // updateDateTable();
                $('#places_select').empty();
                $('#books_select').empty().html(data);
            });
        }

        function getBookCoursePlaces(obj) {
            $.get('/getBookAsaneedCoursePlaces/' + obj.value + '/' + $('#teachers_select').val(), function(data) {
                // updateDateTable();
                $('#places_select').empty().html(data);
            });
        }

        function getSubAreas(obj) {

            // $('#teachers_select').empty().html('<option value="0">اختر المعلم</option>');

            // $('#books_select').empty().html('<option value="0">اختر كتاب الدورة</option>');

            if (obj.value != 0) {
                $.get('/getSubAreas/' + obj.value, function(data) {
                    $('#sub_areas_select').empty().html(data);
                });
                // updateDateTable();
            } else {
                $('#sub_areas_select').empty().html('<option value="0">اختر المنطقة المحلية</option>');
                // updateDateTable();
            }
        }

        function updateDateTable() {
            table.ajax.url(
                "/getAsaneedCourseStudentsData?teacher_id=" + $('#teachers_select').val() + '&book_id=' + $(
                    '#books_select').val() + '&place_id=' + $('#places_select').val() + "&sub_area_id=" + $(
                    '#sub_areas_select').val() + '&area_id=' + $('#areas_select').val()
            ).load();
        }
    </script>
@endsection
