$(document).ready(function () {
    init_table();
});

function init_table(){
    $('#table-concert').DataTable().destroy();
    $('#table-concert').DataTable({
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
            {
                data: 'concert_band',
                name: 'band',
                render: function (data, type, row) {
                    if(row.concert_price == 0) {
                        return `${data} <span class="badge bg-success"> Free 🎉</span>`;
                    }else{
                        return `${data} <span class="badge bg-danger"> Paid</span>`
                    }
                },
            },
            {
                data: 'concert_date',
                name: 'date',
                render: function (data) {
                    return moment(data).format('D MMMM YYYY');
                },
                orderable: false,
                searchable: false
            },
            // {
            //     data: 'concert_start',
            //     name: 'time',
            //     render: function (data, type, row) {
            //         const start = moment(data, 'HH:mm').format('HH:mm');

            //         if (row.concert_end_status === 1) {
            //         return `${start} - Done`;
            //         } else {
            //         const end = moment(row.concert_end, 'HH:mm').format('HH:mm');
            //         return `${start} - ${end}`;
            //         }
            //     },
            //     orderable: false,
            //     searchable: false
            // },
            { data: 'concert_location', name: 'concert_location' },
            // {
            //     data: 'concert_price',
            //     name: 'price',
            //     render: function (data) {
            //         return `IDR ${data}`;
            //     },
            //     orderable: false,
            //     searchable: false
            // },
            {
                data: 'concert_quota',
                name: 'date',
                render: function (data, type, row) {
                    return `${row.concert_remaining_quota} / ${data}`;
                },
                orderable: false,
                searchable: false
            },
            { data: 'category_name', name: 'category_name' },
            {
                data: 'concert_id',
                name: 'action',
                render: function (data) {
                    return `<button class="btn btn-sm btn-primary" title="Detail" onclick="onDetail(${data})"><i class="fa-solid fa-circle-info"></i></button>
                            <button class="btn btn-sm btn-warning" title="Edit" onclick="onEdit(${data})"><i class="fa-solid fa-file-pen"></i></button>
                            <button class="btn btn-sm btn-danger" title="Delete" onclick="onDestroy(${data})"><i class="fa-solid fa-trash"></i></button>`;
                },
                orderable: false,
                searchable: false
            }
        ]
    });
}

let cropper;
let rawImage;

document.getElementById('inputBanner').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (event) {
        rawImage = event.target.result;
        document.getElementById('imageToCrop').src = rawImage;
        const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
        cropModal.show();
    };
    reader.readAsDataURL(file);
});

document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
    cropper = new Cropper(document.getElementById('imageToCrop'), {
        aspectRatio: 16 / 9,
        viewMode: 1,
        autoCropArea: 1,
    });
});

document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

document.getElementById('cropImageBtn').addEventListener('click', function () {
    const canvas = cropper.getCroppedCanvas({
        width: 1280,
        height: 720,
    });

    canvas.toBlob(function (blob) {
        const reader = new FileReader();
        reader.onloadend = function () {
            const base64data = reader.result;
            document.getElementById('croppedBanner').value = base64data;
            document.getElementById('previewBanner').src = base64data;
            bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
        };
        reader.readAsDataURL(blob);
    }, 'image/jpeg');
});

$('#toggleEndTime').on('change', function () {
    $('#inputConcertEnd').prop('disabled', !this.checked);
});

$('#toggleFree').on('change', function () {
    const isFree = $(this).is(':checked');
    if (isFree) {
        $('#priceGroup').hide();
        $('#inputConcertPrice').val(0);
    } else {
        $('#priceGroup').show();
        $('#inputConcertPrice').val('');
    }
});

function onAdd() {
    $('#form-concert')[0].reset();
    $('#previewBanner').attr('src', '');
    $('#inputConcertCategory').select2({
        theme: 'bootstrap-5',
        placeholder: '— Choose —',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#modal-concert'), // penting agar dropdown tidak terpotong
        ajax: {
            url: URLcategoryList,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    }).on('select2:open', function () {
    }).next('.select2-container').find('.select2-selection').addClass('form-control');

    $('#modal-concert').modal('show');
}

function save(){
    const form = document.getElementById('form-concert');
    const formData = new FormData(form);

    const btn = $(form).find('button[onclick="save()"]');
    btn.prop('disabled', true).text('Saving…');

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
                    'X-CSRF-TOKEN': csrf
                },
                success(response) {
                    $('#modal-concert').modal('hide');
                    $('#form-concert')[0].reset();
                    $('#previewBanner').attr('src', '');
                    init_table();
        
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved',
                        text: response.message,
                    });
                },
                error(xhr) {
                    // Tampilkan error pertama
                    let msg = 'Something went wrong.';
                    if (xhr.responseJSON?.errors) {
                        const first = Object.values(xhr.responseJSON.errors)[0];
                        msg = Array.isArray(first) ? first[0] : first;
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }
        
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg,
                    });
                },
                complete() {
                    // Reset tombol
                    btn.prop('disabled', false).text('Save');
                }
            });
        }
    }); 
}

function onDetail(el) {
    $.ajax({
        url: URLread,
        type: 'POST',
        data: {
            concert_id: el,
            _token: csrf
        },
        success: function(response) {
            resetModal();
            $('#detailConcertBand').val(response.data.concert_band);
            $('#detailConcertDate').val(moment(response.data.concert_date).format('D MMMM YYYY'));
            $('#detailConcertStart').val(moment(response.data.concert_start, 'HH:mm').format('HH:mm'));
            
            if (response.data.concert_end_status === 1) {
                $('#detailConcertEnd').val('Done');
            } else {
                $('#detailConcertEnd').val(moment(response.data.concert_end, 'HH:mm').format('HH:mm'));
            }

            $('#detailConcertLocation').val(response.data.concert_location);
            $('#detailConcertPrice').val(response.data.concert_price == 0 ? 'Free' : `IDR ${response.data.concert_price}`);
            $('#detailConcertQuota').val(response.data.concert_quota + ' pax');
            $('#detailConcertCategory').val(response.data.category_name);
            $('#detailConcertBanner').attr('src', asset + '/' + response.data.concert_banner);
            
            // Tampilkan modal
            $('#modal-detail-concert').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Failed to load concert details.'
            });
        }
    });
}

function resetModal() {
    $('#detailConcertBand').val('');
    $('#detailConcertDate').val('');
    $('#detailConcertStart').val('');
    $('#detailConcertEnd').val('');
    $('#detailConcertLocation').val('');
    $('#detailConcertPrice').val('');
    $('#detailConcertQuota').val('');
    $('#detailConcertCategory').val('');
    $('#detailConcertBanner').attr('src', '');
}

function onEdit(el) {
    $('#inputConcertCategory').select2({
        theme: 'bootstrap-5',
        placeholder: '— Choose —',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#modal-concert'), // penting agar dropdown tidak terpotong
        ajax: {
            url: URLcategoryList,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    }).on('select2:open', function () {
    }).next('.select2-container').find('.select2-selection').addClass('form-control');

    $.ajax({
        url: URLread,
        type: 'POST',
        data: {
            concert_id: el,
            _token: csrf
        },
        success: function(response) {
            const concert = response.data;

            $('#inputConcertId').val(concert.concert_id);
            $('#inputConcertBand').val(concert.concert_band);
            $('#inputConcertDate').val(concert.concert_date);
            $('#inputConcertStart').val(concert.concert_start);
            $('#inputConcertLocation').val(concert.concert_location);

            if (concert.concert_price == 0) {
                $('#toggleFree').prop('checked', true);
                $('#inputConcertPrice').val(0).closest('.input-group').hide();
              } else {
                $('#toggleFree').prop('checked', false);
                $('#inputConcertPrice').val(concert.concert_price).closest('.input-group').show();
              }

            $('#inputConcertQuota').val(concert.concert_quota);
            $('#previewBanner').attr('src', asset + '/' + response.data.concert_banner);

            if (concert.concert_end_status === 1) {
                $('#toggleEndTime').prop('checked', false);
                $('#inputConcertEnd').prop('disabled', true).val('');
            } else {
                $('#toggleEndTime').prop('checked', true);
                $('#inputConcertEnd').prop('disabled', false).val(concert.concert_end);
            }

            const option = new Option(concert.category_name, concert.concert_category_id, true, true);
            $('#inputConcertCategory').append(option).trigger('change');

            $('#modal-concert').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Failed to load concert data for edit.'
            });
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
                    concert_id: el,
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