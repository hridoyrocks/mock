@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Subscription Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Subscriptions</li>
    </ol>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Active Subscribers</div>
                            <div class="h3 mb-0">{{ $stats['total_subscribers'] }}</div>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Revenue This Month</div>
                            <div class="h3 mb-0">৳{{ number_format($stats['revenue_this_month'], 0) }}</div>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">New This Week</div>
                            <div class="h3 mb-0">{{ $stats['new_subscribers_this_week'] }}</div>
                        </div>
                        <i class="fas fa-user-plus fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Churn Rate</div>
                            <div class="h3 mb-0">{{ $stats['churn_rate'] }}%</div>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Plan Distribution Chart --}}
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Plan Distribution
                </div>
                <div class="card-body">
                    <canvas id="planDistributionChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-clock me-1"></i>
                    Subscriptions Expiring Soon
                </div>
                <div class="card-body">
                    @if($expiringSoon->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Plan</th>
                                        <th>Expires</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiringSoon as $subscription)
                                    <tr>
                                        <td>{{ $subscription->user->name }}</td>
                                        <td>{{ $subscription->plan->name }}</td>
                                        <td>{{ $subscription->ends_at->format('d M') }}</td>
                                        <td>
                                            <a href="mailto:{{ $subscription->user->email }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No subscriptions expiring soon</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Subscriptions --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Recent Subscriptions
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSubscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->created_at->format('d M Y') }}</td>
                        <td>{{ $subscription->user->name }}</td>
                        <td>{{ $subscription->user->email }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $subscription->plan->name }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $subscription->isActive() ? 'success' : 'danger' }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td>{{ $subscription->ends_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.subscriptions.users') }}?search={{ $subscription->user->email }}" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Plan Distribution Chart
    const ctx = document.getElementById('planDistributionChart');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($planDistribution->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($planDistribution->pluck('count')) !!},
                backgroundColor: ['#dc3545', '#0d6efd', '#6f42c1'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection