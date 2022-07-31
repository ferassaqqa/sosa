

                        <table class="table table-centered   table-nowrap mb-0"  id="dataTable" style="font-size: 18px">

                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th scope="col"  style="width: 50px;">الكتاب</th>
                                    <th scope="col">فئة الخريجين</th>
                                    <th scope="col">العدد المطلوب</th>
                                    <th scope="col">العدد المنجز</th>
                                    <th scope="col">نسبة الانجاز</th>
                                    <th scope="col">نسبة الفائض</th>
                                </tr>
                            </thead>
                            <tbody>


                                @if (isset($value) && count($value) > 0)
                                @foreach ($value as $index => $val)
                                             {!! $val !!}
                                @endforeach
                                @endif





                            </tbody>

                        </table>

<script>

    // $(document).ready(function() {
    //     var table = $('#dataTable').DataTable( {
    //         "processing": true,
    //         "serverSide": true,
    //         "ajax": "{{ route('reports.getAnalysisData').'?analysis_type=coursePlanProgress'}}",
    //         language: {
    //             search: "بحث",
    //             processing:     "جاري معالجة البيانات" ,
    //             lengthMenu:    "عدد _MENU_ الصفوف",
    //             info:           "من _START_ الى _END_ من أصل _TOTAL_ صفحة",
    //             infoEmpty: "لا يوجد بيانات",
    //             loadingRecords: "يتم تحميل البيانات",
    //             zeroRecords:    "<p style='text-align: center'>لا يوجد بيانات</p>",
    //             emptyTable:     "<p style='text-align: center'>لا يوجد بيانات</p>",
    //             paginate: {
    //                 first:      "الأول",
    //                 previous:   "السابق",
    //                 next:       "التالي",
    //                 last:       "الأخير"
    //             },
    //             aria: {
    //                 sortAscending:  ": ترتيب تصاعدي",
    //                 sortDescending: ": ترتيب تنازلي"
    //             }
    //         },
    //         "columnDefs": [
    //             // { "sortable": false, "targets": [2] }
    //         ],
    //         "aoColumns": [
    //             { "mData": "id" },
    //             { "mData": "book_name" },
    //             { "mData": "graduated_categories" },
    //             { "mData": "required_num" },
    //             { "mData": "completed_num" },
    //             { "mData": "completed_num_percentage" },
    //             { "mData": "excess_num_percentage" }
    //         ]
    //     } );
    // });
</script>
