
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> تعديل فصل ضمن خطة كتاب {{$book->name}} - {{ $plan_name }} -</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <form action="{{ route('planSemesters.update',$plan->id) }}" method="POST" id="updateMonthForm">
            <input type="hidden" name="_method" value="PUT">
            @include('control_panel.plans.basic.year_plans.semesters.form')
        </form>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button type="submit" form="updateMonthForm" class="btn btn-primary waves-effect waves-light">تعديل</button>
</div>

<script>
    $('#updateMonthForm').submit(function(event){
        var form = $('#updateMonthForm input').filter(function () {
            return !!this.value;
        });
        var submit_button = $('button[form="updateMonthForm"]');
        submit_button.attr('disabled','true');
        submit_button.append('<div class="spinner-border text-danger" role="status" style="margin-right:​15px;height: 15px;width: 15px;"></div>');
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        $.ajax({
            url:$('#updateMonthForm').attr('action'),
            type:$('#updateMonthForm').attr('method'),
            data:form.serialize(),
            success:function(result){
                $.notify('&nbsp;&nbsp;&nbsp;&nbsp; <strong>'+ result.title +' </strong> | '+result.msg,
                    { allow_dismiss: true,type:result.type }
                );
                $('.res-restore-modal').modal('toggle');
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