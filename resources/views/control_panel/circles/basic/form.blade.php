@csrf

<div class="mb-3 row">
    <label for="start_date" class="col-md-3 col-form-label">بداية الحلقة</label>
    <div class="col-md-9">
        <div class="input-group" id="datepicker1">
            <input type="text" class="form-control" placeholder="تاريخ بداية الحلقة" name="start_date"  autocomplete="off"
                value="{{ old('start_date', $circle->start_date) }}" id="start_date" data-date-format="yyyy-mm-dd"
                data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true">
            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
        </div>
    </div>
</div>


<div class="mb-3 row">
    <label for="included_in_plan" class="col-md-3 col-form-label">المنطقة الكبرى:</label>
    <div class="col-md-3">
        <select class="form-control" name="area_id">
            <option value="">-- تحديد --</option>
            @foreach ($areas as $key => $area)
                <option value="{{ $area->id }}" @if ($area->id == $circle->area_father_id) selected @endif>
                    {{ $area->name }}</option>
            @endforeach
        </select>
    </div>

    <label for="included_in_plan" class="col-md-2 col-form-label">المحلية:</label>
    <div class="col-md-4">
        <select class="form-control" name="sub_area_id" id="sub_area_id">
            @if (isset($sub_areas))
                @foreach ($sub_areas as $key => $area)
                    <option value="{{ $area->id }}" @if ($area->id == $circle->area_id) selected @endif>
                        {{ $area->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="included_in_plan" class="col-md-3 col-form-label">المسجد:</label>
    <div class="col-md-3">
        <select class="form-control" name="place_id" id="place_id">
            @if (isset($places))
                @foreach ($places as $key => $place)
                    <option value="{{ $place->id }}" @if ($place->id == $circle->place_id) selected @endif>
                        {{ $place->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <label for="teacher_id" class="col-md-2 col-form-label">المحفظ:</label>
    <div class="col-md-4">
        <select class="form-control" name="teacher_id" id="teacher_id">
            <option value="">-- تحديد --</option>
            @if (isset($teachers))
                {!! $teachers !!}
            @endif
        </select>
    </div>
</div>
<div class="mb-3 row">
    <label for="supervisor_id" class="col-md-3 col-form-label">المشرف الميداني:</label>
    <div class="col-md-9">
        <select class="form-control" name="supervisor_id" id="supervisor_id">
            <option value="">-- تحديد --</option>
            @if (isset($supervisors))
                {!! $supervisors !!}
            @endif
        </select>
    </div>
</div>

{{-- <div class="mb-3 row">
    <label for="supervisor_id" class="col-md-3 col-form-label">نوع الحلقة:</label>
    <div class="col-md-3" style="padding: 10px;">

        <input class="form-check-input" type="checkbox" name="contract_type"
            @if (isset($circle->contract_type) && $circle->contract_type == 'مكفول') checked @endif id="exampleRadios61" value="مكفول">
        <label class="form-check-label" for="exampleRadios61" style="margin-left: 22px;">
            مكفول
        </label>




        <input class="form-check-input" type="checkbox" name="contract_type"
            @if (isset($circle->) && $circle-> == 'متطوع') checked @endif id="exampleRadios62" value="متطوع">
        <label class="form-check-label" for="exampleRadios62">
            متطوع
        </label>


    </div>
    <label for="included_in_plan" class="col-md-2 col-form-label">قيمة الكفالة:</label>
    <div class="col-md-4">
        <input type="text" class="form-control" placeholder="قيمة الكفالة" name="contract_type_value"
        value="{{ old('contract_type_value', isset($circle->contract_type_value) ? $circle->contract_type_value : '') }}">
    </div>
</div> --}}

<div class="mb-3 row">
    <label for="supervisor_id" class="col-md-3 col-form-label">ملاحظات:</label>
    <div class="col-md-9">
        <textarea name="notes" rows="2" cols="10" class="form-control" placeholder="ملاحظات"><?= $circle->notes ?></textarea>
    </div>
</div>

<style>
    .form-check-input[type="checkbox"] {
        font-size: 20px;
    }
</style>


<script>

$('input[type="checkbox"]').on('change', function() {
   $('input[type="checkbox"]').not(this).prop('checked', false);
});

    $('select[name="area_id"]').on('change', function() {
        // console.log($(this).val(),$(this)[0][$(this)[0].selectedIndex]);
        var area_id = $(this).val();
        $.get('/getSubAreas/' + area_id, function(data) {
            $('#sub_area_id').empty().html(data);
        });
    });
    $('#sub_area_id').on('change', function() {
        var sub_area_id = $(this).val();
        $.get('/getSubAreaPlaces/' + sub_area_id, function(data) {
            $('#place_id').empty().html(data);
        });
    });
    $('#place_id').on('change', function() {
        var place_id = $(this).val();
        $.get('/getPlaceTeachersForCircles/' + place_id +
            '/{{ $circle->teacher_id ? $circle->teacher_id : 0 }}',
            function(data) {
                $('#teacher_id').empty().html(data);
            });
        $.get('/getPlaceAreaSupervisorForCircles/' + place_id +
            '/{{ $circle->supervisor_id ? $circle->supervisor_id : 0 }}',
            function(data) {
                $('#supervisor_id').empty().html(data);
            });
    });
</script>
