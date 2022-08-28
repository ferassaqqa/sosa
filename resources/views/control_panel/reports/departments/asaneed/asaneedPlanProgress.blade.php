
<style>
    h3{
        padding: 20px;
    }
</style>


<h2>تقرير انجاز خطة دورات الاسانيد</h2>

<div class="row">
    <div class="row justify-content-md-center">


                        <table class="table table-centered   table-nowrap mb-0"  style="font-size: 18px">

                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th scope="col"  style="width: 50px;">الكتاب</th>

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
                                @else
                                <tr>
                                    <td colspan="7" style="color: #00734b" scope="col"><b >  لا يوجد كتب داخل الخطة   </b></td>
                                </tr>
                                @endif

                            </tbody>

                        </table>
    </div>

</div>



