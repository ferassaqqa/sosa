<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">


                <style>                  
                    th,
                    td {
                        border: 1px solid rgb(161, 161, 161) !important;
                    }
                </style>

                <div class="row mb-3" id="custom_filters"></div>
                <div id="tableContainer">



                    <table class="table table-centered table-bordered  table-nowrap mb-0" id="dataTable"
                        style="font-size: 16px">

                        <thead>
                            <tr>
                                <th colspan="11" scope="col">تفصيل تقييم قسم الدورات</th>
                            </tr>


                            <col style="background: #f0f0f0">
                            <colgroup style="background: #f0f0f0" span="6"></colgroup>
                            <col style="background: #f0f0f0">
                            <col style="background: #f0f0f0">
                            <col style="background: #f0f0f0">
                            <col style="background: #f0f0f0">
                            <col style="background: #f0f0f0">


                            <tr>
                                <td rowspan="2" style="width:50px; ">#</td>
                                <td rowspan="2"> <b> المنطقة </b></td>


                                <td colspan="6"> <b> بنود تقييم قسم الدورات </b></td>

                                <td rowspan="2"> <b>التقييم العام (100%)</b></td>
                                <td rowspan="2"> <b> الترتيب </b></td>
                                <td rowspan="2"> <b> ملحوظات </b></td>

                            </tr>


                            <tr>
                                <td scope="col1">دورات الخطة (38%)</td>
                                <td scope="col1">جودة الاختبارات (5%)</td>
                                <td scope="col1">تمييز فائض الخريجين (2%)</td>
                                <td scope="col1">فئات الخريجين (3%)</td>
                                <td scope="col1"> برنامج الصفوة (2%)</td>

                                <td scope="col1"> <b>التقييم العام (50%)</b></td>
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





                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
