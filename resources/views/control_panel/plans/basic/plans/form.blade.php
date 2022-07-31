@csrf

<input type="hidden" class="form-control" value="{{ $year }}"
       name="year"/>
@if($department == 1)
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered mb-0 text-center">
            <thead>
                <tr>
                    <th rowspan="2" class="center-align">اسم الكتاب</th>
                    <th colspan="4" class="center-align">مكفول</th>
                    <th colspan="4" class="center-align">متطوع</th>
                </tr>
                <tr>
                    <th class="center-align">سنوي</th>
                    <th colspan="2" class="center-align">فصلي</th>
                    <th class="center-align">شهري</th>
                    <th class="center-align">سنوي</th>
                    <th colspan="2" class="center-align">فصلي</th>
                    <th class="center-align">شهري</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($books)&&!isset($plans))
                    @foreach($books as $key => $book)
                        <tr data-book-id="{{ $book->id }}">
                            <td>{{ $book->name }}</td>
                            <td>
                                <input type="number" style="width: 60px;" min="0" step="1" placeholder="سنويا" name="yearly_count[{{ $book->id }}][]">
                                <button type="button" class="btn btn-primary btn-sm new-semester"><i class="mdi mdi-plus"></i></button>
                            </td>
                            <td><input type="text" style="width: 120px;" placeholder="الفصل الاول" name="year_semester[{{ $book->id }}][0][]"></td>
                            <td><input type="number" style="width: 60px;" min="0" step="1" placeholder="فصليا" name="semester_count[{{ $book->id }}][0][]"></td>
                            <td><input type="number" style="width: 60px;" min="0" step="1" placeholder="شهريا" name="month_count[{{ $book->id }}][0][]"></td>
                            <td>
                                <input type="number" style="width: 60px;" min="0" step="1" placeholder="سنويا" name="yearly_count[{{ $book->id }}][]">
                            </td>
                            <td><input type="text" style="width: 120px;" placeholder="الفصل الاول" name="year_semester[{{ $book->id }}][1][]"></td>
                            <td><input type="number" style="width: 60px;" min="0" placeholder="فصليا" step="1" name="semester_count[{{ $book->id }}][1][]"></td>
                            <td><input type="number" style="width: 60px;" min="0" placeholder="شهريا" step="1" name="month_count[{{ $book->id }}][1][]"></td>
                        </tr>
                    @endforeach
                @else
                    @if(isset($plans)&&$plans->count())
                        @foreach($plans as $key => $plan)
                            <tr data-book-id="{{ $plan[0]->book_id }}">
                                <td>{{ $key }}</td>
                                <td>
                                    <input type="number" style="width: 60px;" min="0" step="1" value="{{ $plan[0]['years'][0]['yearly_count'] }}" placeholder="سنويا" name="yearly_count[{{ $plan[0]->book_id }}][]">
                                    <button type="button" class="btn btn-primary btn-sm new-semester"><i class="mdi mdi-plus"></i></button>
                                </td>
                                <td><input type="text" style="width: 120px;" value="{{ $plan[0]['years'][0]['semesters'][0]['year_semester'] }}" placeholder="الفصل الاول" name="year_semester[{{ $plan[0]->book_id }}][0][]"></td>
                                <td><input type="number" style="width: 60px;" min="0" step="1" value="{{ $plan[0]['years'][0]['semesters'][0]['semester_count'] }}" placeholder="فصليا" name="semester_count[{{ $plan[0]->book_id }}][0][]"></td>
                                <td><input type="number" style="width: 60px;" min="0" step="1" value="{{ $plan[0]['years'][0]['semesters'][0]['month_count'] }}" placeholder="شهريا" name="month_count[{{ $plan[0]->book_id }}][0][]"></td>
                                <td>
                                    <input type="number" style="width: 60px;" min="0" step="1" value="{{ $plan[1]['years'][0]['yearly_count'] }}" placeholder="سنويا" name="yearly_count[{{ $plan[0]->book_id }}][]">
                                </td>
                                <td><input type="text" style="width: 120px;" value="{{ $plan[1]['years'][0]['semesters'][0]['year_semester'] }}" placeholder="الفصل الاول" name="year_semester[{{ $plan[0]->book_id }}][1][]"></td>
                                <td><input type="number" style="width: 60px;" min="0" placeholder="فصليا" step="1" value="{{ $plan[1]['years'][0]['semesters'][0]['semester_count'] }}" name="semester_count[{{ $plan[0]->book_id }}][1][]"></td>
                                <td><input type="number" style="width: 60px;" min="0" placeholder="شهريا" step="1" value="{{ $plan[1]['years'][0]['semesters'][0]['month_count'] }}" name="month_count[{{ $plan[0]->book_id }}][1][]"></td>
                            </tr>
                        @endforeach
                    @endif
                @endif
            </tbody>
        </table>
    </div>
</div>
@elseif($department == 2)
    <?php
        $sub_area_count = 0;
        $larger_sub_area_count = 0;
    ?>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center">
                    <tr style="background-color: #F9FAFB; ">
                        <th rowspan="1" class="center-align">اسم الكتاب</th>
                        <th colspan="1" class="th_header_label center-align">اسم المنطقة</th>
                    </tr>
                @foreach($areas as $key => $area)
                    <?php
                        $sub_area_count = 0;
                    ?>
                    <tr style="background-color: #F9FAFB; ">
                        <th></th>
                        <th class="th_header center-align">{{ $area->name }}</th>
                        <th>
                            <input disabled class="form-control area_{{$area->id}}" value="{{getCoursePlanAreaTotalValue($area->id ,$year )}}" type="number" min="0" step="1" name="area_total_value[{{$area->id}}]" style="width: 92px;margin: 0px auto;">
                        </th>
                    </tr>
                    <tr>
                    <tr>
                        <td style="background-color: #F9FAFB; "></td>
                        @foreach($area->subArea as $key => $subArea)
                            <?php $sub_area_count++;?>
                            <th>{{ $subArea->name }}</th>
                        @endforeach
                    </tr>
                    <?php
                        ($sub_area_count > $larger_sub_area_count) ? ($larger_sub_area_count = $sub_area_count) : '';
                    ?>

                        @if(isset($books))
                            @foreach($books as $key => $book)
                                <tr>
                                    <td style="background-color: #F9FAFB; ">{{ $book->name }}</td>
                                    @foreach($area->subArea as $key => $subArea)
                                        <td>
                                            <input disabled class="form-control subArea_{{$subArea->id}}" value="{{getCoursePlanSubAreaBookValue($subArea->id, $book->id ,$year )}}" oninput="checkTotalLimit(this,'{{ $subArea->id }}')" type="number" min="0" step="1" name="sub_area_value[{{$area->id}}][{{$subArea->id}}][{{$book->id}}]" style="width: 92px;margin: 0px auto;">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @else
                            @if(isset($plans)&&$plans->count())
                                @foreach($plans as $key => $plan)
                                    <tr data-book-id="{{ $plan[0]->book_id }}">
                                        <td>{{ $key }}</td>
                                        <td>
                                            <input disabled type="number" style="width: 60px;" min="0" step="1" value="{{ $plan[0]['years'][0]['yearly_count'] }}" placeholder="سنويا" name="yearly_count[{{ $plan[0]->book_id }}][]">
                                            <button type="button" class="btn btn-primary btn-sm new-semester"><i class="mdi mdi-plus"></i></button>
                                        </td>
                                        <td><input disabled type="text" style="width: 120px;" value="{{ $plan[0]['years'][0]['semesters'][0]['year_semester'] }}" placeholder="الفصل الاول" name="year_semester[{{ $plan[0]->book_id }}][0][]"></td>
                                        <td><input disabled type="number" style="width: 60px;" min="0" step="1" value="{{ $plan[0]['years'][0]['semesters'][0]['semester_count'] }}" placeholder="فصليا" name="semester_count[{{ $plan[0]->book_id }}][0][]"></td>
                                        <td><input disabled type="number" style="width: 60px;" min="0" step="1" value="{{ $plan[0]['years'][0]['semesters'][0]['month_count'] }}" placeholder="شهريا" name="month_count[{{ $plan[0]->book_id }}][0][]"></td>
                                        <td>
                                            <input disabled type="number" style="width: 60px;" min="0" step="1" value="{{ $plan[1]['years'][0]['yearly_count'] }}" placeholder="سنويا" name="yearly_count[{{ $plan[0]->book_id }}][]">
                                        </td>
                                        <td><input disabled type="text" style="width: 120px;" value="{{ $plan[1]['years'][0]['semesters'][0]['year_semester'] }}" placeholder="الفصل الاول" name="year_semester[{{ $plan[0]->book_id }}][1][]"></td>
                                        <td><input disabled type="number" style="width: 60px;" min="0" placeholder="فصليا" step="1" value="{{ $plan[1]['years'][0]['semesters'][0]['semester_count'] }}" name="semester_count[{{ $plan[0]->book_id }}][1][]"></td>
                                        <td><input disabled type="number" style="width: 60px;" min="0" placeholder="شهريا" step="1" value="{{ $plan[1]['years'][0]['semesters'][0]['month_count'] }}" name="month_count[{{ $plan[0]->book_id }}][1][]"></td>
                                    </tr>
                                @endforeach
                            @endif
                        @endif
                    <script>
                        $('.th_header_label').attr('colspan','{{ $larger_sub_area_count }}');
                        $('.th_header').attr('colspan','{{ $larger_sub_area_count-1 }}');
                    </script>
                @endforeach
            </table>
        </div>
    </div>
@endif
<input type="hidden" name="department" value="{{ $department }}">
<script>
    // $("#datepicker1").datepicker({
    //     format: "yyyy",
    //     viewMode: "years",
    //     minViewMode: "years"
    // });
    $('td').on('click','.new-semester',function(){
        var clicked_btn = $(this);
        var clicked_tr = clicked_btn.closest('tr');
        var book_id = clicked_tr.data('book-id');
        clicked_tr.after(
            '<tr>'+
            '   <td></td>'+
            '   <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteSemester(this)"><i class="mdi mdi-trash-can"></i></button></td>'+
            '   <td><input type="text" style="width: 120px;" placeholder="الفصل الاول" name="year_semester['+book_id+'][0][]"></td>'+
            '   <td><input type="number" style="width: 60px;" min="0" step="1" placeholder="فصليا" name="semester_count['+book_id+'][0][]"></td>'+
            '   <td><input type="number" style="width: 60px;" min="0" step="1" placeholder="شهريا" name="month_count['+book_id+'][0][]"></td>'+
            '   <td></td>'+
            '   <td><input type="text" style="width: 120px;" placeholder="الفصل الاول" name="year_semester['+book_id+'][1][]"></td>'+
            '   <td><input type="number" style="width: 60px;" min="0" placeholder="فصليا" step="1" name="semester_count['+book_id+'][1][]"></td>'+
            '   <td><input type="number" style="width: 60px;" min="0" placeholder="شهريا" step="1" name="month_count['+book_id+'][1][]"></td>'+
            '</tr>'
        );
    });
    function deleteSemester(obj){
        obj.parentElement.parentElement.parentElement.removeChild(obj.parentElement.parentElement);
    }

    /**
     * courses scripts
     */
    function checkTotalLimit(obj,area_id){
        // console.log(obj.value,area_id);
        var area_total_summation_value = 0;
        var area_input = $('.area_'+area_id);
        area_input.removeClass('is-invalid');
        area_input.closest('th').find('span').remove();
        var area_total_value = area_input.val();
        $('.subArea_'+area_id).each(function(){
            // console.log($(this).val());
            if($(this).val().length) {
                area_total_summation_value = +parseInt(area_total_summation_value) + +parseInt($(this).val());
            }
        });
        if(area_total_value < area_total_summation_value){
            obj.value = 0;
            area_input.addClass('is-invalid');
            area_input.closest('th').append('<span style="color: red;"> لقد تخطيت المجموع الكلي للمنطقة </span>');
        }
        // console.log(area_total_value,area_total_summation_value);
    }
</script>
