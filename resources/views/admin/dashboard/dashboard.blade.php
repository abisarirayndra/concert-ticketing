@extends('admin.master.master-admin')

@section('title')
    <title>Admin - Dashboard</title>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Admin Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Easy to manage your concert</li>
    </ol>
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body"><h2 id="concert"></h2></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small>Concert</small>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body"><h2 id="sold"></h2></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small>Sold Out</small>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body"><h2 id="book"></h2></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small>Book this week</small>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-ticket"></i>
              Tickets of the week
            </h5>
        </div>
        <div class="card-body">
            <table id="table-ticket">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Artis/Band</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>User</th>
                    </tr>
                </thead>
            
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const csrf = '{{ csrf_token() }}';
        const URLgetData = '{{ route("admin.dashboard.getdata") }}';
        const URLgetDataTable = '{{ route("admin.dashboard.getdatatable") }}';
    </script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script> {{-- JavaScript utama --}}
@endpush