@csrf
<div class="mb-3 row">
    <label for="start_date" class="col-md-3 col-form-label">اسم التصنيف</label>
    <div class="col-md-9">
        <div class="input-group" id="datepicker1">
            <input type="text" class="form-control" placeholder="اسم التصنيف"
                   name="name" value="{{old('name',$asaneedBookCategory->name )}}" id="name">
        </div>
    </div>
</div>
<input type="hidden" name="id" value="{{ $asaneedBookCategory->id }}">
