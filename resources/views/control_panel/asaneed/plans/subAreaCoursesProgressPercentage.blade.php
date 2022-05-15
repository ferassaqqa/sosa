
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> نسبة إنجاز المناطق لخطة الاسانيد والاجازات لعام - {{ $year }}  - منطقة {{ $main_area->name }} </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
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
                    <th>{{ $area->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
                @php
                    $required_count = getAsaneedBookPlanAreaIdTotalValue($book->id ,$year,$main_area->id);
                    $done_count = $book->getAreaCoursesPassedStudentsCount($main_area->id);
                @endphp
                <tr>
                    <td>{{ $book->name }}</td>
                    @foreach($areas as $area_key => $area)
                        <td>{{ $book->CoursePlansSubAreaValues($year,$area->id) }}</td>
                    @endforeach
                    <td>{{ $required_count }}</td>
                    <td>{{ $done_count }}</td>
                    <td>{{ $required_count ? round((($done_count * 100)/$required_count), 2) : 0 }}</td>
                </tr>
        </tbody>
    </table>
</div>

<div class="modal-footer">
    <a href="#!" class="btn btn-secondary waves-effect" onclick="areaCoursesProgressPercentage('{{ route('asaneedPlans.areaCoursesProgressPercentage', ['year' => $year]) }}')"> رجوع </a>
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal" >رجوع</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
</div>


<script>
    function areaCoursesProgressPercentage(link) {
        $('#user_modal_content')
            .html(
                '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' +
                '   <span class="sr-only">يرجى الانتظار ...</span>' +
                '</div>'
            );
        $.get(link,function (data){
            $('#user_modal_content').empty().html(data);
        });
    }
</script>