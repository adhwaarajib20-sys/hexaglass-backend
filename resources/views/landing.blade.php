<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilir Migas — Platform Monitoring Operasional Digital</title>
    <meta name="description" content="Platform digital untuk monitoring operasional, distribusi, infrastruktur, dan pengelolaan aset Hilir Migas secara real-time.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-deep: #0D5C2E;
            --green-mid:  #15803D;
            --green-light:#22C55E;
            --orange-deep:#C2410C;
            --orange-mid: #EA580C;
            --orange-light:#FB923C;
            --dark-base:  #080E1A;
            --dark-card:  #0F1825;
            --dark-surface:#141F30;
            --dark-border: rgba(255,255,255,0.06);
            --dark-border-hover: rgba(255,255,255,0.12);
            --text-primary: #F1F5F9;
            --text-secondary: #94A3B8;
            --text-muted: #475569;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--dark-base); }
        ::-webkit-scrollbar-thumb { background: var(--green-mid); border-radius: 10px; }

        html { font-size: 16px; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--dark-base);
            color: var(--text-secondary);
            overflow-x: hidden;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }
        h1,h2,h3,h4,h5 { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-primary); line-height: 1.2; }

        /* ── UTILITIES ── */
        .text-gradient {
            background: linear-gradient(130deg, var(--green-light) 0%, var(--orange-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(130deg, var(--green-mid), var(--orange-mid));
            color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700; font-size: 0.9rem; letter-spacing: 0.02em;
            padding: 0.75rem 1.75rem; border-radius: 50px;
            border: none; cursor: pointer; text-decoration: none;
            transition: transform 0.25s, box-shadow 0.25s;
            box-shadow: 0 4px 24px rgba(234,88,12,0.25);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(234,88,12,0.35); }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--text-primary); font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600; font-size: 0.9rem;
            padding: 0.72rem 1.75rem; border-radius: 50px;
            border: 1px solid var(--dark-border-hover);
            background: rgba(255,255,255,0.04);
            cursor: pointer; text-decoration: none;
            backdrop-filter: blur(8px);
            transition: background 0.25s, border-color 0.25s, transform 0.25s;
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.2); transform: translateY(-2px); }

        .btn-login {
            display: inline-flex; align-items: center; gap: 7px;
            color: var(--text-primary); font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600; font-size: 0.85rem;
            padding: 0.6rem 1.35rem; border-radius: 50px;
            border: 1px solid rgba(21,128,61,0.55);
            background: rgba(21,128,61,0.08);
            cursor: pointer; text-decoration: none;
            transition: background 0.25s, border-color 0.25s;
        }
        .btn-login:hover { background: rgba(21,128,61,0.18); border-color: rgba(34,197,94,0.6); }

        .logo-ring {
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%;
            border: 2px solid transparent;
            background:
                linear-gradient(var(--dark-card), var(--dark-card)) padding-box,
                linear-gradient(135deg, var(--green-mid), var(--orange-mid)) border-box;
            padding: 4px;
            flex-shrink: 0;
        }

        /* ── NAVBAR ── */
        nav.main-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(8,14,26,0.75);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border-bottom: 1px solid var(--dark-border);
            transition: background 0.3s;
        }
        .nav-inner {
            max-width: 1280px; margin: 0 auto;
            padding: 0 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            height: 72px; gap: 2rem;
        }
        .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-brand span { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.15rem; color: var(--text-primary); letter-spacing: -0.01em; }
        .nav-links { display: flex; align-items: center; gap: 2rem; }
        .nav-links a { color: var(--text-secondary); font-size: 0.88rem; font-weight: 500; text-decoration: none; transition: color 0.2s; white-space: nowrap; }
        .nav-links a:hover { color: var(--text-primary); }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }

        .hamburger { display: none; background: none; border: none; cursor: pointer; color: var(--text-primary); padding: 0.25rem; }

        /* ── HERO ── */
        .hero {
            position: relative; min-height: 100svh;
            display: flex; align-items: center;
            overflow: hidden; padding-top: 72px;
        }
        .hero-bg {
            position: absolute; inset: 0; z-index: 0;
        }
        .hero-bg img { width: 100%; height: 100%; object-fit: cover; }
        .hero-bg::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg,
                rgba(8,14,26,0.85) 0%,
                rgba(8,14,26,0.55) 40%,
                rgba(8,14,26,0.80) 100%);
        }
        /* Decorative orbs */
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); pointer-events: none; z-index: 1;
        }
        .orb-green { width: 520px; height: 520px; background: rgba(21,128,61,0.18); top: -120px; left: -80px; }
        .orb-orange { width: 400px; height: 400px; background: rgba(234,88,12,0.14); bottom: -80px; right: -60px; }

        .hero-content {
            position: relative; z-index: 2;
            max-width: 1280px; margin: 0 auto;
            padding: 5rem 1.5rem 4rem;
            width: 100%;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 50px; padding: 0.4rem 1rem;
            font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.75);
            letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 1.75rem;
        }
        .pulse-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--green-light); animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(0.85)} }

        .hero h1 {
            font-size: clamp(2.8rem, 6vw, 5.5rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.05;
            margin-bottom: 1.5rem;
            max-width: 820px;
        }
        .hero p.lead {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: rgba(241,245,249,0.72);
            max-width: 560px; margin-bottom: 2.5rem;
            line-height: 1.8;
        }
        .hero-cta { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }

        .scroll-hint {
            position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%);
            z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 6px;
        }
        .scroll-hint span { font-size: 0.7rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted); }
        .scroll-line { width: 1px; height: 40px; background: linear-gradient(to bottom, var(--green-mid), transparent); animation: scrollDrop 2s ease-in-out infinite; }
        @keyframes scrollDrop { 0%{transform:scaleY(0);transform-origin:top} 50%{transform:scaleY(1);transform-origin:top} 51%{transform:scaleY(1);transform-origin:bottom} 100%{transform:scaleY(0);transform-origin:bottom} }

        /* ── SECTIONS ── */
        section { padding: 6rem 0; }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }

        .section-label {
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.14em;
            text-transform: uppercase; margin-bottom: 0.75rem; display: block;
        }
        .label-green { color: var(--green-light); }
        .label-orange { color: var(--orange-light); }

        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.75rem);
            font-weight: 800; letter-spacing: -0.02em;
            margin-bottom: 1rem; line-height: 1.15;
        }
        .section-sub { font-size: 1rem; color: var(--text-secondary); max-width: 540px; line-height: 1.75; }

        /* ── TENTANG ── */
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
        .about-img-wrap {
            position: relative; border-radius: 20px; overflow: hidden;
        }
        .about-img-wrap::before {
            content: ''; position: absolute; inset: -1px; border-radius: 21px;
            background: linear-gradient(135deg, rgba(21,128,61,0.6) 0%, rgba(234,88,12,0.4) 100%);
            z-index: 0; pointer-events: none;
        }
        .about-img-wrap img { display: block; width: 100%; border-radius: 20px; position: relative; z-index: 1; transition: transform 0.6s; }
        .about-img-wrap:hover img { transform: scale(1.04); }
        .check-list { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1.75rem; }
        .check-list li { display: flex; align-items: center; gap: 10px; font-size: 0.95rem; color: rgba(241,245,249,0.8); }
        .check-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .dot-green { background: var(--green-light); }
        .dot-orange { background: var(--orange-light); }

        /* ── FITUR ── */
        .fitur-bg { background: var(--dark-card); }
        .fitur-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 3.5rem; }

        .feature-card {
            padding: 2rem 1.75rem;
            border-radius: 18px;
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            transition: border-color 0.3s, transform 0.3s;
            position: relative; overflow: hidden;
        }
        .feature-card::before {
            content: ''; position: absolute; inset: 0; border-radius: 18px;
            opacity: 0; transition: opacity 0.4s;
            background: radial-gradient(circle at 30% 30%, rgba(21,128,61,0.08), transparent 70%);
        }
        .feature-card:hover::before { opacity: 1; }
        .feature-card:hover { border-color: rgba(21,128,61,0.35); transform: translateY(-4px); }

        .feature-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
        }
        .feature-icon svg { width: 26px; height: 26px; color: #fff; }
        .fi-green { background: linear-gradient(135deg, var(--green-deep), var(--green-mid)); }
        .fi-orange { background: linear-gradient(135deg, var(--orange-deep), var(--orange-mid)); }
        .fi-blue { background: linear-gradient(135deg, #1e3a5f, #3b82f6); }
        .fi-purple { background: linear-gradient(135deg, #4c1d95, #8b5cf6); }
        .fi-pink { background: linear-gradient(135deg, #831843, #ec4899); }
        .fi-teal { background: linear-gradient(135deg, #164e63, #06b6d4); }

        .feature-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary); }
        .feature-card p { font-size: 0.88rem; line-height: 1.7; }

        /* ── OPERASIONAL ── */
        .op-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }

        /* ── STATS ── */
        .stats-bg { background: var(--dark-card); }
        .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 2rem; }
        .stat-item { text-align: center; }
        .stat-num { font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; margin-bottom: 0.4rem; }
        .stat-label { font-size: 0.88rem; color: var(--text-secondary); }

        /* ── KEUNGGULAN ── */
        .adv-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.25rem; margin-top: 3rem; }
        .adv-card {
            padding: 1.75rem 1.5rem;
            border-radius: 16px;
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            transition: border-color 0.3s;
        }
        .adv-card:hover { border-color: var(--dark-border-hover); }
        .adv-icon { margin-bottom: 1rem; }
        .adv-icon svg { width: 28px; height: 28px; }
        .adv-card h3 { font-size: 0.95rem; font-weight: 700; margin-bottom: 0.35rem; }
        .adv-card p { font-size: 0.82rem; line-height: 1.65; }

        /* ── TESTIMONI ── */
        .testi-bg { background: var(--dark-card); }
        .testi-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; margin-top: 3.5rem; }
        .testi-card {
            padding: 2rem 1.75rem;
            border-radius: 18px;
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            transition: border-color 0.3s;
        }
        .testi-card:hover { border-color: rgba(21,128,61,0.3); }
        .testi-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans',sans-serif; font-weight: 700; font-size: 1rem; color: #fff;
            flex-shrink: 0;
        }
        .testi-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1rem; }
        .testi-name { font-family: 'Plus Jakarta Sans',sans-serif; font-size: 0.9rem; font-weight: 700; color: var(--text-primary); }
        .testi-role { font-size: 0.75rem; color: var(--text-muted); }
        .testi-card p { font-size: 0.88rem; line-height: 1.75; font-style: italic; }

        /* ── DOWNLOAD CTA ── */
        .dl-section {
            position: relative; overflow: hidden;
            background: linear-gradient(160deg, rgba(13,92,46,0.15) 0%, rgba(8,14,26,1) 50%, rgba(194,65,12,0.12) 100%);
        }
        .dl-section::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 60% 50% at 20% 30%, rgba(21,128,61,0.12), transparent),
                        radial-gradient(ellipse 50% 50% at 80% 70%, rgba(234,88,12,0.1), transparent);
        }
        .dl-content { position: relative; z-index: 1; }
        .dl-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 3rem 0 2rem; }
        .dl-card {
            padding: 2rem 1.75rem; border-radius: 20px;
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
        }
        .dl-card-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1rem;
        }
        .dl-card-icon svg { width: 26px; height: 26px; color: #fff; }
        .dl-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
        .dl-card p { font-size: 0.88rem; margin-bottom: 1.25rem; line-height: 1.7; }

        .qr-wrap { background: #fff; border-radius: 12px; padding: 1rem; display: inline-block; margin-bottom: 0.75rem; }
        .qr-wrap img { display: block; width: 140px; height: 140px; }

        .sys-req { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.5rem; margin-top: 2rem; }
        .req-item { display: flex; align-items: flex-start; gap: 10px; }
        .req-icon svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 2px; color: var(--green-light); }
        .req-item strong { display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-primary); margin-bottom: 2px; }
        .req-item span { font-size: 0.78rem; color: var(--text-muted); }

        /* ── GALERI ── */
        .galeri-bg { background: var(--dark-base); }
        .galeri-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; margin-top: 3rem; }
        .galeri-item {
            border-radius: 14px; overflow: hidden; cursor: pointer;
            aspect-ratio: 4/3; position: relative;
        }
        .galeri-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .galeri-item::after {
            content: ''; position: absolute; inset: 0;
            background: rgba(0,0,0,0); transition: background 0.3s;
        }
        .galeri-item:hover img { transform: scale(1.08); }
        .galeri-item:hover::after { background: rgba(0,0,0,0.35); }
        .galeri-item:nth-child(1) { grid-column: span 2; grid-row: span 2; aspect-ratio: auto; }

        /* ── FOOTER ── */
        footer {
            background: var(--dark-card);
            border-top: 1px solid var(--dark-border);
            padding: 4rem 0 2rem;
        }
        .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 2.5rem; margin-bottom: 3rem; }
        .footer-brand p { font-size: 0.85rem; line-height: 1.75; margin-top: 0.75rem; }
        .footer-col h4 { font-family: 'Plus Jakarta Sans',sans-serif; font-size: 0.88rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem; }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; }
        .footer-col ul li a, .footer-col ul li span { font-size: 0.85rem; color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .footer-col ul li a:hover { color: var(--text-primary); }
        .footer-bottom { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding-top: 2rem; border-top: 1px solid var(--dark-border); }
        .footer-bottom p { font-size: 0.8rem; color: var(--text-muted); }
        .footer-bottom-links { display: flex; gap: 1.5rem; }
        .footer-bottom-links a { font-size: 0.8rem; color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .footer-bottom-links a:hover { color: var(--text-primary); }

        .social-links { display: flex; gap: 0.75rem; margin-top: 1.25rem; }
        .social-link {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            transition: border-color 0.2s, background 0.2s;
        }
        .social-link:hover { background: rgba(21,128,61,0.15); border-color: rgba(21,128,61,0.4); }
        .social-link svg { width: 16px; height: 16px; fill: var(--text-muted); }
        .social-link:hover svg { fill: var(--green-light); }

        /* ── LOGIN MODAL ── */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(8,14,26,0.85);
            backdrop-filter: blur(16px);
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
        }
        .modal-box {
            background: var(--dark-card);
            border: 1px solid rgba(21,128,61,0.25);
            border-radius: 24px; padding: 2.5rem;
            width: 100%; max-width: 420px;
            position: relative;
            box-shadow: 0 32px 80px rgba(0,0,0,0.6), 0 0 0 1px rgba(21,128,61,0.1);
        }
        .modal-close {
            position: absolute; top: 1.25rem; right: 1.25rem;
            background: rgba(255,255,255,0.05); border: 1px solid var(--dark-border);
            border-radius: 50%; width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-secondary); transition: all 0.2s;
        }
        .modal-close:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }
        .modal-logo { display: flex; align-items: center; justify-content: center; margin-bottom: 1.75rem; }
        .modal-title { text-align: center; font-size: 1.5rem; font-weight: 800; margin-bottom: 0.4rem; }
        .modal-sub { text-align: center; font-size: 0.875rem; color: var(--text-muted); margin-bottom: 2rem; }

        .form-group { margin-bottom: 1.1rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.45rem; letter-spacing: 0.03em; }
        .form-input {
            width: 100%; background: var(--dark-surface);
            border: 1px solid var(--dark-border-hover);
            border-radius: 12px; padding: 0.75rem 1rem;
            color: var(--text-primary); font-family: 'DM Sans', sans-serif; font-size: 0.9rem;
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input::placeholder { color: var(--text-muted); }
        .form-input:focus { border-color: rgba(21,128,61,0.6); box-shadow: 0 0 0 3px rgba(21,128,61,0.12); }

        .form-forgot { display: block; text-align: right; font-size: 0.78rem; color: var(--green-light); text-decoration: none; margin-top: 0.4rem; opacity: 0.85; }
        .form-forgot:hover { opacity: 1; }
        .btn-submit {
            width: 100%; padding: 0.85rem;
            background: linear-gradient(130deg, var(--green-mid), var(--orange-mid));
            border: none; border-radius: 12px; cursor: pointer;
            color: #fff; font-family: 'Plus Jakarta Sans',sans-serif; font-weight: 700; font-size: 0.95rem;
            margin-top: 0.5rem; transition: opacity 0.2s, transform 0.2s;
        }
        .btn-submit:hover { opacity: 0.92; transform: translateY(-1px); }

        .divider { display: flex; align-items: center; gap: 0.75rem; margin: 1.25rem 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--dark-border-hover); }
        .divider span { font-size: 0.75rem; color: var(--text-muted); white-space: nowrap; }

        /* ── LIGHTBOX ── */
        .lightbox {
            position: fixed; inset: 0; z-index: 300;
            background: rgba(0,0,0,0.92); backdrop-filter: blur(12px);
            display: flex; align-items: center; justify-content: center; padding: 1rem;
        }
        .lightbox img { max-width: 90vw; max-height: 88vh; border-radius: 12px; object-fit: contain; }

        /* ── MOBILE ── */
        @media (max-width: 900px) {
            .nav-links, .nav-actions .btn-primary { display: none; }
            .hamburger { display: flex; }
            .about-grid, .op-grid { grid-template-columns: 1fr; gap: 2.5rem; }
            .op-grid .order-fix { order: -1; }
            .fitur-grid { grid-template-columns: repeat(2,1fr); }
            .stats-grid, .adv-grid { grid-template-columns: repeat(2,1fr); }
            .testi-grid, .dl-cards { grid-template-columns: 1fr; }
            .galeri-grid { grid-template-columns: repeat(2,1fr); }
            .galeri-item:nth-child(1) { grid-column: span 2; grid-row: span 1; aspect-ratio: 4/3; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .sys-req { grid-template-columns: repeat(2,1fr); }
            .footer-brand { grid-column: span 2; }
        }
        @media (max-width: 560px) {
            .fitur-grid, .galeri-grid, .adv-grid { grid-template-columns: 1fr; }
            .galeri-item:nth-child(1) { grid-column: span 1; }
            .stats-grid { grid-template-columns: repeat(2,1fr); }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-brand { grid-column: span 1; }
            .dl-cards { grid-template-columns: 1fr; }
            .sys-req { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>

<body x-data="{
    mobileMenu: false,
    loginOpen: false,
    lightboxOpen: false,
    lightboxSrc: ''
}">

<!-- ===== NAVBAR ===== -->
<nav class="main-nav">
    <div class="nav-inner">
        <a href="#beranda" class="nav-brand">
            <div class="logo-ring">
                <img src="img/logo.png" alt="Hilir Migas" style="height:38px;width:38px;border-radius:50%;object-fit:cover;">
            </div>
            <span>Hilir Migas</span>
        </a>

        <div class="nav-links">
            <a href="#beranda">Beranda</a>
            <a href="#tentang">Tentang</a>
            <a href="#fitur">Fitur</a>
            <a href="#operasional">Operasional</a>
            <a href="#galeri">Galeri</a>
            <a href="/hakcipta" style="color:var(--green-light);">Hak Cipta</a>
        </div>

        <div class="nav-actions">
            <a href="#" class="btn-login" @click.prevent="loginOpen = true">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Masuk
            </a>
            <a href="https://github.com/adhwaarajib20-sys/hexaglass-backend/releases/download/v.1.0.0/Migas.apk" download class="btn-primary" style="display:none;" id="nav-dl-btn">
                Download APK
            </a>
            <button class="hamburger" @click="mobileMenu = !mobileMenu" aria-label="Menu">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-transition style="background:var(--dark-card);border-top:1px solid var(--dark-border);padding:1rem 1.5rem 1.5rem;display:flex;flex-direction:column;gap:0.75rem;">
        <a href="#beranda" @click="mobileMenu=false" style="color:var(--text-secondary);text-decoration:none;font-size:0.92rem;font-weight:500;padding:0.5rem 0;border-bottom:1px solid var(--dark-border);">Beranda</a>
        <a href="#tentang" @click="mobileMenu=false" style="color:var(--text-secondary);text-decoration:none;font-size:0.92rem;font-weight:500;padding:0.5rem 0;border-bottom:1px solid var(--dark-border);">Tentang</a>
        <a href="#fitur" @click="mobileMenu=false" style="color:var(--text-secondary);text-decoration:none;font-size:0.92rem;font-weight:500;padding:0.5rem 0;border-bottom:1px solid var(--dark-border);">Fitur</a>
        <a href="#operasional" @click="mobileMenu=false" style="color:var(--text-secondary);text-decoration:none;font-size:0.92rem;font-weight:500;padding:0.5rem 0;border-bottom:1px solid var(--dark-border);">Operasional</a>
        <a href="#galeri" @click="mobileMenu=false" style="color:var(--text-secondary);text-decoration:none;font-size:0.92rem;font-weight:500;padding:0.5rem 0;border-bottom:1px solid var(--dark-border);">Galeri</a>
        <a href="/hakcipta" @click="mobileMenu=false" style="color:var(--green-light);text-decoration:none;font-size:0.92rem;font-weight:500;padding:0.5rem 0;border-bottom:1px solid var(--dark-border);">Hak Cipta</a>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;padding-top:0.5rem;">
            <a href="#" class="btn-login" @click.prevent="loginOpen=true;mobileMenu=false">Masuk</a>
            <a href="https://github.com/adhwaarajib20-sys/hexaglass-backend/releases/download/v.1.0.0/Migas.apk" download class="btn-primary">Download APK</a>
        </div>
    </div>
</nav>


<!-- ===== HERO ===== -->
<section id="beranda" class="hero">
    <div class="hero-bg">
        <img src="img/hilirmigas.jpg.jpeg" alt="Hilir Migas Operasional">
    </div>
    <div class="orb orb-green"></div>
    <div class="orb orb-orange"></div>

    <div class="hero-content">
        <div data-aos="fade-up" data-aos-duration="900">
            <div class="hero-badge">
                <span class="pulse-dot"></span>
                Sistem Terintegrasi — Real-Time
            </div>
            <h1>
                Hilir Migas<br>
                <span class="text-gradient">Monitoring System</span>
            </h1>
            <p class="lead">Platform digital untuk monitoring operasional, distribusi, infrastruktur, aset, dan fasilitas CNG secara real-time, terintegrasi, dan modern.</p>
            <div class="hero-cta">
                <a href="https://github.com/adhwaarajib20-sys/hexaglass-backend/releases/download/v.1.0.0/Migas.apk" download class="btn-primary" style="font-size:1rem;padding:0.9rem 2rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Migas.apk
                </a>
                <button onclick="document.getElementById('tentang').scrollIntoView({behavior:'smooth'})" class="btn-ghost" style="font-size:1rem;padding:0.88rem 2rem;">
                    Pelajari Lebih Lanjut
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div class="scroll-hint">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
</section>


<!-- ===== TENTANG ===== -->
<section id="tentang" style="background:var(--dark-base);">
    <div class="container">
        <div class="about-grid">
            <div data-aos="fade-right" data-aos-duration="900">
                <span class="section-label label-orange">Tentang Aplikasi</span>
                <h2 class="section-title">Platform Digital <span class="text-gradient">Operasional Hilir Migas</span></h2>
                <p class="section-sub">Hilir Migas membantu perusahaan melakukan monitoring fasilitas CNG, infrastruktur perpipaan, aset, dan operasional lapangan secara efisien dan transparan.</p>
                <p style="font-size:0.92rem;line-height:1.8;margin-top:1rem;">Dengan teknologi <strong style="color:var(--text-primary);">real-time monitoring</strong> dan dashboard interaktif, kami memberikan solusi terpadu untuk mengelola seluruh aspek operasional Hilir Migas dengan mudah.</p>
                <ul class="check-list">
                    <li><span class="check-dot dot-green"></span> Platform terintegrasi untuk semua kebutuhan monitoring</li>
                    <li><span class="check-dot dot-orange"></span> Update data real-time setiap saat</li>
                    <li><span class="check-dot dot-green"></span> Keamanan data tingkat enterprise</li>
                    <li><span class="check-dot dot-orange"></span> Antarmuka mobile-first yang responsif</li>
                </ul>
            </div>
            <div data-aos="fade-left" data-aos-duration="900" class="about-img-wrap">
                <img src="img/parkir.jpg.jpeg" alt="Fasilitas Hilir Migas">
            </div>
        </div>
    </div>
</section>


<!-- ===== FITUR ===== -->
<section id="fitur" class="fitur-bg">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="section-label label-green">Fitur Premium</span>
            <h2 class="section-title">Fitur Unggulan Platform</h2>
            <p class="section-sub" style="margin:0 auto;">Berbagai fitur canggih untuk mendukung operasional Hilir Migas secara real-time</p>
        </div>

        <div class="fitur-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-icon fi-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3>Monitoring Real-Time</h3>
                <p>Pantau kondisi fasilitas secara langsung dengan data yang diperbarui setiap detik.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="80">
                <div class="feature-icon fi-orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3>Dashboard Interaktif</h3>
                <p>Visualisasi data operasional dengan grafik dan statistik yang informatif dan mudah dipahami.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="160">
                <div class="feature-icon fi-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3>Manajemen Aset</h3>
                <p>Kelola aset perusahaan dengan mudah dan terorganisir dalam satu platform terpusat.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="240">
                <div class="feature-icon fi-purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3>Pelaporan Digital</h3>
                <p>Pelaporan cepat dan terintegrasi dengan format yang profesional dan lengkap.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="320">
                <div class="feature-icon fi-pink">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <h3>Manajemen Pengguna</h3>
                <p>Role-based access control untuk keamanan dan kontrol data perusahaan secara maksimal.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon fi-teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                </div>
                <h3>Mobile Friendly</h3>
                <p>Akses sistem dari perangkat mobile kapan saja dan di mana saja dengan performa optimal.</p>
            </div>
        </div>
    </div>
</section>


<!-- ===== OPERASIONAL ===== -->
<section id="operasional" style="background:var(--dark-base);">
    <div class="container">
        <div class="op-grid">
            <div data-aos="fade-right" data-aos-duration="900" class="about-img-wrap order-fix">
                <img src="img/truk.jpg.jpeg" alt="Distribusi Hilir Migas">
            </div>
            <div data-aos="fade-left" data-aos-duration="900">
                <span class="section-label label-orange">Operasional</span>
                <h2 class="section-title">Distribusi & <span class="text-gradient">Transportasi</span></h2>
                <p class="section-sub">Kelola seluruh aktivitas distribusi, transportasi, dan operasional lapangan dengan pemantauan real-time yang memastikan efisiensi rute dan keamanan pengiriman.</p>
                <ul class="check-list">
                    <li><span class="check-dot dot-green"></span> Pemantauan distribusi real-time</li>
                    <li><span class="check-dot dot-green"></span> Manajemen transportasi terintegrasi</li>
                    <li><span class="check-dot dot-green"></span> Optimalisasi rute operasional</li>
                </ul>
            </div>
        </div>
    </div>
</section>


<!-- ===== MONITORING & MAINTENANCE ===== -->
<section style="background:var(--dark-card);">
    <div class="container">
        <div class="op-grid">
            <div data-aos="fade-right" data-aos-duration="900">
                <span class="section-label label-green">Maintenance</span>
                <h2 class="section-title">Monitoring & <span class="text-gradient">Inspeksi Fasilitas</span></h2>
                <p class="section-sub">Dukung aktivitas monitoring, inspeksi, dan maintenance fasilitas CNG secara digital. Sistem mencatat jadwal perawatan, kondisi aset, dan tindakan perbaikan secara terstruktur.</p>
                <ul class="check-list">
                    <li><span class="check-dot dot-orange"></span> Jadwal inspeksi terintegrasi</li>
                    <li><span class="check-dot dot-orange"></span> Laporan kondisi aset real-time</li>
                    <li><span class="check-dot dot-orange"></span> Tindakan perbaikan terdokumentasi</li>
                </ul>
            </div>
            <div data-aos="fade-left" data-aos-duration="900" class="about-img-wrap">
                <img src="img/teknisi.jpg.jpeg" alt="Teknisi Monitoring">
            </div>
        </div>
    </div>
</section>


<!-- ===== GALERI ===== -->
<section id="galeri" class="galeri-bg">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="section-label label-orange">Portofolio</span>
            <h2 class="section-title">Galeri Operasional</h2>
            <p class="section-sub" style="margin:0 auto;">Dokumentasi visual dari berbagai fasilitas dan aktivitas operasional Hilir Migas</p>
        </div>

        <div class="galeri-grid">
            <div class="galeri-item" data-aos="zoom-in" data-aos-delay="0" @click="lightboxOpen=true;lightboxSrc='img/truk.jpg.jpeg'">
                <img src="img/truk.jpg.jpeg" alt="Galeri">
            </div>
            <div class="galeri-item" data-aos="zoom-in" data-aos-delay="60" @click="lightboxOpen=true;lightboxSrc='img/teknisi.jpg.jpeg'">
                <img src="img/teknisi.jpg.jpeg" alt="Galeri">
            </div>
            <div class="galeri-item" data-aos="zoom-in" data-aos-delay="120" @click="lightboxOpen=true;lightboxSrc='img/parkir.jpg.jpeg'">
                <img src="img/parkir.jpg.jpeg" alt="Galeri">
            </div>
            <div class="galeri-item" data-aos="zoom-in" data-aos-delay="180" @click="lightboxOpen=true;lightboxSrc='img/hilirmigas.jpg.jpeg'">
                <img src="img/hilirmigas.jpg.jpeg" alt="Galeri">
            </div>
            <div class="galeri-item" data-aos="zoom-in" data-aos-delay="240" @click="lightboxOpen=true;lightboxSrc='img/gas.jpg.jpeg'">
                <img src="img/gas.jpg.jpeg" alt="Galeri">
            </div>
            <div class="galeri-item" data-aos="zoom-in" data-aos-delay="300" @click="lightboxOpen=true;lightboxSrc='img/demosistem.jpg.jpeg'">
                <img src="img/demosistem.jpg.jpeg" alt="Galeri">
            </div>
            <div class="galeri-item" data-aos="zoom-in" data-aos-delay="360" @click="lightboxOpen=true;lightboxSrc='img/fotbar.jpg.jpeg'">
                <img src="img/fotbar.jpg.jpeg" alt="Galeri">
            </div>
            <div class="galeri-item" data-aos="zoom-in" data-aos-delay="420" @click="lightboxOpen=true;lightboxSrc='img/ptmigashilirjabar.jpg.jpeg'">
                <img src="img/ptmigashilirjabar.jpg.jpeg" alt="Galeri">
            </div>
        </div>
    </div>
</section>


<!-- ===== STATS ===== -->
<section class="stats-bg">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-num">24/7</div>
                <div class="stat-label">Monitoring Operasional</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="80">
                <div class="stat-num text-gradient">100%</div>
                <div class="stat-label">Digital Reporting</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="160">
                <div class="stat-num">Live</div>
                <div class="stat-label">Data Update</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="240">
                <div class="stat-num text-gradient">Multi</div>
                <div class="stat-label">User Access</div>
            </div>
        </div>
    </div>
</section>


<!-- ===== KEUNGGULAN ===== -->
<section style="background:var(--dark-base);">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <h2 class="section-title">Mengapa Memilih <span class="text-gradient">Hilir Migas</span>?</h2>
            <p class="section-sub" style="margin:0 auto;">Keunggulan platform kami dalam mendukung digitalisasi operasional</p>
        </div>
        <div class="adv-grid">
            <div class="adv-card" data-aos="fade-up" data-aos-delay="0">
                <div class="adv-icon"><svg viewBox="0 0 24 24" fill="none" stroke="var(--green-light)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                <h3>Efisiensi Operasional</h3>
                <p>Optimalkan proses operasional dengan sistem yang sepenuhnya terintegrasi.</p>
            </div>
            <div class="adv-card" data-aos="fade-up" data-aos-delay="80">
                <div class="adv-icon"><svg viewBox="0 0 24 24" fill="none" stroke="var(--orange-light)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <h3>Monitoring Real-Time</h3>
                <p>Pantau kondisi aset dan operasional secara langsung tanpa penundaan.</p>
            </div>
            <div class="adv-card" data-aos="fade-up" data-aos-delay="160">
                <div class="adv-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                <h3>Data Terpusat</h3>
                <p>Semua data tersimpan rapi dan aman dalam satu platform terintegrasi.</p>
            </div>
            <div class="adv-card" data-aos="fade-up" data-aos-delay="240">
                <div class="adv-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg></div>
                <h3>Akses Mobile</h3>
                <p>Kelola operasional dari mana saja melalui aplikasi mobile yang responsif.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== DOWNLOAD ===== -->
<section class="dl-section">
    <div class="container dl-content">
        <div class="text-center" data-aos="fade-up" style="max-width:640px;margin:0 auto;">
            <span class="section-label label-green">Download Sekarang</span>
            <h2 class="section-title" style="font-size:clamp(2rem,5vw,3.5rem);">Siap Mulai Digitalisasi?</h2>
            <p class="section-sub" style="margin:0 auto;">Unduh aplikasi Hilir Migas dan mulai monitoring operasional secara real-time</p>
        </div>

        <div class="dl-cards" data-aos="fade-up" data-aos-delay="100">
            <div class="dl-card" style="border-color:rgba(21,128,61,0.3);">
                <div class="dl-card-icon" style="background:linear-gradient(135deg,var(--green-mid),var(--green-light));">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <h3>Download Langsung</h3>
                <p>Unduh file APK secara langsung dan instal di perangkat Android Anda dengan mudah.</p>
                <a href="https://github.com/adhwaarajib20-sys/hexaglass-backend/releases/download/v.1.0.0/Migas.apk" download class="btn-primary" style="width:100%;justify-content:center;">
                    Download APK (Direct)
                </a>
                <p style="font-size:0.75rem;margin-top:0.75rem;color:var(--text-muted);">~45 MB · Android 8.0+ · Gratis</p>
            </div>

            <div class="dl-card" style="border-color:rgba(234,88,12,0.3);">
                <div class="dl-card-icon" style="background:linear-gradient(135deg,var(--orange-deep),var(--orange-mid));">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h.01M15 9h.01M9 15h.01M15 15h.01M9 12h.01M12 9h.01M15 12h.01M12 15h.01M12 12h.01"/></svg>
                </div>
                <h3>Scan QR Code</h3>
                <p>Scan kode QR dengan smartphone untuk download langsung dari Expo build server.</p>
                <div class="qr-wrap">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&color=0D5C2E&data=https://expo.dev/accounts/adhwaa20/projects/hexaglass/builds/a844a4fc-11a9-4108-b7e3-7460ee85f2c3" alt="QR Code">
                </div>
                <p style="font-size:0.75rem;color:var(--text-muted);">Arahkan kamera ke QR Code untuk membuka link download</p>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-delay="200">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem;color:var(--text-primary);">Persyaratan Sistem</h3>
            <div class="sys-req">
                <div class="req-item">
                    <div class="req-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div><strong>Sistem Operasi</strong><span>Android 8.0 atau lebih baru</span></div>
                </div>
                <div class="req-item">
                    <div class="req-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div><strong>Memori</strong><span>Minimal 2 GB RAM</span></div>
                </div>
                <div class="req-item">
                    <div class="req-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div><strong>Penyimpanan</strong><span>Minimal 50 MB ruang kosong</span></div>
                </div>
                <div class="req-item">
                    <div class="req-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div><strong>Koneksi</strong><span>Internet aktif (WiFi / 4G)</span></div>
                </div>
            </div>
        </div>

        <div class="text-center" style="margin-top:3rem;" data-aos="fade-up" data-aos-delay="300">
            <a href="https://github.com/adhwaarajib20-sys/hexaglass-backend/releases/download/v.1.0.0/Migas.apk" download class="btn-primary" style="font-size:1.05rem;padding:1rem 2.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download Aplikasi Sekarang
            </a>
            <p style="font-size:0.8rem;color:var(--text-muted);margin-top:1rem;">Gratis &nbsp;·&nbsp; Aman &nbsp;·&nbsp; Tanpa Iklan</p>
        </div>
    </div>
</section>


<!-- ===== FOOTER ===== -->
<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:0.75rem;">
                    <div class="logo-ring">
                        <img src="img/logo.png" alt="Hilir Migas" style="height:36px;width:36px;border-radius:50%;object-fit:cover;">
                    </div>
                    <span style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:1.1rem;color:var(--text-primary);">Hilir Migas</span>
                </div>
                <p>Platform monitoring operasional, distribusi, infrastruktur, dan aset CNG secara digital dan real-time.</p>
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Twitter">
                        <svg viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#galeri">Galeri</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Aplikasi</h4>
                <ul>
                    <li><a href="https://github.com/adhwaarajib20-sys/hexaglass-backend/releases/download/v.1.0.0/Migas.apk" download>Download Aplikasi</a></li>
                    <li><a href="#">Dokumentasi</a></li>
                    <li><a href="/hakcipta">Hak Cipta</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Kontak</h4>
                <ul>
                    <li><span>info@hilirmigas.id</span></li>
                    <li><span>Support: 24/7</span></li>
                    <li><a href="#" @click.prevent="loginOpen=true">Login Dashboard</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2025 Hilir Migas. All Rights Reserved.</p>
            <div class="footer-bottom-links">
                <a href="/hakcipta">Hak Cipta</a>
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
            </div>
        </div>
    </div>
</footer>


<!-- ===== LOGIN MODAL ===== -->
<div class="modal-overlay" x-show="loginOpen" x-cloak x-transition.opacity.duration.250ms @click.self="loginOpen=false">
    <div class="modal-box" x-transition.scale.duration.250ms>
        <button class="modal-close" @click="loginOpen=false" aria-label="Tutup">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="modal-logo">
            <div class="logo-ring" style="padding:6px;">
                <img src="img/logo.png" alt="Hilir Migas" style="height:52px;width:52px;border-radius:50%;object-fit:cover;">
            </div>
        </div>

        <h2 class="modal-title">Masuk ke Dashboard</h2>
        <p class="modal-sub">Masukkan kredensial Anda untuk mengakses platform Hilir Migas</p>

        <div class="form-group">
            <label class="form-label" for="login-email">Email / Username</label>
            <input class="form-input" id="login-email" type="email" placeholder="nama@hilirmigas.id" autocomplete="username">
        </div>
        <div class="form-group">
            <label class="form-label" for="login-pass">Password</label>
            <input class="form-input" id="login-pass" type="password" placeholder="••••••••" autocomplete="current-password">
            <a href="#" class="form-forgot">Lupa password?</a>
        </div>

        <button class="btn-submit" onclick="alert('Menghubungkan ke server...')">
            Masuk Sekarang
        </button>

        <div class="divider"><span>atau</span></div>

        <a href="https://github.com/adhwaarajib20-sys/hexaglass-backend/releases/download/v.1.0.0/Migas.apk" download class="btn-ghost" style="width:100%;justify-content:center;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download Aplikasi Mobile
        </a>

        <p style="text-align:center;font-size:0.75rem;color:var(--text-muted);margin-top:1.25rem;">
            Belum punya akses?
            <a href="mailto:info@hilirmigas.id" style="color:var(--green-light);text-decoration:none;">Hubungi admin</a>
        </p>
    </div>
</div>


<!-- ===== LIGHTBOX ===== -->
<div class="lightbox" x-show="lightboxOpen" x-cloak x-transition.opacity.duration.200ms @click="lightboxOpen=false">
    <button style="position:absolute;top:1.5rem;right:1.5rem;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff;" @click="lightboxOpen=false">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <img :src="lightboxSrc" alt="Gallery">
</div>


<!-- ===== SCRIPTS ===== -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({ duration: 750, easing: 'ease-out-cubic', once: true, mirror: false, offset: 60 });

    // Show download btn on desktop nav after scroll
    const dlBtn = document.getElementById('nav-dl-btn');
    window.addEventListener('scroll', () => {
        if(window.scrollY > 80) {
            dlBtn.style.display = 'inline-flex';
        } else {
            dlBtn.style.display = 'none';
        }
    });
</script>
</body>
</html>