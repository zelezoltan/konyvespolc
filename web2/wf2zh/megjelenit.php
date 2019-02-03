<?php
include('database.php');

$id = $_GET['id'];
$alakzat = lekerdezes($kapcsolat,
    'select * from teszt where id = :id',
    [':id' => $id]
)[0];
?>

<link rel="stylesheet" href="style.css">

<h1>Megjelenít</h1>
<dl>
    <dt>Azonosító</dt>
    <dd><?= $alakzat['id'] ?></dd>
    <dt>Név</dt>
    <dd><?= $alakzat['nev'] ?>
        <input type="text" id="nev" value="<?= $alakzat['nev'] ?>" style="display: none">
    </dd>
    <dt>Méret</dt>
    <dd><?= $alakzat['magassag'] ?> x <?= $alakzat['szelesseg'] ?></dd>
    <dt>Kedvenc</dt>
    <dd><?= (bool)$alakzat['kedvenc'] ? '♥' : '♡' ?>
        <input type="checkbox" id="kedvenc" <?= (bool)$alakzat['kedvenc'] ? 'checked' : '' ?> hidden>
    </dd>
    <dt>Vetületek</dt>
    <dd>
    <textarea id="alakzat" cols="30" rows="10" hidden><?= $alakzat['alakzat'] ?></textarea>
    <table>
        <tr>
            <th>Felül</th>
            <th>Oldal</th>
            <th>Alul</th>
        </tr>
        <tr>
            <td>
                <table class="vetulet" id="felul"></table>
            </td>
            <td>
                <div class="vetulet" id="oldal"></div>
            </td>
            <td>
                <div class="vetulet" id="elol"></div>
            </td>
        </tr>
    </table>
    </dd>
</dl>
<button type="button" id="szerkeszt">Szerkeszt</button>
<button type="button" id="mentes" hidden>Mentés</button>

<script src="ajax.js"></script>
<script>
$('#szerkeszt').addEventListener('click', onSzerkeszt);
function onSzerkeszt(e) {
    $('#nev').style.display = '';
    $('#alakzat').hidden = false;
    $('#mentes').hidden = false;
    $('#kedvenc').hidden = false;
}

$('#mentes').addEventListener('click', onMentes);
function onMentes(e) {
    const id = $('dd:nth-of-type(1)').innerHTML;
    const nev = $('#nev').value;
    const alakzat = $('#alakzat').value;
    const kedvenc = $('#kedvenc').checked;
    ajax({
        mod: 'post',
        url: 'ajax_mentes.php',
        postadat: `id=${id}&nev=${nev}&alakzat=${alakzat}&kedvenc=${kedvenc}`,
        siker: onSiker
    })
}
function onSiker(xhr, text) {
    const resp = JSON.parse(text);
    $('dd:nth-of-type(3)').innerHTML = `${resp.magassag} x ${resp.szelesseg}`;
    x = JSON.parse($('#alakzat').value);
    megjelenit();
}


let x = <?= $alakzat['alakzat'] ?>;

function $(sel) {
    return document.querySelector(sel);
}

function megjelenit() {
    const balrol = x.map(sor => sor.reduce((max, e) => e > max ? e : max, -Infinity));
    const alulrol = x[0].map((e, i) => x.reduce((max, e) => e[i] > max ? e[i] : max, -Infinity))
    $('#felul').innerHTML = genTable2(x);
    $('#oldal').innerHTML = genVetulet(balrol);
    $('#elol').innerHTML = genVetulet(alulrol);
}

const range = n => Array.from({length: n});

function genVetulet(x) {
    return `
        ${x.map((o, i) => `
            <div>
                ${range(o).map(e => `<div></div>`).join('')}
                
            </div>
        `).join('')}
    `;
}

function genTable2(x) {
    return `
        ${x.map(sor => `
            <tr>
                ${sor.map(e => `
                    <td class="${e > 0 ? 'black' : ''}"></td>
                `).join('')}
            </tr>
        `).join('')}
    `;
}
megjelenit();
</script>