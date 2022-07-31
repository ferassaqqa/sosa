@csrf
<div class="mb-3 row">
    <label for="name" class="col-md-3 col-form-label">الاسم</label>
    <div class="col-md-9">
        <input class="form-control" type="text" name="name" value="{{old('name',$place->name)}}" id="name" required>
    </div>
</div>
<div class="mb-3 row">
    <label for="area_name" class="col-md-3 col-form-label">المنطقة</label>
    <div class="col-md-9">
        <input class="form-control area_name" type="text" name="area_name" value="{{old('area_name',$place->area_name)}}" id="area_name" required>
        <input class="form-control" type="hidden" name="area_id" value="{{old('area_id',$place->area_id)}}">
        <div>
            <ul class="list-group" style="position: absolute; z-index: 999;">

            </ul>
        </div>
    </div>
</div>
<input type="hidden" name="id" value="{{ $place->id }}">
<script>
    var area_name_request_count = 0;
    $(document).unbind('input').on('input', ".area_name", function() {
        var button = $(this);
        if(typeof $(this).val() !='undefined' && $(this).val().length) {
            area_name_request_count++;
            $.get('searchAreaForPlaces/' + $(this).val()+'/'+area_name_request_count, function (data) {
                if(data[1] == area_name_request_count && button.val()) {
                    $('.list-group').html(data[0]);
                    $('.list-group-item').css('cursor','pointer')
                }
            });
        }else{
            $('.list-group').html('');
        }
    });
    $(document).unbind('click').on('click', ".selected-area", function() {
        var area_name = $(this).data('name');
        var area_id = $(this).data('id');
        $('input[name="area_name"]').val(area_name);
        $('input[name="area_id"]').val(area_id);
        $('.list-group').html('');
    });
</script>