<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';

$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
require_once __DIR__ . '/routes/web.php';