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

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .header img {
            max-width: 600px;
            height: auto;
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 20px;
        }

        .agreement-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #2ba6db;
            margin-bottom: 20px;
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        @media print {
            .container {
                width: 100%;
                max-width: none;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
                margin: 0;
            }

            .header img, .footer img {
                width: 100%;
                max-width: 600px;
            }

            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Image -->
        <div class="header">
            <img src="/public/assets/BG-02.jpeg" alt="Pattern">
        </div>

        <!-- Agreement Title -->
        <h1 class="agreement-title">ASSET AGREEMENT FORM</h1>

        <div class="content">
            <p><strong>Date:</strong> {{ date('F j, Y') }}</p>
            <p><strong>Employee Name:</strong> {{ $asset->assigned_to }}</p>
            <p><strong>Assigned By:</strong> {{ $asset->assigned_by }}</p>
            
            <p>This agreement is made between <strong>{{ $asset->assigned_by }}</strong>, acting on behalf of the company, and <strong>{{ $asset->assigned_to }}</strong> regarding the assignment of the following asset.</p>

            <p><strong>Asset Name:</strong> {{ $asset->asset_name }}</p>
            <p><strong>Asset Number:</strong> {{ $asset->asset_number }}</p>
            <p><strong>Condition:</strong> {{ $asset->condition }}</p>
            <p><strong>Description:</strong> {{ $asset->description }}</p>
            <p><strong>Purchase Date:</strong> {{ $asset->purchase_date }}</p>
            <p><strong>Collection Date:</strong> {{ $asset->collection_date }}</p>
            <p><strong>Asset Type:</strong> {{ $asset->asset_type }}</p>
            <p><strong>Warranty Expiry:</strong> {{ $asset->warranty_expiry }}</p>

            <h3>Terms & Conditions:</h3>
            <ol>
                <li>The assigned asset is the property of the company and must be used solely for work-related purposes.</li>
                <li>The employee is responsible for maintaining the asset in good condition and reporting any damages immediately.</li>
                <li>In case of loss, theft, or damage due to negligence, the employee may be required to cover the repair or replacement costs.</li>
                <li>The asset must be returned to the company upon termination of employment or as requested by management.</li>
            </ol>

            <p>By signing below, the employee acknowledges receipt of the asset and agrees to the above terms.</p>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div>
                <p><strong>Employee Signature:</strong></p>
                @if($asset->signature)
                    <img src="{{ $asset->signature }}" alt="Signature">
                @else
                    <p>_______________________</p>
                @endif
                <p><strong>{{ $asset->assigned_to }}</strong></p>
            </div>

            <div>
                <p><strong>Authorized By:</strong></p>
                <p><strong>{{ $asset->assigned_by }}</strong></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <img src="/public/assets/BG-02.jpeg" alt="Pattern">
        </div>
    </div>
</body>
</html>
