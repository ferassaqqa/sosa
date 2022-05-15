
<div class="modal-header">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #000 !important;"> خطة كتاب {{$book->name}} - {{ $plan->name }} -</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="mb-3 row">
            <div class="col-md-10">{{ $plan->name }}</div>
            <div class="col-md-2">
                @if($plan->type == 'سنوية')
                    <button type="button" class="btn btn-primary btn-sm" data-url="{{route('planYears.create',$plan->id)}}" data-bs-toggle="modal" data-bs-target=".res-restore-modal" onclick="callApi(this,'restore_modal_content')"><i class="mdi mdi-plus"></i></button>
                @else
                    <button type="button" class="btn btn-primary btn-sm" data-url="{{route('planHours.create',$plan->id)}}" data-bs-toggle="modal" data-bs-target=".res-restore-modal" onclick="callApi(this,'restore_modal_content')"><i class="mdi mdi-plus"></i></button>
                @endif
            </div>
        </div>
        <div class="mb-3 row" style="padding: 5px;border:1px solid #DDD;width: 98%;margin-right:5px;">
            @if($plan->type == 'سنوية')
                <div class="treeview w-20">
                    <ul class="mb-1 pl-3 pb-2 years_list">
                        @foreach($plan->years as $key => $year)
                            <li>
                                <div class="mb-3 row">
                                    <div class="col-md-8">
                                        {{ $year->plan_year }}
                                    </div>
                                    <div class="col-md-4">
                                        {!! $year->tools !!}
                                    </div>
                                </div>
                                <ul class="nested">
                                    @if($year->semesters->count())
                                        @foreach($year->semesters as $key => $semester)
                                            <li class="mb-3" style="padding: 5px;border:1px solid #DDD">
                                                <div class="mb-3 row">
                                                    <div class="col-md-8">
                                                        {{$semester->year_semester}}
                                                    </div>
                                                    <div class="col-md-4">
                                                        {!! $semester->tools !!}
                                                    </div>
                                                </div>
                                                <ul class="nested">

                                                    @if($semester->months->count())
                                                        <li>
                                                            @foreach($semester->months as $key => $month)
                                                                @if(!$key)
                                                                    <div class="mb-3 row table-responsive" style="width: 104%;">
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th scope="col">الشهر</th>
                                                                                    <th scope="col">عدد الاحاديث</th>
                                                                                    <th scope="col">من</th>
                                                                                    <th scope="col">الى</th>
                                                                                    <th scope="col">ادوات</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                @endif
                                                                {!! $month->row !!}

                                                                {{--<tr>--}}
                                                                                    {{--<td>{{ $month->semester_month }}</td>--}}
                                                                                    {{--<td>{{ $month->hadith_count }}</td>--}}
                                                                                    {{--<td>{{ $month->from_hadith }}</td>--}}
                                                                                    {{--<td>{{ $month->to_hadith }}</td>--}}
                                                                                    {{--<td>--}}
                                                                                        {{--{!! $month->tools !!}--}}
                                                                                    {{--</td>--}}
                                                                                {{--</tr>--}}
                                                                @if($key == ($semester->months->count()-1))
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </li>
                                                    @endif
                                                </ul>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                @if($plan->hours->count())
                    <div class="mb-3 row table-responsive" style="width: 104%;">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">من</th>
                                <th scope="col">الى</th>
                                <th scope="col">عدد الساعات</th>
                                <th scope="col">ادوات</th>
                            </tr>
                            </thead>
                                @foreach($plan->hours as $key => $hour)
                                    {!! $hour->row !!}
                                @endforeach
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

{{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">إلغاء</button>--}}
    {{--<button type="submit" form="form" class="btn btn-primary waves-effect waves-light">تعديل</button>--}}
{{--</div>--}}
