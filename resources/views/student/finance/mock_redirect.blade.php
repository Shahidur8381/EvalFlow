<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSLCommerz Mock Redirect</title>
</head>
<body onload="document.getElementById('mockForm').submit();">
    <p>Redirecting to SSLCommerz Sandbox...</p>
    <form id="mockForm" action="{{ $url }}" method="POST">
        @csrf
        <input type="hidden" name="tran_id" value="{{ $tran_id }}">
        <input type="hidden" name="amount" value="{{ $amount }}">
        <input type="hidden" name="status" value="VALID">
    </form>
</body>
</html>
