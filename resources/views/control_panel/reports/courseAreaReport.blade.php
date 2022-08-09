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

                    </div>



                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
