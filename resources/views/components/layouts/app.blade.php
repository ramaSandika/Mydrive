<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyDrive - Personal Storage</title>
    <style>
        :root {
            --primary: #2563eb;
            --bg: #f8fafc;
            --text: #1e293b;
        }
        body { font-family: 'Segoe UI', sans-serif; background-color: var(--bg); color: var(--text); margin: 0; padding: 0; }
        
        /* Header */
        header { background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .logo { font-weight: bold; font-size: 1.5rem; color: var(--primary); }
        
        /* Main Layout */
        .container { max-width: 900px; margin: 2rem auto; padding: 2rem; }
        .hero { text-align: center; margin-bottom: 3rem; }
        
        /* Upload Area */
        .upload-card { background: white; padding: 2rem; border-radius: 12px; border: 2px dashed #cbd5e1; text-align: center; transition: 0.3s; }
        .upload-card:hover { border-color: var(--primary); }
        .btn-upload { background: var(--primary); color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; margin-top: 1rem; }
        
        /* File List Preview */
        .file-list { margin-top: 3rem; background: white; border-radius: 8px; padding: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    </style>
    <!-- Memuat style bawaan Livewire -->
    @livewireStyles
</head>
<body>

<header>
    <div class="logo">MyDrive</div>
    <div>Profil Pengguna</div>
</header>

<div class="container">
    <!-- Di sinilah komponen DriveManager akan dirender secara dinamis -->
    {{ $slot }}
</div>

<!-- Memuat script bawaan Livewire -->
@livewireScripts
</body>
</html>