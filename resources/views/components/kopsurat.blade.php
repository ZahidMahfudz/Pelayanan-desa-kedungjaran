<style>
    body {
        font-family: 'Times New Roman', Times, serif; 
    }
    header {
        text-align: center;
    }
    header p {
        margin: 0;
        padding: 0;
        font-size: 20px; /* Ganti nilai ini sesuai kebutuhan */
        font-weight: bold;
        line-height: 1;
    }
    .desa{
        font-size: 30px;
    }
    .address {
        font-size: 14px; /* Ukuran font kecil */
        font-weight: normal; /* Font tidak tebal */
    }
    .logo {
        width: 75px; /* Ganti nilai ini sesuai kebutuhan */
        height: auto; /* Menjaga aspek rasio */
        margin-left: 10px;
    }
    .content{
        margin-left: 85px;
    }
    hr {
    border: 1px solid black; /* Mengubah hr menjadi garis tebal dengan ketebalan 1px dan warna hitam */
    margin: 10px 0; /* Memberikan margin atas dan bawah 20px untuk memisahkan dari konten di atas dan di bawah */
    }
</style>

<div class="d-flex flex-row bd-highlight mb-0">
    <div class="p-0 bd-highlight">
        <img src="{{ asset('image/logo-pekalongan.png') }}" alt="logo pekalongan" class="logo">
    </div>
    <div class="p-0 bd-highlight content">
        <header>
            <p>PEMERINTAH KABUPATEN PEKALONGAN</p>
            <p>KECAMATAN SRAGI</p>
            <p class="desa">DESA KEDUNGJARAN</p>
            <p class="address">Alamat  : Jl.Raya Sragi-Bojong Km 2 No. 3 Pekalongan 51115</p>
        </header>
    </div>
</div>
<hr>