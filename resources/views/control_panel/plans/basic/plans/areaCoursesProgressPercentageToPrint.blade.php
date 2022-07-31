<link href="{{asset('control_panel/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
<table class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th rowspan="2" class="center-align">اسم الكتاب:</th>
                <th colspan="{{ $areas->count() }}" class="center-align">المناطق:</th>
                <th rowspan="2" class="center-align">الأعداد المطلوبة:</th>
                <th rowspan="2" class="center-align">الإنجاز:</th>
                <th rowspan="2" class="center-align">نسبة التحقيق:</th>
            </tr>
            <tr>
                @foreach($areas as $area_key => $area)
                    <th id="area_{{ $area->id }}"><a href="#!" ></a></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($books as $key => $book)
                @php
                    $required_count = getBookPlanAreaTotalValue($book->id ,$year);
                    $done_count = $book->courses_passed_students_count;
                @endphp
                <tr>
                    <td>{{ $book->name }}</td>
                    @foreach($areas as $area_key => $area)
                        @if(!$key)
                            <script>
                                $('#area_{{ $area->id }}').find('a').text('{{ $area->getAreaNameWithPercentage($year,$book->id) }}');
                                {{--console.log($('#area_{{ $area->id }}').find('a'));--}}
                                $('#area_{{ $area->id }}').find('a').on('click',function(){
                                    CoursePlansFatherAreaSonsAllBooksValues('{{$year}}','{{$area->id}}');
                                });
                            </script>
                        @endif
                        <td>{!! '<a href="#!" onclick="CoursePlansFatherAreaSonsValues(\''.$year.'\',\''.$area->id.'\',\''.$book->id.'\')">'.$book->CoursePlansFatherAreaValues($year,$area->id).'</a>' !!}</td>
                    @endforeach
                    <td>{{ $required_count }}</td>
                    <td>{{ $done_count }}</td>
                    <td>{{ $required_count ? round((($done_count * 100)/$required_count), 2) : 0 }}</td>
                </tr>
            @endforeach
                @php
                    $required_count = getOutOfPlanAllAreasTotalRequiredValue($year);
                    $done_count = getOutOfPlanAllAreasTotalDoneValue($year);
                @endphp
                <tr>
                    <td>كتب خارج الخطة</td>
                    @foreach($areas as $area_key => $area)
                        <td>{{getOutOfPlanAreaTotalValue($area->id ,$year )}}</td>
                    @endforeach
                    <td>{{ $required_count }}</td>
                    <td>{{ $done_count }}</td>
                    <td>{{ $required_count ? round((($done_count * 100)/$required_count), 2) : 0 }}</td>
                </tr>
        </tbody>
    </table>