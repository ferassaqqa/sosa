@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | تصنيفات كتب الدورات العلمية </title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0"> تصنيفات كتب الدورات العلمية</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active"> تصنيفات كتب الدورات العلمية</li>
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
                        <button type="button" class="btn btn-info call-modal" data-url="{{route('CourseBookCategory.create')}}" style="width: 305px;"
                                data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" >
                            <i class="mdi mdi-plus"></i>
                            اضافة تصنيف جديد
                        </button>
                    </div>
                </div>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">تصنيفات الكتب</th>
                                <th scope="col">عدد الكتب</th>
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
        $(document).ready(function() {
            var table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('CourseBookCategory.getData') }}",
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
                    { "sortable": false, "targets": [2,3] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "book_count" },
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

    </script>

@endsection