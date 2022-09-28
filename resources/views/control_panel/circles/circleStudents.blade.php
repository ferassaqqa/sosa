
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> طلاب حلقة المحفظ {{$circle->teacher_name}}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-responsive">
        <thead>
            <th>#</th>
            <th>الاسم</th>
            <th>المكان</th>
            {{-- <th>تاريخ البداية</th> --}}
            <th>تاريخ الميلاد</th>
            <th>مكان الميلاد</th>
            @if(hasPermissionHelper('حذف بيانات الاسانيد'))
                <th>حذف</th>
            @endif
        </thead>
        <tbody>
        @foreach($circle->students as $key => $student)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->place_name }}</td>
                {{-- <td>{{ $circle->start_date }}</td> --}}
                <td>{{ $student->dob }}</td>
                <td>{{ $student->pob }}</td>
                {{-- <td>{{ $circle->start_date }}</td> --}}

            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">

</div>


<script></script>
