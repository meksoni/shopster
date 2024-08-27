@extends('admin.layouts.app')




@section('content')
<div class="container-fluid px-4">
@include('admin.components.messages')  
</div>

<div class="toolbar px-3 px-lg-6 pt-0 pb-3">
    <div class="position-relative container-fluid px-0">
      <div class="row align-items-center position-relative">
        <div class="col-sm-7 mb-3 mb-sm-0">
          <h3 class="mb-2 fw-normal">
            Pozdrav {{ Auth::guard('admin')->user()->first_name }} 👋
          </h3>
          <p class="mb-0">Pogledaj najnovije informacije o tvojoj prodavnici.</p>
        </div>
        {{-- <div class="col-sm-5 text-md-end">
          <div class="d-flex justify-content-sm-end align-items-center">
            <div class="d-flex align-items-center">
              <div
                role="button"
                id="reportrange"
                class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center"
                data-tippy-placement="left"
                data-tippy-content="Select dashboard data range"
              >
                <i class="material-symbols-rounded align-middle fs-5">
                  calendar_month
                </i>
                <span class="d-inline-block ms-2"></span>
              </div>
            </div>
            <a
              href="#!"
              data-tippy-placement="left"
              data-tippy-content="Reload Dashboard"
              class="flex-shrink-0 ms-2 p-0 rounded-2 size-30 d-flex align-items-center justify-content-center btn btn-primary"
            >
              <span class="material-symbols-rounded lh-1 fs-4">
                refresh
              </span>
            </a>
          </div>
        </div> --}}
      </div>
    </div>
</div>


<div class="content pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-xl-9 mb-2">
                <div class="card overflow-hidden">
                    <div class="card-header d-flex align-items-center">
                        <div class="pe-3 flex-grow-1">
                            <div class="d-flex align-items-center">
                                <h3 class="mb-2" data-tippy-placement="bottom-start" data-tippy-content="Statistika se vodi mesečno, prikazujući broj prodatih proizvoda i procenat rasta/pada u odnosu na prethodni mesec.">
                                    Mesečna statistika
                                </h3>
                                <div class="flex-grow-1 ms-3 align-items-center">
                                    <div class="d-inline-flex align-items-center lh-1 text-success" id="salesPercentageContainer">
                                        <span class="material-symbols-rounded align-middle" id="salesTrendIcon"></span>
                                        <span id="salesPercentage"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-2">{{ $formattedStartDate }} - {{ $formattedEndDate }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <form id="dateFilterForm" method="GET" action="{{ route('admin.dashboard') }}">
                                <div class="input-group">
                                    <select name="month" id="month" class="form-select">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="year" id="year" class="form-select">
                                        @for ($i = now()->year; $i >= 2024; $i--)
                                            <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-primary">Prikaži</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body card-refresh position-relative overflow-hidden p-0">
                        <div class="w-100">
                            <div id="chart_overview"></div>
                        </div>
                        <div class="loader-refresh w-100 h-100 position-absolute start-0 top-0 bottom-0 end-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-25">
                            <div>
                                <div class="spinner-border spinner-border-sm me-2 text-white" role="status"></div>
                                <span class="text-white">Refreshing...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
    
            <div class="col-xl-3">
                <div class="row">
                    <div class="col-xl-12 col-md-6 mb-2">
                        <div class="card">
                            <div class="card-body p-4 pb-3 d-flex align-items-center justify-content-between">
                                <!--Icon-->
                                <div
                                class="size-50 me-3 flex-shrink-0 bg-success-subtle text-success d-flex align-items-center justify-content-center rounded-pill">
                                <span class="material-symbols-rounded align-middle">payments</span>
                                </div>
                                <div class="flex-grow-1">
                                <h3 class="mb-1">{{ number_format($totalRevenueLast7Days, 2, ',', '.') }} {{$store->global_currency}}</h3>
                                <p class="mb-0">Prihod / 7 dana</p>
                                </div>
                            </div>
                            <div class="rounded-bottom overflow-hidden">
                                <!--chart-->
                                <div id="chart_sparkline_revenue" class="rounded-bottom"></div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-xl-12 col-md-6 mb-2">
                        <div class="card">
                            <div class="card-body p-4 pb-3 pb-lg-6 d-flex align-items-center justify-content-between">
                                <!--Icon-->
                                <div class="size-50 me-3 flex-shrink-0 bg-primary-subtle d-flex align-items-center justify-content-center rounded-pill text-primary">
                                <span class="material-symbols-rounded align-middle">shopping_cart</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h3 class="mb-1">{{ $totalSalesLast7Days }}</h3>
                                    <p class="mb-0">Prodatih proizvoda / 7 dana</p>
                                </div>
                                
                            </div>
                            <div class="rounded-bottom">
                                <!--chart-->
                                <div id="chart_sparkline_sales" class="rounded-bottom"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-xl-3 mb-2">
                <!--::begin card-->
                <div class="card card-body">
                    <!--Title-->
                    <h6 class="mb-3 ">
                        Porudžbine
                        <span class="material-symbols-rounded ms-2 fs-6 align-middle opacity-75" data-tippy-content="Ukupno isporučenih porudžbina" data-tippy-placement="top">info</span>
                    </h6>
                    <div class="d-flex lh-1 align-items-center">
                        <h4 class="mb-0">{{ $totalOrdersAll }}</h4>
                        <!--vertical line-->
                        {{-- <div class="vr lh-1 align-self-center opacity-25 me-1 ms-3 height-10" style="min-height: auto"></div> --}}
                        <!--::/vertical line-->
                        <!--:Badge:-->
                        {{-- <div class="d-inline-flex align-items-center text-success">
                            <span class="material-symbols-rounded align-middle text-success">arrow_drop_up</span>
                            <small>11%</small>
                        </div> --}}
                    </div>
                </div>
                <!--::/end card-->
            </div>
        
            <div class="col-sm-6 col-xl-3 mb-2">
                <!--::begin card-->
                <div class="card card-body">
                    <!--Title-->
                    <h6 class="mb-3">
                        Prihod
                        <span class="material-symbols-rounded ms-2 fs-6 align-middle opacity-75" data-tippy-content="Ukupan prihod od isporučenih porudžbina" data-tippy-placement="top">info</span>
                    </h6>
                    <div class="d-flex lh-1 align-items-center">
                        <h4 class="mb-0">{{ number_format($totalRevenueAll, 2, ',', '.') }} RSD</h4>
                        <!--vertical line-->
                        {{-- <div class="vr lh-1 align-self-center opacity-25 me-1 ms-3 height-10" style="min-height: auto"></div> --}}
                        <!--::/vertical line-->
                        <!--:Badge:-->
                        {{-- <div class="d-inline-flex align-items-center text-success">
                            <span class="material-symbols-rounded align-middle text-success">arrow_drop_up</span>
                            <small>36%</small>
                        </div> --}}
                    </div>
                </div>
                <!--::/end card-->
            </div>
        
            <div class="col-sm-6 col-xl-3 mb-2">
                <!--::begin card-->
                <div class="card card-body">
                    <!--Title-->
                    <h6 class="mb-3">
                        Otkazane porudžbine
                        <span class="material-symbols-rounded ms-2 fs-6 align-middle opacity-75" data-tippy-content="Ukupno otkazanih porudžbina" data-tippy-placement="top">info</span>
                    </h6>
                    <div class="d-flex lh-1 align-items-center">
                        <h4 class="mb-0">{{ $totalOtkazano }}</h4>
                        <!--vertical line-->
                        {{-- <div class="vr lh-1 align-self-center opacity-25 me-1 ms-3 height-10" style="min-height: auto"></div> --}}
                        <!--::/vertical line-->
                        <!--:Badge:-->
                        {{-- <div class="d-inline-flex align-items-center text-danger">
                            <span class="material-symbols-rounded align-middle text-danger">arrow_drop_down</span>
                            <small>24%</small>
                        </div> --}}
                    </div>
                </div>
                <!--::/end card-->
            </div>
        
            <div class="col-sm-6 col-xl-3 mb-2">
                <!--::begin card-->
                <div class="card card-body">
                    <!--Title-->
                    <h6 class="mb-3">
                        Vraćene porudžbine
                        <span class="material-symbols-rounded ms-2 fs-6 align-middle opacity-75" data-tippy-content="Ukupno vraćenih porudžbina" data-tippy-placement="top">info</span>
                    </h6>
                    <div class="d-flex lh-1 align-items-center">
                        <h4 class="mb-0">{{ $totalVraceno }}</h4>
                        <!--vertical line-->
                        {{-- <div class="vr lh-1 align-self-center opacity-25 me-1 ms-3 height-10" style="min-height: auto"></div> --}}
                        <!--::/vertical line-->
                        <!--:Badge:-->
                        {{-- <div class="d-inline-flex align-items-center text-success">
                            <span class="material-symbols-rounded align-middle text-success">arrow_drop_up</span>
                            <small>24%</small>
                        </div> --}}
                    </div>
                </div>
                <!--::/end card-->
            </div>
        </div>

        <!--row-->
        <div class="row">
            <div class="col-lg-7 col-xxl-8 mb-2">
                <div class="card table-card table-nowrap overflow-hidden h-100">
                    <div class="d-flex card-header align-items-center justify-content-between">
                        <h5 class="me-3 card-title mb-0">Top 5 Prodatih proizvoda</h5>
                        <a href="#!" class="btn-refresh">
                            <span class="material-symbols-rounded align-middle fs-5">refresh</span>
                        </a>
                    </div>
                    <div class="position-relative card-refresh">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0" style="width: 100%">
                                <thead class="text-body small">
                                    <tr>
                                        <th style="width: 190px"><h6>Proizvod</h6></th>
                                        {{-- <th>Change</th> --}}
                                        <th><h6>Cena</h6></th>
                                        <th><h6>Prodato</h6></th>
                                        <th><h6>Ukupna zarada</h6></th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle">
                                    @foreach ($topProducts as $item)
                                    @php
                                        $product = $item->product;
                                        $productImage = $product->product_images->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if (!empty($productImage->image))
                                                <a href="{{ route('products.index', ['keyword' => $product->title]) }}" target="_blank">
                                                        <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" class="avatar sm rounded-pill me-3 flex-shrink-0" alt="{{ $product->name }}">
                                                    </a>
                                                @endif
                                                <p class="mb-0 text-truncate fs-6">
                                                    <a href="{{ route('products.index', ['keyword' => $product->title]) }}" target="_blank" class="text-truncate">{{ $product->title }}</a>
                                                </p>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <div class="d-inline-flex badge bg-success-subtle text-success align-items-center">
                                                <span class="me-0">8%</span>
                                                <span class="text-success">
                                                    <span class="material-symbols-rounded align-middle fs-5">arrow_drop_up</span>
                                                </span>
                                            </div>
                                        </td> --}}
                                        <td>{{ number_format($product->price, 2, ',','.') }} {{$store->global_currency}}</td>
                                        <td>{{ $item->total_sold }}</td>
                                        <td>{{ number_format($product->price * $item->total_sold, 2,',','.') }} {{ $store->global_currency}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="loader-refresh w-100 h-100 position-absolute start-0 top-0 bottom-0 end-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-25">
                            <div>
                                <div class="spinner-border spinner-border-sm me-2 text-white" role="status"></div>
                                <span class="text-white">Refreshing...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xxl-4 mb-2">
                <div class="card h-100 overflow-hidden">
                    <div class="d-flex align-items-center card-header justify-content-between">
                        <div class="flex-grow-1 overflow-hidden pe-3">
                            <div class="d-flex align-items-center">
                                <h3 class="mb-2">{{ $totalVisitorsByCountry->sum('total') }}</h3>
                                <small class="text-body-secondary ms-2">Ukupno posetilaca</small>
                            </div>
                            <p class="mb-0 text-truncate">Posetioci po zemlji</p>
                        </div>
                        {{-- <a href="#!" class="btn flex-shrink-0 btn-outline-secondary btn-sm">View Statistics</a> --}}
                    </div>
                    <div class="overflow-hidden">
                        <div class="w-100" id="chart_top_countries"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-body">
                    <h3 class="mb-0 mb-md-2">Podešavanje prikaza kategorizacije</h3>
                    <p class="mb-3">Prikaz kategorizacije na početnoj strani</p>
                    @include('admin.components.shopSelectedItems')
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('customJS')
<script src="{{ asset('assets/vendor/node_modules/js/apexcharts.min.js')}}"></script>
{{-- Grafikon za mesecnu statistiku --}}
<script>

        var dates = @json($dates);
        var sales = @json($sales);
        var revenue = @json($revenue);
        
        var salesData = @json($salesData);
        
        var totalRevenuoForMonth = salesData.totalRevenueForMonth; // Ukupna zarada
        var roundedForMonthRevenue = Math.round(totalRevenuoForMonth); // Zaokruživanje broja
        var formattedForMonthRevenue = roundedForMonthRevenue.toLocaleString('de-DE'); // Formatiranje broja

        var totalSalesForMonth = salesData.totalProductsSoldForMonth;
        
        document.getElementById('salesPercentage').innerText = salesData.salesPercentageChange + '%';
        
        if (salesData.salesTrend === 'positive') {
            document.getElementById('salesTrendIcon').innerText = 'arrow_drop_up';
            document.getElementById('salesPercentageContainer').classList.add('text-success');
            document.getElementById('salesPercentageContainer').classList.remove('text-danger');
        } else {
            document.getElementById('salesTrendIcon').innerText = 'arrow_drop_down';
            document.getElementById('salesPercentageContainer').classList.add('text-danger');
            document.getElementById('salesPercentageContainer').classList.remove('text-success');
        }

        
        var optionsSalesOverview = {
            colors: ["var(--bs-primary)", "#b2b2b2"],
            series: [
                {
                    name: "<b>Prihod</b>",
                    type: "area",
                    data: revenue,
                },
                {
                    name: "<b>Prodato proizvoda</b>",
                    type: "area",
                    data: sales,
                },
            ],
            chart: {
                height: 350,
                type: "line",
                fontFamily: "Arial",
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: [2, 2],
                curve: "smooth",
            },
            grid: {
                strokeDashArray: 2,
                padding: {
                    top: 0,
                    bottom: 0,
                    right: 20,
                },
            },
            labels: dates,
            yaxis: [
                {
                    title: {
                        text: "PRIHOD",
                        style: {
                            color: "var(--bs-primary)",
                            fontWeight: "bold",
                        },
                    },
                    labels: {
                        style: {
                            colors: "var(--bs-primary)",
                        },
                        formatter: function (val) {
                            return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'RSD', minimumFractionDigits: 2 }).format(val).replace("RSD", ""); 
                        },
                    },
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: "var(--bs-primary)",
                    },
                },
                {
                    opposite: true,
                    title: {
                        text: "BROJ PRODATIH PROIZVODA",
                        style: {
                            color: "var(--bs-info)",
                            fontWeight: "bold",
                        },
                    },
                    labels: {
                        style: {
                            colors: "var(--bs-info)",
                        },
                        formatter: function (val) {
                            return Math.round(val);
                        },
                    },
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: "var(--bs-info)",
                    },
                },
            ],
            xaxis: {
                tickAmount: 6,
                labels: {
                    show: true,
                    rotate: 0,
                },
                tooltip: {
                    enabled: false,
                },
                axisTicks: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.05,
                    opacityTo: 0,
                    stops: [0, 100],
                },
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (val, { seriesIndex }) {
                        if (seriesIndex === 0) {
                            return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'RSD', minimumFractionDigits: 2 }).format(val).replace("RSD", " RSD");
                        } else {
                            return Math.round(val) + '';
                        }
                    },
                },
            },
            markers: {
                strokeWidth: 5,
                strokeOpacity: 1,
                strokeColors: [
                    "var(--bs-body-bg)",
                    "var(--bs-body-bg)",
                    "var(--bs-body-bg)",
                ],
            },
        };
        
        var chartOverview = new ApexCharts(
            document.querySelector("#chart_overview"),
            optionsSalesOverview
        );
        chartOverview.render();
    
       
        // Prikaz prihoda za poslednjih 7 dana
    var optionsSparkLineRevenue = {
        colors: ["var(--bs-border-color)"],
        series: [{
            name: "Prihod",
            data: @json($salesData['revenueCounts'])
        }],
        chart: {
            fontFamily: 'inherit',
            type: 'line',
            height: 120,
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            },
            sparkline: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        markers: {
            strokeWidth: 5,
            strokeOpacity: 1,
            strokeColors: ["var(--bs-body-bg)"],
        },
        labels: @json($salesData['last7DaysDates']),
        xaxis: {
            type: 'category',
        },
        yaxis: {
            opposite: true
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                // Formatiranje broja
                const roundedValue = Math.round(val); // Zaokruživanje broja
                const formattedValue = roundedValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Formatiranje sa tačkom
                return formattedValue + ' RSD'; // Dodavanje RSD na kraju
            },
            },
        },
    };
    // render chart
    var chartSparkLineRevenue = new ApexCharts(document.querySelector("#chart_sparkline_revenue"), optionsSparkLineRevenue);
    chartSparkLineRevenue.render();
    
    // Prikaz broja prodatih proizvoda za poslednjih 7 dana
    var optionsSparkLineSales = {
        colors: ["var(--bs-warning)"],
        series: [{
            name: "Prodatih proizvoda",
            data: @json($salesData['salesCounts'])
        }],
        chart: {
            fontFamily: 'inherit',
            type: 'line',
            height: 120,
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            },
            sparkline: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        markers: {
            strokeWidth: 5,
            strokeOpacity: 1,
            strokeColors: ["var(--bs-body-bg)"],
        },
        labels: @json($salesData['last7DaysDates']),
        xaxis: {
            type: 'category',
        },
        yaxis: {
            opposite: true
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return val;
                },
            },
        },
    };
    // render chart
    var chartSparkLineSales = new ApexCharts(document.querySelector("#chart_sparkline_sales"), optionsSparkLineSales);
    chartSparkLineSales.render();
    
    //top countries
    const countryColors = [
        "var(--bs-primary)",
        "var(--bs-warning)",
        "var(--bs-info)",
        "var(--bs-success)",
        "var(--bs-danger)",
    ];
    
    const visitorData = @json($totalVisitorsByCountry);
    
    const countries = visitorData.map(item => item.country);
    const visitors = visitorData.map(item => item.total);
    
    const optionsCountries = {
        series: [{ name: "Posetioci", data: visitors }],
        chart: {
            type: "bar",
            height: 300,
            fontFamily: "inherit",
            toolbar: {
                show: false,
            },
        },
        legend: {
            show: false,
        },
        colors: countryColors,
        grid: {
            strokeDashArray: 4,
            position: "back",
            padding: {
                right: 30,
                left: 10,
                bottom: -10,
            },
            xaxis: {
                lines: {
                    show: true,
                },
            },
            yaxis: {
                lines: {
                    show: false,
                },
            },
        },
        plotOptions: {
            bar: {
                columnWidth: "30%",
                horizontal: false,
                distributed: true,
                borderRadius: 0,
                dataLabels: {
                    position: "top",
                },
            },
        },
        labels: {
            show: false,
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: false,
        },
        xaxis: {
            categories: countries,
            axisTicks: {
                show: true,
            },
            axisBorder: {
                show: false,
            },
        },
        yaxis: {
            labels: {
                show: true,
                formatter: function (value) {
                    return parseInt(value); // Prikazuje brojeve kao cele brojeve
                }
            },
            axisTicks: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + '<span class="fw-normal text-body-secondary"></span>';
                },
            },
        },
    };
    
    const chartCountries = new ApexCharts(
        document.querySelector("#chart_top_countries"),
        optionsCountries
    );
    chartCountries.render();
</script>

@endsection