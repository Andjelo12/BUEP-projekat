<?php

const PARAMS = [
    "HOST" => 'localhost',
    "USER" => 'root',
    "PASS" => '',
    "DBNAME" => 'nwp_projekat',
    "CHARSET" => 'utf8mb4'
];

const SITE = 'http://localhost/nwp_projekat_01/'; // enter your path on localhost

$dsn = "mysql:host=" . PARAMS['HOST'] . ";dbname=" . PARAMS['DBNAME'] . ";charset=" . PARAMS['CHARSET'];

$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

$actions = ['login', 'register', 'forget'];

$messages = [
    0 => 'Loš URL!',
    1 => 'Netačni podaci!',
    2 => 'Korisnik sa navedenim e-mailom već postoji, izaberite drugi!',
    3 => 'Proverite e-mail da bi ste aktivirali nalog!',
    4 => 'Popunite sva polja!',
    5 => 'Izlogovani ste!!',
    6 => 'Vaš nalog je aktiviran, možete se ulogovati!',
    7 => 'Lozinke se ne poklapaju!',
    8 => 'E-mail adresa je u lošem formatu!',
    9 => 'Lozinka je prekratka! Mora biti minimum 8 karaktera!',
    10 => 'Lozinka nije dovoljno jaka! (min. 8 karaktera, jedno malo, jedno veliko slovo, jedan broj i jedan specijalan karakter)',
    11 => 'Something went wrong with mail server. We will try to send email later!',
    12 => 'Vaš nalog je već aktiviran!',
    13 => 'Ukoliko imate nalog na našem sajtu link za reset lozinke vam je poslat.',
    14 => 'Something went wrong with server.',
    15 => 'Token or other data are invalid!',
    16 => 'Vaša nova lozinka je postavljena i možete se ulogovati <a href="login.php" class="text-white">ovde</a>',
    17 => 'Vaš nalog je trenutno blokiran. Obratite se administratoru',
    18 => 'Nalog još uvek nije aktiviran! Proverite vaš email'
];

$emailMessages = [
    'register' => [
        'subject' => 'Registracija na sajt',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ],
    'forget' => [
        'subject' => 'Zaboravljena lozinka - kreirajte novu',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ],
    'change' =>[
        'subject' => 'Zahtev za izmenu lozinke',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ]
];
$apiFields = "country,proxy";