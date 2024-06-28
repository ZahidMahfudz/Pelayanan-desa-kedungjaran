<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<link href="css/headers.css" rel="stylesheet">
<link href="css/sidebars.css" rel="stylesheet">
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/sidebars.js"></script>

<x-header></x-header>
<x-sidebars></x-sidebars>
<div class="main-content" style="width: 100%; padding: 10px; height: 100vh; overflow-y: auto;">
    <div class="border-bottom">
        <h3>{{ $title }}</h3>
    </div>
    <div class="mt-2">
        {{ $slot }}
    </div>
</div>
</div>

