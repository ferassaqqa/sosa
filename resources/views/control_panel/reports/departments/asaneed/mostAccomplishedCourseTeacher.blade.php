<style>
    .dataTables_length{
        display: block;
    }
    .dataTables_info{
        display: block;
    }
    .dataTables_paginate{
        display: block;
    }
</style>


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
<table class="table table-centered table-nowrap mb-0" id="dataTable" >
    <thead style="text-align: center !important;">
    <tr>
        <th scope="col" style="width: 50px;">
            #
        </th>
        <th scope="col">المعلم</th>
        <th scope="col">عدد الدورات</th>
        <th scope="col">عدد الخريجين</th>
        <th scope="col">الدورة الاكثر انجاز</th>
    </tr>
    </thead>
    <tbody style="text-align: justify !important;">


    </tbody>

</table>


<script>


var table = '';
        $(document).ready(function() {

            var filters = '?department_id=' + $('#reports_department_id').val()
                + '&analysis_type=' + $('#analysis_type').val()
                + '&start_date=' + $('#start_date').val()
                + '&end_date=' + $('#end_date').val()
                + '&sub_area_id=' + $('#report_sub_area_select').val()
                + '&teacher_id=' + $('#teachers_select').val()
                + '&place_id=' + $('#place_area').val()
                + '&area_id=' + $('#report_area_select').val()
                + '&analysis_sub_type=' + $('#analysis_sub_type').val()
                + '&book_id=' + $('#books_select').val();

            table = $('#dataTable').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('reports.getAnalysisData').'?analysis_type=asaneedMostAccomplished'}}"+ filters,
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

                    { "sortable": false, "targets": [1] }

                    // { "sortable": false, "targets": [3] }
                ],
                "aoColumns": [
                    { "mData": "id" },
                    { "mData": "teacher_name" },
                    { "mData": "total_accomplished_course" },
                    { "mData": "total_accomplished_students" },
                    { "mData": "most_accomplished_course" },
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


                }
            } );
        });





</script>


