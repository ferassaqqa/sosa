@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة |  الخطة السنوية لكتب التحفيظ</title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0"> الخطة السنوية لكتب تحفيظ السنة النبوية</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active"> الخطة السنوية لكتب تحفيظ السنة النبوية</li>
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
                {{--<h4 class="card-title mb-4" style="display: inline-block;"> الخطة السنوية لكتب التحفيظ</h4>--}}
                {{--<div class="dropdown d-inline-block user-dropdown" style="float: left;">--}}
                <div class="row mb-3">
                    <div class="col-md-2">
                        <button class="btn btn-primary" onclick="createNewPlan('{{route('circlePlans.create','')}}')" style="width: 306px;">
                            <i class="mdi mdi-plus"></i>
                            اضافة خطة سنوية
                        </button>
                    </div>
                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0 table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">خطة الأحاديث المطلوبة من الحلقات المكفولة والمتطوعة</th>
                                <th scope="col">الخطط السنوية</th>
                                {{--<th scope="col">أدوات</th>--}}
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
    <script src="{{asset('control_panel/assets/js/pages/form-advanced.init.js')}}">

    </script>

    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('circlePlans.getData') }}",
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
                    { "sortable": false, "targets": [1,2] }
                ],

                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "year" },
                    { "mData": "agenda" }
                    // { "mData": "tools" }
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
        function createNewPlan(link){
            var years = {};
            var d = new Date();
            var current_year = d.getFullYear();
            var i = 0;
            for(i;i<6;i++){
                years[(current_year-5)+i] = (current_year-5)+i;
            }
            i = 0;
            for(i;i<6;i++){
                years[current_year+i] = current_year+i;
            }
            Swal.fire({
                customClass: 'swal-wide',
                title: 'ادخل السنة',
                input: 'select',
                inputOptions: years,
                inputAttributes: {
                    'name':'year',
                    'class':'form-control'
                },
                inputPlaceholder: 'اختر السنة',
                showCancelButton:true,
                showCloseButton:true,
                confirmButtonText: 'اضافة',
                cancelButtonText: 'الغاء',
                showLoaderOnConfirm: true,
                preConfirm: function(value){
                    if(value != '') {
                        return fetch(link + '/' + value)
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
                    }else{
                        Swal.showValidationMessage(
                            'اختر سنة'
                        );
                    }
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