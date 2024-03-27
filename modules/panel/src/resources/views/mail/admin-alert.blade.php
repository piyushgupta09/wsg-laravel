<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PO Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sacramento&display=swap" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .font-sign {
            font-family: 'Sacramento', cursive;
            font-size: 1.5rem;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            font-size: 0.75rem !important;
        }
    </style>
</head>
<body class="p-3">
    <h3>Dear Admin,</h3>
    
    <p>{{ $message }}</p>

    <div class="d-flex align-items-center justify-content-start mb-3">
        <a class="btn btn-sm btn-secondary me-3" href="{{ route('panel.dashboard') }}" target="__blank">My Dashboard</a><br><br>
    </div>

    <p>Thank you,</p>
    <p class="font-sign">App Admin</p>
    <p class="fs-5 fw-bold mb-2">{{ config('brand.name') }}</p>
    <small>{{ config('brand.address') }}</small><br>
    <small>{{ config('brand.details') }}</small>
</body>
</html>
