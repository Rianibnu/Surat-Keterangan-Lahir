<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Keterangan Lahir - {{ $skl->document_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            padding: 20px 40px;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-logo {
            display: inline-block;
            vertical-align: middle;
            margin-right: 15px;
        }

        .header-logo img {
            width: 80px;
            height: 80px;
        }

        .header-text {
            display: inline-block;
            vertical-align: middle;
            text-align: center;
        }

        .hospital-name {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .hospital-address {
            font-size: 10pt;
        }

        .title {
            text-align: center;
            margin: 25px 0;
        }

        .title h1 {
            font-size: 16pt;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .title .doc-number {
            font-size: 11pt;
        }

        .content {
            margin: 20px 0;
        }

        .section-title {
            font-weight: bold;
            font-size: 12pt;
            margin: 15px 0 10px 0;
            text-decoration: underline;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table.data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        table.data-table td.label {
            width: 35%;
        }

        table.data-table td.separator {
            width: 3%;
            text-align: center;
        }

        table.data-table td.value {
            width: 62%;
        }

        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-box.right {
            float: right;
        }

        .signature-space {
            height: 80px;
            position: relative;
        }

        .signature-image {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            max-height: 70px;
            max-width: 150px;
        }

        .signature-name {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            display: inline-block;
        }

        .signature-role {
            font-size: 10pt;
            margin-top: 3px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            text-align: center;
            color: #666;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
        }

        .qr-label {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80pt;
            color: rgba(200, 200, 200, 0.2);
            z-index: -1;
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @if(!$skl->is_signed)
    <div class="watermark">DRAFT</div>
    @endif

    <!-- Header -->
    <div class="header">
        <div class="header-text">
            <div class="hospital-name">{{ $record->hospital_name ?? 'RS Maranatha' }}</div>
            <div class="hospital-address">
                Jl. Kesehatan No. 123, Kota Bandung, Jawa Barat 40115<br>
                Telp: (022) 1234567 | Email: info@rsmaranatha.co.id
            </div>
        </div>
    </div>

    <!-- Title -->
    <div class="title">
        <h1>Surat Keterangan Lahir</h1>
        <div class="doc-number">Nomor: {{ $skl->document_number }}</div>
    </div>

    <!-- Content -->
    <div class="content">
        <p style="text-align: justify; margin-bottom: 15px;">
            Yang bertanda tangan di bawah ini menerangkan bahwa pada:
        </p>

        <!-- Birth Info -->
        <table class="data-table">
            <tr>
                <td class="label">Hari/Tanggal</td>
                <td class="separator">:</td>
                <td class="value">{{ \Carbon\Carbon::parse($record->birth_date)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Pukul</td>
                <td class="separator">:</td>
                <td class="value">{{ \Carbon\Carbon::parse($record->birth_time)->format('H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="label">Tempat</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->hospital_name }}</td>
            </tr>
        </table>

        <p style="margin: 15px 0;">Telah lahir seorang bayi:</p>

        <!-- Baby Data -->
        <div class="section-title">Data Bayi</div>
        <table class="data-table">
            <tr>
                <td class="label">Nama Bayi</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $record->baby_name }}</strong></td>
            </tr>
            <tr>
                <td class="label">No. Rekam Medis</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->medical_record_no }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->gender }}</td>
            </tr>
            <tr>
                <td class="label">Anak Ke</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->child_order }}</td>
            </tr>
            <tr>
                <td class="label">Cara Persalinan</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->delivery_method }}</td>
            </tr>
            <tr>
                <td class="label">Golongan Darah</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->baby_blood_type ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Berat Badan</td>
                <td class="separator">:</td>
                <td class="value">{{ number_format($record->weight, 0, ',', '.') }} gram</td>
            </tr>
            <tr>
                <td class="label">Panjang Badan</td>
                <td class="separator">:</td>
                <td class="value">{{ number_format($record->length, 1, ',', '.') }} cm</td>
            </tr>
            <tr>
                <td class="label">Lingkar Kepala</td>
                <td class="separator">:</td>
                <td class="value">{{ number_format($record->head_circ, 1, ',', '.') }} cm</td>
            </tr>
            <tr>
                <td class="label">Lingkar Dada</td>
                <td class="separator">:</td>
                <td class="value">{{ number_format($record->chest_circ, 1, ',', '.') }} cm</td>
            </tr>
        </table>

        <!-- Mother Data -->
        <div class="section-title">Data Ibu</div>
        <table class="data-table">
            <tr>
                <td class="label">Nama Ibu</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $record->mother_name }}</strong></td>
            </tr>
            <tr>
                <td class="label">No. KTP</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->mother_ktp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->mother_address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->mother_occupation ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Golongan Darah</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->mother_blood_type ?? '-' }}</td>
            </tr>
        </table>

        <!-- Father Data -->
        <div class="section-title">Data Ayah</div>
        <table class="data-table">
            <tr>
                <td class="label">Nama Ayah</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $record->father_name }}</strong></td>
            </tr>
            <tr>
                <td class="label">No. KTP</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->father_ktp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->father_address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->father_occupation ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Golongan Darah</td>
                <td class="separator">:</td>
                <td class="value">{{ $record->father_blood_type ?? '-' }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px; text-align: justify;">
            Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan seperlunya.
        </p>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box right">
            <p>{{ $record->hospital_name }}, {{ \Carbon\Carbon::parse($skl->issue_date)->translatedFormat('d F Y') }}</p>
            <p style="margin-top: 5px;">{{ $skl->signer_role }}</p>
            <div class="signature-space">
                @if($doctor && $doctor->signature_path)
                    <img src="{{ public_path('storage/' . $doctor->signature_path) }}" alt="Signature" class="signature-image">
                @endif
            </div>
            <p class="signature-name">{{ $skl->signer_name }}</p>
            @if($doctor && $doctor->license_no)
            <p class="signature-role">SIP: {{ $doctor->license_no }}</p>
            @endif
        </div>
    </div>

    <!-- QR Code for verification -->
    @if(isset($qrCode) && $qrCode)
    <div style="clear: both; padding-top: 120px;">
        <div class="qr-code">
            <img src="{{ $qrCode }}" alt="QR Code">
            <div class="qr-label">Scan untuk verifikasi keaslian dokumen</div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        Dokumen ini dicetak secara otomatis oleh sistem pada {{ now()->translatedFormat('d F Y, H:i') }} WIB<br>
        &copy; {{ date('Y') }} {{ $record->hospital_name }} - Sistem Informasi Manajemen Rumah Sakit
    </div>
</body>
</html>
