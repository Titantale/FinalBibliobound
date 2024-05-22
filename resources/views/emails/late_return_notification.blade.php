<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Late Return Notification</title>
</head> 
<body>
    <p>Dear {{ $username }},</p>
    <p>You are late in returning the book you borrowed "{{ $bookName }}" which is supposed to be returned by {{ \Carbon\Carbon::parse($returnDate)->format('Y-m-d') }}.</p>
</body>
</html>
