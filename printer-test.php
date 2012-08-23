<?php

//$printer_name = "BIXOLON SRP-350plusII";

//$handle = printer_open($printer_name);

$handle = printer_open();

/*printer_set_option($handle, PRINTER_MODE, "RAW");
printer_write($handle, "TEST TEST TEST ТЕСТ");
printer_close($handle);*/



printer_start_doc($handle, "My Document");
printer_start_page($handle);

$arial = printer_create_font("Arial", 72, 48, 400, false, false, false, 0);

$arial_bold = printer_create_font("Arial", 72, 48, 700, false, false, false, 0);

$arial_italic = printer_create_font("Arial", 72, 48, 400, TRUE, false, false, 0);
printer_select_font($handle, $arial_bold);
printer_draw_text($handle, "Иван: Хочу вклад с 90% в неделю.
Очень.", 10, 10);
printer_delete_font($arial_bold);
printer_select_font($handle, $arial);
printer_draw_text($handle, "Хочу вклад с 90% в неделю.
Очень.", 10, 30);
printer_delete_font($arial);
printer_select_font($handle, $arial_italic);
printer_draw_text($handle, "18:59 20.02.1887", 10, 60);


printer_delete_font($arial_italic);

printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);
//var_dump(printer_list(PRINTER_ENUM_LOCAL));

?>