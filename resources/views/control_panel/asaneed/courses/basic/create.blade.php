
<div class="modal-header " style="background-color: #00937c !important;">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #fff !important;width: 100%;text-align-last: center;">
        إضافة مجلس اسناد علمي
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <form action="{{ route('asaneedCourses.store') }}" method="POST" id="form">
        @include('control_panel.asaneed.courses.basic.form')
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect btn-lg" data-bs-dismiss="modal">إلغاء</button>
    <button type="submit" form="form" class="btn btn-primary waves-effect waves-light btn-lg">حفظ</button>
</div>

    <script src="{{asset('control_panel/assets/js/pages/form-advanced.init.js')}}"></script>

    <script>
        $('#form').submit(function(event){
            $('input').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            var form = $(this).serialize();
            $('input[disabled]').each( function() {
                form = form + '&' + $(this).attr('name') + '=' + $(this).val();
            });
            event.preventDefault(); // avoid to execute the actual submit of the form.
            $.ajax({
                url:$(this).attr('action'),
                type:$(this).attr('method'),
                data:form,
                success:function(result){
                    $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                        { allow_dismiss: true,type:result.type }
                    );
                    document.querySelector('button[data-bs-dismiss="modal"]').click();
                    // setTimeout(function(){
                    $('#dataTable').DataTable().ajax.reload();
                    // }, 1100)
                },
                error:function (errors) {
                    const entries = Object.entries(errors.responseJSON.errors);
                    var errors_message = document.createElement('div');
                    for(let x of entries){
                        errors_message = document.createElement('div');
                        errors_message.classList.add('invalid-feedback');
                        errors_message.classList.add('show');
                        if(x[0] == 'place_id'){
                            x[0] = 'place_name';
                        }
                        if(x[0] == 'role_id'){
                            x[0] = 'role_id[]';
                        }
                        if(document.querySelector('[name="'+x[0]+'"]')) {
                            document.querySelector('[name="' + x[0] + '"]').classList.add('is-invalid');
                        }else{
                            // console.log(x[0],x[0] == 'role_id',document.querySelector('[name="'+x[0]+'"]'));
                        }
                        errors_message.innerHTML = x[1][0];
                        document.querySelector('[name="'+x[0]+'"]').parentElement.appendChild(errors_message);
                    }
                }

            });
            return false;
        });
    </script>
