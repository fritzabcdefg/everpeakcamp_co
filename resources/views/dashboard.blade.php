@extends('layouts.base')

@section('content')
    <div class="container mt-4">

        <!-- Dashboard Header -->
        <div class="mb-5">
            <div style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 40px; border-radius: 12px; text-align: center;">
                <h1 class="mb-0" style="font-size: 2.5rem; font-weight: 700; color: var(--cream);"></i>Dashboard</h1>
                <p class="mb-0 mt-2" style="opacity: 0.9;color: var(--cream);">Sales Analytics & Business Insights</p>
            </div>
        </div>

        <!-- Stats Cards Row with Quick Actions -->
        <div class="row g-4 mb-5">
            <!-- Total Users -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Users</p>
                                <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-users text-primary fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="card-footer bg-transparent border-top text-center text-decoration-none text-primary small fw-semibold">
                        View Users <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Quick Action for Users -->
                <a href="{{ route('users.create') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fas fa-plus"></i> Add User
                </a>
            </div>

            <!-- Total Products -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Products</p>
                                <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-box text-success fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}" class="card-footer bg-transparent border-top text-center text-decoration-none text-success small fw-semibold">
                        View Products <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Quick Action for Products -->
                <a href="{{ route('products.create') }}" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>

            <!-- Total Categories -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Categories</p>
                                <h3 class="mb-0">{{ $stats['total_categories'] }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-list text-info fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('categories.index') }}" class="card-footer bg-transparent border-top text-center text-decoration-none text-info small fw-semibold">
                        View Categories <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Quick Action for Categories -->
                <a href="{{ route('categories.create') }}" class="btn btn-info w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            </div>

            <!-- Total Orders -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Orders</p>
                                <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-shopping-cart text-warning fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}" class="card-footer bg-transparent border-top text-center text-decoration-none text-warning small fw-semibold">
                        View Orders <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Quick Action for Orders -->
                <a href="{{ route('orders.create') }}" class="btn btn-warning w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fas fa-plus"></i> Create Order
                </a>
            </div>
        </div>

        <!-- Revenue & Pending Orders Row with Date Filter -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0" style="color: var(--cream);"><i class="fas fa-money-bill-wave me-2"></i>Revenue & Orders Summary</h5>
                        </div>
                        <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2" style="margin: 0;">
                            <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm" style="width: 140px;">
                            <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm" style="width: 140px;">
                            <input type="hidden" name="yearly_start_date" value="{{ $yearlyStartDate }}">
                            <input type="hidden" name="yearly_end_date" value="{{ $yearlyEndDate }}">
                            <input type="hidden" name="product_start_date" value="{{ $productStartDate }}">
                            <input type="hidden" name="product_end_date" value="{{ $productEndDate }}">
                            <button type="submit" class="btn btn-sm btn-dark" style="min-width: 80px;"><i class="fas fa-filter me-1"></i> Filter</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="text-center">
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-money-bill-wave text-success"></i> Total Revenue
                                    </p>
                                    <h2 class="text-success mb-0">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</h2>
                                    <small class="text-muted">From all completed and pending orders</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-clock text-danger"></i> Pending Orders
                                    </p>
                                    <h2 class="text-danger mb-0">{{ $stats['pending_orders'] }}</h2>
                                    <small class="text-muted">Orders waiting to be processed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Old Revenue & Pending Orders Row (removed)
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0"></i>₱ Total Revenue</h5>
                        </div>
                        <h2 class="text-success mb-2">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</h2>
                        <p class="text-muted small mb-0">From all completed and pending orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0"><i class="fas fa-clock text-danger"></i> Pending Orders</h5>
                        </div>
                        <h2 class="text-danger mb-2">{{ $stats['pending_orders'] }}</h2>
                        <p class="text-muted small mb-0">Orders waiting to be processed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mb-5">
            <!-- Yearly Sales Chart -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0" style="color: var(--cream);"><i class="fas fa-chart-bar me-2"></i>Yearly Sales Revenue</h5>
                        </div>
                        <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2" style="margin: 0;">
                            <input type="date" name="yearly_start_date" value="{{ $yearlyStartDate }}" class="form-control form-control-sm" style="width: 140px;">
                            <input type="date" name="yearly_end_date" value="{{ $yearlyEndDate }}" class="form-control form-control-sm" style="width: 140px;">
                            <input type="hidden" name="daily_start_date" value="{{ $dailyStartDate }}">
                            <input type="hidden" name="daily_end_date" value="{{ $dailyEndDate }}">
                            <input type="hidden" name="product_start_date" value="{{ $dailyStartDate }}">
                            <input type="hidden" name="product_end_date" value="{{ $dailyEndDate }}">
                            <button type="submit" class="btn btn-sm btn-light" style="min-width: 80px;"><i class="fas fa-filter me-1"></i> Filter</button>
                        </form>
                    </div>
                    <div class="card-body" style="min-height: 300px;">
                        <canvas id="yearlySalesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Product Sales Percentage Pie Chart -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0" style="color: var(--cream);"><i class="fas fa-chart-pie me-2"></i>Product Sales Distribution</h5>
                        </div>
                        <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2" style="margin: 0;">
                            <input type="date" name="product_start_date" value="{{ $dailyStartDate }}" class="form-control form-control-sm" style="width: 140px;">
                            <input type="date" name="product_end_date" value="{{ $dailyEndDate }}" class="form-control form-control-sm" style="width: 140px;">
                            <input type="hidden" name="yearly_start_date" value="{{ $yearlyStartDate }}">
                            <input type="hidden" name="yearly_end_date" value="{{ $yearlyEndDate }}">
                            <input type="hidden" name="daily_start_date" value="{{ $dailyStartDate }}">
                            <input type="hidden" name="daily_end_date" value="{{ $dailyEndDate }}">
                            <button type="submit" class="btn btn-sm btn-light" style="min-width: 80px;"><i class="fas fa-filter me-1"></i> Filter</button>
                        </form>
                    </div>
                    <div class="card-body" style="display: flex; justify-content: center; min-height: 450px;">
                        <div style="max-width: 500px; width: 100%; position: relative;">
                            <canvas id="productSalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Yearly Sales Bar Chart
                const yearlySalesCtx = document.getElementById('yearlySalesChart');
                if (yearlySalesCtx) {
                    const yearlySalesData = @json($yearlySales) || [
                        {year: '2025', total: 1000},
                        {year: '2026', total: 1500}
                    ];

                    new Chart(yearlySalesCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: yearlySalesData.map(item => item.year),
                            datasets: [{
                                label: 'Revenue (₱)',
                                data: yearlySalesData.map(item => item.total),
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '₱' + value.toLocaleString();
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }

                const productSalesCtx = document.getElementById('productSalesChart');
                if (productSalesCtx) {
                    const productSalesData = @json($productSales) || [];
                    console.log('Product Sales Data:', productSalesData);

                    new Chart(productSalesCtx.getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: productSalesData.map(item => item.product_name + ' (' + item.percentage + '%)'),
                            datasets: [{
                                data: productSalesData.map(item => item.percentage),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(255, 205, 86, 0.6)',
                                    'rgba(75, 192, 192, 0.6)',
                                    'rgba(153, 102, 255, 0.6)',
                                    'rgba(255, 159, 64, 0.6)',
                                    'rgba(201, 203, 207, 0.6)',
                                    'rgba(255, 99, 255, 0.6)',
                                    'rgba(99, 255, 132, 0.6)',
                                    'rgba(132, 99, 255, 0.6)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 205, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(201, 203, 207, 1)',
                                    'rgba(255, 99, 255, 1)',
                                    'rgba(99, 255, 132, 1)',
                                    'rgba(132, 99, 255, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Chart initialization error:', error);
            }
        });
    </script>
@endsection
