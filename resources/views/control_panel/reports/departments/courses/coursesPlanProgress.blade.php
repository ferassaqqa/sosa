<style>
    .dataTables_length{
        display: none;
    }
    .dataTables_info{
        display: none;
    }
    .dataTables_paginate{
        display: none;
    }
</style>
<table class="table table-centered table-nowrap mb-0" id="dataTable" >
    <thead style="text-align: center !important;">
    <tr>
        <th scope="col" style="width: 50px;">
            #
        </th>
        <th scope="col">الكتاب</th>
        <th scope="col">فئة الخريجين</th>
        <th scope="col">العدد المطلوب</th>
        <th scope="col">العدد المنجز</th>
        <th scope="col">نسبة الانجاز</th>
        <th scope="col">نسبة الفائض</th>
    </tr>
    </thead>
    <tbody style="text-align: justify !important;">


    </tbody>
    <tr style="background-color: #00937C; color: white">
        <th scope="col" colspan="3">
            المجموع
        </th>
        <th scope="col">{{$required_num}}</th>
        <th scope="col">{{$completed_num}}</th>
        <th scope="col">%{{$completed_num_percentage}}</th>
        <th scope="col">%{{$excess_num_percentage}}</th>
    </tr>
</table>
{{--<div>--}}
{{--    <table class="table table-centered table-nowrap mb-0" style="font-size: 18px" >--}}
{{--        <thead style="text-align: center !important;">--}}
{{--        <tr>--}}
{{--            <th scope="col" >--}}
{{--                المجموع--}}
{{--            </th>--}}

{{--            <th scope="col" style="width: 20%"></th>--}}
{{--            <th scope="col" style="width: 30%"></th>--}}
{{--            <th scope="col">{{$required_num}}</th>--}}
{{--            <th scope="col">{{$completed_num}}</th>--}}
{{--            <th scope="col">{{$completed_num_percentage}}%</th>--}}
{{--            <th scope="col">{{$excess_num_percentage}}%</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody style="text-align: center !important;">--}}

{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}

<script>
    var table = '';
    $(document).ready(function () {
        {{--alert('{{ $year }}');--}}
            table = $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            'pageLength': 1000 ,
            "ajax": "{{ route('reports.getAnalysisData').'?analysis_type=coursePlanProgress'}}" + "&year={{$year}}",
            language: {
                search: "بحث",
                processing: "جاري معالجة البيانات",
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
            "columnDefs": [
                // { "sortable": false, "targets": [2] }
            ],
            "aoColumns": [
                {"mData": "id"},
                {"mData": "book_name"},
                {"mData": "graduated_categories"},
                {"mData": "required_num"},
                {"mData": "completed_num"},
                {"mData": "completed_num_percentage"},
                {"mData": "excess_num_percentage"}
            ]
        });
    });

    function changeBook(obj) {
        table.ajax.url(
            "{{ route('reports.getAnalysisData').'?analysis_type=coursePlanProgress'}}" + '&start_date={{$start_date}}&end_date={{$end_date}}&year={{$year}}&book_id=' + $(obj).val()
        ).load();
    }
</script>
