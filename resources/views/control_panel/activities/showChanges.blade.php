
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">{{ $activity->description }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
        @if(isset($view[0]) && count($view[0]))
            <label>{{ $attributes_label }}</label>
            <table class="table table-responsive table-bordered">
                <tr class="row mb-3">
                    @foreach ($view[0] as $key => $data)
                        <td class="col-md-2" style="background-color: #F3F3F4;">{{ $key }}</td><td class="col-md-2">{{ $data }}</td>
                    @endforeach
                </tr>
            </table>
        @endif
        @if(isset($view[1]) && count($view[1]))
            <label>البيانات قبل التعديل</label>
            <table class="table table-responsive table-bordered">
                <tr class="row mb-3">
                    @foreach ($view[1] as $key => $data)
                        <td class="col-md-2" style="background-color: #F3F3F4;">{{ $key }}</td><td class="col-md-2">{{ $data }}</td>
                    @endforeach
                </tr>
            </table>
        @endif
</div>

{{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>--}}
{{--</div>--}}