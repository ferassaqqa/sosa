
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> طلاب حلقة المحفظ {{$circle->teacher_name}}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-responsive">
        <thead>
            <th>الاسم</th>
            <th>المكان</th>
            <th>تاريخ البداية</th>
        </thead>
        <tbody>
        @foreach($circle->students as $key => $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $circle->place_name }}</td>
                <td>{{ $circle->start_date }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button class="btn btn-primary waves-effect waves-light" data-url="{{ route('circles.restoreItem',$circle->id) }}" onclick="restoreItem(this)">استرجاع</button>--}}
</div>
