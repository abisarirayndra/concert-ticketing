$(document).ready(function () {
    init_cards();
});

function init_cards(){
    $.ajax({
        url: URLgetDataHistory,
        type: "GET",
        success: function(res){
            data = res.data;
            data.forEach(item => {
                const isFree = item.concert_price == 0;
                const start = moment(item.concert_start, 'HH:mm').format('HH:mm');
                if(item.concert_end_status == 0) var end = moment(item.concert_end, 'HH:mm').format('HH:mm');
                else var end = `Done`;

                if(!item.concert_banner) var src = bannerDefault;
                else var src = asset + `/${item.concert_banner}`;

                checkTicket(user_id, item.concert_id, function(buttonHtml) {
                    const card = `
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="${src}" class="card-img-top" alt="${item.concert_band}">
                                <div class="card-body d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <h5 class="card-title">${item.concert_band}</h5>
                                        <p class="card-text mb-1"><i class="fa-solid fa-calendar-days me-1"></i> ${moment(item.concert_date).format('D MMMM YYYY')} ${start} - ${end}</p>
                                        <p class="card-text mb-1"><i class="fa-solid fa-location-dot me-1"></i> ${item.concert_location}</p>
                                        <p class="card-text mb-1"><i class="fa-solid fa-map-pin me-1"></i> ${item.category_name}</p>
                                        <span class="badge bg-${isFree ? 'success' : 'primary'}">
                                            ${isFree ? 'Free' : 'IDR ' + item.concert_price.toLocaleString()}
                                        </span>
                                    </div>
                                    ${buttonHtml}
                                </div>
                            </div>
                        </div>
                    `;
                    $('#card-wrapper').append(card);
                });

            });
        }
    });

    function checkTicket(user, concert, callback) {
        $.ajax({
            url: URLcheckTicket,
            type: 'POST',
            data: {
                concert_id: concert,
                user_id: user,
            },
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            success(response) {
                let buttonHtml;
                if (response.success) {
                    if(response.data.ticket_redeem == 1){
                        buttonHtml = `
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0 text-muted"><i class="fa-solid fa-circle-check me-1 text-success"></i> Redeemed, check your Ticket!</p>
                                <button class="btn btn-sm btn-outline-success" onclick="onProcess(${concert}, ${user}, 'ticket')">
                                    <i class="fa-solid fa-ticket me-1"></i> Ticket
                                </button>
                            </div>
                        `;
                    }else{
                        if(moment().format('YYYY-MM-DD HH:mm') > response.data.concert_date+' '+ response.data.concert_start){
                            buttonHtml = `<p class="mb-0 text-muted"><i class="fa-solid fa-circle-check me-1 text-danger"></i> Concert Out of Date</p>`;
                        }else{
                            buttonHtml = `
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <p class="mb-0 text-muted"><i class="fa-solid fa-circle-check me-1 text-success"></i> Booked, Redeem it!</p>
                                    <button class="btn btn-sm btn-outline-success" onclick="onProcess(${concert}, ${user}, 'redeem')">
                                        <i class="fa-solid fa-ticket me-1"></i> Redeem
                                    </button>
                                </div>
                            `;
                        }
                    }
                } 
                callback(buttonHtml);
            }
        });
    }

}

function onProcess(concert, user, command){
    if(command == 'ticket'){
        fetch(URLdownload, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({
                concert_id: concert,  // ID konser
                user_id: user,        // ID user
            })
        })
        .then(response => response.blob())  // Mengambil file PDF sebagai Blob
        .then(blob => {
            // Membuat URL untuk file PDF dan memulai download
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `ticket.pdf`;  // Nama file untuk download
            document.body.appendChild(a);
            a.click();
            a.remove();  // Hapus elemen setelah download
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }else{
        Swal.fire({
            title: 'Are you sure to '+ command +' ?',
            text: "Data will be "+command+"ed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: URLprocess,
                    type: 'POST',
                    data: {
                        concert_id: concert,
                        user_id: user,
                        command: command,
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    success(response) {
                        $('#card-wrapper').empty();
                        init_cards();
            
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
    
}