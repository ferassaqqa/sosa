@extends('control_panel.master')

@section('style')
    <link href="{{ asset('control_panel/assets/css/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <title>برنامج السنة | التقارير</title>
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
                <h4 class="mb-sm-0">التقارير</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">التقارير</li>
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

                    @include('control_panel.reports.reportFilter')

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

                        {{-- <table class="table table-centered   table-nowrap mb-0" id="dataTable" style="font-size: 18px">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th scope="col">الكتاب</th>
                                    <th scope="col">فئة الخريجين</th>
                                    <th scope="col">العدد المطلوب</th>
                                    <th scope="col">العدد المنجز</th>
                                    <th scope="col">نسبة الانجاز</th>
                                    <th scope="col">نسبة الفائض</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($value) && count($value) > 0)
                                    @foreach ($value as $index => $val)
                                        {{-- <tr>
                                        <td>{{$index + 1}}</td>
                                        <td style="text-align: justify">{{$val['book_name']}}</td>
                                        <td style="text-align: justify">
                                            {{$val['graduated_categories']}}
                                        </td>
                                        <td>{{$val['required_num']}}</td>
                                        <td>{{$val['completed_num']}}</td>
                                        <td>{{$val['completed_num_percentage']}} %</td>
                                        <td>{{$val['excess_num_percentage']}} %</td>
                                    </tr> --}}
                        {{-- @endforeach
                                @endif
                                <tr style="background-color: #00937C; color: white">
                                    <th scope="col" colspan="3">
                                        المجموع
                                    </th>
                                    <th scope="col">{{ $required_num }}</th>
                                    <th scope="col">{{ $completed_num }}</th>
                                    <th scope="col">%{{ $completed_num_percentage }}</th>
                                    <th scope="col">%{{ $excess_num_percentage }}</th>
                                </tr>
                            </tbody>
                        </table> --}}



                        <table class="table table-centered   table-nowrap mb-0"  id="dataTable" style="font-size: 18px">

                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th scope="col">الكتاب</th>
                                    <th scope="col">فئة الخريجين</th>
                                    <th scope="col">العدد المطلوب</th>
                                    <th scope="col">العدد المنجز</th>
                                    <th scope="col">نسبة الانجاز</th>
                                    <th scope="col">نسبة الفائض</th>
                                </tr>
                            </thead>
                            <tbody>


                                @if (isset($value) && count($value) > 0)
                                @foreach ($value as $index => $val)
                                             {!! $val !!}
                                @endforeach
                                @endif





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
