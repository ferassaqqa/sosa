@csrf
<div class="mb-3 row">
    <label for="name" class="col-md-3 col-form-label">الاسم</label>
    <div class="col-md-9">
        <input class="form-control" type="text" name="name" value="{{old('name',$role->name)}}" id="name" required>
    </div>
</div>
<input type="hidden" name="id" value="{{ $role->id }}">
