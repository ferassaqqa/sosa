<table class="table table-centered table-nowrap mb-0" id="dataTable">
    <thead>
        <tr>
            <th scope="col"  style="width: 50px;">
                #
            </th>
            <th scope="col">عدد الدورات</th>
            <th scope="col">عدد الخريجين</th>
            <th scope="col">الدورة الأكثر إنجازا (عدداً)</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    var table = '';
    $(document).ready(function() {
        {{--alert('{{ $year }}');--}}
        table = $('#dataTable').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('reports.getAnalysisData').'?analysis_type=الأكثر إنجازًا'}}"+"&year={{$year}}",
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
                // { "sortable": false, "targets": [2] }
            ],
            "aoColumns": [
                { "mData": "id" },
                { "mData": "courses_num" },
                { "mData": "graduated_num" },
                { "mData": "most" },

            ]
        } );
    });
    function changeMoallem(obj) {
        table.ajax.url(
            "{{ route('reports.getAnalysisData').'?analysis_type=الأكثر إنجازًا'}}"+'&start_date={{$start_date}}&end_date={{$end_date}}&year={{$year}}&moallem_id='+$(obj).val()
        ).load();
    }
</script>
