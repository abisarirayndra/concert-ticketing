@extends('admin.master.master-admin')

@section('title')
    <title>Admin - Concerts</title>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Concerts</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Management of Concerts</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                  <i class="fas fa-table me-1"></i>
                  List of Concerts
                </h5>
                <button class="btn btn-primary" onclick="onAdd()">
                    <i class="fa-solid fa-square-plus"></i> Add
                </button>
            </div>
            <div class="card-body">
                <table id="table-concert">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Artist/Band</th>
                            <th>Date</th>
                            {{-- <th>Time</th> --}}
                            <th>Location</th>
                            {{-- <th>Price</th> --}}
                            <th>Quota</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                
                </table>
            </div>
        </div>
    </div>

    <!-- Concert Modal -->
    <div class="modal fade" id="modal-concert" tabindex="-1" aria-labelledby="modalConcertLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalConcertLabel">Form Concert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-concert" enctype="multipart/form-data">
                    <input type="hidden" name="concert_id" id="inputConcertId" />
        
                    <div class="modal-body">
                        <div class="row">
                        <!-- Band -->
                        <div class="col-md-6 mb-3">
                            <label for="inputConcertBand" class="form-label">Band/Artist</label>
                            <input type="text" class="form-control" id="inputConcertBand" name="concert_band" required>
                        </div>
                        <!-- Date -->
                        <div class="col-md-6 mb-3">
                            <label for="inputConcertDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="inputConcertDate" name="concert_date" required>
                        </div>
                        <!-- Start Time -->
                        <div class="col-md-6 mb-3">
                            <label for="inputConcertStart" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="inputConcertStart" name="concert_start" required>
                        </div>
                        <!-- End Time -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="inputConcertEnd">End Time</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" id="toggleEndTime">
                                </div>
                                <input type="time" class="form-control" id="inputConcertEnd" name="concert_end" disabled>
                            </div>
                        </div>
                        <!-- Location -->
                        <div class="col-md-6 mb-3">
                            <label for="inputConcertLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="inputConcertLocation" name="concert_location" required>
                        </div>
                        
                        <!-- Quota -->
                        <div class="col-md-6 mb-3">
                            <label for="inputConcertQuota" class="form-label">Quota</label>
                            <div class="input-group">
                            <input type="number"
                                    class="form-control"
                                    id="inputConcertQuota"
                                    name="concert_quota"
                                    required
                                    placeholder="0">
                            <span class="input-group-text">pax</span>
                            </div>
                        </div>
                        <!-- Category (Select2) -->
                        <div class="col-md-6 mb-3">
                            <label for="inputConcertCategory" class="form-label">Category</label>
                            <select name="concert_category_id" id="inputConcertCategory" class="form-control"></select>
                        </div>
                        <!-- Price -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price</label>
                            <div class="d-flex align-items-center gap-2">
                                <span>It's Free ? 🤩</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="toggleFree">
                                </div>
                            </div>
                            <div class="input-group mt-2" id="priceGroup">
                                <span class="input-group-text">IDR</span>
                                <input type="number" class="form-control" id="inputConcertPrice" name="concert_price" placeholder="Enter price">
                            </div>
                        </div>
                        <!-- Banner -->
                        <div class="col-md-6 mb-3">
                            <label for="inputConcertBanner" class="form-label">Banner</label>
                            <input type="file" id="inputBanner" accept="image/*" class="form-control" />
                            <input type="hidden" name="concert_banner" id="croppedBanner" />
                            <img id="previewBanner" src="" class="img-fluid mt-2" />
                        </div>
                        </div>
                    </div>
        
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                        </button>
                        <button type="button" class="btn btn-primary" onclick="save()">
                        Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Crop Modal -->
    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div>
                    <img id="imageToCrop" style="max-width: 100%;" />
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropImageBtn">Crop & Upload</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-detail-concert" tabindex="-1" aria-labelledby="modal-detail-concertLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-detail-concertLabel">Concert Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Concert Band -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertBand" class="form-label">Band/Artist</label>
                            <input type="text" class="form-control" id="detailConcertBand" disabled>
                        </div>
                        <!-- Date -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertDate" class="form-label">Date</label>
                            <input type="text" class="form-control" id="detailConcertDate" disabled>
                        </div>
                        <!-- Start Time -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertStart" class="form-label">Start Time</label>
                            <input type="text" class="form-control" id="detailConcertStart" disabled>
                        </div>
                        <!-- End Time -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertEnd" class="form-label">End Time</label>
                            <input type="text" class="form-control" id="detailConcertEnd" disabled>
                        </div>
                        <!-- Location -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="detailConcertLocation" disabled>
                        </div>
                        <!-- Price -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="detailConcertPrice" disabled>
                        </div>
                        <!-- Quota -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertQuota" class="form-label">Quota</label>
                            <input type="text" class="form-control" id="detailConcertQuota" disabled>
                        </div>
                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="detailConcertCategory" disabled>
                        </div>
                        <!-- Banner -->
                        <div class="col-md-6 mb-3">
                            <label for="detailConcertBanner" class="form-label">Banner</label>
                            <img id="detailConcertBanner" class="img-fluid" src="" alt="Concert Banner" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-detail-ticket" tabindex="-1" aria-labelledby="modal-detail-ticketLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-detail-ticketLabel">Concert Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="table-ticket" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>User</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                    
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const URLgetData = '{{ route("admin.concert.getdata") }}';
        const URLsave = '{{ route("admin.concert.save") }}';
        const URLcategoryList = '{{ route("admin.categories.list") }}';
        const URLread = '{{ route("admin.concert.read") }}';
        const csrf = '{{ csrf_token() }}';
        const asset = '{{ asset("uploads/concerts/")}}';
        const URLdestroy = '{{ route("admin.concert.destroy") }}';
        const URLgetDataTicket = '{{ route("admin.concert.getdataticket") }}';
        
    </script>
    <script src="{{ asset('js/admin/concerts.js') }}"></script> {{-- JavaScript utama --}}
@endpush