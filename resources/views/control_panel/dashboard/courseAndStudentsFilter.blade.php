
<!-- end col -->
<div class="col-xl-3 col-sm-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <div class="flex-shrink-0 me-3 align-self-center">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                            <i class="mdi mdi-refresh"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <p class="mb-1">الدورات</p>
                    <h5 class="mb-3">{{ $courses }}</h5>
                    {{--<p class="text-truncate mb-0"><span class="text-success me-2"> 1.7% <i class="ri-arrow-right-up-line align-bottom ms-1"></i></span> From previous</p>--}}
                </div>
            </div>
        </div>
        <!-- end card-body -->
    </div>
    <!-- end card -->
</div>
<!-- end col -->

<div class="col-xl-3 col-sm-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex text-muted">
                <div class="flex-shrink-0 me-3 align-self-center">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                            <i class="mdi mdi-alpha"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <p class="mb-1">المعلمين</p>
                    <h5 class="mb-3">{{ $moallems }}</h5>
                    {{--<p class="text-truncate mb-0"><span class="text-danger me-2"> 0.01% <i class="ri-arrow-right-down-line align-bottom ms-1"></i></span> From previous</p>--}}
                </div>
            </div>
        </div>
        <!-- end card-body -->
    </div>
    <!-- end card -->
</div>
<!-- end col -->

<div class="col-xl-3 col-sm-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex text-muted">
                <div class="flex-shrink-0  me-3 align-self-center">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                            <i class="ri-group-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <p class="mb-1">الطلاب</p>
                    <h5 class="mb-3">{{ $course_students_count }}</h5>
                    {{--<p class="text-truncate mb-0"><span class="text-success me-2"> 0.01% <i class="ri-arrow-right-up-line align-bottom ms-1"></i></span> From previous</p>--}}
                </div>
            </div>
        </div>
        <!-- end card-body -->
    </div>
    <!-- end card -->
</div>
<!-- end col -->