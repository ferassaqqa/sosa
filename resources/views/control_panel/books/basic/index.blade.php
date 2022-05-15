@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | {{ $department_name }}</title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $department_name }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">{{ $department_name }}</li>
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
                        <button class="btn btn-primary call-user-modal" data-url="{{route('books.create',['department'=>$department])}}" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" style="width: 305px;">
                            <i class="mdi mdi-plus"></i>
                            اضافة كتاب
                        </button>
                    </div>
                    <div class="col-md-3" style="margin-right: -11px;">
                        <select class="form-control" onchange="updateDateTable()" id="authorSelect">
                            <option value="0">اختر المؤلف</option>
                            @foreach($courseBookAuthors as $courseBookAuthor)
                                <option value="{{ $courseBookAuthor }}">{{ $courseBookAuthor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" onchange="updateDateTable()" id="courseBookCategories">
                            <option value="0">اختر تصنيف الكتاب</option>
                            @foreach($courseBookCategories as $courseBookCategory)
                                <option value="{{ $courseBookCategory->id }}">{{ $courseBookCategory->name }}</option>
                            @endforeach
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
                                <th scope="col">الكتاب</th>
                                <th scope="col">المؤلف</th>
                                <th scope="col">عدد الساعات</th>
                                <th scope="col">تصنيف الكتاب</th>
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
                "ajax": "{{ route('books.getData',['department'=>$department]) }}",
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
                    { "sortable": false, "targets": [5] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "author" },
                    { "mData": "hours_count" },
                    { "mData": "category_name" },
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
                    //     if(paginate_buttons.length == 6) {
                    //         paginate_buttons[5].after(paginate_buttons[4]);
                    //     }
                    //     if(paginate_buttons.length == 5) {
                    //         paginate_buttons[4].after(paginate_buttons[3]);
                    //     }
                    //     if(paginate_buttons.length == 4) {
                    //         paginate_buttons[3].after(paginate_buttons[2]);
                    //         paginate_buttons[2].after(paginate_buttons[1]);
                    //     }
                    // }

                }
            } );
        });
        function addPlan(obj,plane_id,type) {
            $.get('/plans/show/' + plane_id + '/'+type, function (data) {
                $('.bs-example-modal-center').modal('show');
                $('#modal_content').html(data);
                // $('#create_plan_form').append(data);
            });
            // Swal.fire({
            //     title: '<strong>ادخل نوع الخطة</strong>',
            //     icon: 'info',
            //     html:
            //     'اختر نوع الخطة' +
            //     '<select class="form-control" name="plan_type">' +
            //     '   <option value="سنوية">سنوية</option>' +
            //     '   <option value="ساعات">ساعات</option>' +
            //     '</select>',
            //     showCloseButton: true,
            //     focusConfirm: false,
            //     confirmButtonAriaLabel: '',
            //     showCancelButton: true,
            //     confirmButtonText: 'اضافة',
            //     cancelButtonText: 'الغاء',
            //     showLoaderOnConfirm: true,
            //     allowOutsideClick: function () {
            //         !Swal.isLoading();
            //     }
            // }).then(function (result) {
            //     if (result.isConfirmed) {
            //         var plan_type = $('select[name="plan_type"]').val();
            //         $.get('/plans/create/' + book + '/' + plan_type, function (data) {
            //             $('.bs-example-modal-xl').modal('show');
            //             $('#user_modal_content').html(data);
            //             // $('#create_plan_form').append(data);
            //         });
            //     }
            // })
        }
        function copyToYear(obj,book_id) {
            obj.classList.add('loading');
            obj.disabled= true;
            $.get('/getYearsDoesNotHaveThisBook/'+book_id,function (data) {
                obj.classList.remove('loading');
                obj.disabled= false;
                Swal.fire({
                    title: '<strong>اختر السنة المطلوب نسخ بيانات الكتاب اليها</strong>',
                    icon: 'info',
                    html:data,
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonAriaLabel: '',
                    showCancelButton: true,
                    confirmButtonText: 'اضافة',
                    cancelButtonText: 'الغاء',
                    showLoaderOnConfirm: true,
                    preConfirm: function(value) {
                        var year = $('#years_select').val();
                        if (year == 0) {
                            Swal.showValidationMessage(
                                'يرجى اختيار سنة'
                            );
                        } else {
                            return fetch('/copyBookDetailsToYear/' + year + '/' + book_id)
                                .then(function (response) {
                                    return response.json();
                                }).then(function (responseJson) {
                                    if (responseJson.errors) {
                                        Swal.showValidationMessage(
                                            responseJson.msg
                                        );
                                    } else {
                                        Swal.close();
                                        $('#dataTable').DataTable().ajax.reload();
                                        Swal.fire(responseJson.msg, responseJson.title, responseJson.type);
                                    }
                                })
                                .catch(function (errors) {
                                    // Swal.showValidationMessage(
                                    //     'لا يوجد اتصال بالشبكة'
                                    // )
                                });
                        }
                    },
                    allowOutsideClick: function(){!Swal.isLoading();}
                });
            });
        }
        function updateDateTable() {
            table.ajax.url(
                "/getBooksData/{{$department}}?courseBookCategory="+$('#courseBookCategories').val()+'&author='+$('#authorSelect').val()
            ).load();
        }

    </script>

@endsection