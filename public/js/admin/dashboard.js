$(document).ready(function () {
    init_cards();
    init_table();
});

function init_cards(){
    $.ajax({
        url: URLgetData,
        type: "GET",
        success: function(res){
            $('#concert').text(res.data.concert);
            $('#sold').text(res.data.sold);
            $('#book').text(res.data.book);

        }
    });
}

function init_table(){
    $('#table-ticket').DataTable().destroy();
    $('#table-ticket').DataTable({
        processing: true,
        serverSide: true,
        ajax: URLgetDataTable,
        columns: [
            {
                data: null,
                name: 'row_number',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false,

            },
            { data: 'ticket_code', name: 'ticket_code' },
            {
                data: 'ticket_redeem',
                name: 'status',
                render: function (data, type, row) {
                    if(data == 0) {
                        return `<span class="badge bg-primary"> Booked</span>`;
                    }else{
                        return `<span class="badge bg-success"> Redeemed</span>`;
                    }
                },
            },
            { data: 'concert_band', name: 'concert_band', },
            { data: 'category_name', name: 'category_name', },
            {
                data: 'concert_date',
                name: 'date',
                render: function (data) {
                    return moment(data).format('D MMMM YYYY');
                },
                orderable: false,
                searchable: false,
            },
            {
                data: 'concert_start',
                name: 'time',
                render: function (data, type, row) {
                    const start = moment(data, 'HH:mm').format('HH:mm');

                    if (row.concert_end_status === 1) {
                    return `${start} - Done`;
                    } else {
                    const end = moment(row.concert_end, 'HH:mm').format('HH:mm');
                    return `${start} - ${end}`;
                    }
                },
                orderable: false,
                searchable: false
            },
            { data: 'concert_location', name: 'concert_location', },
            {
                data: 'user_name',
                name: 'name',
                render: function (data, type, row) {
                    return `${data} ${row.user_name_last}` ;
                },
                orderable: false,
                searchable: false
            },
        ]
    });
}