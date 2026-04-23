<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: 20px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            position: relative;
            border-top: 6px solid #2ba6db;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
        }

        .header h1 {
            font-size: 24px;
            color: #2ba6db;
            margin-top: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .content {
            margin-bottom: 20px;
        }

        .content p {
            font-size: 16px;
            margin: 10px 0;
        }

        .content p strong {
            color: #555;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            border-top: 2px solid #ddd;
            padding-top: 15px;
        }

        .signature-box {
            text-align: center;
            flex: 1;
        }

        .signature-box img {
            max-width: 150px;
            height: auto;
            border-bottom: 1px solid #333;
        }

        .footer {
            text-align: center;
            font-size: 10px;
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

        @media print {
            body {
                padding: 0;
                margin: 0;
                font-size: 12px;
            }

            .container {
                width: 100%;
                max-width: none;
                padding: 20px;
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                page-break-before: always;
            }

            .header h1 {
                font-size: 12px;
                margin-top: 0;
            }

            .signature-section {
                page-break-inside: avoid;
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
        <!-- Header Section -->
        <div class="header">
            <img src="/public/assets/Logos/2.png" alt="Company Logo">
            <h1>Leave Application</h1>
        </div>

        <!-- Employee Leave Information -->
        <div class="content">
            <p><strong>Employee Name:</strong> {{ $leaveApplication->employee_name }}</p>
            <p><strong>Leave Type:</strong> {{ $leaveApplication->leave_type }}</p>
            <p><strong>Leave Dates:</strong> From {{ $leaveApplication->start_date }} to {{ $leaveApplication->end_date }}</p>
            <p><strong>Reason for Leave:</strong> {{ $leaveApplication->reason }}</p>
            <p><strong>Supervisor:</strong> {{ $leaveApplication->supervisor_name }}</p>
            <p><strong>HR Department:</strong> Lombe Chileshe (Human Resource Manager)</p>
        </div>

        <!-- Signatures Section -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Supervisor Approval</p>
                <img src="{{ $leaveApplication->supervisor_signature }}" alt="Supervisor Signature">
                <p>Supervisor</p>
            </div>
            <div class="signature-box">
                <p>HR Approval</p>
                <img src="{{ $leaveApplication->hr_signature }}" alt="HR Signature">
                <p>Human Resource Manager</p>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>For further assistance, please contact the HR Department.</p>
        </div>
    </div>
</body>
</html>
