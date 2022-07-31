@csrf

<div class="mb-3 row">
    <div class="col-md-3">
        <label for="from">من صفحة</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="text" name="from" value="{{old('from',$plan->from)}}" placeholder="من صفحة" id="from" >
    </div>
</div>

<div class="mb-3 row">
    <div class="col-md-3">
        <label for="to">الى صفحة</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="text" name="to" value="{{old('to',$plan->to)}}" placeholder="الى صفحة" id="to" >
    </div>
</div>

<div class="mb-3 row">
    <div class="col-md-3">
        <label for="hours">عدد الساعات</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="text" name="hours" value="{{old('hours',$plan->hours)}}" placeholder="عدد الساعات" id="hours" >
    </div>
</div>

<input type="hidden" name="book_plan_id" value="{{ $bookPlan->id }}">
<input type="hidden" name="id" value="{{ $plan->id }}">
