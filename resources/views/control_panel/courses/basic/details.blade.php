


<style>


    .modal-content{
        border: unset;
    }

    .modal-body{
            padding: 25px;
    }


    </style>




<div class="modal-header " style="background-color: #00937c !important;">
    <h5 class="modal-title" id="myLargeModalLabel" style="color: #fff !important;width: 100%;text-align-last: center;">
        تفاصيل دورة علمية
         {{-- ({{$course_number}}) --}}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class='row'>

      <table border="0" width="100%" class="ui compact selectable striped celled table data-table nomargin" dir="rtl" style="margin-top: 0px;" data-time="">
          <tbody>
              <tr>
                  <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">الكتاب</td>      
                  <td class="white-bg print-white" style="background-color: #fff;">{!! $course->book_name !!}</td>
                  <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">المعلم</td>
                  <td class="white-bg print-white" style="background-color: #fff;">{!! $course->teacher_name !!}</td>
              </tr>
              <tr>
                  <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">عدد الطلاب</td>
                  <td class="white-bg print-white" style="background-color: #fff;">{{ $course->studentsForPermissions->count() }}</td>
                  <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">تاريخ بداية الدورة</td>
                  <td class="white-bg print-white" style="background-color: #fff;">{{ $course->start_date }}</td>
              </tr>

              <tr>
                <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">إضافة الدورة بواسطة</td>
                <td class="white-bg print-white" style="background-color: #fff;">{{ $course->created_at_user ? $course->created_at_user->name : '' }}</td>
                <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">تاريخ الاضافة</td>
                <td class="white-bg print-white" style="background-color: #fff;">{{ $course->created_at_user ? \Carbon\Carbon::parse($course->created_at_user->created_at)->format('Y-m-d') : '' }}</td>
            </tr>

              <tr>
                  <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">العنوان</td>
                  <td class="white-bg print-white" colspan="3" style="background-color: #fff;">{!! $course->area_father_name !!} - {!! $course->area_name !!} - {!! $course->place_name !!}</td>
              </tr>

              <tr>
                <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">فئة الطلاب</td>
                <td class="white-bg print-white" colspan="3" style="background-color: #fff;">{!! $course->book_students_category_string !!}</td>
            </tr>

            <tr>
                <td class="dark-th normal-bg" style="background-color: rgba(0,0,50,.02);">عدد الساعات</td>
                <td class="white-bg print-white" colspan="3" style="background-color: #fff;">{!! $course->book_students_hours_count !!}</td>
            </tr>
          </tbody>
      </table>
         
      



</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect btn-lg" data-bs-dismiss="modal">إغلاق</button>
</div>


