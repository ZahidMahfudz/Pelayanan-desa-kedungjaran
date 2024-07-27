<head>
    <title>{{ $tabs }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link href="css/headers.css" rel="stylesheet">
    <link href="css/sidebars.css" rel="stylesheet">
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/sidebars.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" 
        integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/9hb7ie6C2ltYz5Y6zF11SAx0n3zUn9Yrv1+nq2" 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     
</head>

@if (session('success'))
    <script type="text/javascript">
        $(document).ready(function() {
            Swal.fire({
                title: "{{ session('success') }}",
                icon: "success"
            });
         });
    </script>
@endif

@if (session('error'))
    <script type="text/javascript">
        $(document).ready(function() {
            Swal.fire({
                title: "Error!",
                text: "{{ session('error') }}",
                icon: "error"
                });
            });
    </script>
@endif

<div class="d-flex flex-column bd-highlight mb-3">
    <div class="p-0 bd-highlight">
        <x-header></x-header>
    </div>
    <div class="p-0 bd-highlight">
        <div class="d-flex flex-row bd-highlight mb-0">
            <div class="p-0 bd-highlight">
                <x-sidebars></x-sidebars>
            </div>
            <div class="p-3 bd-highlight d-block" style="width: 100%;">
                <div class="border-bottom">
                    <h3>{{ $title }}</h3>
                </div>
                <div class="mt-2">
                    {{ $slot }}                    
                </div>
            </div>
        </div>
    </div>
</div>




