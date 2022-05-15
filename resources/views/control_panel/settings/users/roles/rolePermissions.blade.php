
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">تعديل صلاحيات الدور</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="checkAll">
        <label class="form-check-label" for="checkAll">
            اختيار الكل
        </label>
    </div>
    <form action="{{ route('roles.updatePermissions',$role->id) }}" method="POST" id="form">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        @foreach($permissionsBag as $key => $titles)
            <div class="row" style="border: 1px solid #DDD;margin: 5px auto;">
                <label style="padding: 17px;background-color: #DDD;" class="text-center-align">{{$key}}</label>
                @foreach($titles as $key1 => $permissions)
                    <div class="row" style="border-top: 1px solid #DDD;margin: 5px auto;">
                        <label style="margin: 17px auto;">{{$key1}}</label>
                        @foreach($permissions as $key2 => $permission)
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" name="permissions[]" type="checkbox" @if($permission->roles_count) checked @endif value="{{ $permission->id }}" id="defaultCheck{{ $permission->id }}">
                                    <label class="form-check-label" for="defaultCheck{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>
</div>

<script>
    $('#form').submit(function(event){
        var permissions = [];
        $("input[name='permissions[]']:checked").each(function(){
            permissions.push($(this).val());
        });
        // console.log(permissions);
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        $.ajax({
            url:$(this).attr('action'),
            type:$(this).attr('method'),
            data:$(this).serialize(),
            success:function(result){
                $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                    { allow_dismiss: true,type:result.type }
                );
                document.querySelector('button[data-bs-dismiss="modal"]').click();
                $('#dataTable').DataTable().ajax.reload();
            },
            error:function (errors) {
                const entries = Object.entries(errors.responseJSON.errors);
                var errors_message = document.createElement('div');
                errors_message.classList.add('invalid-feedback');
                errors_message.classList.add('show');
                for(let x of entries){
                    document.querySelector('input[name="'+x[0]+'"]').classList.add('is-invalid');
                    errors_message.innerHTML = x[1][0];
                    document.querySelector('input[name="'+x[0]+'"]').parentElement.appendChild(errors_message);
                }
            }

        });
        return false;
    });
    $("#checkAll").click(function(){
        $('input[name="permissions[]"]').not(this).prop('checked', this.checked);
    });
</script>