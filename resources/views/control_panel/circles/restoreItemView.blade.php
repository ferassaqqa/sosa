
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;">استرجاع حلقة محذوفة</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3 row">
        <label for="name" class="col-md-3 col-form-label">الاسم</label>
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

    <div style="width: 94%;">
        <div class="mb-3 row">
            <div class="col-md-11">
                <label> مواعيد الدورات </label>
            </div>
        </div>
        @if(isset($circle->appointments) && $circle->appointments->count())
            @foreach($circle->appointments as $key => $appointment)
                <span> الموعد {{$key+1}}</span>
                <div class="mb-3 row">
                    <div class="col-md-4">
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input disabled type="text" class="form-control" value="{{ $appointment->from }}" onchange="validateFrom(this)" name="from[]" placeholder="من">
                            <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                            <input disabled type="text" class="form-control" value="{{ $appointment->to }}" onchange="validateTo(this)" name="to[]" placeholder="الى">
                            <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input disabled class="form-control" type="text" name="day[]" value="{{ $appointment->day }}" placeholder="اليوم" />
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <hr>
    <div style="width: 94%;">
        <div class="mb-3 row">
            <div class="col-md-11">
                <label> مواعيد استثنائية </label>
            </div>
        </div>
        @if(isset($circle->extraAppointments) && $circle->extraAppointments->count())
            @foreach($circle->extraAppointments as $key => $appointment)
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
                    <div class="col-md-6" style="margin-top: 5px;">
                        <input disabled class="form-control" type="date" value="{{ $appointment->date }}" pattern="\d{2}-\d{4}-\d{2}" name="extra_date[]" placeholder="التاريخ" />
                    </div>
                    <div class="col-md-5" style="margin-top: 5px;">
                        <input disabled class="form-control place_name" value="{{ $appointment->pure_deleted_place_name }}" type="text" name="place_name" placeholder="المكان">
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>
    <button class="btn btn-primary waves-effect waves-light" data-url="{{ route('circles.restoreItem',$circle->id) }}" onclick="restoreItem(this)">استرجاع</button>
</div>
