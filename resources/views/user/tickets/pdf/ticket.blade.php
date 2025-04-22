<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Concert Ticket</title>
    <style>
        body { font-family: sans-serif; background: #f8f9fa; }
        .container {
            width: 100%;
            height: 100%;
            padding: 20px;
            text-align: center;
        }
        .banner {
            width: 200px;
            height: auto;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .info {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $bannerPath = $ticket->concert_banner
                ? public_path('uploads/concerts/' . $ticket->concert_banner)
                : public_path('assets/default_banner.jpg');

            if (!file_exists($bannerPath)) {
                $bannerPath = public_path('assets/default_banner.jpg');
            }
        @endphp

        <img class="banner" src="{{ $bannerPath }}" alt="Banner">

        <h1>Concert Ticket</h1>
        <div class="info">
            <p><strong>Band:</strong> {{ $ticket->concert_band }}</p>
            <p><strong>Code:</strong> {{ $ticket->ticket_code }}</p>
            <p><strong>Redeemed by User #{{ $ticket->user_name }}</strong></p>
        </div>
    </div>
</body>
</html>