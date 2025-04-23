@extends('user.master.master-user')

@section('title')
    <title>User - Tickets</title>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tickets</h1>
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item active">Choose your concert, book your tickets now!</li>
    </ol>
    <div class="col-12 d-flex justify-content-end mb-2">
        <button class="btn btn-success" onclick="onFilter()">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
    </div>
    <div class="row" id="card-wrapper">

    </div>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="filterForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="filterModalLabel">Filter Concerts</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
                <div class="mb-3">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="startDate" name="start_date">
                </div>
                <div class="mb-3">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" class="form-control" id="endDate" name="end_date">
                </div>
                <div class="mb-3">
                <label for="concertCategory" class="form-label">Concert Category</label>
                <select class="form-select" id="concertCategory" name="category_id">
                </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="applyFilter()" class="btn btn-primary">
                <i class="fas fa-search"></i> Apply Filter
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Cancel
                </button>
            </div>
        </div>
      </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const URLgetData = '{{ route("user.tickets.getdata") }}';
        const URLcheckTicket = '{{ route("user.tickets.checkTicket") }}';
        const URLprocess = '{{ route("user.tickets.process") }}';
        const asset = '{{ asset("uploads/concerts/")}}';
        const bannerDefault = '{{ asset("assets/default_banner.jpg")}}';
        const user_id = '{{ Auth::user()->user_id }}';
        const csrf = '{{ csrf_token() }}';
        const URLdownload = '{{ route("user.tickets.download") }}';
        const URLcategoryList = '{{ route("user.categories.list") }}';
    </script>
    <script src="{{ asset('js/user/tickets.js') }}"></script> {{-- JavaScript utama --}}
@endpush

