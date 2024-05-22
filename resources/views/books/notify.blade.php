@extends(auth()->check() && auth()->user()->userstatus == 2 ? 'layout.privatebook' : 'layout.publicbook')

@section('content')
<div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Books Recommended</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <!-- InHere -->

                        
                    

                        <!-- ToHere -->
                    </div>
                </div>
                
            </div>
        </div>


@endsection
