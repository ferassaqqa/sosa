@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | حلقات التحفيظ</title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">حلقات تحفيظ السنة النبوية</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">حلقات التحفيظ</li>
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
                {{--<h4 class="card-title mb-4" style="display: inline-block;">حلقات التحفيظ</h4>--}}
                <div class="row mb-3">
                    <div class="col-md-2">
                        <button class="btn btn-primary" onclick="callApi(this,'user_modal_content')" data-url="{{route('circles.create')}}" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" style="width: 207px;">
                            <i class="mdi mdi-plus"></i>
                            اضافة حلقة جديدة
                        </button>
                    </div>
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
                </div>

                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">الحلقة</th>
                                <th scope="col">تاريخ البداية</th>
                                <th scope="col">المشرف العام</th>
                                <th scope="col">المشرف الميداني</th>
                                {{--<th scope="col">المكان - المنطقة الفرعية - المنطقة الكبرى</th>--}}
                                <th scope="col">المكان</th>
                                <th scope="col">حالة الحلقة</th>
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
                "ajax": "{{ route('circles.getData') }}",
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
                    { "sortable": false, "targets": [6,7] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "teacher_name" },
                    { "mData": "start_date" },
                    { "mData": "area_supervisor_name" },
                    { "mData": "supervisor_name" },
                    { "mData": "place_name" },
                    { "mData": "StatusSelect" },
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
                "/getCirclesDate?sub_area_id="+$('#sub_areas_select').val()+'&area_id='+$('#areas_select').val()
            ).load();
        }
        function changeCircleStatus(circle_id,status) {
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
                        var link = result.value ? '/changeCircleStatus/'+circle_id+'/'+status+'/'+result.value : '/changeCircleStatus/'+circle_id+'/'+status;
                        $.get(link,function (result) {
                            $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                                { allow_dismiss: true,type:result.type }
                            );
                        });
                    }
                })
            }else {
                $.get('/changeCircleStatus/'+circle_id+'/'+status, function (result) {
                    $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                        { allow_dismiss: true,type:result.type }
                    );
                });
            }
        }
    </script>

@endsection