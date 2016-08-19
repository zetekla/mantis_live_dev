<?php
//sleep(1);
 
$qr = (!empty($_POST['q'])) ? strtolower($_POST['q']) : null;
 
if (!isset($qr)) {
    die('Invalid query.');
}
 
$status = true;
$databaseUsers = array(
    array(
        "id"        => 4152589,
        "username"  => "TheTechnoMan",
        "avatar"    => "https://avatars2.githubusercontent.com/u/4152589"
    ),
    array(
        "id"        => 7377382,
        "username"  => "Helsinki",
        "avatar"    => "https://avatars3.githubusercontent.com/u/748137"
    ),
    array(
        "id"        => 748137,
        "username"  => "juliocastrop",
        "avatar"    => "https://avatars3.githubusercontent.com/u/748137"
    ),
    array(
        "id"        => 619726,
        "username"  => "cfreear",
        "avatar"    => "https://avatars0.githubusercontent.com/u/619726"
    ),
    array(
        "id"        => 5741776,
        "username"  => "solevy",
        "avatar"    => "https://avatars3.githubusercontent.com/u/5741776"
    ),
    array(
        "id"        => 906237,
        "username"  => "nilovna",
        "avatar"    => "https://avatars2.githubusercontent.com/u/906237"
    ),
    array(
        "id"        => 612578,
        "username"  => "Thiago Talma",
        "avatar"    => "https://avatars2.githubusercontent.com/u/612578"
    ),
    array(
        "id"        => 2051941,
        "username"  => "webcredo",
        "avatar"    => "https://avatars2.githubusercontent.com/u/2051941"
    ),
    array(
        "id"        => 985837,
        "username"  => "ldrrp",
        "avatar"    => "https://avatars2.githubusercontent.com/u/985837"
    ),
    array(
        "id"        => 1723363,
        "username"  => "dennisgaudenzi",
        "avatar"    => "https://avatars2.githubusercontent.com/u/1723363"
    ),
    array(
        "id"        => 2649000,
        "username"  => "i7nvd",
        "avatar"    => "https://avatars2.githubusercontent.com/u/2649000"
    ),
    array(
        "id"        => 2757851,
        "username"  => "pradeshc",
        "avatar"    => "https://avatars2.githubusercontent.com/u/2757851"
    )
);
 
$resultUsers = [];
foreach ($databaseUsers as $key => $oneUser) {
    if (strpos(strtolower($oneUser["username"]), $qr) !== false ||
        strpos(str_replace('-', '', strtolower($oneUser["username"])), $qr) !== false ||
        strpos(strtolower($oneUser["id"]), $qr) !== false) {
        $resultUsers[] = $oneUser;
    }
}
 
$databaseProjects = array(
    array(
        "id"        => 1,
        "project"   => "MantisBT",
        "image"     => "http://mantisbt.org/images/mantis_logo_262x90.png",
        "version"   => "1.7.0",
        "demo"      => 10,
        "option"    => 23,
        "callback"  => 6,
    ),
    array(
        "id"        => 2,
        "project"   => "SerialScan",
        "image"     => "http://www.mdtronics.com/images/icon_barcode.png",
        "version"   => "1.1",
        "demo"      => 11,
        "option"    => 14,
        "callback"  => 8,
    )
);
 
$resultProjects = [];
foreach ($databaseProjects as $key => $oneProject) {
    if (strpos(strtolower($oneProject["project"]), $qr) !== false) {
        $resultProjects[] = $oneProject;
    }
}
 
// Means no result were found
if (empty($resultUsers) && empty($resultProjects)) {
    $status = false;
}
 
header('Content-Type: application/json');
 
echo json_encode(array(
    "status" => $status,
    "error"  => null,
    "data"   => array(
        "user"      => $resultUsers,
        "project"   => $resultProjects
    )
));