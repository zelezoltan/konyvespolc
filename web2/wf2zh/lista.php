<?php
include('database.php');

// $nehezseg = $_GET['nehezseg'] ?? '';
$kedvenc = $_GET['kedvenc'] ?? '';

if ($kedvenc === '') {
    $vetuletek = lekerdezes($kapcsolat,
        'select * from teszt'
    );
} else {
    $vetuletek = lekerdezes($kapcsolat,
        'select * from teszt where kedvenc = :kedvenc',
        [':kedvenc' => $kedvenc === 'true']
    );
}
?>

<h1>Lista</h1>
<a href="uj.php">Új alakzat</a>

<table>
    <tr>
        <!-- <th>Sorszám</th> -->
        <th>Név</th>
        <th>Méret</th>
        <!-- <th>Nehézség</th> -->
        <th>Kedvenc</th>
        <th>Funkciók</th>
    </tr>
    <?php foreach($vetuletek as $v) : ?>
        <tr data-id="<?= $v['id'] ?>">
            <!-- <td><?= $v['id'] ?></td> -->
            <td><?= $v['nev'] ?></td>
            <td><?= $v['magassag'] ?> x <?= $v['szelesseg'] ?></td>
            <!-- <td><?= $v['nehezseg'] ?></td> -->
            <td><?= (bool)$v['kedvenc'] ? '♥' : '♡' ?></td>
            <td>
                <a href="megjelenit.php?id=<?= $v['id'] ?>">Megjelenít</a>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<script src="ajax.js"></script>
<script>
function $(sel) {
    return document.querySelector(sel);
}
$('table').addEventListener('click', onClick);
function onClick(e) {
    if (e.target.matches('a.torol')) {
        e.preventDefault();
        const url = e.target.href;
        ajax({
            mod: 'post',
            url,
            siker: function (xhr, text) {
                onSiker(text, e.target);
            } 
        })
    }
}
function onSiker(text, a) {
    const resp = JSON.parse(text);
    if (resp) {
        const td = a.parentNode;
        const tr = td.parentNode;
        const p = tr.parentNode;
        p.removeChild(tr);
    }
}
</script>