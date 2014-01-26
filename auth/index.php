<?php

function l($name) {
    echo '<p><a href="' . $name . '.php">' . $name . '</a></p>';
}

array_map(l, array('eswis', 'library.ajax', 'library'));
