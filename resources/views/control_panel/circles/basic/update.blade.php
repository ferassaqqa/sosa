
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">تعديل بيانات حلقة </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('circles.update',$circle->id) }}" method="POST" id="form">
        <input type="hidden" name="_method" value="PUT">
        @include('control_panel.circles.basic.form')
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">تعديل</button>
</div>

<script>
    $('#form').submit(function(event){
        // var form = $('#form input').filter(function () {
        //     return !!this.value;
        // });
        // console.log($(this).serialize());
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
                for(let x of entries){
                    var errors_message = '';
                    if(x[0].includes('.')){
                        errors_message = document.createElement('div');
                        var key = x[0].split('.');
                        errors_message.classList.add('invalid-feedback');
                        errors_message.classList.add('show');
                        document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].classList.add('is-invalid');
                        errors_message.innerHTML = x[1][0];
                        document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].parentElement.appendChild(errors_message);
                    }else {
                        errors_message = document.createElement('div');
                        errors_message.classList.add('invalid-feedback');
                        errors_message.classList.add('show');
                        document.querySelector('input[name="' + x[0] + '"]').classList.add('is-invalid');
                        errors_message.innerHTML = x[1][0];
                        document.querySelector('input[name="' + x[0] + '"]').parentElement.appendChild(errors_message);
                    }
                }
            }

        });
        return false;
    });
</script>