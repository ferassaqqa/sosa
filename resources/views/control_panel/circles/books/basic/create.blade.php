
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">اضافة كتاب جديد</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('circleBooks.store') }}" method="POST" id="form">
        @include('control_panel.circles.books.basic.form')
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button type="submit" form="form" class="btn btn-primary waves-effect waves-light">حفظ</button>
</div>

<script src="{{asset('control_panel/assets/js/pages/form-advanced.init.js')}}"></script>
<script>
    $('#form').submit(function(event){
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
                // console.log(entries);
                var errors_message = document.createElement('div');
                for(let x of entries){
                    if(document.querySelector('input[name="'+x[0]+'"]')) {
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

    // var plan_type = '';
    // function addPlan(obj) {
    //     Swal.fire({
    //         title: '<strong>ادخل نوع الخطة</strong>',
    //         icon: 'info',
    //         html:
    //         'اختر نوع الخطة' +
    //         '<select class="form-control" name="plan_type">' +
    //         '   <option value="سنوية">سنوية</option>' +
    //         '   <option value="ساعات">ساعات</option>' +
    //         '</select>' +
    //         '',
    //         showCloseButton: true,
    //         focusConfirm: false,
    //         confirmButtonAriaLabel: '',
    //         showCancelButton: true,
    //         confirmButtonText: 'اضافة',
    //         cancelButtonText: 'الغاء',
    //         showLoaderOnConfirm: true,
    //         // preConfirm: function(value){
    //         //     return fetch('/books/plan/create/'+value)
    //         //         .then(function(response){
    //         //             return response.json();
    //         //         }).then(function(responseJson) {
    //         //             // console.log(responseJson);
    //         //             if (responseJson.errors){
    //         //                 Swal.showValidationMessage(
    //         //                     responseJson.msg
    //         //                 );
    //         //                 // throw new Error('Something went wrong')
    //         //             }else{
    //         //                 Swal.close();
    //         //                 $('.bs-example-modal-xl').modal('show');
    //         //                 $('#user_modal_content').html(responseJson.view);
    //         //             }
    //         //             // Do something with the response
    //         //         })
    //         //         .catch(function (errors) {
    //         //             // Swal.showValidationMessage(
    //         //             //     'لا يوجد اتصال بالشبكة'
    //         //             // )
    //         //         });
    //         // },
    //         allowOutsideClick: function () {
    //             !Swal.isLoading();
    //         }
    //     }).then(function (result) {
    //         // console.log($('select[name="plan_type"]').val());
    //         if (result.isConfirmed) {
    //             plan_type = $('select[name="plan_type"]').val();
    //             obj.setAttribute('onclick','addNewPlanRecord(this)');
    //             // addNewPlanRecord
    //             $.get('/plans/create/' + plan_type, function (data) {
    //                 $('#create_plan_form').append(data);
    //             });
    //             // if(result.value.errors == 0) {
    //             // $('.bs-example-modal-xl').modal('show');
    //             // $('#user_modal_content').html(result.value.view);
    //             // }
    //         }
    //     })
    // }
    // function addNewPlanRecord() {
    //     $.get('/plans/create/'+plan_type,function(data){
    //         $('#create_plan_form').append(data);
    //         console.log(data);
    //     });
    // }
</script>