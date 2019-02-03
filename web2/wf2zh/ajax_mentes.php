<?php

include('database.php');

$id = $_POST['id'];
$nev = $_POST['nev'];
$alakzat = $_POST['alakzat'];
$kedvenc = $_POST['kedvenc'] === 'true';
$alak = json_decode($alakzat);
$magassag = count($alak);
$szelesseg = count($alak[0]);

vegrehajtas($kapcsolat,
    'update teszt set nev = :nev, alakzat = :alakzat, szelesseg=:szelesseg, magassag=:magassag, kedvenc=:kedvenc where id = :id',
    [
        ':nev'      => $nev,
        ':alakzat'  => $alakzat,
        ':magassag'  => $magassag,
        ':szelesseg'  => $szelesseg,
        ':kedvenc'  => $kedvenc,
        ':id'  => $id,
    ]
);

$resp = [
    'szelesseg' => $szelesseg,
    'magassag' => $magassag,
];
echo json_encode($resp);

