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

    <style>
        th,
        td {
            border: 1px solid rgb(161, 161, 161) !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">



                    <div id="tableContainer">

                        <div class="table-responsive-md">
                        <table class="table "  style="font-size: 16px;">
                            <thead >
                                <col >
                                <col >
                                <col style="background-color: #f3f3f4">
                                <colgroup  span="14"></colgroup>

                                <col style="background-color: #f3f3f4">
                                <col style="background-color: #f3f3f4">
                                <col style="background-color: #f3f3f4">

                                <tr style="background-color: #f3f3f4">
                                    <td rowspan="3" style="width:50px; ">#</td>
                                    <td rowspan="3"> <b> الدورة </b></td>
                                    <td rowspan="3" > <b> إجمالي العدد المطلوب </b></td>


                                    <td colspan="14" > <b> إنجاز المناطق الكبرى </b></td>

                                    <td rowspan="3"> <b>إجمالي الإنجاز</b></td>
                                    <td rowspan="3"> <b> إجمالي المتبقي </b></td>
                                    <td rowspan="3"> <b> نسبة الإنجاز </b></td>

                                </tr>


                                <tr style="background-color: #f3f3f4">
                                    @if (isset($areas) && count($areas) > 0)
                                    @foreach ($areas as $index => $val)
                                    <td colspan="2" scope="col">  {!! $val->name !!} </td>
                                    @endforeach
                                    @endif
                                </tr>


                                <tr style="background-color: #f3f3f4">
                                    @if (isset($areas) && count($areas) > 0)
                                    @foreach ($areas as $index => $val)
                                    <td  style="border: 1px solid black;" scope="col">المنجز</td>
                                    <td  style="border: 1px solid black;" scope="col">المتبقي</td>
                                    @endforeach
                                    @endif
                                </tr>

                            </thead>


                            <tbody>


                             @if (isset($in_plane_books_value) && count($in_plane_books_value) > 0)
                                @foreach ($in_plane_books_value as $index => $val)
                                             {!! $val !!}
                                @endforeach
                                @endif


                        <td colspan="20"><b>برنامج الصفوة</b></td>
                                @if (isset($project_books_value) && count($project_books_value) > 0)
                                   @foreach ($project_books_value as $index => $val)
                                                {!! $val !!}
                                   @endforeach
                                   @endif


                        <td colspan="20"><b>كتب خارج الخطة</b></td>
                             @if (isset($out_plane_books_value) && count($out_plane_books_value) > 0)
                                @foreach ($out_plane_books_value as $index => $val)
                                             {!! $val !!}
                                @endforeach
                                @endif


                            </tbody>

                        </table>
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
