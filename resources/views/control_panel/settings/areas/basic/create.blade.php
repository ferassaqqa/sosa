
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">اضافة بيانات منطقة جديدة</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('areas.store') }}" method="POST" id="form">
        @include('control_panel.settings.areas.basic.form')
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>
</div>

<script>
    $('#form').submit(function(event){
        var form = $('#form input').filter(function () {
            return !!this.value;
        });
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        $.ajax({
            url:$(this).attr('action'),
            type:$(this).attr('method'),
            data:form.serialize(),
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
                    if(document.querySelector('input[name="'+x[0]+'"]')) {
                        document.querySelector('input[name="' + x[0] + '"]').classList.add('is-invalid');
                        errors_message.innerHTML = x[1][0];
                        document.querySelector('input[name="' + x[0] + '"]').parentElement.appendChild(errors_message);
                    }else{
                        var input_name = x[0].split('.');
                        var input = document.querySelectorAll('input[name="' + input_name[0] + '[]"]')[input_name[1]];
                        if(typeof input !='undefined') {
                            input.classList.add('is-invalid');
                            console.log(input);
                            errors_message.innerHTML = x[1][0];
                            console.log(input.parentElement, errors_message);
                            input.parentElement.appendChild(errors_message);
                        }else{
                            document.querySelector('input[name="' + x[0] + '[]"]').classList.add('is-invalid');
                            errors_message.innerHTML = x[1][0];
                            document.querySelector('input[name="' + x[0] + '[]"]').parentElement.appendChild(errors_message);
                        }
                    }
                }
            }

        });
        return false;
    });
</script>