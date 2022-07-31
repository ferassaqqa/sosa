
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">استرجاع مهارة شخصية محذوفة</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3 row">
        <label for="name" class="col-md-3 col-form-label">الاسم</label>
        <div class="col-md-9">
            <input class="form-control" type="text" name="name" value="{{ $personalSkill->name }}" id="name" disabled>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button class="btn btn-primary waves-effect waves-light" data-url="{{ route('personalSkills.restoreItem',$personalSkill->id) }}" onclick="restoreItem(this)">استرجاع</button>
</div>
