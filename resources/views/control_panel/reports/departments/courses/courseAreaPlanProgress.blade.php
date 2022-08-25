<style>
    th,
    td {
        border: 1px solid rgb(161, 161, 161) !important;
    }

    h3 {
        padding: 20px;
    }
</style>
<h3>تقرير انجاز المناطق الكبرى للدورات العلمية</h3>

@if ($_REQUEST['area_id'])
    {{ $colspan = 2 }}
@else
    {{ $colspan = 14 }}
@endif

<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">


                <div id="tableContainer">

                    <div class="table-responsive-md">
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


                                    <td colspan="{{$colspan}}"> <b> إنجاز المناطق الكبرى </b></td>

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


                                <td colspan="20"><b>برنامج الصفوة</b></td>
                                @if (isset($project_books_value) && count($project_books_value) > 0)
                                    @foreach ($project_books_value as $index => $val)
                                        {!! $val !!}
                                    @endforeach
                                @endif


                                <td colspan="20"><b>كتب خارج الخطة</b></td>
                                @if (isset($out_plane_books_value) && count($out_plane_books_value) > 0)
                                    @foreach ($out_plane_books_value as $index => $val)
                                        {!! $val !!}
                                    @endforeach
                                @endif



                            </tbody>

                        </table>
                    </div>





                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
