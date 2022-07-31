
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">تعديل بيانات منطقة </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('areas.update',$area->id) }}" method="POST" id="form">
        <input type="hidden" name="_method" value="PUT">
        @include('control_panel.settings.areas.basic.form')
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">تعديل</button>
</div>

<script>
    $('#form').submit(function(event){
        var form = $('#form input').filter(function () {
            return !!this.value;
        });
        // console.log(form.serialize());
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        $.ajax({
            url:$(this).attr('action'),
            type:$(this).attr('method'),
            data:form.serialize(),
            success:function(result){
                if(result.type == 'success') {
                    $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>' + result.title + ' </strong> | ' + result.msg,
                        {allow_dismiss: true, type: result.type}
                    );
                    document.querySelector('button[data-bs-dismiss="modal"]').click();
                    $('#dataTable').DataTable().ajax.reload();
                }else{
                    // console.log(result.errors);
                    for(let x in result.errors){
                        // console.log(x);
                        var keys = x.split('.');
                        var input = document.querySelectorAll('input[name="'+keys[0]+'[]"]')[keys[1]];
                        var errors_message = document.createElement('div');
                        errors_message.classList.add('invalid-feedback');
                        errors_message.classList.add('show');
                        input.classList.add('is-invalid');
                        errors_message.innerHTML = result.errors[x];
                        input.parentElement.appendChild(errors_message);
                    }
                }
            },
            error:function (errors) {
                const entries = Object.entries(errors.responseJSON.errors);
                var input = '';
                for(let x of entries){
                    var errors_message = document.createElement('div');
                    errors_message.classList.add('invalid-feedback');
                    errors_message.classList.add('show');
                    input = document.querySelector('input[name="'+x[0]+'"]');
                    if(input) {
                        input.classList.add('is-invalid');
                        errors_message.innerHTML = x[1][0];
                        input.parentElement.appendChild(errors_message);
                    }else{
                        input = document.querySelector('input[name="'+x[0]+'[]"]');
                        input.classList.add('is-invalid');
                        errors_message.innerHTML = x[1][0];
                        input.parentElement.appendChild(errors_message);
                    }
                }
            }

        });
        return false;
    });
</script>