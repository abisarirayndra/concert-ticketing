@extends('user.master.master-user')

@section('title')
    <title>User - Dashboard</title>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">User Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Enjoy your happiness with us!</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body"><h2 id="booked"></h2></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small>Concert Booked</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body"><h2 id="tickets"></h2></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small>Your Redeemed Tickets</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body"><h2 id="others"></h2></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small>Available Concert</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body"><h2 id="week"></h2></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small>Concert on The Week</small>
                </div>
            </div>
        </div>
    </div>
    <h5 class="mt-2">Coming this week, Don't miss it !</h5>
    <div class="row mt-2" id="card-wrapper">

    </div>
</div>
@endsection

@push('scripts')
    <script>
        const asset = '{{ asset("uploads/concerts/")}}';
        const bannerDefault = '{{ asset("assets/default_banner.jpg")}}';
        const user_id = '{{ Auth::user()->user_id }}';
        const csrf = '{{ csrf_token() }}';
        const URLgetData = '{{ route("user.dashboard.getdata") }}';
        const URLcheckTicket = '{{ route("user.tickets.checkTicket") }}';
        const URLprocess = '{{ route("user.tickets.process") }}';
        const URLdownload = '{{ route("user.tickets.download") }}';
    </script>
    <script src="{{ asset('js/user/dashboard.js') }}"></script> {{-- JavaScript utama --}}
@endpush