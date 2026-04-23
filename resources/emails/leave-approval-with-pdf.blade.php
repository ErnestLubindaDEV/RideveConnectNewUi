<!DOCTYPE html>
<html>
<head>
    <title>Leave Application Status</title>
</head>
<body>
    <h1>Leave Application Update</h1>
    <p>Dear {{ $leaveApplication->employee_name }},</p>
    <p>Your leave application has been {{ $leaveApplication->status }} by HR.</p>
    <p>Please find the attached PDF for the full details of your leave request.</p>
    <p>Best regards,</p>
    <p>HR Department</p>
    <p><em>RideveConnect HR System</em></p>
</body>
</html>
