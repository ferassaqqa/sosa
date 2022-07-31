<style>
    .pointer {
        cursor: pointer;
    }

    .table_label {
        color: #343a40;
        /* font-weight: 600; */
        background-color: #ced4da !important;
    }

    .modal-body,
    .modal-footer {
        padding: 20px 40px 20px 40px;
    }

</style>
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> تأكيد حجز اختبار دورة -
        {{ $exam->course_book_name }} - للمعلم - {{ $exam->course_name }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">






    {{-- <table class="table table-responsive table-bordered"> --}}
    {{-- <thead> --}}
    {{-- <th rowspan="2">مشرف الجودة:</th> --}}
    {{-- <th colspan="7" class="center-align">أيام الأسبوع:</th> --}}
    {{-- </thead> --}}
    {{-- <tbody> --}}
    {{-- <tr> --}}
    {{-- <td></td> --}}
    {{-- @for ($i = 0; $i < 7; $i++) --}}
    {{-- <td>{{ \Carbon\Carbon::parse('next saturday')->addDays($i)->toDateString() }}</td> --}}
    {{-- @endfor --}}
    {{-- </tr> --}}
    {{-- @foreach ($qualitySupervisors as $key => $qualitySupervisor) --}}
    {{-- <tr> --}}
    {{-- <td>{{ $qualitySupervisor->name }}</td> --}}
    {{-- <td class="pointer" onclick="addAppointment('{{\Carbon\Carbon::parse('next saturday')->addDays(0)->toDateString()}}','{{ $qualitySupervisor->id }}')"> --}}
    {{-- السبت --}}
    {{-- {!! $exam->hasAppointment(\Carbon\Carbon::parse('next saturday')->addDays(0)->toDateString(), $qualitySupervisor->id) !!} --}}
    {{-- </td> --}}
    {{-- <td class="pointer" onclick="addAppointment('{{\Carbon\Carbon::parse('next saturday')->addDays(1)->toDateString()}}','{{ $qualitySupervisor->id }}')"> --}}
    {{-- الأحد --}}
    {{-- {!! $exam->hasAppointment(\Carbon\Carbon::parse('next saturday')->addDays(1)->toDateString(), $qualitySupervisor->id) !!} --}}
    {{-- </td> --}}
    {{-- <td class="pointer" onclick="addAppointment('{{\Carbon\Carbon::parse('next saturday')->addDays(2)->toDateString()}}','{{ $qualitySupervisor->id }}')"> --}}
    {{-- الإثنين --}}
    {{-- {!! $exam->hasAppointment(\Carbon\Carbon::parse('next saturday')->addDays(2)->toDateString(), $qualitySupervisor->id) !!} --}}
    {{-- </td> --}}
    {{-- <td class="pointer" onclick="addAppointment('{{\Carbon\Carbon::parse('next saturday')->addDays(3)->toDateString()}}','{{ $qualitySupervisor->id }}')"> --}}
    {{-- الثلاثاء --}}
    {{-- {!! $exam->hasAppointment(\Carbon\Carbon::parse('next saturday')->addDays(3)->toDateString(), $qualitySupervisor->id) !!} --}}
    {{-- </td> --}}
    {{-- <td class="pointer" onclick="addAppointment('{{\Carbon\Carbon::parse('next saturday')->addDays(4)->toDateString()}}','{{ $qualitySupervisor->id }}')"> --}}
    {{-- الأربعاء --}}
    {{-- {!! $exam->hasAppointment(\Carbon\Carbon::parse('next saturday')->addDays(4)->toDateString(), $qualitySupervisor->id) !!} --}}
    {{-- </td> --}}
    {{-- <td class="pointer" onclick="addAppointment('{{\Carbon\Carbon::parse('next saturday')->addDays(5)->toDateString()}}','{{ $qualitySupervisor->id }}')"> --}}
    {{-- الخميس --}}
    {{-- {!! $exam->hasAppointment(\Carbon\Carbon::parse('next saturday')->addDays(5)->toDateString(), $qualitySupervisor->id) !!} --}}
    {{-- </td> --}}
    {{-- <td class="pointer" onclick="addAppointment('{{\Carbon\Carbon::parse('next saturday')->addDays(6)->toDateString()}}','{{ $qualitySupervisor->id }}')"> --}}
    {{-- الجمعة --}}
    {{-- {!! $exam->hasAppointment(\Carbon\Carbon::parse('next saturday')->addDays(6)->toDateString(), $qualitySupervisor->id) !!} --}}
    {{-- </td> --}}
    {{-- </tr> --}}
    {{-- @endforeach --}}
    {{-- </tbody> --}}
    {{-- </table> --}}



    <div class="mb-3 row">

        <div class="col-md-6">
            <label for="start_date" class=" col-form-label">تاريخ الاختبار</label>
            <div class="col-md-12">
                <div class="input-group" id="datepicker1">
                    <input type="text" class="form-control" placeholder="تاريخ الاختبار" name="date"
                        value="{{ old('date', $exam->date) }}" id="date" data-date-format="yyyy-mm-dd"
                        data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true">
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label for="start_date" class=" col-form-label">ساعة الاختبار</label>
            <div class="col-md-12">
                <div class="input-group">
                    <input type="time" name="time" id="time" class="form-control" placeholder="وقت الاختبار"
                        value="{{ $exam->time }}">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <label for="start_date" class=" col-form-label">الموعد</label>
            <div class="col-md-12">
                <div class="input-group">
                    <input type="text" name="appointment" id="appointment" class="form-control"
                        value="{{ $exam->appointment }}">
                </div>
            </div>
        </div>



        <div class="col-md-6">
            <label for="supervisor_id" class="col-form-label"> مشرف الجودة</label>
            <div class="col-md-12">
                <select id="supervisor_id" class="form-control" name="supervisor_id[]">
                    <option value="">اختر</option>
                    @foreach ($qualitySupervisors as $key => $qualitySupervisor)
                        <option @if (in_array($qualitySupervisor->id, $selected_quality_supervisors)) selected @endif
                            value="{{ $qualitySupervisor->id }}">{{ $qualitySupervisor->name }}</option>
                    @endforeach
                </select>

                {{-- <div>
                @foreach ($qualitySupervisors as $key => $qualitySupervisor)
                    <input type="checkbox" name="supervisor_id[]" @if (in_array($qualitySupervisor->id, $selected_quality_supervisors)) checked @endif
                        value="{{ $qualitySupervisor->id }}">&nbsp;{{ $qualitySupervisor->name }}<br>
                @endforeach
            </div> --}}
            </div>
        </div>

        <div class="col-md-12">
            <label for="start_date" class=" col-form-label">الملاحظات</label>
            <div class="col-md-12">
                <div class="input-group">
                    <textarea name="notes" id="notes" class="form-control">{{ $exam->notes }}</textarea>

                </div>
            </div>
        </div>

    </div>
    <div class="mb-3 row">

    </div>
    <div class="row">

    </div>

    <table border="0" width="100%" class="ui compact selectable striped celled table data-table nomargin" dir="rtl"
        style="margin-top: 0px;" data-time="">
        <tbody>
            <tr>
                <td class="dark-th normal-bg table_label">عنوان الدورة</td>
                <td class="white-bg print-white" style="background-color: #fff;">{{ $exam->course_book_name }}</td>
                <td class="dark-th normal-bg table_label">عدد الطلاب</td>
                <td class="white-bg print-white" style="background-color: #fff;">{{ $exam->students_count }}</td>
            </tr>
            <tr>
                <td class="dark-th normal-bg table_label">اسم المعلم</td>
                <td class="white-bg print-white" style="background-color: #fff;">{{ $exam->course_name }}</td>
                <td class="dark-th normal-bg table_label">هاتف المعلم</td>
                <td class="white-bg print-white" style="background-color: #fff;">{{ $exam->teacher_mobile }}</td>
            </tr>

            <tr>
                <td class="dark-th normal-bg table_label">المشرف الميداني</td>
                <td class="white-bg print-white" style="background-color: #fff;">
                    {{ $exam->sub_area_supervisor_name }}
                </td>
                <td class="dark-th normal-bg table_label">مكان الإختبار</td>
                <td class="white-bg print-white" style="background-color: #fff;">{{ $exam->place_name }}</td>
            </tr>


        </tbody>
    </table>



</div>

<div class="modal-footer">
    {{-- <button type="button" class="btn btn-secondary waves-effect" onclick="getPendingExamRequests()">رجوع</button> --}}
    <button type="button" onclick="setExamAppointment()"
        class="btn btn-primary waves-effect waves-light btn-lg">حفظ</button>
</div>

<script>
    function setExamAppointment() {
        var superVisors = [];
        var appointment = document.getElementById('appointment').value ? document.getElementById('appointment').value :
            0;
        var date = document.getElementById('date').value ? document.getElementById('date').value : 0;
        var time = document.getElementById('time').value ? document.getElementById('time').value : 0;
        var notes = document.getElementById('notes').value ? document.getElementById('notes').value : 0;


        var selected_supervisor = $("select#supervisor_id option").filter(":selected").val();
        superVisors.push(selected_supervisor);

        // $('input[name="supervisor_id[]"]:selected').each(function() {
        //     superVisors.push($(this).val());
        // });

        $.get('/updateExamAppointmentApprove/{{ $exam->id }}/' + appointment + '/' + date + '/' + superVisors +
            '/' + time + '/' + notes,
            function(data) {
                document.querySelector('button[data-bs-dismiss="modal"]').click();
                // setTimeout(function(){
                $('#dataTable1').DataTable().ajax.reload();
            })
    }

    {{-- function addAppointment(date,quality_supervisor_id) { --}}
    {{-- Swal.fire({ --}}
    {{-- title: 'حدد وقت الاختبار', --}}
    {{-- icon: 'info', --}}
    {{-- // input:'time', --}}
    {{-- html:'<input type="time" id="exam_time" class="form-control" value="{{ $exam->time }}">', --}}
    {{-- showCloseButton: true, --}}
    {{-- showDenyButton: true, --}}
    {{-- focusConfirm: false, --}}
    {{-- showLoaderOnConfirm: true, --}}
    {{-- confirmButtonText:'حفظ', --}}
    {{-- denyButtonText:'حذف', --}}
    {{-- preConfirm: function(value){ --}}
    {{-- var time = document.getElementById('exam_time').value; --}}
    {{-- var appointment = document.getElementById('appointment').value; --}}
    {{-- appointment = appointment ? appointment : 0; --}}
    {{-- console.log(time); --}}
    {{-- if(!time.length) { --}}
    {{-- Swal.showValidationMessage( --}}
    {{-- 'ادخل وقت الاختبار' --}}
    {{-- ); --}}
    {{-- return false; --}}
    {{-- } --}}
    {{-- return fetch('/updateExamAppointmentApprove/{{ $exam->id }}/'+appointment+'/'+date+'/'+quality_supervisor_id+'/'+time) --}}
    {{-- .then(function(response){ --}}
    {{-- return response.json() --}}
    {{-- }).then(function(responseJson) { --}}
    {{-- // console.log(responseJson); --}}
    {{-- if (responseJson.errors){ --}}
    {{-- Swal.showValidationMessage( --}}
    {{-- responseJson.msg --}}
    {{-- ); --}}
    {{-- }else{ --}}
    {{-- Swal.close(); --}}
    {{-- $('#user_modal_content') --}}
    {{-- .html( --}}
    {{-- '<div class="spinner-border text-success" role="status" style="margin:25px auto;">' + --}}
    {{-- '   <span class="sr-only">يرجى الانتظار ...</span>' + --}}
    {{-- '</div>' --}}
    {{-- ); --}}
    {{-- $.get('/approveExamAppointment/{{ $exam->id }}',function (data) { --}}
    {{-- $('#user_modal_content').empty().html(data); --}}
    {{-- }); --}}
    {{-- } --}}
    {{-- }) --}}
    {{-- .catch(function(error){ --}}
    {{-- // Swal.showValidationMessage( --}}
    {{-- //     error --}}
    {{-- // ) --}}
    {{-- }) --}}
    {{-- }, --}}
    {{-- allowOutsideClick: function(){!Swal.isLoading();} --}}
    {{-- }).then(function(result){ --}}
    {{-- console.log(result); --}}
    {{-- if (result.isConfirmed) { --}}
    {{-- } else if (result.isDenied) { --}}
    {{-- $.get('/deleteExamQualitySupervisor/{{ $exam->id }}/'+quality_supervisor_id,function(data){ --}}
    {{-- Swal.close(); --}}
    {{-- $.get('approveExamAppointment/{{ $exam->id }}',function (data) { --}}
    {{-- $('#user_modal_content').empty().html(data); --}}
    {{-- }); --}}
    {{-- }); --}}
    {{-- } --}}
    {{-- }) --}}
    {{-- } --}}
</script>
