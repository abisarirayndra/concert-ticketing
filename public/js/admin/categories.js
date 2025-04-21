$(document).ready(function () {
    init_table()
});

function init_table(){
    $('#table-categories').DataTable().destroy();
    $('#table-categories').DataTable({
        processing: true,
        serverSide: true,
        ajax: URLgetData,
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
                url: URLsave,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrf,
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
        url: URLread,
        type: 'POST',
        data: {
            category_id: el,
            _token: csrf
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
                url: URLdestroy,
                type: 'POST',
                data: {
                    category_id: el,
                    _token: csrf
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