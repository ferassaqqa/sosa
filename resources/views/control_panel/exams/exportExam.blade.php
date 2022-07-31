@extends('control_panel.master')


@section('style')
    <link href="{{asset('control_panel/assets/css/datatable.css')}}" rel="stylesheet" type="text/css" />
@endsection
<style>
    .course_status_option{
        background: white;
        color: black;
    }
    .course_status_select{
        color: white !important;
    }
</style>
@section('title')
    <title>برنامج السنة | قسم الجودة والإختبارات </title>
@endsection
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">قسم الجودة والإختبارات</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">قسم الجودة والإختبارات</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                <th>المنطقة الكبرى:</th>
                                <th>المنطقة المحلية:</th>
                                <th>مكان الدورة:</th>
                                <th>نوع الدورة:</th>
                                <th>بداية الدورة:</th>
                                <th>نهاية الدورة:</th>
                                <th>معلم الدورة:</th>
                                <th>فئة الطلاب:</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $course->area_father_name }}</td>
                                        <td>{{ $course->area_name }}</td>
                                        <td>{{ $course->place_name }}</td>
                                        <td>{{ $course->course_type }}</td>
                                        <td>{{ $course->start_date }}</td>
                                        <td>{{ $course->exam_date }}</td>
                                        <td>{{ $course->teacher_name }}</td>
                                        <td>{!! $course->book_students_category_string !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <th>#</th>
                                    <th>الاسم رباعي:</th>
                                    <th>تاريخ الميلاد:</th>
                                    <th>مكان الميلاد:</th>
                                    <th>الدرجة:</th>
                                    <th>التقدير:</th>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                    @foreach($students as $key => $student)
{{--                                        @if($student->mark >= 60)--}}
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $student->user_name }}</td>
                                                <td>{{ $student->user_dob }}</td>
                                                <td>{{ $student->user_pob }}</td>
                                                <td>
                                                    {{ $student->mark }}
                                                </td>
                                                <td style="max-width: 71px;">{!! markEstimation($student->mark) !!}</td>
                                            </tr>
                                        {{--@endif--}}
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>

                        <div class="modal-footer" style="display: block;">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    تم اعتماد رئيس قسم الاختبارات
                                </div>
                                <div class="col-md-4">
                                    تم اعتماد مدير دائرة التخطيط والجودة
                                </div>
                                <div class="col-md-4">
                                    تم اعتماد مدير الدائرة
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>

@endsection

@section('script')
@endsection


