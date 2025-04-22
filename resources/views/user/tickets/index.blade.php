@extends('user.master.master-user')

@section('title')
    <title>User - Tickets</title>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tickets</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Choose your concert, book your tickets now!</li>
    </ol>
    <div class="row" id="card-wrapper">

    </div>
</div>
@endsection

@push('scripts')
    {{-- <script>
        const URLgetData = '{{ route("admin.concert.getdata") }}';
        const URLsave = '{{ route("admin.concert.save") }}';
        const URLcategoryList = '{{ route("admin.categories.list") }}';
        const URLread = '{{ route("admin.concert.read") }}';
        
        const asset = '{{ asset("uploads/concerts/")}}';
        const URLdestroy = '{{ route("admin.concert.destroy") }}';
    </script> --}}
    <script>
        const URLgetData = '{{ route("user.tickets.getdata") }}';
        const URLcheckTicket = '{{ route("user.tickets.checkTicket") }}';
        const URLprocess = '{{ route("user.tickets.process") }}';
        const asset = '{{ asset("uploads/concerts/")}}';
        const bannerDefault = '{{ asset("assets/default_banner.jpg")}}';
        const user_id = '{{ Auth::user()->user_id }}';
        const csrf = '{{ csrf_token() }}';
        const URLdownload = '{{ route("user.tickets.download") }}';
    </script>
    <script src="{{ asset('js/user/tickets.js') }}"></script> {{-- JavaScript utama --}}
@endpush

