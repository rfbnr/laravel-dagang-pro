@extends('theem.layouts.app')

@section('content')
    <style>
        .stats-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px 20px;
            text-align: center;
            transform: translateY(50px);
            opacity: 0;
            transition: all 0.6s ease;
        }

        .stats-card.show {
            transform: translateY(0);
            opacity: 1;
        }

        .stats-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
            cursor: pointer;
        }



        .stats-title {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 12px;
            text-transform: uppercase;
            font-weight: 500;
        }

        .stats-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #343a40;
        }

        .stats-badge {
            display: inline-block;
            margin-top: 15px;
            padding: 6px 18px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-primary {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .badge-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .badge-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .badge-info {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .badge-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .quick-action-card {
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 15px;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #333;
            transition: transform 0.6s ease, opacity 0.6s ease;
            transform: translateY(50px);
            opacity: 0;
        }

        .quick-action-card.show {
            transform: translateY(0);
            opacity: 1;
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .action-title {
            font-size: 1rem;
            font-weight: 600;
        }
    </style>

    <div class="content-page">
        <div class="container-fluid mt-4">
            <!-- KPI Cards -->
            <div class="row g-4">
                @php
                    $cards = [
                        ['Total Users', $totalUsers, 'Admins & Cashiers', 'primary', route('user.index')],
                        ['Products In Stock', $totalStock, 'Available Now', 'success', route('product.index')],
                        ['Low Stock', $lowStockProducts, 'Reorder Needed', 'danger', route('product.lowStock')],
                        ['Total Customers', $totalCustomers, 'Active', 'info', route('customer.index')],
                        ['Suppliers', $totalSuppliers, 'Suppliers', 'warning', route('supplier.index')],
                        ["Today's Sales", $todaysSales, 'Sales', 'success', null, 'IDR'],
                    ];
                @endphp

                @foreach ($cards as $i => $item)
                    <div class="col-md-4">
                        @if ($item[4])
                            <a href="{{ $item[4] }}" class="text-decoration-none">
                        @endif
                        <div class="stats-card" data-target="{{ $item[1] }}" data-suffix="{{ $item[5] ?? '' }}"
                            style="animation-delay: {{ 0.1 * $i }}s">
                            <div class="stats-title">{{ $item[0] }}</div>
                            <div class="stats-value counter">0</div>
                            <span class="stats-badge badge-{{ $item[3] }}">{{ $item[2] }}</span>
                        </div>
                        @if ($item[4])
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Quick Actions -->
            <div class="row g-3 mt-5">
                <div class="col-12">
                    <h5 class="mb-3 text-muted">Quick Actions</h5>
                </div>

                @php
                    $quickActions = [
                        ['Add New Product', 'ri-add-box-line', route('product.create'), 'primary'],
                        ['Add Supplier', 'ri-truck-line', route('supplier.create'), 'success'],
                        ['Add Customer', 'ri-user-add-line', route('customer.create'), 'info'],
                        ['Create Invoice', 'ri-file-add-line', route('invoice.create'), 'warning'],
                    ];
                @endphp

                @foreach ($quickActions as $i => $action)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ $action[2] }}" class="quick-action-card">
                            <div class="action-icon bg-{{ $action[3] }}">
                                <i class="{{ $action[1] }}"></i>
                            </div>
                            <div class="action-title">{{ $action[0] }}</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const cards = document.querySelectorAll('.stats-card');

        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('show');
                startCounter(
                    card.querySelector('.counter'),
                    +card.getAttribute('data-target'),
                    card.getAttribute('data-suffix')
                );
            }, index * 300);
        });

        function startCounter(counter, target, suffix = '') {
            let startTimestamp = null;
            const duration = 2000;

            const easeOutQuad = (t) => t * (2 - t);

            const updateCounter = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const easedProgress = easeOutQuad(progress);
                counter.innerText = Math.floor(easedProgress * target) + (suffix ? ' ' + suffix : '');

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target + (suffix ? ' ' + suffix : '');
                }
            };

            requestAnimationFrame(updateCounter);
        }

        // Quick Actions Animation
        const quickCards = document.querySelectorAll('.quick-action-card');
        quickCards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('show');
            }, index * 200);
        });
    });
</script>
