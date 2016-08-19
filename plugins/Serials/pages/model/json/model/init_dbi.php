<?php
$strut = fn([
    // 'p' => '',
    'query' => function ($p) {
        return mysql_query($p);
    },
    'error' => function () { return die(mysql_error()); },
    'num_rows' => function () { return mysql_num_rows($this->result); },
    'fetch_assoc' => function () { return mysql_fetch_array($this->result,MYSQL_ASSOC);}
]);