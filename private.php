<?php

$pageContent = 'home';
if (isset($_GET['page'])) {
    $con = $_GET['page'];
    $pathPage = "private/content/$con.php";

    if (file_exists($path)) {
        $pageContent = $_GET['page'];
    }
}

include "app/private/dashboard.php";
