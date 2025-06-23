@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Subscription Users</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a></li>
        <li class="breadcrumb-item active">Users</li>
    </ol>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="free" {{ request('status') == 'free' ? 'selected' : '' }}>Free Users</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.subscriptions.users') }}" class="btn btn-secondary w-100">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Current Plan</th>
                            <th>Status</th>
                            <th>Tests Taken</th>
                            <th>AI Used</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->activeSubscription())
                                    <span class="badge bg-primary">{{ $user->activeSubscription()->plan->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Free</span>
                                @endif
                            </td>
                            <td>
                                @if($user->activeSubscription())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $user->tests_taken_this_month }}</td>
                            <td>{{ $user->ai_evaluations_used }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#grantModal{{ $user->id }}">
                                        Grant
                                    </button>
                                    @if($user->activeSubscription())
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                            data-bs-target="#revokeModal{{ $user->id }}">
                                        Revoke
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Grant Modal --}}
                        <div class="modal fade" id="grantModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.subscriptions.grant', $user) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Grant Subscription to {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Select Plan</label>
                                                <select name="plan_id" class="form-select" required>
                                                    @foreach(\App\Models\SubscriptionPlan::active()->get() as $plan)
                                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ৳{{ $plan->price }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Duration (days)</label>
                                                <input type="number" name="duration_days" class="form-control" value="30" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Reason</label>
                                                <textarea name="reason" class="form-control" rows="2" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Grant Subscription</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Revoke Modal --}}
                        @if($user->activeSubscription())
                        <div class="modal fade" id="revokeModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.subscriptions.revoke', $user->activeSubscription()) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Revoke Subscription</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to revoke {{ $user->name }}'s subscription?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Reason</label>
                                                <textarea name="reason" class="form-control" rows="2" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Revoke Subscription</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection