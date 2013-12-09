<?

if (isset($_REQUEST['fitcon_project_name'])) {
    $s = session_start();
    $_SESSION['fitcon_project_name'] = $_REQUEST['fitcon_project_name'];
    $_SESSION['fitcon_project_flag'] = $_REQUEST['fitcon_project_flag'];
    exit('session:'.$s);
}

if (isset($_REQUEST['fitcon_project_flag'])) {
    session_start();
    $_SESSION['fitcon_project_name'] = $_REQUEST['fitcon_project_name'];
    $_SESSION['fitcon_project_flag'] = $_REQUEST['fitcon_project_flag'];
}

session_start();
$_SESSION['fitcon_project_name_test'] = $_REQUEST['fitcon_project_name'];

AddEventHandler("calendar", "OnAfterBuildSceleton", "FitconOnAfterBuildSceleton");
function FitconOnAfterBuildSceleton()
{
    // Здесь задаются названия проектов
    $fitconCalendarProjectsList = array(
            'TEST',
            'ГРЕКО',
            'ОКА',
            'СБОР',
            'ГОА',
            'ЗУБР МЕЛОМ',
    );

    CJSCore::Init('jquery');

    ?>
    <script type="text/javascript">
    function loadNewDiv(){
        divAddedAlready = $( "#BXCEditEvent #fitcon_project_form" ).length;
        console.log('divAddedAlready');
        console.log(divAddedAlready);
        if (!divAddedAlready) {
            console.log('inside if');
            lastDivInEditEventForm = $( "#BXCEditEvent .bxec-d-cont div:first .bxec-popup-row:last" );
            console.log(lastDivInEditEventForm.length);
            if (lastDivInEditEventForm.length != 0) {
                console.log('Yea! Adding div');
                newDIV = "<div id='fitcon_project_form'><div class='bxec-popup-row bxec-ed-meeting-vis'>"+
                        "<span class='bxec-field-label-edev'><label for=''>Проект:</label></span>"+
                        "<span class='bxec-field-val-2' style='padding-left: 3px'>"+
                        "<select name='fitcon_project_name' class='calendar-select' id='fitcon_project_name' style='width: 310px'>"+
                            "<option selected value='0'>не выбран</option>"+
                            "<?foreach($fitconCalendarProjectsList as $p):?><option><?=$p?></option><?endforeach;?>"+
                            "</select></span></div><div class='bxec-popup-row'><span class='bxec-field-label-edev'><label for=''>Смена проекта:</label></span><span class='bxec-field-val-2' style='padding-left: 3px'><select name='fitcon_project_flag' class='calendar-select' id='fitcon_project_flag' style='width: 310px'><option value='0'>невозможна</option><option value='1'>под вопросом</option></select></span></div></div>";
                lastDivInEditEventForm.before(newDIV);
            }
        }
    }

    window.setTimeout(function(){
        addButton = $( ".bxec-tabs-cnt .bxec-buttons-cont span.bxec-add-but span" );
        console.log('addButton:');
        console.log(addButton);
        loadNewDiv();
        addButton.click( function() {
            window.setTimeout(function(){
                loadNewDiv();
                trueSaveButton = $( ".popup-window-button-accept" );
                saveButton = $( ".popup-window-button.popup-window-button-accept" );
                var oldOnClick = trueSaveButton.get(0).click;
                trueSaveButton.unbind( "click" );
                trueSaveButton.click(function (e) {
                    // use http://stackoverflow.com/questions/1506729/how-to-intercept-the-onclick-event
                    // nameVal = $( "#fitcon_project_name" ).val();
                    // flagVal = $( "#fitcon_project_flag" ).val();
                    // $.ajax({
                        // type: "POST",
                        // // url: "http://<?=SITE_SERVER_NAME?>/bitrix/fitconRequestHandler.php",
                        // url: "http://localhost:6448<?=$_SERVER['REQUEST_URI']?>",
                        // data: { fitcon_project_name: nameVal, fitcon_project_flag: flagVal }
                        // })
                    var eventTitle = $( "#popup-window-content-BXCEditEvent input[id$='edit_ed_name']");
                    console.log(eventTitle.val());
                    var projectName = $( "#fitcon_project_form #fitcon_project_name").val();
                    console.log(projectName);
                    var projectFlag = $( "#fitcon_project_form #fitcon_project_flag").val();
                    console.log(projectFlag);
                    eventTitle.val("[" + projectFlag + " " + projectName + "] " + eventTitle.val());
                    console.log(eventTitle.val());

                    //console.log("saveButton clicked, calling old click event...")
                    oldOnClick.call(this, e);
                    return false;
                });
                window.setTimeout(function(){
                    projectOrFlagSelects = $( "#fitcon_project_form select" );
                    console.log('projectOrFlagSelects');
                    console.log(projectOrFlagSelects);
                    projectOrFlagSelects.change(function(){
                        console.log('changed');
                        nameVal = $( "#fitcon_project_name" ).val();
                        flagVal = $( "#fitcon_project_flag" ).val();
                        $.ajax({
                            type: "GET",
                            url: "https://portal.fitcon.ru:443<?=$_SERVER['REQUEST_URI']?>",
                            // is this necessary?
                            data: { fitcon_project_name: nameVal, fitcon_project_flag: flagVal, 
                                   event_id:446, action: 'userfield_edit', sessid: '688bbc318e8284669b3b0433a00203eb', bx_event_calendar_request: 'Y', reqId:744150 }
                            })
                    });
                }, 1200);
            }, 3000);
        });
    }, 3500);

        </script>
    <?
}

AddEventHandler("calendar", "OnAfterCalendarEventEdit", "FitconOnAfterCalendarEventEdit");
function FitconOnAfterCalendarEventEdit($arFields, $bNew, $USER_ID)
{

$_SESSIONS = array();
$_SESSIONS['testing_session_start'] = 'test';

if ($_REQUEST['fitcon_project_name']) {
    session_start();
    $_SESSION['fitcon_project_name'] = $_REQUEST['fitcon_project_name'];
    $_SESSION['fitcon_project_flag'] = $_REQUEST['fitcon_project_flag'];
}

if ($_REQUEST['fitcon_project_flag']) {
    session_start();
    $_SESSION['fitcon_project_name'] = $_REQUEST['fitcon_project_name'];
    $_SESSION['fitcon_project_flag'] = $_REQUEST['fitcon_project_flag'];
}

$f = fopen ($_SERVER['DOCUMENT_ROOT']."/bitrix/arFields.txt", "w+");
    fwrite ($f, "STEP 1: fitcon_project_name\n");
    fwrite ($f, print_r ($_SESSION,true));
fwrite ($f, print_r ($_GLOBALS,true));
fwrite ($f, print_r ($_REQUEST,true));

    if (isset($_SESSION['fitcon_project_name']) && !empty($_SESSION['fitcon_project_name'])) { 
        $arEdit = array();
        $arEdit['ID'] = $arFields['ID'];
        $arEdit['NAME'] = '[' . $_SESSION['fitcon_project_name'] . '] ' . $arFields['NAME'];
        if ($_SESSION["fitcon_project_flag"]) $arEdit['NAME'] = '[? ' . $_SESSION['fitcon_project_name'] . '] ' . $arFields['NAME'];
        $Params['arFields'] = $arEdit;

        $newName = $arEdit['NAME'];
        $IDtoChange = $arEdit['ID'];

        global $DB;
        $DB->PrepareFields("b_calendar_event");
        $arFields = array(
            "NAME"                    => "'$newName'",
        );
        $DB->StartTransaction();
        $DB->Update("b_calendar_event", $arFields, "WHERE ID=$IDtoChange");
        $DB->Commit();

        fwrite ($f, print_r ('Wrote to DB',true));
    }
fclose($f); 
}

?>
