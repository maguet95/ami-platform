<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sesion expirada — AMI</title>
    <link rel="icon" href="{{ asset('images/logos/isotipo.jpg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-950 text-surface-100 antialiased flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <p class="text-8xl font-bold text-surface-700">419</p>
        <h1 class="mt-4 text-2xl font-bold text-white">Sesion expirada</h1>
        <p class="mt-2 text-sm text-surface-400">Tu sesion ha expirado por inactividad. Recarga la pagina para continuar.</p>
        <div class="mt-8 flex items-center justify-center gap-3">
            <button onclick="location.reload()"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-ami-500 hover:bg-ami-600 rounded-xl transition-all duration-200 shadow-lg shadow-ami-500/25">
                Recargar pagina
            </button>
        </div>
    </div>
</body>
</html>
