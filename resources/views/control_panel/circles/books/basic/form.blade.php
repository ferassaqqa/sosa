@csrf
<div class="mb-3 row">
    <label for="name" class="col-md-3 col-form-label">الاسم</label>
    <div class="col-md-9">
        <input class="form-control" type="text" name="name" value="{{old('name',$book->name)}}" id="name" >
    </div>
</div>
    <div class="mb-3 row">
        <label for="hadith_count" class="col-md-3 col-form-label">عدد الاحاديث</label>
        <div class="col-md-9">
            <input class="form-control" type="number" min="0" step="1" name="hadith_count" value="{{old('hadith_count',$book->hadith_count)}}" id="hadith_count" style="direction: rtl;" >
        </div>
    </div>
<div class="mb-3 row">
    <label for="pass_mark" class="col-md-3 col-form-label">علامة النجاح</label>
    <div class="col-md-9">
        <input class="form-control" type="number" min="0" step="1" name="pass_mark" value="{{old('pass_mark',$book->pass_mark)}}" id="pass_mark" style="direction: rtl;">
    </div>
</div>
<div class="mb-3 row">
    <label for="book_code" class="col-md-3 col-form-label">رمز الكتاب</label>
    <div class="col-md-9">
        <input class="form-control" type="text" name="book_code" value="{{old('book_code',$book->book_code)}}" id="book_code" >
    </div>
</div>
<input type="hidden" name="id" value="{{ $book->id }}">
