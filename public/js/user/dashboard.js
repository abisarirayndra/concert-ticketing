$(document).ready(function () {
    init_cards();
});

function init_cards(){
    $.ajax({
        url: URLgetData,
        type: "GET",
        success: function(res){
            $('#booked').text(res.data.booked);
            $('#tickets').text(res.data.tickets);
            $('#others').text(res.data.others);
            $('#week').text(res.data.week);

            if(!res.data.week_data || res.data.week_data.length === 0){
                $('#card-wrapper').html('<p>Data Not Found!</p>');
            }else{
                const data = res.data.week_data.sort((a, b) => {
                    const dateA = moment(a.concert_date, 'YYYY-MM-DD').toDate();
                    const dateB = moment(b.concert_date, 'YYYY-MM-DD').toDate();
                    return dateB - dateA;  
                });
                
                renderConcertCards(data, user_id); 
            }
        }
    });
}

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
            const now = moment();
            if (response.success) {
                if (response.data.ticket_redeem == 1) {
                    buttonHtml = `
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="mb-0 text-muted"><i class="fa-solid fa-circle-check me-1 text-success"></i> Redeemed, check your Ticket!</p>
                            <button class="btn btn-sm btn-outline-success" onclick="onProcess(${concert}, ${user}, 'ticket')">
                                <i class="fa-solid fa-cloud-arrow-down me-1"></i> Ticket
                            </button>
                        </div>
                    `;
                } else if (now.format('YYYY-MM-DD HH:mm') > response.data.concert_date + ' ' + response.data.concert_start) {
                    buttonHtml = `<p class="mb-0 text-muted"><i class="fa-solid fa-circle-check me-1 text-danger"></i> Concert Out of Date</p>`;
                } else {
                    buttonHtml = `
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="mb-0 text-muted"><i class="fa-solid fa-circle-check me-1 text-success"></i> Booked, Redeem it!</p>
                            <button class="btn btn-sm btn-outline-success" onclick="onProcess(${concert}, ${user}, 'redeem')">
                                <i class="fa-solid fa-ticket me-1"></i> Redeem
                            </button>
                        </div>
                    `;
                }
            } else {
                if(response.data.concert_remaining_quota === 0){
                    buttonHtml = `<p class="mb-0 text-muted"><i class="fa-solid fa-circle-check me-1 text-danger"></i> Sold Out</p>`;
                }else{
                    buttonHtml = `
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-sm btn-outline-primary" onclick="onProcess(${concert}, ${user}, 'book')">
                                <i class="fa-solid fa-ticket me-1"></i> Book
                            </button>
                        </div>
                    `;
                }
            }
            callback(buttonHtml);
        }
    });
}

function checkTicketPromise(user_id, concert_id) {
    return new Promise(resolve => {
        checkTicket(user_id, concert_id, resolve);
    });
}

function renderCard(item, end, start, src, quotaHTML, buttonHtml = '', isExpired = false) {
    return `
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="${src}" class="card-img-top" alt="${item.concert_band}">
                <div class="card-body d-flex flex-column justify-content-between h-100">
                    <div>
                        <h5 class="card-title">${item.concert_band}</h5>
                        <p class="card-text mb-1"><i class="fa-solid fa-calendar-days me-1"></i> ${moment(item.concert_date).format('D MMMM YYYY')} ${start} - ${end}</p>
                        <p class="card-text mb-1"><i class="fa-solid fa-location-dot me-1"></i> ${item.concert_location}</p>
                        <p class="card-text mb-1"><i class="fa-solid fa-map-pin me-1"></i> ${item.category_name}</p>
                        <span class="badge bg-${item.concert_price == 0 ? 'success' : 'primary'}">
                            ${item.concert_price == 0 ? 'Free' : 'IDR ' + item.concert_price.toLocaleString()}
                        </span>
                        ${!isExpired ? quotaHTML : ''}
                    </div>
                    ${isExpired ? `<p class="mb-0 text-muted"><i class="fa-solid fa-circle-check me-1 text-danger"></i> Concert Out of Date</p>` : buttonHtml}
                </div>
            </div>
        </div>
    `;
}

function renderConcertCards(data, user_id) {
    const cardPromises = data.map(async (item) => {
        const isFree = item.concert_price == 0;
        const start = moment(item.concert_start, 'HH:mm').format('HH:mm');
        const end = item.concert_end_status == 0
            ? moment(item.concert_end, 'HH:mm').format('HH:mm')
            : 'Done';

        const src = item.concert_banner ? asset + `/${item.concert_banner}` : bannerDefault;

        const totalQuota = item.concert_quota;
        const remaining = item.concert_remaining_quota;
        const used = totalQuota - remaining;
        const percent = totalQuota > 0 ? (used / totalQuota) * 100 : 0;

        let barClass = 'bg-success';
        if (percent < 30) barClass = 'bg-danger';
        else if (percent < 60) barClass = 'bg-warning';

        const quotaHTML = `
            <div class="mt-3">
                <small class="text-muted">Quota: ${used} / ${totalQuota} pax</small>
                <div class="progress" style="height: 18px;">
                    <div class="progress-bar ${barClass}" role="progressbar"
                        style="width: ${percent}%;"
                        aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100">
                        ${Math.round(percent)}%
                    </div>
                </div>
            </div>
        `;

        const concertDateTime = moment(item.concert_date + ' ' + item.concert_start, 'YYYY-MM-DD HH:mm');
        const isExpired = moment().isAfter(concertDateTime);

        let buttonHtml = '';
        if (!isExpired) {
            buttonHtml = await checkTicketPromise(user_id, item.concert_id);
        }

        return renderCard(item, end, start, src, quotaHTML, buttonHtml, isExpired);
    });

    Promise.all(cardPromises).then(cards => {
        $('#card-wrapper').html(cards.join(''));
    });
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
            
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: command+'ed!',
                                text: response.message,
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Abort!',
                                text: response.message,
                            });
                        }
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
                });
            }
        });
    }
    
}