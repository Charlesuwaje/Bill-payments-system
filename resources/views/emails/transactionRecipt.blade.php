<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .email-body {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .header img {
            max-width: 100px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .content {
            padding: 20px 0;
        }

        .content p {
            font-size: 16px;
            color: #333;
        }

        .transaction-details {
            background-color: #f8f8f8;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .transaction-details h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #333;
        }

        .transaction-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 12px;
            color: #aaa;
        }

        .footer a {
            color: #0066cc;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="email-body">
            <div class="header">
                <img src="{{ asset('images/easybill-logo.svg') }}" alt="Company Logo">
                <h1>Transaction Receipt</h1>
            </div>
            <div class="content">
                <p>Dear {{ $user->name }},</p>
                <p>Thank you for your transaction. Below are the details of your transaction:</p>
                <div class="transaction-details">
                    <h3>Transaction Details:</h3>
                    <p><strong>Amount:</strong> ${{ number_format($transaction->amount, 2) }}</p>
                    <p><strong>Description:</strong> {{ $transaction->description }}</p>
                    <p><strong>Date:</strong> {{ $transaction->created_at->format('F j, Y, g:i a') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($transaction->status ?? 'pending') }}</p>
                </div>
                <p>If you have any questions, feel free to contact us at any time.</p>
                <p>Best regards,<br>The {{ config('app.name') }} Team</p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p><a href="{{ url('/') }}">Visit our website</a></p>
            </div>
        </div>
    </div>
</body>

</html>
