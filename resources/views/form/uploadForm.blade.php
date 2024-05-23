<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Form</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    </head>
    <body class="font-sans antialiased">
        <form action="{{ }}" enctype="multipart/form-data" method="POST">
            @csrf 
            <input type="file" name="import_file" />
            <br />
            <button type="submit">Import File</button>
        </form>
    </body>
</html>
