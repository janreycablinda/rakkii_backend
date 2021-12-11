
<!DOCTYPE html>
<html>
<head>
 <title>Laravel 8 Send Email Example</title>
</head>
<body>
<!-- Hello <strong>{{ $client_name }}</strong>, -->
{!! $messages !!}
 
<p>Upload LOA Documents on this link. {{$link}}.</p>
<small>Note this link is only active for 3 days.</small>
</body>
</html>