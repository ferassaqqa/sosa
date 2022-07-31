@csrf

<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered mb-0 text-center">
            <thead>
                <tr>
                    <th rowspan="2" class="center-align">نوع الكتاب</th>
                    <th colspan="4" class="center-align">عدد الاحاديث المطلوبة للحلقات المكفولة</th>
                    <th colspan="4" class="center-align">عدد الأحاديث المطلوبة للحلقات المتطوعة</th>
                </tr>
                <tr>
                    <th class="center-align">سنوي</th>
                    <th colspan="2" class="center-align">فصلي</th>
                    <th class="center-align">شهري</th>
                    <th class="center-align">سنوي</th>
                    <th colspan="2" class="center-align">فصلي</th>
                    <th class="center-align">شهري</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $key => $book)
                    <tr>
                        <td>{{ $book->name }}</td>
                        {{--<td>{{ $book->plan->guaranteed_yearly }}</td>--}}
                        {{--<td>{{ $book->plan->guaranteed_semesterly }}</td>--}}
                        {{--<td>{{ $book->plan->guaranteed_monthly }}</td>--}}
                        {{--<td>{{ $book->plan->volunteer_yearly }}</td>--}}
                        {{--<td>{{ $book->plan->volunteer_semesterly }}</td>--}}
                        {{--<td>{{ $book->plan->volunteer_monthly }}</td>--}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<input type="hidden" name="year" value="{{ $year }}">
