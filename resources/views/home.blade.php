<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>JARA — Kelola Tugas Bersama</title>
        <style>
            :root { font-family: Arial, sans-serif; color: #1f2937; background: #f8fafc; }
            * { box-sizing: border-box; }
            body { margin: 0; }
            .container { width: min(960px, calc(100% - 32px)); margin: auto; }
            nav { display: flex; align-items: center; justify-content: space-between; padding: 18px 0; }
            .brand { color: #4338ca; font-size: 24px; font-weight: bold; text-decoration: none; }
            .brand small { display: block; color: #64748b; font-size: 11px; font-weight: normal; }
            .button { display: inline-block; border-radius: 8px; padding: 10px 15px; color: white; background: #4338ca; text-decoration: none; }
            .hero { padding: 75px 0; border-top: 1px solid #e2e8f0; background: white; }
            h1 { max-width: 650px; margin: 0 0 15px; color: #1e1b4b; font-size: clamp(32px, 6vw, 54px); }
            p { color: #64748b; line-height: 1.6; }
            .features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 48px 0; }
            .card { padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; background: white; }
            .card h2 { margin-top: 0; color: #312e81; font-size: 18px; }
            footer { padding: 25px 0; color: #64748b; border-top: 1px solid #e2e8f0; font-size: 14px; }
            @media (max-width: 650px) { .features { grid-template-columns: 1fr; } }
        </style>
    </head>
    <body>
        <header class="container">
            <nav>
                <a class="brand" href="{{ route('home') }}">JARA<small>Jadwal, Aktivitas, dan Rencana Anda</small></a>
                @auth
                    <a class="button" href="{{ route('lists.index') }}">Buka Daftar Tugas</a>
                @else
                    <a class="button" href="{{ route('login') }}">Login</a>
                @endauth
            </nav>
        </header>

        <main>
            <section class="hero">
                <div class="container">
                    <h1>Kelola tugas pribadi dan tim dalam satu tempat.</h1>
                    <p>Buat daftar tugas, tentukan prioritas dan deadline, ajak kolaborator, lalu pantau progress penyelesaian pekerjaan bersama.</p>
                    @auth
                        <a class="button" href="{{ route('lists.index') }}">Lihat Daftar Tugas</a>
                    @else
                        <a class="button" href="{{ route('register') }}">Mulai dengan Register</a>
                    @endauth
                </div>
            </section>

            <section class="container features">
                <article class="card"><h2>Daftar & Tugas</h2><p>Kelompokkan pekerjaan ke dalam daftar agar lebih rapi dan mudah dipantau.</p></article>
                <article class="card"><h2>Prioritas & Deadline</h2><p>Tambahkan prioritas serta tenggat waktu pada setiap tugas penting.</p></article>
                <article class="card"><h2>Kolaborasi & Progress</h2><p>Tambahkan anggota ke daftar dan lihat jumlah tugas yang telah selesai.</p></article>
            </section>
        </main>

        <footer><div class="container">© 2026 JARA — Aplikasi Kelola Tugas Pribadi dan Tim</div></footer>
    </body>
</html>
