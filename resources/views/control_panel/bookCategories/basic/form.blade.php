@csrf
<div class="mb-3 row">
    <label for="start_date" class="col-md-3 col-form-label">من</label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="من"
                   name="from" value="{{old('from',$bookCategory->from )}}" id="from">
        </div>
    </div>
</div>
<div class="mb-3 row">
    <label for="start_date" class="col-md-3 col-form-label">الى</label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="الى"
                   name="to" value="{{old('to',$bookCategory->to )}}" id="to">
        </div>
    </div>
</div>
<input type="hidden" name="id" value="{{ $bookCategory->id }}">
