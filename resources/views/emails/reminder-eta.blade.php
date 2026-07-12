<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body style="font-family:Arial,sans-serif;font-size:13px;color:#333;margin:0;padding:0;">
<div style="max-width:600px;margin:0 auto;padding:20px;">
<div style="background:#1A202C;color:#FFD700;padding:15px 20px;border-radius:8px 8px 0 0;">
<h2 style="margin:0;font-size:16px;">🔔 Reminder Backlog - Unit Mendekati Plan Date</h2>
</div>
<div style="background:#f9f9f9;padding:20px;border:1px solid #e0e0e0;">
<p>Kepada Yth. <strong>{{ $namaPlanner }}</strong>,</p>
<p>Berikut daftar unit yang mendekati Plan Date hari ini:</p>
<table cellpadding="0" cellspacing="0" border="0" style="width:100%;border-collapse:collapse;margin-top:10px;">
<thead>
<tr>
<th style="background:#1A202C;color:#FFD700;padding:8px 12px;text-align:left;font-size:12px;">No</th>
<th style="background:#1A202C;color:#FFD700;padding:8px 12px;text-align:left;font-size:12px;">Unit</th>
<th style="background:#1A202C;color:#FFD700;padding:8px 12px;text-align:left;font-size:12px;">Jumlah Part</th>
<th style="background:#1A202C;color:#FFD700;padding:8px 12px;text-align:left;font-size:12px;">Plan Date</th>
<th style="background:#1A202C;color:#FFD700;padding:8px 12px;text-align:left;font-size:12px;">Sisa Hari</th>
</tr>
</thead>
<tbody>
@php $no = 1; @endphp
@foreach($unitList as $unit => $data)
@php $hc = $data['sisaHari'] <= 3 ? '#E53E3E' : ($data['sisaHari'] <= 4 ? '#D97706' : '#38A169'); @endphp
<tr style="background:{{ $no % 2 == 0 ? '#f5f5f5' : '#fff' }};">
<td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-size:12px;">{{ $no++ }}</td>
<td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-size:12px;font-weight:bold;">{{ $unit }}</td>
<td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-size:12px;">{{ $data['count'] }} part</td>
<td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-size:12px;">{{ \Carbon\Carbon::parse($data['plan_date'])->format('d/m/Y') }}</td>
<td style="padding:8px 12px;border-bottom:1px solid #e0e0e0;font-size:12px;font-weight:bold;color:{{ $hc }};">H-{{ $data['sisaHari'] }}</td>
</tr>
@endforeach
</tbody>
</table>
<p style="margin-top:15px;font-size:12px;color:#666;">Untuk detail lengkap setiap unit, silakan cek di link berikut:</p>
<div style="background:#1A202C;padding:15px;border-radius:6px;text-align:center;margin-top:10px;">
<a href="https://opex.greater-sti.com" style="color:#FFD700;font-weight:bold;text-decoration:none;font-size:14px;">🔗 Periksa detail lengkap di OPEX Monitor</a>
</div>
</div>
<div style="background:#1A202C;color:#A0AEC0;padding:10px 20px;border-radius:0 0 8px 8px;font-size:11px;text-align:center;">
Salam, <strong style="color:#FFD700;">OPEX Monitor - Backlog Monitoring System</strong>
</div>
</div>
</body>
</html>