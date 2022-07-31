
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> كتب ضمن التصنيف - {{ $asaneedBookCategory->name }} -</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-responsive table-bordered">
        <thead>
            <th>الكتاب:</th>
            <th>المؤلف:</th>
            <th>عدد الدورات:</th>
        </thead>
        <tbody>
        @foreach($asaneedBookCategory->books as $book)
            <tr>
                <td>{{ $book->name }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->courses->count() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
{{--</div>--}}

