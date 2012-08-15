<?php

$printer_name = "";

$handle = printer_open($printer_name);


printer_write($handle, "Text to print");
printer_close($handle);

var_dump(printer_list(PRINTER_ENUM_LOCAL | PRINTER_ENUM_SHARED));

?>
