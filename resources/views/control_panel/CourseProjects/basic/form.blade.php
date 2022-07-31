@csrf
<div class="mb-3 row">
    <label for="name" class="col-md-3 col-form-label">الاسم</label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="اسم البرنامج"
                   name="name" value="{{old('name',$courseProject->name )}}" id="name">
        </div>
    </div>
</div>
<div class="mb-3 row">
    <label for="books" class="col-md-3 col-form-label">الكتب</label>
    <select class="select2 form-control select2-multiple" multiple name="books[]" id="books">
        @foreach($books as $key => $value)
            <option value="{{ $value->id }}" @if(in_array($value->id,$courseProject->books_ids_array)) selected @endif>{{ $value->name }}</option>
        @endforeach
    </select>
</div>
<input type="hidden" name="id" value="{{ $courseProject->id }}">
