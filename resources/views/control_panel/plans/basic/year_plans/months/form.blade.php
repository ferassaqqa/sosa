@csrf
<div class="mb-3 row">
    <div class="col-md-3">
        <label for="semester_month">الشهر</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="text" name="semester_month" value="{{old('semester_month',$plan->semester_month)}}" placeholder="الشهر" id="semester_month" >
    </div>
</div>
<div class="mb-3 row">
    <div class="col-md-3">
        <label for="hadith_count">عدد الاحاديث</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="number" min="1" step="1" name="hadith_count" value="{{old('hadith_count',$plan->hadith_count)}}" placeholder="عدد الاحاديث" id="hadith_count" >
    </div>
</div>
<div class="mb-3 row">
    <div class="col-md-3">
        <label for="hadith_from">من حديث</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="number" min="1" step="1" name="from_hadith" value="{{old('from_hadith',$plan->from_hadith)}}" placeholder="من الحديث" id="hadith_from" >
    </div>
</div>
<div class="mb-3 row">
    <div class="col-md-3">
        <label for="hadith_to">الى حديث</label>
    </div>
    <div class="col-md-9">
        <input class="form-control" type="number" min="1" step="1" name="to_hadith" value="{{old('to_hadith',$plan->to_hadith)}}" placeholder="الى الحديث" id="hadith_to" >
    </div>
</div>


<input type="hidden" name="id" value="{{ $plan->id }}">
<input type="hidden" name="book_plan_year_semester_id" value="{{ $plan->book_plan_year_semester_id }}">