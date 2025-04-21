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

@section('js')
    <script>
        $(document).ready(function () {
            init_table()
        });

        function init_table(){
            $('#table-categories').DataTable().destroy();
            $('#table-categories').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.categories.getdata') }}",  // menggunakan GET, jadi cukup URL
                columns: [
                    {
                        data: null,
                        name: 'row_number',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'category_name', name: 'category_name' },
                    {
                        data: 'category_id',
                        name: 'action',
                        render: function (data) {
                            return `<button class="btn btn-sm btn-warning" title="Edit" onclick="onEdit(${data})"><i class="fa-solid fa-file-pen"></i></button>
                                    <button class="btn btn-sm btn-danger" title="Delete" onclick="onDestroy(${data})"><i class="fa-solid fa-trash"></i></button>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }

        function onSave(){
            let form = document.getElementById('form-category');
            let formData = new FormData(form);

            Swal.fire({
                title: 'Are you sure to save this data ?',
                text: "Data will be stored!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.categories.save") }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#form-category')[0].reset();
                            init_table()
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON?.errors;
                            if (errors) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: Object.values(errors)[0][0]
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something wrong.'
                                });
                            }
                        }
                    });
                }
            });
        }

        function onEdit(el){
            $.ajax({
                url: '{{ route("admin.categories.read") }}',
                type: 'POST',
                data: {
                    category_id: el,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#inputCategory').val('');
                    $('#inputCategoryId').val('');
                    $('#inputCategory').val(response.data.category_name);
                    $('#inputCategoryId').val(response.data.category_id);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: Object.values(errors)[0][0]
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something wrong.'
                        });
                    }
                }
            });
        }

        function onDestroy(el){
            Swal.fire({
                title: 'Are you sure to delete this data ?',
                text: "Data will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.categories.destroy") }}',
                        type: 'POST',
                        data: {
                            category_id: el,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            init_table()
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON?.errors;
                            if (errors) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: Object.values(errors)[0][0]
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something wrong.'
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
    @yield('js-addon')
@endsection