<x-app-layout :title="$title">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">LoanJar</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-1 overflow-hidden">
                                            <p class="text-truncate font-size-14 mb-2">Total Capital Amount</p>
                                            <h4 class="mb-0">₹ {{$totalAmount ?? 0 }}</h4>
                                        </div>
                                        <div class="text-primary ms-auto">
                                            <i class="ri-wallet-3-line font-size-24"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body border-top py-3">
                                    <div class="text-truncate">
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-1 overflow-hidden">
                                            <p class="text-truncate font-size-14 mb-2">Total Interest Amount</p>
                                            <h4 class="mb-0">₹ {{$totalIntAmount ?? 0}} </h4>
                                        </div>
                                        <div class="text-primary ms-auto">
                                            <i class="ri-percent-line font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border-top py-3">
                                    <div class="text-truncate">
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-1 overflow-hidden">
                                            <p class="text-truncate font-size-14 mb-2">Total Collected Amount</p>
                                            <h4 class="mb-0">₹ {{$totalCollectedAmount}}</h4>
                                        </div>
                                        <div class="text-primary ms-auto">
                                            <i class="ri-hand-coin-line font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border-top py-3">
                                    <div class="text-truncate">
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="card">
                        <div class="card-body">
                            <div class="dropdown float-end">
                                <!-- <a href="#" class="dropdown-toggle arrow-none card-drop"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </a> -->
                                <!-- <div class="dropdown-menu dropdown-menu-end"> -->
                                    <!-- item-->
                                    <!-- <a href="javascript:void(0);" class="dropdown-item">Sales Report</a> -->
                                    <!-- item-->
                                    <!-- <a href="javascript:void(0);" class="dropdown-item">Export Report</a> -->
                                    <!-- item-->
                                    <!-- <a href="javascript:void(0);" class="dropdown-item">Profit</a> -->
                                    <!-- item-->
                                    <!-- <a href="javascript:void(0);" class="dropdown-item">Action</a> -->
                                <!-- </div> -->
                            </div>

                            <h4 class="card-title mb-4">Recent Loan Activity</h4>

                            <div data-simplebar style="max-height: 330px;">
                                <ul class="list-unstyled activity-wid">
                                @foreach ($installments as $installment)
                                    <li class="activity-list">
                                        <div class="activity-icon avatar-xs">
                                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                <i class="ri-calendar-check-fill"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <div>
                                                <h5 class="font-size-13">
                                                    {{ \Carbon\Carbon::parse($installment->created_at)->format('d M, Y') }}
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($installment->created_at)->format('h:i a') }}</small>
                                                    <small> <b> : {{$installment->loan_unique_id}} - {{$installment->user_name}}</b></small>
                                                </h5>
                                            </div>
                                            <div>
                                                <p class="text-muted mb-0">Paid {{ number_format($installment->amount, 2) }} by {{ $installment->payment_method }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach


                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
@php
    $currentMonth = \Carbon\Carbon::now()->format('F'); // e.g. "June"
    $months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
@endphp

<select class="form-select form-select-sm" id="monthFilter" name="month">
    @foreach ($months as $month)
        <option value="{{ $month }}" {{ $month == $currentMonth ? 'selected' : '' }}>
            {{ $month }}
        </option>
    @endforeach
</select>



                            </div>
                            <h4 class="card-title mb-4">Loan Analytics</h4>

                            <!-- <div id="donut-chart" class="apex-charts"></div> -->

                            <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- <h4 class="card-title">Pie Chart</h4> -->
                                        <canvas id="pieChart" height="200"></canvas>
                                        <p id="noDataMessage" style="display:none; color: red; text-align: center;"></p>

                                        </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                  
                </div>
            </div>
            <!-- end row -->


            <!-- end row -->
           
            <!-- end row -->
        </div>
    </div>

    <script>
    let chart;

    function renderChart(data) {
        const ctx = document.getElementById('pieChart').getContext('2d');

        if (chart) chart.destroy();

        chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Capital', 'Interest', 'Collected'],
                datasets: [{
                    data: [data.loan, data.collected, data.remaining],
                    backgroundColor: ['#1cbb8c', '#5664d2', '#e9ecef']
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: '#fff',
                        formatter: (value, context) => {
                            return context.chart.data.labels[context.dataIndex];
                        }
                    },
                    legend: {
                        display: true
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    function fetchChartData(month) {
        $.ajax({
            url: '{{ route("admin.chartData") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                month: month
            },
            success: function(response) {
                if(response.loan ==0 || response.collected==0 || response.remaining==0){
                    $('#noDataMessage').show().text('No data available for loan in selected month.');
                    $('#pieChart').hide();
                }else{
                renderChart(response);
                $('#noDataMessage').hide().text('No data available for loan in selected month.');
                }
            },
            error: function(xhr) {
                console.error('Chart data fetch failed', xhr.responseText);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        const defaultMonth = document.getElementById('monthFilter').value;
        fetchChartData(defaultMonth);
    });

    document.getElementById('monthFilter').addEventListener('change', function () {
        fetchChartData(this.value);
    });
</script>


</x-app-layout>
