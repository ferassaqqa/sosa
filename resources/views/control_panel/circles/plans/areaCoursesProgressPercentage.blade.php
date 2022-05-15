
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> نسبة إنجاز المناطق لخطة الدورات العلمية لعام {{ $year }} </h5>
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
            @foreach($books as $key => $book)
                @php
                    $required_count = getBookPlanAreaTotalValue($book->id ,$year);
                    $done_count = $book->courses_passed_students_count;
                @endphp
                <tr>
                    <td>{{ $book->name }}</td>
                    @foreach($areas as $area_key => $area)
                        <td>{{ $book->CoursePlansFatherAreaValues($year,$area->id) }}</td>
                    @endforeach
                    <td>{{ $required_count }}</td>
                    <td>{{ $done_count }}</td>
                    <td>{{ $required_count ? round((($done_count * 100)/$required_count), 2) : 0 }}</td>
                </tr>
            @endforeach
                <tr>
                    <td>كتب خارج الخطة</td>
                    @foreach($areas as $area_key => $area)
                        <td>{{ $book->CoursePlansFatherAreaValues($year,$area->id) }}</td>
                    @endforeach
                    <td>{{ $required_count }}</td>
                    <td>{{ $done_count }}</td>
                    <td>{{ $required_count ? round((($done_count * 100)/$required_count), 2) : 0 }}</td>
                </tr>
        </tbody>
    </table>
</div>

{{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
{{--</div>--}}