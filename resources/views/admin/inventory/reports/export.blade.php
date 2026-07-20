<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        $siteSetting = \App\Models\SiteSetting::first();
        $siteName = $siteSetting ? $siteSetting->site_name : 'Corporate HRM';
        
        $siteLogo = null;
        if ($siteSetting && $siteSetting->site_logo) {
            $logoPath = ltrim($siteSetting->site_logo, '/');
            if (strpos($logoPath, 'public/') === 0) {
                $logoPath = substr($logoPath, 7);
            }
            $absoluteLogo = public_path($logoPath);
            if (file_exists($absoluteLogo)) {
                $siteLogo = $absoluteLogo;
            }
        }
        
        $siteAddress = $siteSetting ? $siteSetting->site_address : '';
    @endphp

    <div style="text-align: center; margin-bottom: 25px;">
        @if($siteLogo)
            @if(isset($isExcel) && $isExcel)
                <img src="{{ $siteLogo }}" alt="Logo" width="100" height="60">
            @else
                <img src="{{ 'data:image/' . pathinfo($siteLogo, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($siteLogo)) }}" alt="Logo" style="height: 60px; margin-bottom: 10px;">
            @endif
        @endif
        <h2>{{ $siteName }}</h2>
        @if($siteAddress)
            <div style="font-size: 12px; color: #555; margin-top: 5px;">{{ $siteAddress }}</div>
        @endif
        <h3 style="margin-top: 10px; margin-bottom: 5px; font-size: 14px; color: #333; font-weight: bold;">{{ $title }}</h3>
        <div style="font-size: 10px; color: #777;">Generated on: {{ date('Y-m-d H:i') }}</div>
        <hr style="border: 0; border-top: 1px solid #ccc; margin-top: 15px; margin-bottom: 15px;">
    </div>
    
    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    @foreach($record as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
