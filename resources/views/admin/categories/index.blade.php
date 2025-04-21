@extends('admin.master.master-admin')

@section('title')
    <title>Admin - Categories</title>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Categories</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Category Concert</li>
        </ol>
        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Form Category
                    </div>
                    <div class="card-body">
                        <form id="form-category" class="table">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="inputCategoryId" type="text" name="category_id" hidden/>
                                <input class="form-control" id="inputCategory" type="text" name="category_name" placeholder="Ex. Festival" />
                                <label for="inputCategory">Category Name (Ex. Festival)</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <button type="button" class="btn btn-primary" onclick="onSave()">Save</button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        List of Categories
                    </div>
                    <div class="card-body">
                        <table id="table-categories">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Category Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@push('scripts')
    <script>
        const URLgetData = '{{ route("admin.categories.getdata") }}';
        const URLread = '{{ route("admin.categories.read") }}';
        const URLsave = '{{ route("admin.categories.save") }}';
        const URLdestroy = '{{ route("admin.categories.destroy") }}';
        const csrf = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/admin/categories.js') }}"></script> {{-- JavaScript utama --}}
@endpush