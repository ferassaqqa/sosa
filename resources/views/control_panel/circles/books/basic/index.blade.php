@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
    <style>
        tbody > tr{
            cursor:move;
        }
    </style>
@endsection
@section('title')
    <title>برنامج السنة |  كتب التحفيظ</title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0"> كتب تحفيظ السنة النبوية</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active"> كتب تحفيظ السنة النبوية</li>
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
                {{--<h4 class="card-title mb-4" style="display: inline-block;"> كتب التحفيظ</h4>--}}
                {{--<div class="dropdown d-inline-block user-dropdown" style="float: left;">--}}
                <div class="row mb-3">
                    <div class="col-md-2">
                        <button class="btn btn-primary" data-url="{{route('circleBooks.create')}}" onclick="callApi(this,'modal_content')" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" style="width: 306px;">
                            <i class="mdi mdi-plus"></i>
                            اضافة كتاب جديد
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
                                <th scope="col">الكتاب</th>
                                <th scope="col">علامة النجاح</th>
                                <th scope="col">عدد الأحاديث</th>
                                <th scope="col">رمز الكتاب</th>
                                <th scope="col">أدوات</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
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
    <script src="https://sunnah1416.com/portal/assests/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('circleBooks.getData') }}",
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
                    { "mData": "id_col" },
                    { "mData": "name" },
                    { "mData": "pass_mark" },
                    { "mData": "hadith_count" },
                    { "mData": "book_code" },
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
            $("#tbody").sortable({
                items: "> tr",
                appendTo: "parent",
                helper: "clone",
                placeholder: "placeholder-style",
                start: function(event, ui) {
                    $(this).find('.placeholder-style td:nth-child(2)').addClass('hidden-td')

                    //copy item html to placeholder html

                    ui.placeholder.html(ui.item.html());

                    //hide the items but keep the height/width.
                    ui.placeholder.css('visibility', 'hidden');
                },
                stop: function(event, ui) {
                    ui.item.css('display', '')
                },

                //add helper function to keep draggable object the same width
                helper: function(e, tr)
                {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index)
                    {
                        // Set helper cell sizes to match the original sizes
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function( event, ui ) {
                    let newOrder = $(this).sortable('toArray');
                    $.ajax({
                        type: "POST",
                        url:'/arrangeCircleBooks',
                        data: {ids: newOrder,_token : '{{ csrf_token() }}'}
                    })
                        .done(function( result ) {
                            $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                                { allow_dismiss: true,type:result.type }
                            );
                            // location.reload();
                        });
                }
            }).disableSelection();
            // $( "ul" ).disableSelection();
        });
    </script>

@endsection