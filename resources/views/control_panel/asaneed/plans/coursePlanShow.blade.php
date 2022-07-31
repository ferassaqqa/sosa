
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> بيانات الخطة والانجازات في الأسانيد والإجازات للعام {{ $year }}  </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <?php
    $sub_area_count = 0;
    $larger_sub_area_count = 0;
    ?>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center">
                <tr style="background-color: #F9FAFB; ">
                    <th rowspan="1" class="center-align">اسم الكتاب</th>
                    <th colspan="1" class="th_header_label center-align">اسم المنطقة</th>
                </tr>
                @foreach($areas as $key => $area)
                    <?php
                    $sub_area_count = 0;
                    ?>
                    <tr style="background-color: #F9FAFB; ">
                        <th></th>
                        <th class="th_header center-align" id="area_{{ $area->id }}"></th>
                        <th>
                            <input disabled class="form-control area_{{$area->id}}" value="{{getAsaneedCoursePlanAreaTotalValue($area->id ,$year )}}" type="number" min="0" step="1" name="area_total_value[{{$area->id}}]" style="width: 92px;margin: 0px auto;">
                        </th>
                    </tr>
                    <tr>
                    <tr>
                        <td style="background-color: #F9FAFB; "></td>
                        @foreach($area->subArea as $key => $subArea)
                            <?php $sub_area_count++;?>
                            <th id="area_{{ $subArea->id }}">{{ $subArea->name }}</th>
                        @endforeach
                    </tr>
                    <?php
                    ($sub_area_count > $larger_sub_area_count) ? ($larger_sub_area_count = $sub_area_count) : '';
                    ?>

                    @if(isset($books))
                        @foreach($books as $key => $book)
                                @if(!$key)
                                    <script>
                                        $('#area_{{ $area->id }}').text('{{ $area->getAreaNameWithPercentageForAsaneed($year,$book->id) }}');
                                    </script>
                                @endif
                            <tr>
                                <td style="background-color: #F9FAFB; ">{{ $book->name }}</td>
                                @foreach($area->subArea as $key => $subArea)
                                    <script>
                                        $('#area_{{ $subArea->id }}').text('{{ $subArea->getAreaNameWithPercentageForAsaneed($year,$book->id) }}');
                                    </script>
                                    <td>
                                        <input disabled class="form-control subArea_{{$subArea->id}}" value="{{getAsaneedCoursePlanSubAreaBookValue($subArea->id, $book->id ,$year )}}" type="number" min="0" step="1" name="sub_area_value[{{$area->id}}][{{$subArea->id}}][{{$book->id}}]" style="width: 92px;margin: 0px auto;">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                            <tr>
                                <td style="background-color: #F9FAFB; ">كتب خارج الخطة</td>
                                @foreach($area->subArea as $key => $subArea)
                                    <td>
                                        <input disabled class="form-control subArea_{{$subArea->id}}" value="{{getAsaneedCoursePlanSubAreaBookValue($subArea->id, null ,$year )}}" oninput="checkTotalLimit(this,'{{ $subArea->id }}')" type="number" min="0" step="1" name="sub_area_value[{{$area->id}}][{{$subArea->id}}][{{$book->id}}]" style="width: 92px;margin: 0px auto;">
                                    </td>
                                @endforeach
                            </tr>
                    @endif
                    <script>
                        $('.th_header_label').attr('colspan','{{ $larger_sub_area_count }}');
                        $('.th_header').attr('colspan','{{ $larger_sub_area_count-1 }}');
                    </script>
                @endforeach
            </table>
        </div>
    </div>
</div>

{{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">تعديل</button>--}}
{{--</div>--}}

<script>
    $('#form').submit(function(event){
        var form = $('#form input').filter(function () {
            return !!this.value;
        });
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        $.ajax({
            url:$(this).attr('action'),
            type:$(this).attr('method'),
            data:form.serialize(),
            success:function(result){
                $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                    { allow_dismiss: true,type:result.type }
                );
                document.querySelector('button[data-bs-dismiss="modal"]').click();
                $('#dataTable').DataTable().ajax.reload();
            },
            error:function (errors) {
                const entries = Object.entries(errors.responseJSON.errors);
                for(let x of entries){
                    var errors_message = '';
                    if(x[0].includes('.')){
                        errors_message = document.createElement('div');
                        var key = x[0].split('.');
                        errors_message.classList.add('invalid-feedback');
                        errors_message.classList.add('show');
                        document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].classList.add('is-invalid');
                        errors_message.innerHTML = x[1][0];
                        document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].parentElement.appendChild(errors_message);
                    }else {
                        errors_message = document.createElement('div');
                        errors_message.classList.add('invalid-feedback');
                        errors_message.classList.add('show');
                        document.querySelector('input[name="' + x[0] + '"]').classList.add('is-invalid');
                        errors_message.innerHTML = x[1][0];
                        document.querySelector('input[name="' + x[0] + '"]').parentElement.appendChild(errors_message);
                    }
                }
            }

        });
        return false;
    });
</script>