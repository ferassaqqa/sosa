
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">مواعيد الحلقة المحذوفة</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3 row">
        <label for="name" class="col-md-3 col-form-label">اسم الحلقة</label>
        <div class="col-md-9">
            <input class="form-control" type="text" name="name" value="{{ $circle->name }}" id="name" disabled>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="place_name" class="col-md-3 col-form-label">اسم المنطقة</label>
        <div class="col-md-9">
            <input class="form-control" type="text" name="place_name" value="{{ $circle->place_name }}" id="place_name" disabled>
        </div>
    </div>

    @if(isset($circle->deletedAppointments) && $circle->deletedAppointments->count())
        <div style="width: 94%;">
            <div class="mb-3 row">
                <div class="col-md-11">
                    <label> المواعيد المحذوفة </label>
                </div>
            </div>
            @foreach($circle->deletedAppointments as $key => $appointment)
                <span> الموعد {{$key+1}}</span>
                <div class="mb-3 row">
                    <div class="col-md-3">
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input disabled type="text" class="form-control" value="{{ $appointment->from }}" onchange="validateFrom(this)" name="from[]" placeholder="من">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input disabled type="text" class="form-control" value="{{ $appointment->to }}" onchange="validateTo(this)" name="to[]" placeholder="الى">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input disabled class="form-control" type="text" name="day[]" value="{{ $appointment->day }}" placeholder="اليوم" />
                    </div>
                    <div class="col-md-1" style="padding: 0px">
                        <button type="button" title="حذف" data-id="{{ $appointment->id }}" class="btn btn-danger waves-effect waves-light remove-appointment"><i class="mdi mdi-close"></i></button>
                    </div>
                    <div class="col-md-1" style="padding: 0;margin-right: 10px;">
                        <button type="button" title="استرجاع" data-id="{{ $appointment->id }}" class="btn btn-success waves-effect waves-light restore-value"><i class="mdi mdi-arrow-top-left"></i></button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <hr>
    @if(isset($circle->deletedExtraAppointments) && $circle->deletedExtraAppointments->count())
        <div style="width: 94%;">
            <div class="mb-3 row">
                <div class="col-md-11">
                    <label> مواعيد استثنائية محذوفة</label>
                </div>
            </div>
            @foreach($circle->deletedExtraAppointments as $key => $appointment)
                <span> الموعد {{$key+1}}</span>
                <div class="mb-3 row">
                    <div class="col-md-4">
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input disabled type="text" class="form-control" value="{{ $appointment->from }}" onchange="validateFrom(this)" name="extra_from[]" placeholder="من">
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input disabled type="text" class="form-control" value="{{ $appointment->to }}" onchange="validateTo(this)" name="extra_to[]" placeholder="الى">
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-left: 0px;">
                        <input disabled class="form-control" type="text" value="{{ $appointment->day }}" name="extra_day[]" placeholder="اليوم" />
                    </div>
                    <div class="col-md-5" style="margin-top: 5px;">
                        <input disabled class="form-control" type="date" value="{{ $appointment->date }}" pattern="\d{2}-\d{4}-\d{2}" name="extra_date[]" placeholder="التاريخ" />
                    </div>
                    <div class="col-md-4" style="margin-top: 5px;">
                        <input disabled class="form-control place_name" value="{{ $appointment->pure_deleted_place_name }}" type="text" name="place_name" placeholder="المكان">
                    </div>
                    <div class="col-md-1" style="padding: 0px;margin-top: 5px;">
                        <button type="button" title="حذف" data-id="{{ $appointment->id }}" class="btn btn-danger waves-effect waves-light remove-appointment"><i class="mdi mdi-close"></i></button>
                    </div>
                    <div class="col-md-1" style="padding: 0;margin-right: 10px;margin-top: 5px;">
                        <button type="button" title="استرجاع" data-id="{{ $appointment->id }}" class="btn btn-success waves-effect waves-light restore-value"><i class="mdi mdi-arrow-top-left"></i></button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button type="button" class="btn btn-danger waves-effect" data-url="{{ route('circles.deleteAllAppointments',$circle->id) }}" onclick="deleteAllItems(this)"  data-bs-dismiss="modal">حذف الكل نهائي</button>
    <button class="btn btn-primary waves-effect waves-light" data-url="{{ route('circles.restoreAllAppointments',$circle->id) }}" onclick="restoreItem(this)">استرجاع الكل</button>
</div>
<script>
    $('.col-md-1').on('click', ".remove-appointment", function() {
        var thisButton = $(this);
        Swal.fire(
            {
                title:"هل انت متأكد",
                text:"لن تتمكن من استرجاع البيانات لاحقاً",
                icon:"warning",
                showCancelButton:!0,
                confirmButtonText:"نعم إحذف البيانات",
                cancelButtonText:"إلغاء",
                confirmButtonClass:"btn btn-success mt-2",
                cancelButtonClass:"btn btn-danger ms-2 mt-2",
                buttonsStyling:!1
            })
            .then(
                function(t){
                    if(t.value) {
                        if(typeof thisButton.data('id') !='undefined') {
                            $.get('deleteCircleAppointmentForEver/' + thisButton.data('id'), function (data) {
                                if(data.type == 'danger') {
                                    Swal.fire('خطأ !',data.msg,'error');
                                }else {
                                    thisButton.parent().parent().remove();
                                }
                            });
                        }else{
                            thisButton.parent().parent().remove();
                        }
                    }else {
                        Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                    }
                }
            );
    });
    $('.col-md-1').on('click', ".restore-value", function() {
        var thisButton = $(this);
        Swal.fire(
            {
                title:"هل انت متأكد",
                text:"سيتم استرجاع البيانات المحذوفة ",
                icon:"warning",
                showCancelButton:!0,
                confirmButtonText:"نعم استرجع البيانات",
                cancelButtonText:"إلغاء",
                confirmButtonClass:"btn btn-success mt-2",
                cancelButtonClass:"btn btn-danger ms-2 mt-2",
                buttonsStyling:!1
            })
            .then(
                function(t){
                    if(t.value) {
                        if(typeof thisButton.data('id') !='undefined') {
                            $.get('restoreCircleAppointment/' + thisButton.data('id'), function (data) {
                                if(data.type == 'danger') {
                                    Swal.fire('خطأ !',data.msg,'error');
                                }else {
                                    thisButton.parent().parent().remove();
                                }
                            });
                        }else{
                            thisButton.parent().parent().remove();
                        }
                    }else {
                        Swal.fire({title: "لم يتم الحذف!", text: "البيانات لم تحذف.", icon: "error"});
                    }
                }
            );
    });

</script>