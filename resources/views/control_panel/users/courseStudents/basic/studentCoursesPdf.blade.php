<style type="text/css">
    body {
        font-family: DejaVu Sans, sans-serif;
        direction: rtl;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 50px auto;
        direction: rtl;

    }


    tr:nth-of-type(odd) {
        background: #eee;
    }

    th {
        background: #0bb197;
        color: white;
        font-weight: bold;
    }

    td,
    th {
        padding: 5px;
        border: 1px solid #ccc;
        text-align: center;
        font-size: 16px;
    }


</style>

<h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> دورات الطالب {{ $user->name }}</h5>


<table class="table">
    <thead>

        <th>استلام الشهادة</th>
        <th>الدرجة</th>
        <th>التقدير</th>
        <th>عدد الساعات</th>
        <th>المشرف العام</th>
        <th>المشرف الميداني</th>
        <th>العنوان</th>
        <th>اسم المعلم</th>
        <th>اسم الدورة</th>




    </thead>
    <tbody>
        @foreach ($courses as $key => $course)
            <tr>

                <td>{{ $course->status == 'منتهية' ? 'تم الاستلام' : 'لم يتم الاستلام'}}</td>
                <td>{{ $course->exam ? ($course->exam->status == 5 ? ($course->pivot->mark ? $course->pivot->mark : 'لم يتم رصد الدرجات') : 'انتظار اعتماد الدرجات' ): 'لم يختبر بعد' }}</td>
                <td>{!! $course->exam->status == 5 ? ($course->pivot->mark ? markEstimationText($course->pivot->mark) : '-'):'-'  !!}</td>

                <td>{{ $course->book->hours_count }}</td>
                <td>{{ $course->area_supervisor_name }}</td>
                <td>{{ $course->sub_area_supervisor_name }}</td>
                <td>{{ $course->place_full_name }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->book_name }}</td>


            </tr>
        @endforeach
    </tbody>
</table>
