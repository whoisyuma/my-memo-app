<?php
    // htmlspecialcharsの関数化
    function h($value) {
        return htmlspecialchars($value, ENT_QUOTES);
    }
?>