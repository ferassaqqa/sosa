<style>
    th,
    td {
        border: 1px solid rgb(161, 161, 161) !important;
    }

    h3 {
        padding: 20px;
    }


</style>

    <div style="color: #9fa4a4; font-size: 2rem; text-align:center;">تقرير الإنجاز العام للخطة - الدورات العلمية - {{ $area_des }}</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-centered table_bordered" style="font-size: 18px;">
                    <thead>
                        <tr>
                            <th>العدد المطلوب</th>
                            <th>العدد المنجز</th>
                            <th>نسبة الانجاز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="main_statistics" style="font-size: 2rem; ">
                            <td>{{ $plan_total_required }}</td>
                            <td>{{ $plan_total_passed }}</td>
                            <td>{{ $total_plan_percentage }} %</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<h3>كتب داخل الخطة</h3>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table " style="font-size: 1rem; ">
                        <thead>
                            <col>
                            <col>
                            <col style="background-color: #f3f3f4">
                            <colgroup span="14"></colgroup>

                            <col style="background-color: #f3f3f4">
                            <col style="background-color: #f3f3f4">
                            <col style="background-color: #f3f3f4">

                            <tr style="background-color: #f3f3f4">
                                <td rowspan="3" style="width:50px; ">#</td>
                                <td rowspan="3"> <b> الدورة </b></td>
                                <td rowspan="3"> <b> إجمالي العدد المطلوب </b></td>


                                <td colspan="{{ $colspan }}"> <b> إنجاز المناطق الكبرى </b></td>

                                <td rowspan="3"> <b>إجمالي الإنجاز</b></td>
                                <td rowspan="3"> <b> إجمالي المتبقي </b></td>

                                <td rowspan="3"> <b> نسبة الإنجاز </b></td>
                                <td rowspan="3"> <b> نسبة الفائض </b></td>


                            </tr>


                            <tr style="background-color: #f3f3f4">
                                @if (isset($areas) && count($areas) > 0)
                                    @foreach ($areas as $index => $val)
                                        <td colspan="2" scope="col"> {!! $val->name !!} </td>
                                    @endforeach
                                @endif
                            </tr>


                            <tr style="background-color: #f3f3f4">
                                @if (isset($areas) && count($areas) > 0)
                                    @foreach ($areas as $index => $val)
                                        <td style="border: 1px solid black;" scope="col">المنجز</td>
                                        <td style="border: 1px solid black;" scope="col">المتبقي</td>
                                    @endforeach
                                @endif
                            </tr>

                        </thead>


                        <tbody>


                            @if (isset($in_plane_books_value) && count($in_plane_books_value) > 0)
                                @foreach ($in_plane_books_value as $index => $val)
                                    {!! $val !!}
                                @endforeach
                            @endif

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<h3>برنامج الصفوة</h3>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">



                <table class="table " style="font-size: 1rem; ">
                    <thead>
                        <col>
                        <col>
                        <col style="background-color: #f3f3f4">
                        <colgroup span="14"></colgroup>

                        <col style="background-color: #f3f3f4">
                        <col style="background-color: #f3f3f4">
                        <col style="background-color: #f3f3f4">

                        <tr style="background-color: #f3f3f4; font-size: 1rem;">
                            <td rowspan="3" style="width:50px; ">#</td>
                            <td rowspan="3"> <b> الدورة </b></td>
                            <td rowspan="3"> <b> إجمالي العدد المطلوب </b></td>


                            <td colspan="{{ $colspan }}"> <b> إنجاز المناطق الكبرى </b></td>

                            <td rowspan="3"> <b>إجمالي الإنجاز</b></td>
                            <td rowspan="3"> <b> إجمالي المتبقي </b></td>

                            <td rowspan="3"> <b> نسبة الإنجاز </b></td>
                            <td rowspan="3"> <b> نسبة الفائض </b></td>


                        </tr>


                        <tr style="background-color: #f3f3f4">
                            @if (isset($areas) && count($areas) > 0)
                                @foreach ($areas as $index => $val)
                                    <td colspan="2" scope="col"> {!! $val->name !!} </td>
                                @endforeach
                            @endif
                        </tr>


                        <tr style="background-color: #f3f3f4">
                            @if (isset($areas) && count($areas) > 0)
                                @foreach ($areas as $index => $val)
                                    <td style="border: 1px solid black;" scope="col">المنجز</td>
                                    <td style="border: 1px solid black;" scope="col">المتبقي</td>
                                @endforeach
                            @endif
                        </tr>

                    </thead>


                    <tbody>

                        @if (isset($project_books_value) && count($project_books_value) > 0)
                            @foreach ($project_books_value as $index => $val)
                                {!! $val !!}
                            @endforeach
                        @endif



                        {{-- @if (isset($out_plane_books_value) && count($out_plane_books_value) > 0)
                            <td colspan="20"><b>كتب خارج الخطة</b></td>
                                @foreach ($out_plane_books_value as $index => $val)
                                    {!! $val !!}
                                @endforeach
                            @endif --}}
                    </tbody>

                </table>


            </div>
        </div>
    </div>
</div>


@if ($out_plane_books_value)
    <h3> الكتب الفائضة</h3>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <table class="table " style="font-size: 16px;">
                        <thead>
                            <col>
                            <col>
                            <col style="background-color: #f3f3f4">
                            <colgroup span="14"></colgroup>

                            <col style="background-color: #f3f3f4">
                            <col style="background-color: #f3f3f4">
                            <col style="background-color: #f3f3f4">

                            <tr style="background-color: #f3f3f4">
                                <td rowspan="3" style="width:50px; ">#</td>
                                <td rowspan="3"> <b> الدورة </b></td>
                                <td rowspan="3"> <b> إجمالي العدد المطلوب </b></td>


                                <td colspan="{{ $colspan }}"> <b> إنجاز المناطق الكبرى </b></td>

                                <td rowspan="3"> <b>إجمالي الإنجاز</b></td>
                                <td rowspan="3"> <b> إجمالي المتبقي </b></td>

                                <td rowspan="3"> <b> نسبة الإنجاز </b></td>
                                <td rowspan="3"> <b> نسبة الفائض </b></td>


                            </tr>


                            <tr style="background-color: #f3f3f4">
                                @if (isset($areas) && count($areas) > 0)
                                    @foreach ($areas as $index => $val)
                                        <td colspan="2" scope="col"> {!! $val->name !!} </td>
                                    @endforeach
                                @endif
                            </tr>


                            <tr style="background-color: #f3f3f4">
                                @if (isset($areas) && count($areas) > 0)
                                    @foreach ($areas as $index => $val)
                                        <td style="border: 1px solid black;" scope="col">المنجز</td>
                                        <td style="border: 1px solid black;" scope="col">المتبقي</td>
                                    @endforeach
                                @endif
                            </tr>

                        </thead>


                        <tbody>

                            @if (isset($out_plane_books_value) && count($out_plane_books_value) > 0)
                                {{-- <td colspan="20"><b>كتب خارج الخطة</b></td> --}}
                                @foreach ($out_plane_books_value as $index => $val)
                                    {!! $val !!}
                                @endforeach
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
