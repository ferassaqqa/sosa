
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> نسبة إنجاز المناطق لخطة الدورات العلمية لعام {{ $year }} </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <a class="btn btn-success" href="{{ route('plans.areaCoursesProgressPercentageToPrint',['year'=>$year,'department'=>2]) }}" target="_blank">طباعة</a>
    <table class="table table-responsive table-bordered">
        <thead>
            <tr >
                <th rowspan="2" class="center-align">اسم الكتاب:</th>
                <th rowspan="2" class="center-align">الأعداد المطلوبة:</th>
                <th colspan="{{ $areas->count() }}" class="center-align">المناطق:</th>
                <th rowspan="2" class="center-align">الإنجاز:</th>
                <th rowspan="2" class="center-align">فائض الانجاز:</th>
                <th rowspan="2" class="center-align">نسبة التحقيق:</th>
            </tr>
            <tr >
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
                    <td class="in_plan_required_count">{{ $required_count }}</td>
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
                    <td>{{ $done_count }}</td>
                    <td>{{ $done_count > $required_count ? $done_count - $required_count : 0}}</td>
                    <td>{{ $required_count ? round((($done_count * 100)/$required_count), 2) : 0 }}</td>
                </tr>
            @endforeach
                @php
                    $required_count = getOutOfPlanAllAreasTotalRequiredValue($year);
                    $done_count = getOutOfPlanAllAreasTotalDoneValue($year);
                @endphp
                <tr>
                    <td>كتب خارج الخطة</td>
                    <td>{{ $required_count }}</td>
                    @foreach($areas as $area_key => $area)
                        <td>{{getOutOfPlanAreaTotalValue($area->id ,$year )}}</td>
                    @endforeach
                    <td>{{ $done_count }}</td>
                    <td>{{ $required_count ? round((($done_count * 100)/$required_count), 2) : 0 }}</td>
                </tr>
                <tr >
                    <td>المجموع بدون خارج الخطة</td>
                    <td id="totalRequiredCount">
                    </td>
                @foreach($areas as $area_key => $area)
                    @php
                        $requiredAreaCount = 0;
                        $requiredTotalAreaCount = 0;
                        $doneAreaCount = 0;
                    @endphp
                    @foreach($books as $key => $book)
                        @php
                            $requiredAreaCount = $requiredAreaCount + $book->CoursePlansFatherAreaValues($year,$area->id);
                            $doneAreaCount = $doneAreaCount + $book->courses_passed_students_count;
                            $requiredTotalAreaCount = $requiredTotalAreaCount + getBookPlanAreaTotalValue($book->id ,$year);
                        @endphp
                    @endforeach
                    <script>$('#totalRequiredCount').text('{{ $requiredTotalAreaCount }}');</script>
                    <td>
                        {{ $requiredAreaCount }}
                    </td>
                @endforeach
                    <td>
                        {{ $doneAreaCount }}
                    </td>
                    <td>
                        {{ $requiredTotalAreaCount ? round((($doneAreaCount * 100)/$requiredTotalAreaCount), 2) : 0 }}
                    </td>
                </tr>
                <tr >
                    <td>المجموع مع خارج الخطة</td>
                    <td id="outTotalRequiredCount">
                    </td>
                @foreach($areas as $area_key => $area)
                    @php
                        $requiredAreaCount = getOutOfPlanAreaTotalValue($area->id ,$year );
                        $requiredTotalAreaCount = getOutOfPlanAllAreasTotalRequiredValue($year);
                        $doneAreaCount = getOutOfPlanAllAreasTotalDoneValue($year);
                    @endphp
                    @foreach($books as $key => $book)
                        @php
                            $requiredAreaCount = $requiredAreaCount + $book->CoursePlansFatherAreaValues($year,$area->id);
                            $doneAreaCount = $doneAreaCount + $book->courses_passed_students_count;
                            $requiredTotalAreaCount = $requiredTotalAreaCount + getBookPlanAreaTotalValue($book->id ,$year);
                        @endphp
                    @endforeach
                    <script>$('#outTotalRequiredCount').text('{{ $requiredTotalAreaCount }}');</script>
                    <td>
                        {{ $requiredAreaCount }}
                    </td>
                @endforeach
                    <td>
                        {{ $doneAreaCount }}
                    </td>
                    <td>
                        {{ $requiredTotalAreaCount ? round((($doneAreaCount * 100)/$requiredTotalAreaCount), 2) : 0 }}
                    </td>
                </tr>
        </tbody>
    </table>
</div>
<script>
    var totalRequiredCount = 0;
    $('.in_plan_required_count').each(function(i,item){
        totalRequiredCount = totalRequiredCount + parseFloat(item.innerHTML);
    });
</script>

{{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
{{--</div>--}}


<script>
    function CoursePlansFatherAreaSonsValues(year,area_id,book_id) {
        console.log(area_id,book_id);
        $('#user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get('/CoursePlansFatherAreaSonsValues/'+year+'/'+area_id+'/'+book_id,function (data) {
            $('#user_modal_content').empty().html(data);
        });
    }
    function CoursePlansFatherAreaSonsAllBooksValues(year,area_id) {
        $('#user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get('/CoursePlansFatherAreaSonsAllBooksValues/'+year+'/'+area_id,function (data) {
            $('#user_modal_content').empty().html(data);
        });
    }
</script>