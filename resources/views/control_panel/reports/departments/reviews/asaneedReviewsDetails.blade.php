
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">


                    <div class="row mb-3" id="custom_filters"></div>
                    <div id="tableContainer">



                        <table class="table table-centered   table-nowrap mb-0" id="dataTable" style="font-size: 18px">

                            <thead>
                                <tr>
                                    <th colspan="8" scope="col">تفصيل تقييم قسم الأسانيد</th>                                    
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
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

