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


                @if (!empty($created_at))
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">تاريخ التحديث {{ $created_at }}</h4>
                            </div>
                        </div>
                    </div>
                @endif


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

                                @if (!$is_sub_area)
                                    <td colspan="6"> <b> بنود تقييم قسم الدورات </b></td>
                                @else
                                <td colspan="5"> <b> بنود تقييم قسم الدورات </b></td>
                                @endif


                                <td rowspan="2"> <b>التقييم العام (100%)</b></td>
                                <td rowspan="2"> <b> الترتيب </b></td>
                                <td rowspan="2"> <b> ملحوظات </b></td>

                            </tr>


                            <tr>

                                @if (!$is_sub_area)
                                    <td scope="col1">دورات الخطة (38%)</td>
                                    <td scope="col1">جودة الاختبارات (5%)</td>
                                    <td scope="col1">تمييز فائض الخريجين (2%)</td>
                                    <td scope="col1">فئات الخريجين (3%)</td>
                                    <td scope="col1"> برنامج الصفوة (2%)</td>
                                    <td scope="col1"> <b>التقييم العام (50%)</b></td>
                                @else
                                    <td scope="col1">دورات الخطة (40%)</td>
                                    <td scope="col1">جودة الاختبارات (5%)</td>
                                    <td scope="col1">تمييز فائض الخريجين (2%)</td>
                                    <td scope="col1">فئات الخريجين (3%)</td>
                                    <td scope="col1"> <b>التقييم العام (50%)</b></td>
                                @endif

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
