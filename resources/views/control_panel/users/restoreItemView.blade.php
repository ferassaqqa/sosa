
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">استرجاع مستخدم محذوف</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="row">
        <label class="mb-4 d-flex justify-content-center">البيانات الأساسية</label>
        <div class="col-md-10">
            <div class="mb-3 row">
                <label class="col-md-1 col-form-label">الاسم</label>
                <div class="col-md-1">
                    <select disabled class="form-select" name="prefix_id">
                        @foreach($prefixes as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->abbreviation }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input disabled class="form-control" type="text" name="name" value="{{old('name',$user->name)}}" id="name">
                </div>
                <label for="dob" class="col-md-2 col-form-label">تاريخ الميلاد</label>
                <div class="col-md-4">
                    <div class="input disabled-group" id="datepicker1">
                        <input disabled type="text" class="form-control" placeholder="تاريخ الميلاد"
                               name="dob" value="{{old('dob',$user->dob )}}" id="dob"
                               data-date-format="dd-mm-yyyy" data-date-container='#datepicker1' data-provide="datepicker">
                        <span class="input disabled-group-text"><i class="mdi mdi-calendar"></i></span>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">مكان الميلاد</label>
                <div class="col-md-4">
                    <input disabled class="form-control" type="text" name="pob" value="{{old('pob',$user->pob)}}" id="pob">
                </div>
                <label for="name" class="col-md-2 col-form-label">رقم الهوية</label>
                <div class="col-md-4">
                    <input disabled class="form-control" type="number" name="id_num" value="{{old('id_num',$user->id_num)}}" id="id_num" >
                </div>
            </div>
            <div class="mb-3 row">
                <label for="mobile" class="col-md-2 col-form-label">رقم الجوال</label>
                <div class="col-md-4">
                    <input disabled class="form-control" type="number" name="mobile" value="{{old('mobile',$user->mobile)}}" id="mobile">
                </div>
                <label for="place_name" class="col-md-2 col-form-label">المسجد</label>
                <div class="col-md-4">
                    <input disabled class="form-control place_name" type="text" name="place_name" value="" id="place_name" ="">
                    <input disabled class="form-control" type="hidden" name="place_id" value="">
                    <div>
                        <ul class="list-group" style="position: absolute; z-index: 999;border: 1px solid #ddd;">

                        </ul>
                    </div>
                    <span class="spinner-grow text-primary" style="display:none;width: 2rem;height: 2rem;position: relative;top: -35px;right: 254px;" role="status" aria-hidden="true"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="email" class="col-md-2 col-form-label">البريد الالكتروني</label>
                <div class="col-md-4">
                    <input disabled class="form-control" type="email" name="email" value="{{old('email',$user->email)}}" id="email">
                </div>
                <label for="role" class="col-md-2 col-form-label">القسم</label>
                <div class="col-md-4">
                    <select disabled class="form-control" name="role">
                        <option value="1" @if($user->role == 1) selected @endif>قسم الطلاب</option>
                        <option value="2" @if($user->role == 2) selected @endif>قسم الطالبات</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="role_id" class="col-md-2 col-form-label">الدور</label>
                <div class="col-md-4">
                    <select disabled class="form-control" multiple name="role_id[]" id="role_id">
                        @foreach($roles as $key => $value)
                            <option value="{{ $value->id }}" @if($value->users_count) selected @endif>{{ $value->name }}</option>
                        @endforeach
                    </select>

                </div>
                <label for="qualification_id" class="col-md-2 col-form-label">المؤهل</label>
                <div class="col-md-4">
                    <select disabled class="form-control" name="qualification_id">
                        @foreach($qualifications as $key => $value)
                            <option value="{{ $value->id }}" @if($value->users_count) selected @endif>{{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-3 row" style="display: inline-block;">
                <img src="{{ asset('user_default_avatar.png') }}" class="user-avatar" alt="">
                <input disabled type="file" accept="image/*" name="user_profile" style="display: none;">
            </div>
        </div>
    </div>
    <div class="row">
        <label class="mt-4 mb-4 d-flex justify-content-center">بيانات أخرى</label>
    </div>
    <div class="mb-3 row">
        <label for="name" class="col-md-2 col-form-label">الحالة الاجتماعية</label>
        <div class="col-md-4">
            <select disabled class="form-control" name="material_status">
                <option value="أعزب" @if(\Illuminate\Support\Str::contains($user->material_status, 'عزب')) selected @endif >أعزب</option>
                <option value="متزوج" @if(\Illuminate\Support\Str::contains($user->material_status, 'متزوج')) selected @endif >متزوج</option>
                <option value="مطلق" @if(\Illuminate\Support\Str::contains($user->material_status, 'مطلق')) selected @endif >مطلق</option>
            </select>
        </div>
        <label for="name" class="col-md-2 col-form-label">عدد الأبناء</label>
        <div class="col-md-4">
            <input disabled class="form-control" type="number" name="sons_count" value="{{old('sons_count',$user->sons_count)}}" id="id_num" >
        </div>
    </div>

    <div class="mb-3 row">
        <label for="address" class="col-md-2 col-form-label">العنوان</label>
        <div class="col-md-4">
            <input disabled class="form-control" type="text" name="address" value="{{old('address',$user->address)}}" id="address">
        </div>
        <label for="home_tel" class="col-md-2 col-form-label">الهاتف</label>
        <div class="col-md-4">
            <input disabled class="form-control" type="number" name="home_tel" value="{{old('home_tel',$user->home_tel)}}" id="home_tel">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="occupation" class="col-md-1 col-form-label">المهنة</label>
        <div class="col-md-3">
            <input disabled class="form-control" type="text" name="occupation" value="{{old('occupation',$user->occupation)}}" id="occupation">
        </div>
        <label for="occupation_place" class="col-md-1 col-form-label">مكان العمل</label>
        <div class="col-md-3">
            <input disabled class="form-control" type="text" name="occupation_place" value="{{old('occupation_place',$user->occupation_place)}}" id="occupation_place">
        </div>
        <label for="income_id" class="col-md-1 col-form-label">متوسط الدخل</label>
        <div class="col-md-3">
            <select disabled class="form-control" name="income_id" id="income_id">
                @foreach($incomes as $key => $value)
                    <option value="{{ $value->id }}" @if($value->users_count) selected @endif>{{ $value->label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="fb_link" class="col-md-1 col-form-label">رابط فيسبوك</label>
        <div class="col-md-3">
            <input disabled class="form-control" type="text" name="fb_link" value="{{old('fb_link',$user->fb_link)}}" id="fb_link">
        </div>
        <label for="collage" class="col-md-1 col-form-label">الكلية/ الجامعة</label>
        <div class="col-md-3">
            <input disabled class="form-control" type="text" name="collage" value="{{old('collage',$user->collage)}}" id="collage">
        </div>
        <label for="speciality" class="col-md-1 col-form-label">التخصص</label>
        <div class="col-md-3">
            <input disabled class="form-control" type="text" name="speciality" value="{{old('speciality',$user->speciality)}}" id="speciality">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="income_id" class="col-md-1 col-form-label">نوع العقد</label>
        <div class="col-md-3">
            <select disabled class="form-control" name="income_id" id="income_id">
                @foreach($contractTypes as $key => $value)
                    <option value="{{ $value->id }}" @if($value->users_count) selected @endif>{{ $value->name }}</option>
                @endforeach
            </select>
        </div>
        <label for="contract_value" class="col-md-1 col-form-label">قيمة العقد</label>
        <div class="col-md-3">
            <input disabled class="form-control" type="text" name="contract_value" value="{{old('contract_value',$user->contract_value)}}" id="contract_value">
        </div>
        <label for="join_date" class="col-md-1 col-form-label">تاريخ الالتحاق</label>
        <div class="col-md-3">
            <div class="input disabled-group" id="datepicker1">
                <input disabled type="text" class="form-control" placeholder="تاريخ بداية العمل بالدائرة"
                       name="join_date" value="{{old('join_date',$user->join_date)}}" id="join_date"
                       data-date-format="dd-m-yyyy" data-date-container='#datepicker1' data-provide="datepicker">
                <span class="input disabled-group-text"><i class="mdi mdi-calendar"></i></span>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        @foreach($personalSkills as $personalSkillKey => $personalSkill)
            <label class="col-md-1 col-form-label">{{ $personalSkill->name }}</label>
            <div class="col-md-3">
                <select disabled class="form-control" name="userPersonalSkillValues[]">
                    @foreach($personalSkill->personalSkillValues as $key => $value)
                        <option value="{{ $value->id }}" @if($value->users_count) selected @endif>{{ $value->value }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button class="btn btn-primary waves-effect waves-light" data-url="{{ route('users.restoreItem',$user->id) }}" onclick="restoreItem(this)">استرجاع</button>
</div>
