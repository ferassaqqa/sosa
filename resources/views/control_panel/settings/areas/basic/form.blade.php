@csrf
<div class="mb-3 row">
    <label for="name" class="col-md-3 col-form-label">الاسم</label>
    <div class="col-md-6">
        <input class="form-control" type="text" name="name" value="{{old('name',$area->name)}}" id="name" placeholder="منطقة غزة" required>
    </div>
    <div class="col-md-3">
        <input class="form-control" type="number" min="0" step="0.01" name="area_percentage" value="{{old('area_percentage',$area->percentage)}}" id="area_percentage" placeholder="نسبة المنطقة" oninput="checkAreaTotalPercentage('{{ $area->id }}',this)">
    </div>
</div>
<div id="area_sub_areas">
    <div class="mb-3 row">
        <div class="col-md-10">
            <label> المناطق الفرعية </label>
        </div>
        <div class="col-md-2">
            <button type="button" title="إضافة منطقة فرعية جديدة" class="btn btn-primary waves-effect waves-light" onclick="addValue()"><i class="mdi mdi-plus"></i></button>
        </div>
    </div>
    @if(isset($area->subArea) && $area->subArea->count())
        @foreach($area->subArea as $subArea)
            <div class="mb-3 row">
                <div class="col-md-8">
                    <input class="form-control" type="text" name="subArea[{{ $subArea->id }}]" value="{{$subArea->name}}" id="subArea" placeholder="غرب غزة" required />
                    <input type="hidden" value="{{ $subArea->id }}" name="subArea_id[{{ $subArea->id }}]">
                </div>
                <div class="col-md-2">
                    <input class="form-control sub_area_percentage" type="number" min="0" step="0.01" name="percentage[{{ $subArea->id }}]" oninput="checkSubAreaTotalPercentage(this)" value="{{$subArea->percentage}}" placeholder="نسبة المنطقة في الخطة السنوية"/>
                </div>
                <div class="col-md-2">
                    <button type="button" title="حذف" data-id="{{ $subArea->id }}" class="btn btn-danger waves-effect waves-light remove-subArea"><i class="mdi mdi-close"></i></button>
                </div>
            </div>
        @endforeach
    @endif
</div>
<input type="hidden" name="id" value="{{ $area->id }}"/>

<script>
    function addValue() {
        var new_sub_area = document.createElement('div');
        new_sub_area.classList.add('mb-3','row');
        new_sub_area.innerHTML =
            '<div class="mb-3 row">' +
            '   <div class="col-md-8">' +
            '       <input class="form-control" type="text" name="subArea[]" id="subArea" placeholder="غرب غزة" />' +
            '   </div>' +
            '   <div class="col-md-2">' +
            '       <input class="form-control" type="number" min="0" step="0.01" oninput="checkSubAreaTotalPercentage(this)" name="percentage[]" placeholder="نسبة المنطقة في الخطة السنوية"/>' +
            '   </div>'+
            '   <div class="col-md-2">' +
            '       <button type="button" title="حذف" class="btn btn-danger waves-effect waves-light remove-subArea"><i class="mdi mdi-close"></i></button>' +
            '   </div>' +
            '</div>';
        $('#area_sub_areas').append(new_sub_area);
        
        $('.remove-subArea').on('click', function() {
            var thisButton = $(this);
            if(typeof $(this).data('id') !='undefined') {
                $.get('deleteSubArea/' + $(this).data('id'), function (data) {
                    if(data.type == 'danger') {
                        Swal.fire('خطأ !',data.msg,'error');
                    }else {
                        thisButton.parent().parent().remove();
                    }
                });
            }else{
                thisButton.parent().parent().parent().remove();
            }
        });
    }
    $('.remove-subArea').on('click', function() {
        var thisButton = $(this);
        if(typeof $(this).data('id') !='undefined') {
            $.get('deleteSubArea/' + $(this).data('id'), function (data) {
                if(data.type == 'danger') {
                    Swal.fire('خطأ !',data.msg,'error');
                }else {
                    thisButton.parent().parent().remove();
                }
            });
        }else{
            thisButton.parent().parent().remove();
        }
    });
    function checkAreaTotalPercentage(area_id,obj) {
        if(obj.value) {
            $('span[class="error"]').remove();
            var inputs = $('.error');
            inputs.css('color','#000');
            inputs.removeClass('error');
            $.get('checkAreaTotalPercentage/' + area_id+ '/' + obj.value, function (data) {
                // console.log(data);
                if (data) {
                    obj.style.color = "red";
                    obj.classList.add('error');
                    $(obj).parent().append('<span style="color: red;" class="error">تخطى الحد الأعلى %100</span>');
                }
            });
        }
    }
    var sub_area_old_value = 0;
    var sub_area_update_value_iteration = 0;
    function checkSubAreaTotalPercentage(obj) {
        if(!sub_area_update_value_iteration){
            sub_area_old_value = obj.value-1;
            sub_area_update_value_iteration++;
        }else{
            sub_area_update_value_iteration++;
        }
        $('span[class="error"]').remove();
        var inputs = $('.error');
        inputs.css('color','#000');
        inputs.removeClass('error');
        var sum_amount = 0;
        $('.sub_area_percentage').each(function(){
            sum_amount += +$(this).val();
        });
        if(sum_amount>100){
            $(obj).val(sub_area_old_value);
            $(obj).css('color' , "red");
            $(obj).addClass('error');
            $(obj).parent().append('<span style="color: red;" class="error">تخطى الحد الأعلى %100</span>');
        }
    }
</script>