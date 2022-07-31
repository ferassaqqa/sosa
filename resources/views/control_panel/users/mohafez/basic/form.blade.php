@csrf
<style>
    #select2-place_id-container{
        direction: rtl;
    }
</style>
<div class="row mb-5">
    <table class="table table-bordered">
        <tr>
            <td class="center-align" colspan="9" style="background-color: #f9fafb;">
                أولا: البيانات الشخصية
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">الاسم رباعي:</td>
            <td colspan="2">
                <select class="form-control" name="prefix" style="width: 12%;display: inline-block;">
                    <option value="أ" @if($user->prefix == 'أ') selected @endif>أ</option>
                    <option value="د" @if($user->prefix == 'د') selected @endif>د</option>
                    <option value="م" @if($user->prefix == 'م') selected @endif>م</option>
                </select>
                <input type="text" class="form-control" disabled name="name" value="{{ $user->name }}" style="width: 86%;display: inline-block;">
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">تاريخ الميلاد:</td>
            <td colspan="2">
                <div class="input-group" id="datepicker1">
                    <input type="text" class="form-control" disabled placeholder="تاريخ الميلاد"
                           name="dob" value="{{old('dob',$user->dob )}}" id="dob"
                           data-date-format="dd-mm-yyyy" data-date-container='#datepicker1' data-provide="datepicker"
                           data-date-autoclose="true">
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                </div>
            </td>
            <td rowspan="5">
                <div class="ui rounded small image upload-photo" style="width: 130px;">
                    <img src="{{ $user->logo }}" class="user-avatar" style="height:273px;max-width:100%">
                    <p>صورة شخصية عالية الجودة</p>
                    <input type="file" accept="image/*" name="user_profile" style="display: none;">
                </div>
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">مكان الميلاد:</td>
            <td colspan="2">
                <input type="text" class="form-control" disabled name="pob" value="{{ $user->pob }}">
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">رقم الهوية / الوثيقة:</td>
            <td colspan="2">
                {{ $user->id_num }}
                <input type="hidden" class="form-control" name="id_num" value="{{ $user->id_num }}">
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">الحالة الإجتماعية:</td>
            <td colspan="2">
                <select class="form-control" disabled name="material_status">
                    <option value="0">-- اختر --</option>
                    <option value="متزوج" @if($user->material_status == 'متزوج') selected @endif>متزوج</option>
                    <option value="أعزب" @if($user->material_status == 'أعزب') selected @endif>أعزب</option>
                    <option value="مطلق" @if($user->material_status == 'مطلق') selected @endif>مطلق</option>
                </select>
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">عدد الأبناء:</td>
            <td colspan="2">
                <input type="number" class="form-control" disabled step="1" min="1" name="sons_count" value="{{ $user->sons_count }}" style="direction: rtl;">
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">المنطقة الكبرى:</td>
            <td colspan="2">
                <select class="form-control" name="area_id">
                    <option value="0">-- اختر --</option>
                    @foreach($areas as $key => $area)
                        <option value="{{ $area->id }}" @if($area->id == $user->area_father_id) selected @endif>{{ $area->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">المنطقة المحلية:</td>
            <td colspan="2">
                <select class="form-control" name="sub_area_id" id="sub_area_id">
                    @if(isset($sub_areas))
                        <option value="0">-- اختر --</option>
                        @foreach($sub_areas as $key => $area)
                            <option value="{{ $area->id }}" @if($area->id == $user->area_id) selected @endif>{{ $area->name }}</option>
                        @endforeach
                    @endif
                </select>
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">عنوان السكن:</td>
            <td colspan="2">
                <input type="text" class="form-control" step="1" min="1" name="address" value="{{ $user->address }}">
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">المسجد:</td>
            <td colspan="2">
                <select class="form-control" name="place_id" id="place_id">
                    @if(isset($places))
                        <option value="0">-- اختر --</option>
                        @foreach($places as $key => $place)
                            <option value="{{ $place->id }}" @if($place->id == $user->place_id) selected @endif>{{ $place->name }}</option>
                        @endforeach
                    @endif
                </select>
            </td>
        </tr>
    </table>
</div>
<div class="row mb-5">
    <table class="table table-bordered">
        <tr>
            <td class="center-align" colspan="12" style="background-color: #f9fafb;">
                ثانيا: بيانات التواصل
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">رقم الجوال:</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="رقم الجوال"
                       name="mobile" value="{{old('mobile',isset($user->userExtraData) ? $user->userExtraData->mobile : '' )}}" >
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">هاتف المنزل:</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="هاتف المنزل"
                       name="home_tel" value="{{old('home_tel',isset($user->userExtraData) ? $user->userExtraData->home_tel : '' )}}" >
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">البريد الإلكتروني:</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="البريد الإلكتروني"
                       name="email" value="{{old('email',isset($user->userExtraData) ? $user->userExtraData->email : ''  )}}" >
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">فيسبوك:</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="فيسبوك"
                       name="fb_link" value="{{old('fb_link',isset($user->userExtraData) ? $user->userExtraData->fb_link : '' )}}" >
                <span style="color:red;">https://www.facebook.com/profile.php?id=100047409799528</span>
            </td>
        </tr>
    </table>
</div>
<div class="row mb-5">
    <table class="table table-bordered">
        <tr>
            <td class="center-align" colspan="12" style="background-color: #f9fafb;">
                ثالثا: البيانات العلمية (الأكاديمية)
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">الدرجة العلمية:</td>
            <td class="text-center-align" colspan="2">
                <input class="form-check-input" type="radio"
                       @if(isset($user->userExtraData) && ($user->userExtraData->study_level == 'الثانوية')) checked @endif
                name="study_level" id="exampleRadios1" value="الثانوية">
                <label class="form-check-label" for="exampleRadios1">
                    الثانوية
                </label>
            </td>
            <td class="text-center-align" colspan="2">
                <input class="form-check-input" type="radio" name="study_level"
                       @if(isset($user->userExtraData) && ($user->userExtraData->study_level == 'دبلوم')) checked @endif
                       id="exampleRadios2" value="دبلوم">
                <label class="form-check-label" for="exampleRadios2">
                    دبلوم
                </label>
            </td>
            <td class="text-center-align" colspan="2">
                <input class="form-check-input" type="radio" name="study_level"
                       @if(isset($user->userExtraData) && ($user->userExtraData->study_level == 'بكلوريوس')) checked @endif
                       id="exampleRadios3" value="بكلوريوس">
                <label class="form-check-label" for="exampleRadios3">
                    بكلوريوس
                </label>
            </td>
            <td class="text-center-align">
                <input class="form-check-input" type="radio" name="study_level"
                       @if(isset($user->userExtraData) && ($user->userExtraData->study_level == 'ماجستير')) checked @endif
                       id="exampleRadios4" value="ماجستير">
                <label class="form-check-label" for="exampleRadios4">
                    ماجستير
                </label>
            </td>
            <td class="text-center-align" colspan="2">
                <input class="form-check-input" type="radio" name="study_level"
                       @if(isset($user->userExtraData) && ($user->userExtraData->study_level == 'دكتوراة')) checked @endif
                       id="exampleRadios5" value="دكتوراة">
                <label class="form-check-label" for="exampleRadios5">
                    دكتوراة
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #f9fafb;">الكلية / التعليم الجامعي:</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="الكلية / التعليم الجامعي"
                       name="collage" value="{{old('collage',isset($user->userExtraData) ? $user->userExtraData->collage : '' )}}" >
            </td>
            <td colspan="2" style="background-color: #f9fafb;">التخصص:</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="التخصص"
                       name="speciality" value="{{old('speciality',isset($user->userExtraData) ? $user->userExtraData->speciality : '' )}}" >
            </td>
        </tr>
    </table>
</div>
<div class="row mb-5">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="center-align" colspan="12" style="background-color: #f9fafb;">
                    رابعا: البيانات العلمية (الدورات القرآنية والشرعية)
                </td>
            </tr>

            <tr>
                <td class="center-align" colspan="11" style="background-color: #f9fafb;">
                    الدورات الحاصل عليها
                </td>
                <td class="center-align" style="background-color: #f9fafb;padding: 0;">
                    <i class="mdi mdi-plus-circle add-new-course" style="color: green;font-size: 30px;cursor: pointer;"></i>
                </td>
            </tr>
            @if(isset($user_old_courses) && $user_old_courses->count())
                @foreach($user_old_courses as $user_old_course)
                    <tr>
                        <td colspan="2" style="background-color: #f9fafb;">الدورة:</td>
                        <td colspan="2">
                             <input type="text" class="form-control" placeholder="الدورة"
                                    name="course_name[]" value="{{ $user_old_course->course }}">
                        </td>
                        <td colspan="2" style="background-color: #f9fafb;">المعلم:</td>
                        <td colspan="2">
                             <input type="text" class="form-control" placeholder="المعلم"
                                    name="course_teacher_name[]" value="{{ $user_old_course->course_teacher }}">
                        </td>
                        <td colspan="1" style="background-color: #f9fafb;">السنة:</td>
                        <td colspan="2">
                             <input type="text" class="form-control" placeholder="السنة"
                                    name="course_year[]" value="{{ $user_old_course->year }}">
                        </td>
                        <td style="padding: 0;">
                             <i class="mdi mdi-alpha-x delete-course" style="color: red;font-size: 43px;cursor: pointer;"></i>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<div class="row mb-5">
    <table class="table table-bordered">
        <tr>
            <td class="center-align" colspan="12" style="background-color: #f9fafb;">
                خامساً: البيانات العملية
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #f9fafb;">المهنة:</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="المهنة"
                       name="occupation" value="{{old('occupation',isset($user->userExtraData) ? $user->userExtraData->occupation : '' ) }}" >
            </td>
            <td colspan="2" style="background-color: #f9fafb;">مكان العمل :</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="مكان العمل"
                       name="occupation_place" value="{{old('occupation_place',isset($user->userExtraData) ? $user->userExtraData->occupation_place : '' )}}" >
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="3" style="background-color: #f9fafb;">مستوى الدخل الشهري:</td>
            <td class="text-center-align" colspan="2">
                <input class="form-check-input" type="radio" name="monthly_income"
                       @if(isset($user->userExtraData) && ($user->userExtraData->monthly_income == 'ضعيف (أقل من 1000 شيكل)')) checked @endif
                       id="exampleRadios6" value="ضعيف (أقل من 1000 شيكل)">
                <label class="form-check-label" for="exampleRadios6">
                    ضعيف (أقل من 1000 شيكل)
                </label>
            </td>
            <td class="text-center-align" colspan="2">
                <input class="form-check-input" type="radio" name="monthly_income"
                       @if(isset($user->userExtraData) && ($user->userExtraData->monthly_income == 'جيد (من1000 حتى 2000)')) checked @endif
                       id="exampleRadios7" value="جيد (من1000 حتى 2000)">
                <label class="form-check-label" for="exampleRadios7">
                    جيد (من1000 حتى 2000)
                </label>
            </td>
            <td class="text-center-align" colspan="2">
                <input class="form-check-input" type="radio" name="monthly_income"
                       @if(isset($user->userExtraData) && ($user->userExtraData->monthly_income == 'ممتاز (أكثر من 2000)')) checked @endif
                       id="exampleRadios8" value="ممتاز (أكثر من 2000)">
                <label class="form-check-label" for="exampleRadios8">
                    ممتاز (أكثر من 2000)
                </label>
            </td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="monthly_income"
                       @if(isset($user->userExtraData) && ($user->userExtraData->monthly_income == 'بدون')) checked @endif
                       id="exampleRadios9" value="بدون">
                <label class="form-check-label" for="exampleRadios9">
                    بدون
                </label>
            </td>
        </tr>
    </table>
</div>
<div class="row mb-5">
    <table class="table table-bordered">
        <tr>
            <td class="center-align" colspan="12" style="background-color: #f9fafb;">
                العمل داخل نطاق دار القرآن الكريم:
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #f9fafb;">تاريخ بداية العمل بالدائرة:</td>
            <td colspan="2">
                <div class="input-group" id="datepicker1">
                    <input type="text" class="form-control" placeholder="تاريخ بداية العمل بالدائرة"
                           name="join_date" value="{{old('join_date',isset($user->userExtraData) ? $user->userExtraData->join_date : '' )}}"
                           data-date-format="dd-mm-yyyy" data-date-container='#datepicker1' data-provide="datepicker"
                           data-date-autoclose="true">
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                </div>
            </td>
            <td colspan="1" style="background-color: #f9fafb;">نوع العقد:</td>
            <td colspan="1">
                <input class="form-check-input" type="radio" name="contract_type"
                       @if(isset($user->userExtraData) && ($user->userExtraData->contract_type == 'مكفول')) checked @endif
                       id="exampleRadios61" value="مكفول">
                <label class="form-check-label" for="exampleRadios61">
                    مكفول
                </label>
            </td>
            <td colspan="1">
                <input class="form-check-input" type="radio" name="contract_type"
                       @if(isset($user->userExtraData) && ($user->userExtraData->contract_type == 'متطوع')) checked @endif
                       id="exampleRadios62" value="متطوع">
                <label class="form-check-label" for="exampleRadios62">
                    متطوع
                </label>
            </td>
            <td colspan="2" style="background-color: #f9fafb;">قيمة الكفالة:</td>
            <td colspan="3">
                <input type="text" class="form-control" placeholder="قيمة الكفالة"
                       name="contract_type_value" value="{{isset($user->userExtraData) ? $user->userExtraData->contract_type_value : (isset($user->userExtraData) ? (($user->userExtraData->contract_type == 'متطوع') ? 0 : '') : '') }}" >
            </td>
        </tr>
    </table>
</div>
<div class="row mb-5">
    <table class="table table-bordered">
        <tr>
            <td class="center-align" colspan="12" style="background-color: #f9fafb;">
                سادساً: المهارات الشخصية
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="3" style="background-color: #f9fafb;">مهارات الحاسوب الأساسية</td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="computer_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->computer_skills == 'متطوع')) checked @endif
                       id="exampleRadios101" value="ضعيف">
                <label class="form-check-label" for="exampleRadios101">
                    ضعيف
                </label>
            </td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="computer_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->computer_skills == 'جيد')) checked @endif
                       id="exampleRadios111" value="جيد">
                <label class="form-check-label" for="exampleRadios111">
                    جيد
                </label>
            </td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="computer_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->computer_skills == 'ممتاز')) checked @endif
                       id="exampleRadios121" value="ممتاز">
                <label class="form-check-label" for="exampleRadios121">
                    ممتاز
                </label>
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="3" style="background-color: #f9fafb;">مهارات اللغة /إنجليزية</td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="english_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->english_skills == 'ضعيف')) checked @endif
                       id="exampleRadios102" value="ضعيف">
                <label class="form-check-label" for="exampleRadios102">
                    ضعيف
                </label>
            </td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="english_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->english_skills == 'جيد')) checked @endif
                       id="exampleRadios112" value="جيد">
                <label class="form-check-label" for="exampleRadios112">
                    جيد
                </label>
            </td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="english_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->english_skills == 'ممتاز')) checked @endif
                       id="exampleRadios122" value="ممتاز">
                <label class="form-check-label" for="exampleRadios122">
                    ممتاز
                </label>
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="3" style="background-color: #f9fafb;">اللياقة البدنية والصحية</td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="health_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->health_skills == 'ضعيف')) checked @endif
                       id="exampleRadios103" value="ضعيف">
                <label class="form-check-label" for="exampleRadios103">
                    ضعيف
                </label>
            </td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="health_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->health_skills == 'جيد')) checked @endif
                       id="exampleRadios113" value="جيد">
                <label class="form-check-label" for="exampleRadios113">
                    جيد
                </label>
            </td>
            <td class="text-center-align" colspan="3">
                <input class="form-check-input" type="radio" name="health_skills"
                       @if(isset($user->userExtraData) && ($user->userExtraData->health_skills == 'ممتاز')) checked @endif
                       id="exampleRadios123" value="ممتاز">
                <label class="form-check-label" for="exampleRadios123">
                    ممتاز
                </label>
            </td>
        </tr>
    </table>
</div>
<div class="row mb-5">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="center-align" colspan="11" style="background-color: #f9fafb;">
                    سابعاً: ملاحظات
                </td>
                <td class="center-align" style="background-color: #f9fafb;padding: 0;">
                    <i class="mdi mdi-plus-circle add-new-comment" style="color: green;font-size: 30px;cursor: pointer;"></i>
                </td>
            </tr>
            @if(isset($user_notes) && $user_notes->count())
                @foreach($user_notes as $user_note)
                    <tr>
                        <td colspan="11">
                           <textarea class="form-control" name="user_comment[]" id="" cols="30" rows="3">{{ $user_note->note }}</textarea>
                        </td>
                        <td class="center-align" style="padding: 0;">
                           <i class="mdi mdi-alpha-x-circle delete-comment" style="color: red;font-size: 30px;cursor: pointer;"></i>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<div class="row mb-5">
    <table class="table table-bordered">
        <tr>
            <td class="center-align" colspan="11" style="background-color: #f9fafb;">
                ثامنا: ملفات / مرفقات
            </td>
            <td class="center-align" style="background-color: #f9fafb;padding: 0;">
                <i class="mdi mdi-plus-circle enclose-new" style="color: green;font-size: 30px;cursor: pointer;"></i>
                <input type="file" name="encloses[]" multiple style="display:none;">
            </td>
        </tr>
        @if(isset($media) && $media->count())
            @foreach($media as $medium)
                <tr>
                    <td colspan="11">
                        {{ $medium->old_name }}
                    </td>
                    <td class="center-align" style="padding: 0;">
                       <i class="mdi mdi-alpha-x-circle delete-enclose" data-url="{{ route('media.destroy',$medium->id) }}" data-id="{{ $medium->id }}" style="color: red;font-size: 30px;cursor: pointer;"></i>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
</div>

<input type="hidden" name="id" value="{{ $user->id }}">

<script>
    $(function () {
        $("#place_id").select2();
    });
    var place_name_request_count = 0;
    $(document).on('input', ".place_name", function() {
        var button = $(this);
        if(typeof $(this).val() !='undefined' && $(this).val().length) {
            button.parent().find('span')[0].style.display = 'block';
            place_name_request_count++;
            $.get('/searchPlaceForCircles/' + $(this).val()+'/'+place_name_request_count, function (data) {
                if(data[1] == place_name_request_count && button.val()) {
                    button.parent().find('.list-group').html(data[0]);
                    button.parent().find('.list-group-item').css('cursor','pointer');
                    button.parent().find('span')[0].style.display = 'none';
                }
            });
        }else{
            button.parent().find('span')[0].style.display = 'none';
            $('.list-group').html('');
        }
    });
    $('#form').on('click', ".selected-place", function() {
        var clickedLi = $(this);
        var place_name = $(this).data('name');
        var place_id = $(this).data('id');
        var grandfather = clickedLi.parent().parent().parent().parent();
        var place_name_input = grandfather.find('input[name="place_name"]');
        var place_id_input = grandfather.find('input[name="place_id"]');
        if(place_name_input){
            place_name_input.val(place_name);
        }
        if(place_id_input){
            place_id_input.val(place_id);
        }
        $('.list-group').html('');
    });
    $('select[name="area_id"]').on('change', function() {
        // console.log($(this).val(),$(this)[0][$(this)[0].selectedIndex]);
        var area_id = $(this).val();
        $.get('/getSubAreas/'+area_id,function(data){
            $('#sub_area_id').empty().html(data);
        });
    });
    $('#sub_area_id').on('change', function() {
        var sub_area_id = $(this).val();
        $.get('/getSubAreaPlaces/'+sub_area_id,function(data){
            $('#place_id').empty().html(data);
        });
    });
    $('.user-avatar').on('click',function(){
        var avatar = $(this);
        var picInp = avatar.parent().find('input[type="file"]');
        picInp.files = [];
        picInp.click();
    });
    $('input[name="user_profile"]').on('change',function(){
        finalFiles['user_profile'] =  $(this)[0].files[0];
        var avatar = $(this).parent().find('.user-avatar');
        readURL(this,avatar);
    });
    function readURL(input,avatar) {
        // var avatar = input.parent().find('.user-avatar');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                avatar.attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            avatar.attr('src', 'https://sunnah1416.com/portal/assests/image.png');
        }
    }
    $('.enclose-new').on('click',function(){
        var input = $(this).parent().find('input');
        input.click();
    });
    $('input[name="encloses[]"]').on('change',function(){
        var input = $(this);
        // console.log(input[0].files);
        if(input && input[0].files.length) {
            var i = 0;
            finalFiles['encloses'] = {};
            for(let x of input[0].files) {
                finalFiles['encloses'][i] = x;
                var tbody = $(this).closest('tbody');
                var new_tr = document.createElement('TR');
                new_tr.classList.add('enclose_tr');
                new_tr.innerHTML =
                    '<td colspan="11">' +
                    '   <input class="form-control" name="enclose_comment[]" value="' + x.name + '"/> ' +
                    '</td> ' +
                    '<td class="center-align" style="padding: 0;"> ' +
                    '   <i class="mdi mdi-alpha-x-circle delete-enclose" data-index="'+i+'" style="color: red;font-size: 30px;cursor: pointer;"></i> ' +
                    '</td>';
                tbody.append(new_tr);
                i++;
            }
        }else{
            $('.enclose_tr').remove();
        }
        $('.delete-enclose').on('click', function () {
            var tr = $(this).closest('tr');
            var index = $(this).data('index');
            delete finalFiles['encloses'][index];
            tr.remove();
        });
    });

    $('.delete-enclose').on('click', function () {
        var tr = $(this).closest('tr');
        var index = $(this).data('index');
        var id = $(this).data('id');
        // console.log(typeof index == 'undefined');
        // var files = document.querySelector('input[name="encloses[]"]').files;
        // console.log(finalFiles['encloses'][index]);
        if(typeof index != 'undefined') {
            delete finalFiles['encloses'][index];
        }
        if(typeof id != 'undefined') {
            deleteItem(this);
        }
        tr.remove();
    });
    $('.add-new-comment').on('click',function(){
        var tbody = $(this).closest('tbody');
        var new_tr = document.createElement('TR');
        new_tr.innerHTML =
            '<td colspan="11">' +
            '   <textarea class="form-control" name="user_comment[]" id="" cols="30" rows="3"></textarea> ' +
            '</td> ' +
            '<td class="center-align" style="padding: 0;"> ' +
            '   <i class="mdi mdi-alpha-x-circle delete-comment" style="color: red;font-size: 30px;cursor: pointer;"></i> ' +
            '</td>';
        tbody.append(new_tr);
        $('.delete-comment').on('click',function(){
            var tr = $(this).closest('tr');
            tr.remove();
        });
    });
    $('.delete-comment').on('click',function(){
        var tr = $(this).closest('tr');
        tr.remove();
    });
    $('.add-new-course').on('click',function(){
        var tbody = $(this).closest('tbody');
        var new_tr = document.createElement('TR');
        new_tr.innerHTML =
            '<td colspan="2" style="background-color: #f9fafb;">الدورة:</td>'+
            '<td colspan="2">'+
            '    <input type="text" class="form-control" placeholder="الدورة"'+
            '           name="course_name[]" >'+
            '</td>'+
            '<td colspan="2" style="background-color: #f9fafb;">المعلم:</td>'+
            '<td colspan="2">'+
            '    <input type="text" class="form-control" placeholder="المعلم"'+
            '           name="course_teacher_name[]" >'+
            '</td>'+
            '<td colspan="1" style="background-color: #f9fafb;">السنة:</td>'+
            '<td colspan="2">'+
            '    <input type="text" class="form-control" placeholder="السنة"'+
            '           name="course_year[]">'+
            '</td>'+
            '<td style="padding: 0;">'+
            '    <i class="mdi mdi-alpha-x delete-course" style="color: red;font-size: 43px;cursor: pointer;"></i>'+
            '</td>';
        tbody.append(new_tr);
        $('.delete-course').on('click',function(){
            var tr = $(this).closest('tr');
            tr.remove();
        });
    });
    $('.delete-course').on('click',function(){
        var tr = $(this).closest('tr');
        tr.remove();
    });
</script>