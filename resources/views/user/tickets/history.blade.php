@extends('user.master.master-user')

@section('title')
    <title>User - History Tickets</title>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">History Tickets</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Have fun always !</li>
    </ol>
    <div class="row" id="card-wrapper">

    </div>
</div>
@endsection

@push('scripts')
    <script>
        const URLgetDataHistory = '{{ route("user.tickets.getdatahistory") }}';
        const URLcheckTicket = '{{ route("user.tickets.checkTicket") }}';
        const URLprocess = '{{ route("user.tickets.process") }}';
        const asset = '{{ asset("uploads/concerts/")}}';
        const bannerDefault = '{{ asset("assets/default_banner.jpg")}}';
        const user_id = '{{ Auth::user()->user_id }}';
        const csrf = '{{ csrf_token() }}';
        const URLdownload = '{{ route("user.tickets.download") }}';
    </script>
    <script src="{{ asset('js/user/tickets-history.js') }}"></script> {{-- JavaScript utama --}}
@endpush

