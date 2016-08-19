<?php
$row = user_get_row( auth_get_current_user_id() );
extract( $row, EXTR_PREFIX_ALL, 'u' );
echo json_encode($row, JSON_PRETTY_PRINT);