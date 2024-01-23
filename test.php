<?php
require_once 'phpqrcode/qrlib.php';

QRcode::png('radi', 'images/temp/temp.png', QR_ECLEVEL_M, 8);

echo '<img src="images/temp/temp.png" alt="qr_code">';