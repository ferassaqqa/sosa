@extends('control_panel.master')

@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | الكتب المحذوفة</title>
@endsection
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">الكتب المحذوفة</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('books.index',['department'=>$department]) }}">الكتب</a></li>
                    <li class="breadcrumb-item active">الكتب المحذوفة</li>
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
                <h4 class="card-title mb-4" style="display: inline-block;">الكتب المحذوفة</h4>

                <button class="btn btn-success" title="استرجاع المحدد" data-url="{{route('books.restoreSelected')}}" onclick="restoreSelected(this)" style="float: left;">
                    <i class="mdi mdi-arrow-top-left"></i>
                    استرجاع المحدد
                </button>
                <button class="btn btn-warning export-excel" data-is-deleted="1" data-url="{{ route('books.exportExcel') }}" style="float: left;margin-left: 5px;">
                    <i class="mdi mdi-file-excel-box"></i>تصدير
                </button>
                <div class="">
                    <table class="table table-centered table-nowrap mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col"  style="width: 50px;">
                                    #
                                </th>
                                <th scope="col">الكتاب</th>
                                <th scope="col">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-all"  type="checkbox">
                                    </div>
                                </th>
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
                "ajax": "{{ route('books.deletedItemsData',['department'=>$department]) }}",
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
                    // { "sortable": false, "targets": [3] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "name" },
                    { "mData": "select" }
                ]
            } );
        });

    </script>

@endsection