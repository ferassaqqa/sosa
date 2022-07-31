@csrf
<div class="mb-3 row">
    <div class="col-md-3">
        <label for="year_semester">الفصل</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="text" name="year_semester" value="{{old('year_semester',$plan->year_semester)}}" placeholder="الفصل" id="year_semester" >
    </div>
</div>
<input type="hidden" name="id" value="{{ $plan->id }}">
<input type="hidden" name="book_plan_year_id" value="{{ $plan->book_plan_year_id }}">