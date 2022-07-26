<style>
    th,
    td {
        border: 1px solid rgb(161, 161, 161) !important;
    }
</style>

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


                                    <td colspan="14"> <b> إنجاز المناطق الكبرى </b></td>

                                    <td rowspan="3"> <b>إجمالي الإنجاز</b></td>
                                    <td rowspan="3"> <b> إجمالي المتبقي </b></td>
                                    <td rowspan="3"> <b> نسبة الإنجاز </b></td>

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


                                @if (isset($value) && count($value) > 0)
                                    @foreach ($value as $index => $val)
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
