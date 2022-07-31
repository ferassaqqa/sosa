@csrf
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
                    <input type="text" class="form-control" placeholder="تاريخ الميلاد"
                           name="dob" value="{{old('dob',$user->dob )}}" id="dob"
                           data-date-format="dd-mm-yyyy" data-date-container='#datepicker1' data-provide="datepicker"
                           data-date-autoclose="true" disabled>
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
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">رقم الجوال:</td>
            <td colspan="2">
                <input type="text" class="form-control" placeholder="رقم الجوال"
                       name="mobile" value="{{old('mobile',isset($user->userExtraData) ? $user->userExtraData->mobile : '' )}}" >
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">رقم الهوية / الوثيقة:</td>
            <td colspan="2">
                {{ $user->id_num }}
                <input type="hidden" class="form-control" name="id_num" value="{{ $user->id_num }}">
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">المؤهل العلمي:</td>
            <td colspan="2">
                <select class="form-control" name="study_level">
                    <option value="ابتدائي" @if($user->study_level == 'ابتدائي') selected @endif>ابتدائي</option>
                    <option value="اعدادي" @if($user->study_level == 'اعدادي') selected @endif>اعدادي</option>
                    <option value="ثانوي" @if($user->study_level == 'ثانوي') selected @endif>ثانوي</option>
                    <option value="دبلوم" @if($user->study_level == 'دبلوم') selected @endif>دبلوم</option>
                    <option value="بكالوريوس" @if($user->study_level == 'بكالوريوس') selected @endif>بكالوريوس</option>
                    <option value="ماجستير" @if($user->study_level == 'ماجستير') selected @endif>ماجستير</option>
                    <option value="دكتوراة" @if($user->study_level == 'دكتوراة') selected @endif>دكتوراة</option>
                </select>
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">المنطقة الكبرى:</td>
            <td colspan="2">
                <select class="form-control" name="area_id">
                    <option value="0">-- اختر --</option>
                    @foreach($areas as $key => $area)
                        <option value="{{ $area->id }}" @if($area->id == $user->area_father_id) selected @endif>{{ $area->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
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
        <tr>

            <td class="center-align" colspan="2" style="background-color: #f9fafb;">المعلم:</td>
            <td colspan="2">
                <select class="form-control" name="teacher_id" id="teacher_id">
                    @if(isset($teachers))
                        {!! $teachers !!}
                    @endif
                </select>
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">ملحوظات:</td>
            <td colspan="2">
                <textarea type="text" class="form-control" cols="3" rows="4" name="notes">{{ $note ? $note->note : '' }}</textarea>
            </td>
        </tr>
    </table>
</div>

<input type="hidden" name="id" value="{{ $user->id }}">

<script>
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
    $('#place_id').on('change', function() {
        var place_id = $(this).val();
        $.get('/getPlaceTeachersForCircles/'+place_id+'/{{ $user->teacher_id ? $user->teacher_id : 0 }}',function(data){
            $('#teacher_id').empty().html(data);
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