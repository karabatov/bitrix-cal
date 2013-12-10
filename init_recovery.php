<?$action = $_GET["action"];
if ($action == "backup"):
copy ($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php', $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php_backup');
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php_backup')):
        echo ('File created');
    else:
        echo ('File created error');
    endif;
elseif ($action == "recovery"):
    rename ($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php', $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php_error');
    rename ($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php_backup', $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php');
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php_error')):
            echo ('Restore completed');
        else:
            echo ('Restore error');
        endif;
endif; ?>