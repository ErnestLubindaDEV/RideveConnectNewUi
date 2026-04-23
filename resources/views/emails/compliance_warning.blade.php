<div style="font-family: sans-serif; padding: 20px; border: 1px solid #eee; border-radius: 8px; max-width: 600px;">
    <h2 style="color: #d9534f; border-bottom: 2px solid #d9534f; padding-bottom: 10px;">
        <span style="font-size: 24px;">⚠️</span> Compliance Expiry Warning
    </h2>
    
    <p>Hello Admin,</p>
    <p>This is an automated reminder from <strong>Rideve Connect</strong>. The following vehicle has documentation reaching its expiry threshold:</p>
    
    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #333;">Vehicle Details</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 5px 0; color: #666;"><strong>Registration:</strong></td>
                <td><strong>{{ $compliance->vehicle->registration_number ?? $compliance->vehicle_id }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 5px 0; color: #666;"><strong>Make/Model:</strong></td>
                <td>{{ $compliance->vehicle->make ?? 'N/A' }} {{ $compliance->vehicle->model ?? '' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0; color: #666;"><strong>Insurance Expiry:</strong></td>
                <td style="color: {{ \Carbon\Carbon::parse($compliance->insurance_expiry_date)->isPast() ? '#d9534f' : '#333' }};">
                    {{ \Carbon\Carbon::parse($compliance->insurance_expiry_date)->format('d M, Y') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0; color: #666;"><strong>Road Tax Expiry:</strong></td>
                <td>{{ \Carbon\Carbon::parse($compliance->road_tax_expiry)->format('d M, Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0; color: #666;"><strong>Fitness Expiry:</strong></td>
                <td>{{ \Carbon\Carbon::parse($compliance->fitness_certificate_expiry)->format('d M, Y') }}</td>
            </tr>
        </table>
    </div>

    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/fleet') }}" style="background-color: #d9534f; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Update Compliance in Dashboard
        </a>
    </p>
    
    <hr style="border: 0; border-top: 1px solid #eee; margin-top: 30px;">
    <p style="font-size: 12px; color: #999; text-align: center;">
        Rideve Connect - Fleet Management System
    </p>
</div>