<?php
include('database.php');

function validate($post, &$data, &$errors) {
    $id = $post['id'];
    $nev = $post['nev'];
    $szelesseg = $post['szelesseg'];
    $magassag = $post['magassag'];
    $alakzat = $post['alakzat'];

    if (strlen(trim($id)) > 0 && filter_var($id, FILTER_VALIDATE_INT) === false) {
        $errors[] = 'Az id nem szám';
    }
    else {
        $data['id'] = $id;
    }

    if ($nev === '') {
        $errors[] = 'A név kötelező!';
    } else {
        $data['nev'] = trim($nev);
    }

    if (trim($szelesseg) == '') {
        $errors[] = 'A szélesség kötelező';
    } 
    else if (filter_var($szelesseg, FILTER_VALIDATE_INT) === false) {
        $errors[] = 'A szélesség nem szám';
    }
    else {
        $data['szelesseg'] = (int)$szelesseg;
    }

    if (trim($magassag) == '') {
        $errors[] = 'A magasság kötelező';
    } 
    else if (filter_var($magassag, FILTER_VALIDATE_INT) === false) {
        $errors[] = 'A magasság nem szám';
    }
    else {
        $data['magassag'] = (int)$magassag;
    }

    $data['kedvenc'] = isset($post['kedvenc']);

    if (trim($alakzat) == '') {
        $errors[] = 'Az alakzat kötelező';
    }   
    else if (json_decode($alakzat) === NULL) {
        $errors[] = 'Az alakzat rossz formátumú';
    }
    else {
        $data['alakzat'] = $alakzat;
    }

    return count($errors) === 0;
}

$errors = [];
if ($_POST) {
    if (validate($_POST, $data, $errors)) {
        vegrehajtas($kapcsolat,
            'INSERT INTO `teszt`
                (`id`, `nev`, `szelesseg`, `magassag`, `alakzat`, `kedvenc`) 
              VALUES 
                (:id, :nev, :szelesseg, :magassag, :alakzat, :kedvenc)',
            $data
        );
        header('Location: lista.php');
        exit();
    }
}

?>
<link rel="stylesheet" href="style.css">
<h1>Új alakzat</h1>

<ul id="hibak">
    <?= var_dump($errors) ?>
</ul>
<form action="" method="post">
    Id: <input type="text" name="id"> <br>
    Név: <input type="text" name="nev"> <br>
    Szélesség: <input type="text" name="szelesseg" id="szelesseg" value="5"> <br>
    Magasság: <input type="text" name="magassag" id="magassag" value="5">
    <button type="button" id="general">Generál</button>
    <br>
    Kedvenc: <input type="checkbox" name="kedvenc"> <br>
    Alakzat: 
    <textarea name="alakzat" id="alakzat" cols="30" rows="10">[[1, 2, 3], [1, 2, 3], [1, 2, 3]]</textarea>
    <button type="button" id="megjelenit">Megjelenít</button>
    <br>
    <button type="submit">Ment</button>
</form>

<table id="tabla"></table>

<!-- <div class="vetulet2">
    <div>
        <div></div>
        <div></div>
        <div></div>
        <p>1111</p>
    </div>
    <div>
        <div></div>
        <p>2</p>
    </div>
</div> -->

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
            <div class="vetulet" id="bal"></div>
        </td>
        <td>
            <div class="vetulet" id="alul"></div>
        </td>
    </tr>
</table>

<script src="gen1.js"></script>