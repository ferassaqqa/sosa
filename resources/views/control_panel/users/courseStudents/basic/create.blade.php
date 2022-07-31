
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">اضافة طالب لدورة علمية</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('courseStudents.store') }}" method="POST" id="form" enctype="multipart/form-data">
        @include('control_panel.users.courseStudents.basic.form')
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>
</div>

    <script src="{{asset('control_panel/assets/js/pages/form-advanced.init.js')}}"></script>

    <script>
        var finalFiles = {};
        $('#form').submit(function(event){
            console.log(finalFiles);
            $('input').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            // $('input[disabled]').each( function() {
            //     form = form + '&' + $(this).attr('name') + '=' + $(this).val();
            // });
            var formData = new FormData();
            var form = $(this).serializeArray();
            for (let x of form){
                if(x.value.length){
                    formData.append(x.name,x.value);
                }
            }
            event.preventDefault(); // avoid to execute the actual submit of the form.
            if(finalFiles['user_profile']) {
                formData.append('user_profile', finalFiles['user_profile']);
            }
            if(finalFiles['encloses']) {
                var ix = 0;
                for (let i in finalFiles['encloses']) {
                    formData.append('encloses[' + ix + ']', finalFiles['encloses'][i]);
                    ix++;
                }
            }
            $.ajax({
                url:$(this).attr('action'),
                type:$(this).attr('method'),
                data:formData,
                contentType: false,
                processData: false,
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
                        console.log(x);
                        errors_message = document.createElement('div');
                        errors_message.classList.add('invalid-feedback');
                        errors_message.classList.add('show');
                        if(x[0].includes('encloses.')){
                            var keys = x[0].split('.');
                            if (document.querySelectorAll('[name="enclose_comment[]"]')[keys[1]]) {
                                document.querySelectorAll('[name="enclose_comment[]"]')[keys[1]].classList.add('is-invalid');
                            }
                            errors_message.innerHTML = x[1][0];
                            document.querySelectorAll('[name="enclose_comment[]"]')[keys[1]].parentElement.appendChild(errors_message);
                        }else {
                            if (document.querySelector('[name="' + x[0] + '"]')) {
                                document.querySelector('[name="' + x[0] + '"]').classList.add('is-invalid');
                            } else {
                                // console.log(x[0],x[0] == 'role_id',document.querySelector('[name="'+x[0]+'"]'));
                            }
                            errors_message.innerHTML = x[1][0];
                            document.querySelector('[name="'+x[0]+'"]').parentElement.appendChild(errors_message);
                        }
                    }
                }

            });
            return false;
        });
    </script>
