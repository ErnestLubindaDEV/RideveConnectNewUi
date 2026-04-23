<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        /* Styles for screen display */
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .flex {
            display: flex;
            align-items: center; /* Aligns logo and text vertically */
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #2ba6db;
        }

        .content {
            margin-bottom: 20px;
        }

        .signature-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .signature-section img {
            max-width: 190px;
            height: auto;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 40px;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Print-specific styles for A4 */
        @media print {
            body {
                padding: 0;
                margin: 0;
                font-size: 12px;
            }

            .container {
                width: 100%;
                max-width: none;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                page-break-before: always;
            }

            .flex {
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }

            .header img {
                width: 100%;
                max-width: 600px;
            }

            .header h1 {
                text-align: center;
                font-size: 18px;
                margin-top: 0;
            }

            .content {
                margin-bottom: 15px;
            }

            .footer img {
                width: 100%;
                max-width: 600px;
            }

            /* Optional: Set up page size and margins for A4 paper */
            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
        <img  src="/public/assets/BG-02.jpeg" style="max-width: 600px; height:auto; margin-bottom: 20px;" alt="Pattern" class="img-fluid w-100">
        <!-- Align logo and Memo in the same row -->
            <div class="flex">
                <img src="/public/assets/Logos/2.png" alt="Company Logo" style="max-width: 200px; height:auto;">
                <h1 style="margin: 0; color: #2ba6db; text-align: right; margin-left: 20px;">Memo</h1> <!-- Adjusted margin-left for spacing -->
            </div>
        </div>
        <div class="header">
            <h1 style="color: #333;text-align: center;">{{ $subject }}</h1>
        </div>
        <div class="content">
            <p>{{ $message }}</p>
        </div>
        
        @if($signature)
            <div class="signature-section">
                <div>
                    <p>Sent by: {{ $memoBy }}</p>
                    <p><strong>{{ date('F j, Y') }}</strong></p> <!-- Corrected date formatting -->
                </div>
                <img src="{{ $signature }}" alt="Signature">
            </div>
        @endif

        <div class="footer">
        <img  src="/public/assets/BG-02.jpeg" style="max-width: 600px; height:auto; margin-bottom: 20px; alt="Pattern" class="img-fluid w-100">
        </div>
    </div>
</body>
</html>
