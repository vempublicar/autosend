<?php

include_once "app/private/partials/head.php";
echo '<div class="container-scroller">';
include_once "app/private/partials/nav.php";
echo '<div class="container-fluid page-body-wrapper">';
include_once "app/private/partials/sidebar.php";
echo '<div class="main-panel">';
//if
include_once "app/private/content/{$pageContent}.php";

//endif
include_once "app/private/partials/footer.php";
echo '</div></div></div>';
include_once "app/private/partials/end.php";
