<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>JARA — Sistem Reservasi Fasilitas</title>
        <style>
            :root { color: #1e293b; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
            * { box-sizing: border-box; }
            html { scroll-behavior: smooth; }
            body { margin: 0; background: #f8fafc; }
            a { color: inherit; text-decoration: none; }
            .container { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
            .navbar { position: sticky; z-index: 5; top: 0; border-bottom: 1px solid rgba(226, 232, 240, .8); background: rgba(255, 255, 255, .86); backdrop-filter: blur(14px); }
            .nav-inner { display: flex; min-height: 72px; align-items: center; justify-content: space-between; gap: 28px; }
            .brand { color: #1e40af; font-size: 1.35rem; font-weight: 800; letter-spacing: -.04em; }
            .brand small { display: block; margin-top: 1px; color: #64748b; font-size: .65rem; font-weight: 600; letter-spacing: 0; }
            .menu { display: flex; gap: 26px; color: #475569; font-size: .93rem; font-weight: 650; }
            .menu a:hover { color: #2563eb; }
            .hero { overflow: hidden; padding: 94px 0 118px; background: radial-gradient(circle at 15% 5%, #dbeafe 0, transparent 30%), radial-gradient(circle at 85% 10%, #e0e7ff 0, transparent 28%), #f8fafc; }
            .hero-grid { display: grid; grid-template-columns: 1.15fr .85fr; align-items: center; gap: 55px; }
            .eyebrow { color: #2563eb; font-size: .8rem; font-weight: 800; letter-spacing: .11em; text-transform: uppercase; }
            h1 { max-width: 700px; margin: 14px 0 20px; color: #172554; font-size: clamp(2.55rem, 5vw, 4.4rem); line-height: 1.04; letter-spacing: -.06em; }
            .hero p { max-width: 590px; margin: 0; color: #64748b; font-size: 1.1rem; line-height: 1.75; }
            .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 29px; }
            .button { display: inline-flex; align-items: center; justify-content: center; border: 0; border-radius: 12px; padding: 12px 18px; font: inherit; font-size: .93rem; font-weight: 750; cursor: pointer; transition: transform .2s, box-shadow .2s, background .2s; }
            .button:hover { transform: translateY(-2px); }
            .button-primary { color: #fff; background: #2563eb; box-shadow: 0 10px 20px rgba(37, 99, 235, .22); }
            .button-primary:hover { background: #1d4ed8; }
            .button-secondary { color: #1e40af; background: #fff; box-shadow: 0 3px 13px rgba(30, 64, 175, .08); }
            .hero-art { position: relative; min-height: 315px; border: 1px solid rgba(255, 255, 255, .9); border-radius: 28px; background: linear-gradient(145deg, #2563eb, #3730a3); box-shadow: 0 24px 55px rgba(30, 64, 175, .25); }
            .hero-art::before, .hero-art::after { position: absolute; border-radius: 999px; background: rgba(255, 255, 255, .13); content: ""; }
            .hero-art::before { width: 230px; height: 230px; top: -65px; right: -40px; }
            .hero-art::after { width: 170px; height: 170px; bottom: -70px; left: -40px; }
            .availability { position: absolute; z-index: 1; inset: 52px 38px auto; padding: 22px; border: 1px solid rgba(255, 255, 255, .25); border-radius: 20px; color: #fff; background: rgba(255, 255, 255, .14); backdrop-filter: blur(10px); }
            .availability span { display: block; color: #bfdbfe; font-size: .8rem; font-weight: 700; }
            .availability strong { display: block; margin: 8px 0; font-size: 1.65rem; letter-spacing: -.04em; }
            .availability small { color: #dbeafe; }
            .search-wrap { position: relative; z-index: 2; margin-top: -48px; }
            .search-panel { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 13px; padding: 17px; border: 1px solid #e2e8f0; border-radius: 18px; background: rgba(255, 255, 255, .95); box-shadow: 0 18px 40px rgba(15, 23, 42, .11); }
            .field { display: grid; gap: 6px; padding: 3px 7px; }
            label { color: #475569; font-size: .76rem; font-weight: 750; }
            input, select { width: 100%; border: 0; outline: 0; color: #1e293b; background: transparent; font: inherit; font-size: .9rem; }
            .content { padding: 86px 0; }
            .section-heading { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 30px; }
            h2 { margin: 0; color: #172554; font-size: clamp(1.8rem, 3vw, 2.35rem); letter-spacing: -.045em; }
            .section-heading p { margin: 7px 0 0; color: #64748b; }
            .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
            .card { display: flex; min-height: 250px; flex-direction: column; padding: 23px; border: 1px solid #e2e8f0; border-radius: 20px; background: #fff; box-shadow: 0 7px 20px rgba(15, 23, 42, .045); transition: transform .2s, box-shadow .2s; }
            .card:hover { transform: translateY(-4px); box-shadow: 0 16px 28px rgba(15, 23, 42, .1); }
            .card-icon { display: grid; width: 48px; height: 48px; place-items: center; border-radius: 14px; color: #1d4ed8; background: #dbeafe; font-size: 1.4rem; }
            .card h3 { margin: 18px 0 7px; color: #1e293b; font-size: 1.18rem; }
            .card p { margin: 0; color: #64748b; font-size: .9rem; line-height: 1.6; }
            .status { display: inline-flex; width: fit-content; margin: 14px 0 17px; border-radius: 999px; padding: 5px 9px; color: #15803d; background: #dcfce7; font-size: .74rem; font-weight: 800; }
            .card .button { margin-top: auto; padding: 10px 13px; color: #1d4ed8; background: #eff6ff; }
            .card .button:hover { background: #dbeafe; }
            footer { padding: 28px 0; border-top: 1px solid #e2e8f0; color: #64748b; text-align: center; font-size: .86rem; }
            .toast { position: fixed; z-index: 10; right: 22px; bottom: 22px; max-width: calc(100% - 44px); transform: translateY(120px); border-radius: 12px; padding: 13px 16px; color: #fff; background: #1e293b; box-shadow: 0 12px 28px rgba(15, 23, 42, .22); opacity: 0; transition: .25s; }
            .toast.show { transform: translateY(0); opacity: 1; }
            @media (max-width: 800px) { .hero { padding-top: 65px; } .hero-grid { grid-template-columns: 1fr; } .hero-art { min-height: 230px; } .availability { inset: 38px 30px auto; } .search-panel { grid-template-columns: 1fr 1fr; } .cards { grid-template-columns: 1fr; } }
            @media (max-width: 540px) { .container { width: min(100% - 28px, 1120px); } .nav-inner { min-height: 64px; } .menu { gap: 14px; font-size: .8rem; } .hero { padding-bottom: 82px; } .search-wrap { margin-top: -35px; } .search-panel { grid-template-columns: 1fr; } .search-panel .button { min-height: 44px; } .content { padding: 64px 0; } }
        </style>
    </head>
    <body>
        <nav class="navbar">
            <div class="container nav-inner">
                <a class="brand" href="#beranda">JARA<small>Jadwal & Reservasi Fasilitas</small></a>
                <div class="menu">
                    <a href="#beranda">Beranda</a>
                    <a href="#katalog">Katalog</a>
                    <a href="#reservasi">Reservasi</a>
                    <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </nav>

        <main id="beranda">
            <section class="hero">
                <div class="container hero-grid">
                    <div>
                        <div class="eyebrow">Reservasi kampus, lebih praktis</div>
                        <h1>Reservasi Fasilitas Kampus dengan Mudah</h1>
                        <p>Cari ruang yang tersedia, pilih jadwal yang sesuai, lalu ajukan reservasi dalam beberapa langkah sederhana.</p>
                        <div class="hero-actions">
                            <a class="button button-primary" href="#katalog">Lihat Fasilitas</a>
                            <a class="button button-secondary" href="#reservasi">Cek Reservasi</a>
                        </div>
                    </div>
                    <div class="hero-art" aria-hidden="true">
                        <div class="availability"><span>JARA hari ini</span><strong>12 fasilitas tersedia</strong><small>Temukan ruang terbaik untuk kegiatanmu.</small></div>
                    </div>
                </div>
            </section>

            <div class="container search-wrap" id="reservasi">
                <form class="search-panel" id="facility-search">
                    <div class="field"><label for="facility">Cari fasilitas</label><input id="facility" type="search" placeholder="Nama fasilitas..."></div>
                    <div class="field"><label for="location">Lokasi</label><select id="location"><option>Semua lokasi</option><option>Gedung A</option><option>Gedung B</option><option>Gedung C</option></select></div>
                    <div class="field"><label for="date">Tanggal</label><input id="date" type="date"></div>
                    <button class="button button-primary" type="submit">Cari Fasilitas</button>
                </form>
            </div>

            <section class="content" id="katalog">
                <div class="container">
                    <div class="section-heading"><div><h2>Fasilitas Populer</h2><p>Temukan fasilitas yang sesuai dengan kebutuhanmu.</p></div></div>
                    <div class="cards">
                        <article class="card"><div class="card-icon">▣</div><h3>Ruang Seminar</h3><p>Gedung A · Kapasitas 100 orang</p><span class="status">Tersedia</span><button class="button" type="button" data-facility="Ruang Seminar">Lihat Detail</button></article>
                        <article class="card"><div class="card-icon">▤</div><h3>Ruang Rapat</h3><p>Gedung B · Kapasitas 20 orang</p><span class="status">Tersedia</span><button class="button" type="button" data-facility="Ruang Rapat">Lihat Detail</button></article>
                        <article class="card"><div class="card-icon">⌘</div><h3>Laboratorium Komputer</h3><p>Gedung C · Kapasitas 40 orang</p><span class="status">Tersedia</span><button class="button" type="button" data-facility="Laboratorium Komputer">Lihat Detail</button></article>
                    </div>
                </div>
            </section>
        </main>

        <footer>© 2026 JARA — Sistem Reservasi Fasilitas</footer>
        <div class="toast" id="toast" role="status" aria-live="polite"></div>
        <script>
            const toast = document.querySelector('#toast');
            const showToast = (message) => { toast.textContent = message; toast.classList.add('show'); setTimeout(() => toast.classList.remove('show'), 2600); };
            document.querySelector('#facility-search').addEventListener('submit', (event) => { event.preventDefault(); const query = document.querySelector('#facility').value.trim(); showToast(query ? `Mencari fasilitas: ${query}` : 'Pilih kriteria pencarian fasilitas.'); });
            document.querySelectorAll('[data-facility]').forEach((button) => button.addEventListener('click', () => showToast(`Detail ${button.dataset.facility} akan segera tersedia.`)));
        </script>
    </body>
</html>
