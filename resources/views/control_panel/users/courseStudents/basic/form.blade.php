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
                <input type="text" class="form-control" name="name" value="{{ $user->name }}" style="width: 86%;display: inline-block;">
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">رقم الهوية / الوثيقة:</td>
            <td colspan="2">
                {{ $user->id_num }}
                <input type="hidden" class="form-control" name="id_num" value="{{ $user->id_num }}">
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">تاريخ الميلاد:</td>
            <td colspan="2">
                <div class="input-group" id="datepicker1">
                    <input type="text" class="form-control" placeholder="تاريخ الميلاد"
                           name="dob" value="{{old('dob',$user->dob )}}" id="dob"
                           data-date-format="dd-mm-yyyy" data-date-container='#datepicker1' data-provide="datepicker"
                           data-date-autoclose="true">
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                </div>
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">مكان الميلاد:</td>
            <td colspan="2">
                <input type="text" class="form-control" placeholder="مكان الميلاد"
                       name="pob" value="{{old('pob',isset($user->pob) ? $user->pob : '' )}}" >
            </td>
        </tr>
    </table>
</div>

<input type="hidden" name="id" value="{{ $user->id }}">
<input type="hidden" name="course_id" value="{{ $course->id }}">

<script>

</script>