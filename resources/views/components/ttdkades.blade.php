<head>
    <style>
        .signatures-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 50px;
        }
        .signature-container,
        .additional-signature {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .signature-container p,
        .additional-signature p {
            margin: 0;
        }
        .signature-container img,
        .additional-signature img {
            max-width: 200px;
            height: auto;
            margin: 0;
        }
        .signature-container p:last-of-type {
            margin-top: -5px; /* Sesuaikan margin atas pada paragraf untuk bersenggolan dengan gambar */
        }
        /* .date-container {
            text-align: right;
            margin-bottom: 5px;
            margin-right: 20px;
        } */
    </style>
</head>

<body>
    <div class="container">
        <div class="signatures-wrapper">
            <div class="additional-signature" style="margin-top: 25px; margin-right:170px; width: 200px;">
                {{ $ttdlain }}
            </div>
            <div class="signature-container" style="text-align: center; width: 350px;">
                <p>Kedungjaran, {{ $tanggalsurat }}</p>
                <p style="margin-bottom: -30px; ">Kepala Desa Kedungjaran</p>
                <img src="/image/{{ $nama_gambar }}" alt="ttd kades" height="75" style="margin-bottom: -45px;">
                <p style="text-decoration: underline; margin-top: -5px"><strong>{{ $nama_kades }}</strong></p>
            </div>
        </div>
    </div>
</body>

