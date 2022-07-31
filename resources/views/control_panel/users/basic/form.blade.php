@csrf
<div class="row mb-5">
    <table class="table table-bordered">
        <tr>
            <td class="center-align" colspan="8" style="background-color: #f9fafb;">
                أولا: البيانات الشخصية
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">الاسم رباعي:</td>
            <td colspan="2">
                <select class="form-control" name="prefix" style="width: 12%;display: inline-block;">
                    <option value="أ" @if ($user->prefix == 'أ') selected @endif>أ</option>
                    <option value="د" @if ($user->prefix == 'د') selected @endif>د</option>
                    <option value="م" @if ($user->prefix == 'م') selected @endif>م</option>
                </select>
                <input type="text" class="form-control" name="name" disabled value="{{ $user->name }}"
                    style="width: 86%;display: inline-block;">
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">البريد الالكتروني:</td>
            <td colspan="2">
                <input type="text" class="form-control" placeholder="البريد الالكتروني" name="email"
                    value="{{ old('email', isset($user->userExtraData) ? $user->userExtraData->email : '') }}">
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">رقم الجوال:</td>
            <td colspan="2">
                <input type="text" class="form-control" placeholder="رقم الجوال" name="mobile"
                    value="{{ old('mobile', isset($user->userExtraData) ? $user->userExtraData->mobile : '') }}">
            </td>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">رقم الهوية / الوثيقة:</td>
            <td colspan="2">
                {{-- {{ $user->id_num }} --}}
                <input type="number" min="1" style="direction: rtl;" step="1" class="form-control" name="id_num"
                    value="{{ $user->id_num }}">
            </td>
        </tr>
        <tr>
            <td class="center-align" colspan="2" style="background-color: #f9fafb;">الصلاحيات:</td>
            <td colspan="6">
                {{-- <select class="select2 form-control select2-multiple" multiple name="role_id[]" id="role_id"> --}}
                <select class="form-control" name="role_id" id="role_id">
                    <option value="0">-- اختر --</option>
                    @foreach ($roles as $key => $value)
                        <option value="{{ $value->name }}" @if ($value->users_count) selected @endif>
                            {{ $value->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        {{-- @if (isset($create) || (isset($edit) && $edit)) --}}
        <tr id="area_tr">
            {!! $areas !!}
            <td class="center-align" id="sub_area_label" colspan="2" style="background-color: #f9fafb; display: @if (isset($edit) and !$edit) none; @endif">
                المنطقة المحلية:</td>
            <td colspan="2" id="sub_area_select" style=" display: @if (isset($edit) and !$edit) none; @endif">
                <select class="form-control sub_area_id" name="sub_area_id">
                    @if (isset($sub_areas))
                        {!! $sub_areas !!}
                    @endif
                </select>
            </td>
        </tr>
        {{-- @endif --}}
    </table>
</div>

<input type="hidden" name="id" value="{{ $user->id }}">


<script>
@if (isset($edit) and !$edit)
        $( document ).ready(function() {
            $('#role_id').change();
        });
 @endif

    $('select[name="area_id"]').on('change', function() {
        var area_id = $(this).val();

        $.get('/getSubAreas/' + area_id, function(data) {
            $('select[name="sub_area_id"]').empty().html(data);
        });
    });
    {{-- @if (isset($create) || (isset($edit) && $edit)) --}}
    $('#role_id').on('change', function() {
        // console.log($('#form').action);
        var role_name = $(this).val();

        switch (role_name) {
            case 'مشرف عام': {

                $('#area_select').removeAttr('style');
                $('#area_label').css('background-color', '#f9fafb');
                $('#area_label').removeAttr('style');
                $('#sub_area_select').css('display', 'none');
                $('#sub_area_label').css('display', 'none');
            }
            break;
        case 'مشرف ميداني': {
            $('#area_select').removeAttr('style');
            $('#area_label').css('background-color', '#f9fafb');
            $('#area_label').removeAttr('style');
            $('#sub_area_select').removeAttr('style');
            $('#sub_area_select').css('background-color', '#f9fafb');
            $('#sub_area_label').removeAttr('style');

            var area_id = $('select[name="area_id"]').val();
            if (area_id) {
                $.get('/getSubAreas/' + area_id, function(data) {
                    $('select[name="sub_area_id"]').empty().html(data);
                    $('.sub_area_id').val(@if (isset($edit) and $edit) $user->place->area_id @endif).change();

                });
            }
        }
        break;
        default: {
            $('#area_select').css('display', 'none');
            $('#area_label').css('display', 'none');
            $('#sub_area_select').css('display', 'none');
            $('#sub_area_label').css('display', 'none');
        }
        }
    });
    {{-- @endif --}}
</script>
