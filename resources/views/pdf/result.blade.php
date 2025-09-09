<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Sertifikat Keterangan Medis - {{ $data->member->name ?? '-' }}</title>

  <style>
    /* Page size A4 */
    @page { size: A4; margin: 25mm 15mm 25mm 15mm; }
    html, body { width: 210mm; font-family: "Arial", sans-serif; color: #111; }
    html, body {
      width: 100%; /* biar ikut area @page */
      font-family: "Arial", sans-serif;
      color: #111;
    }

    .container { width: 100%; padding: 0; box-sizing: border-box; }

    /* Header */
    .header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 8px;
    }
    .logo { width: 80px; height: 80px; object-fit: contain; }
    .institution {
      flex: 1;
      text-align: left;
    }
    .institution h1 { margin: 0; font-size: 16px; letter-spacing: 0.5px; font-weight: 700; }
    .institution p { margin: 0; font-size: 12px; }

    .title {
      text-align: center;
      margin: 12px 0 18px;
      font-weight: 700;
      font-size: 14px;
      text-transform: uppercase;
    }

    .meta, .recipient { width: 100%; margin-bottom: 12px; }

    /* two column meta (Nomor, Klasifikasi, Lampiran, Perihal) */
    .meta .row { display:flex; gap: 12px; margin-bottom: 6px; }
    .meta .label { width: 120px; font-weight: 600; }
    .meta .value { flex: 1; }

    /* Recipient table-like layout */
    .recipient .heading { margin: 10px 0; font-weight: 600; }
    .recipient dl { display: grid; grid-template-columns: 180px 8px 1fr; row-gap: 6px; column-gap: 8px; }
    .recipient dt { font-weight: 600; }
    .recipient dd { margin: 0; }

    /* Section content */
    .section { margin: 10px 0; font-size: 13px; line-height: 1.45; }
    .section ol { padding-left: 18px; margin: 6px 0; }
    .small { font-size: 12px; color: #333; }

    /* Hal khusus & saran: preserve line breaks */
    .pre { white-space: pre-wrap; word-wrap: break-word; }

    /* Footer / signature */
    .sign { display:flex; justify-content: flex-end; margin-top: 28px; gap: 40px; }
    .sign .box { width: 220px; text-align: center; }
    .sign .name { margin-top: 70px; font-weight:700; text-decoration: underline; }

    /* Print friendly */
    .page-break { page-break-after: always; }

    /* Emphasis badge */
    .badge { display:inline-block; padding: 3px 8px; border-radius: 4px; color: #fff; font-weight:600; font-size:12px; }
    .badge-danger { background:#d9534f; }
    .badge-success { background:#5cb85c; }

    /* table-like for details in compact form */
    .details { margin-top: 6px; border-collapse: collapse; width: 100%; }
    .details td { vertical-align: top; padding: 3px 6px; }

    td {
      white-space: normal;
      word-wrap: break-word;
      word-break: break-word;
      max-width: 100%; /* jaga agar tidak overflow */
    }

    table {
      table-layout: fixed; /* wajib supaya max-width dihitung */
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <table>
      <tr>
        <td style="width: 40%;">
          <div class="institution" style="text-align: center; line-height: 1.5;">
            <p>KEPOLISIAN NEGARA REPUBLIK INDONESIA</p>
            <p>DAERAH JAWA BARAT</p>
            <p><u>BIDANG KEDOKTERAN DAN KESEHATAN</u></p>
          </div>
        </td>
        <td style="width: 60%;">
        </td>
      </tr>
    </table>
    <br>
    <br>
    <!-- Meta info (Nomor, Klasifikasi, Lampiran, Perihal) -->
    <table style="width:100%; border-collapse: collapse; font-size:12px;">
        <tr>
            <td style="width:10%;">Nomor</td>
            <td style="width:70%;">: {{ $data->nomor ?? '-' }}</td>
        </tr>
        <tr>
            <td>Klasifikasi</td>
            <td>: {{ strtoupper($data->klasifikasi ?? '-') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>: -</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>: {{ $data->hal ?? '-' }}</td>
        </tr>
    </table>
    <br>
    <!-- Intro paragraphs -->
    <table width="100%">
      <tr>
        <td style="font-size:12px; line-height:1.5;
          white-space: normal;
          word-break: break-word;
          max-width: 170mm;">
          1. Keputusan Menteri Dalam Negeri Nomor 800.1.2.2-2441 Tahun 2025 tentang Pedoman Seleksi
          Penerimaan Calon Praja Institut Pemerintahan Dalam Negeri Tahun 2025.
        </td>
      </tr>
      <tr>
        <td style="font-size:12px; line-height:1.5; word-wrap:break-word; word-break:break-word;">
          2. Dengan ini disampaikan Sertifikat Keterangan Medis kepada:
        </td>
      </tr>
    </table>
    
    <!-- Recipient info -->
    <div class="recipient">
      <table style="width:100%; border-collapse: collapse; font-size:12px; margin-left:15px;">
        <tr>
          <td style="width:25%;">No Peserta</td>
          <td>: {{ $data->member->nomor ?? '-' }}</td>
        </tr>
        <tr>
          <td>Nama</td>
          <td>: {{ $data->member->name ?? '-' }}</td>
        </tr>
        <tr>
          <td>Jenis Kelamin</td>
          <td>:
            @if(isset($data->member->jk))
              {{ $data->member->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}
            @else
              -
            @endif
          </td>
        </tr>
        <tr>
          <td>Asal Kota/Kab</td>
          <td>: {{ $data->member->city->name ?? '-' }}</td>
        </tr>
        <tr>
          <td>No Kesehatan Tahap I</td>
          <td>: {{ $data->member->no_kesehatan_tahap1 ?? '-' }}</td>
        </tr>
        <tr>
          <td>No Kesehatan Tahap II</td>
          <td>: {{ $data->member->no_kesehatan_tahap2 ?? '-' }}</td>
        </tr>
      </table>
    </div>

    <!-- Hal khusus -->
    <div class="section">
      <div>a. Hal-hal khusus yang di temukan :</div>
      <div class="pre small">
        {!! nl2br(e($data->hal_khusus ?? '-')) !!}
      </div>
    </div>

    <!-- Nilai -->
    <div class="section">
      <div>b. Nilai status kesehatan :</div>
      <div class="pre small">
        {{ $data->nilai ?? '-' }}
      </div>
    </div>

    <!-- Saran -->
    <div class="section">
      <div>c. Saran Medis :</div>
      <div class="pre small">
        {!! nl2br(e($data->saran ?? '-')) !!}
      </div>
    </div>

    <div class="section">
      <p class="small">Demikian untuk menjadi maklum.</p>
    </div>

    <!-- Signature area -->
    <div class="sign">
      <div class="box">
        <div class="small">Ditetapkan di: {{ $place ?? 'Bandung' }}</div>
        <div class="small">Pada tanggal: {{ $data->created_at ? $data->created_at->format('d/m/Y') : now()->format('d/m/Y') }}</div>
        <div style="height:60px;"></div>
        <div class="name">{{ $signatoryName ?? 'Kepala Bidang Kedokteran & Kesehatan' }}</div>
        <div class="small">{{ $signatoryPosition ?? 'Biddokkes' }}</div>
      </div>
    </div>

  </div>
</body>
</html>
