<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul_halaman) ? htmlspecialchars($judul_halaman) . ' - JahitLink' : 'JahitLink'; ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .panel-wrap { display: flex; min-height: calc(100vh - 130px); }
        .sidebar { width: 240px; background: var(--bg-card); border-right: 1px solid var(--border-soft); padding: 24px 16px; flex-shrink: 0; }
        .sidebar-title { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 12px; padding: 0 12px; font-weight: 700; }
        .sidebar a { display: block; padding: 10px 14px; border-radius: 10px; font-size: 0.93rem; font-weight: 500; color: var(--text-main); margin-bottom: 4px; }
        .sidebar a:hover, .sidebar a.active { background: var(--pastel-sage-light); color: var(--pastel-sage); font-weight: 600; }
        .panel-content { flex: 1; padding: 32px; overflow-x: auto; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 0.8rem; font-weight: 600; }
        .badge-info { background: var(--pastel-lavender); color: #5C4B8A; }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--pastel-sage); margin-top: 6px; }
        @media (max-width: 768px) { .panel-wrap { flex-direction: column; } .sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-soft); } }
    </style>
</head>
<body>