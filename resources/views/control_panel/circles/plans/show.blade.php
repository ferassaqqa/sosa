
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> الخطة السنوية - تحفيظ السنة النبوية - للعام {{ $year }}  </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center">
                <thead>
                    <tr>
                        <th rowspan="2" class="center-align">نوع الكتاب</th>
                        <th colspan="3" class="center-align">عدد الاحاديث المطلوبة للحلقات المكفولة</th>
                        <th colspan="3" class="center-align">عدد الأحاديث المطلوبة للحلقات المتطوعة</th>
                    </tr>
                    <tr>
                        <th class="center-align">سنوي</th>
                        <th class="center-align">فصلي</th>
                        <th class="center-align">شهري</th>
                        <th class="center-align">سنوي</th>
                        <th class="center-align">فصلي</th>
                        <th class="center-align">شهري</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $key => $book)
                        <tr data-book-id="{{ $book->id }}">
                            <td>{{ $book->name }}</td>
                            {!! $book->bookPlanDataForTable($year) !!}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('.BookPlanData').on('change',function () {
        var input = $(this);
        var book_id = input.closest('tr').data('book-id');
        var input_name = input.attr('name');
        var input_value = input.val();
        if(book_id && input_name && input_value){
            $.get('/updateCircleBookPlan/{{ $year }}/'+book_id+'/'+input_value+'?input_name='+input_name,function (data) {
                
            });
        }
    });
</script>


