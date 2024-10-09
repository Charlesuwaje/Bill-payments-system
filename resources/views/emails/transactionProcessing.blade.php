<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Processing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #34495e;
            font-size: 16px;
            line-height: 1.6;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }

        .btn {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Hello, {{ $fullName }}!</h1>

        <p>We wanted to let you know that your transaction is currently being processed.</p>

        <p><strong>Transaction Details:</strong></p>
        <ul>
            <li>Amount: ${{ number_format($transaction->amount, 2) }}</li>
            <li>Description: {{ $transaction->description }}</li>
            <li>Status: <strong>Processing</strong></li>
        </ul>

        <p>We will notify you once the transaction is complete. Thank you for your patience!</p>

        <a href="#" class="btn">View Transaction</a>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>

</body>

</html>
