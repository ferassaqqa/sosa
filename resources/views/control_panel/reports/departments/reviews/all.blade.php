@extends('control_panel.master')

@section('style')
    <link href="{{ asset('control_panel/assets/css/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | التقييمات</title>
@endsection
@section('content')
    <style>
        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        }

        .dataTables_paginate {
            display: none;
        }
    </style>

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">التقييمات</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">التقييمات</li>
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

                    @include('control_panel.reports.departments.reviews.reportFilter')

                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">


                    <div class="row mb-3" id="custom_filters"></div>
                    <div id="tableContainer">



                        <table class="table table-centered   table-nowrap mb-0" id="dataTable" style="font-size: 18px">

                            <thead>
                                <tr>
                                    <th colspan="8" scope="col">التقييمات العامة للمناطق في أقسام دائرة السنة النبوية                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- <tr>
                                    <tr>
                                        <th style="background: #f0f0f0; width:50px; ">#</th>
                                        <th style="background: #f0f0f0">المنطقة</th>
                                        <th style="background: #f0f0f0">قسم التحفيظ (38%)</th>
                                        <th style="background: #f0f0f0">قسم الدورات (50%)</th>
                                        <th style="background: #f0f0f0">قسم الاسانيد (10%)</th>
                                        <th style="background: #f0f0f0">التقييم العام (100%)</th>
                                        <th style="background: #f0f0f0">الترتيب</th>
                                        <th style="background: #f0f0f0">ملحوظات</th>
                                    </tr>
                                </tr>

                                @if (isset($value) && count($value) > 0)
                                @foreach ($value as $index => $val)
                                             {!! $val !!}
                                @endforeach
                                @endif --}}




                            </tbody>

                        </table>





                    </div>
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
