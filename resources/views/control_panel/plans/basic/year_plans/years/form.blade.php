@csrf
<div class="mb-3 row">
    <div class="col-md-3">
        <label for="plan_year">السنة</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="text" name="plan_year" value="{{old('plan_year',$plan->plan_year)}}" placeholder="السنة" id="plan_year" >
    </div>
</div>
<input type="hidden" name="id" value="{{ $plan->id }}">
<input type="hidden" name="book_plan_id" value="{{ $plan->book_plan_id }}">