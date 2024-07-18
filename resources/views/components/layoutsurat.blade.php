<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <title>{{ $tabs }}</title>

        <style>
            .judul {
                margin: 0; /* Menghapus margin */
                padding: 0; /* Menghapus padding */
                text-align: center;
            }
            .judul-surat {
                text-decoration: underline; /* Membuat teks menjadi bergaris bawah */
                font-size: 1.2em; /* Ukuran teks judul */
                margin-bottom: 5px; /* Jarak antara judul dan nomor surat */
                line-height: 1; /* Mengatur jarak antarbaris menjadi lebih dekat */
            }
            .nomor-surat {
                font-size: 1em; /* Ukuran teks nomor surat */
                line-height: 1; /* Mengatur jarak antarbaris menjadi lebih dekat */
            }
            .justify {
                text-align: justify;
                line-height: 1.5;
            }
        </style>
        
    </head>
    <body>
        <x-kopsurat></x-kopsurat>

        <div class="judul mt-4">
            <strong class="judul-surat">{{ $judulsurat }}</strong>
            <p class="nomor-surat mt-1">No.{{ $nomorsurat }}</p>
        </div>

        <div class="mt-4">
            {{ $slot }}
        </div>

    </body>
    </html>
    
    
    <script type="text/javascript">
        window.print();
    </script>