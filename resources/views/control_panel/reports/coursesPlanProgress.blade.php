
<style>
    h3{
        padding: 20px;
    }
</style>


<h2>تقرير انجاز خطة الدورات العلمية</h2>


<h3>  كتب داخل الخطة   </h3>
<div class="row">
    <div class="row justify-content-md-center">


                        <table class="table table-centered   table-nowrap mb-0"  style="font-size: 18px">

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


                                @if (isset($in_plane_books_value) && count($in_plane_books_value) > 0)
                                @foreach ($in_plane_books_value as $index => $val)
                                             {!! $val !!}
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" style="color: #00734b" scope="col"><b >  لا يوجد كتب داخل الخطة   </b></td>
                                </tr>
                                @endif

                            </tbody>

                        </table>
    </div>

</div>
<h3>  كتب خارج الخطة  </h3>
<div class="row">
    <div class="row justify-content-md-center">


                        <table class="table table-centered   table-nowrap mb-0"  style="font-size: 18px">

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


                                @if (isset($out_plane_books_value) && count($out_plane_books_value) > 0)
                                @foreach ($out_plane_books_value as $index => $val)
                                             {!! $val !!}
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" style="color: #00734b" scope="col"><b>  لا يوجد لبرنامج الصفوة   </b></td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
    </div>
</div>

<div class="row">
    <div class="row justify-content-md-center">

                        <h3>  برنامج الصفوة  </h3>
                        <table class="table table-centered   table-nowrap mb-0"  style="font-size: 18px">

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




                                @if (isset($project_books_value) && count($project_books_value) > 0)
                                @foreach ($project_books_value as $index => $val)
                                             {!! $val !!}
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" style="color: #00734b" scope="col"><b>  لا يوجد كتب خارج الخطة   </b></td>
                                </tr>
                               
                                @endif

                            </tbody>
                        </table>
    </div>
</div>


