<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 30px; }
        .card { background: #fff; border-radius: 8px; padding: 30px; max-width: 500px; margin: auto; }
        h1 { color: #4f46e5; }
        p { color: #374151; line-height: 1.6; }
        .badge { display: inline-block; background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 999px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Hello, {{ $recipientName }}!</h1>
        <p>This is a test email sent from <strong>Laravel Money</strong> via <strong>Mailgun</strong>.</p>
        <p>If you received this, your Mailgun integration is working correctly.</p>
        <p><span class="badge">✓ Mailgun connected</span></p>
        <p style="color:#9ca3af; font-size:12px; margin-top:24px;">Sent at: {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>
