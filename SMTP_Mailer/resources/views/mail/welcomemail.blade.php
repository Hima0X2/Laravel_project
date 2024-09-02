<!DOCTYPE html>
<html>
<head>
    <title>{{ $request['subject'] }}</title>
</head>
<body>
    <p><strong>Name:</strong> {{ $request['name'] }}</p>
    <p><strong>Email:</strong> {{ $request['email'] }}</p>
    <p><strong>Subject:</strong> {{ $request['subject'] }}</p>
    <p><strong>Message:</strong> {{ $request['message'] }}</p>
</body>
</html>
