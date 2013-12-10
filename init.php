<?

if (isset($_REQUEST['fitcon_project_name'])) {
	$s = session_start();
	$_SESSION['fitcon_project_name'] = $_REQUEST['fitcon_project_name'];
	$_SESSION['fitcon_project_flag'] = $_REQUEST['fitcon_project_flag'];
}

if (isset($_REQUEST['fitcon_project_flag'])) {
	session_start();
	$_SESSION['fitcon_project_name'] = $_REQUEST['fitcon_project_name'];
	$_SESSION['fitcon_project_flag'] = $_REQUEST['fitcon_project_flag'];
}

session_start();

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
	if (!divAddedAlready) {
		lastDivInEditEventForm = $( "#BXCEditEvent .bxec-d-cont div:first .bxec-popup-row:last" );
		
		currentEventNameEl = $( "#BXCEditEvent input[id*='_edit_ed_name']" );
			currentEventNameVal = currentEventNameEl.val();
			if (currentEventNameVal) {
				clearedCurrentEventNameVal = currentEventNameVal.replace(/\[.*\]\s+/, "");
				currentEventNameEl.val(clearedCurrentEventNameVal);
			}
		
		if (lastDivInEditEventForm.length != 0) { 
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
	loadNewDiv();
	addButton.click( function() {
		window.setTimeout(function(){
			loadNewDiv();
			window.setTimeout(function(){
				projectOrFlagSelects = $( "#fitcon_project_form select" );
				projectOrFlagSelects.change(function(){
					nameVal = $( "#fitcon_project_name" ).val();
					flagVal = $( "#fitcon_project_flag" ).val();
					$.ajax({
						type: "GET",
						url: "https://portal.fitcon.ru:443<?=$_SERVER['REQUEST_URI']?>",
						data: { fitcon_project_name: nameVal, fitcon_project_flag: flagVal }
						})
				});
			}, 1200);
		}, 3000);
	});
	
	editButton = $( ".bxec-event-but.bxec-ev-edit-icon" );
	editButton.click(function(){
		window.setTimeout(function(){
			loadNewDiv();
			window.setTimeout(function(){
				projectOrFlagSelects = $( "#fitcon_project_form select" );
				projectOrFlagSelects.change(function(){
					nameVal = $( "#fitcon_project_name" ).val();
					flagVal = $( "#fitcon_project_flag" ).val();
					$.ajax({
						type: "GET",
						url: "https://portal.fitcon.ru:443<?=$_SERVER['REQUEST_URI']?>",
						data: { fitcon_project_name: nameVal, fitcon_project_flag: flagVal }
						})
				});
			}, 1200);
		}, 1000);
	});
	
}, 3500);
	</script>

<?
}

AddEventHandler("calendar", "OnAfterCalendarEventEdit", "FitconOnAfterCalendarEventEdit");
function FitconOnAfterCalendarEventEdit($arFields, $bNew, $USER_ID)
{
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
        $arFieldsNew = array(
            "NAME"                    => "'$newName'",
		);
		$DB->StartTransaction();
        $DB->Update("b_calendar_event", $arFieldsNew, "WHERE ID=$IDtoChange");
		$DB->Commit();

		$el = new CIBlockElement;

		$PROP = array();
		$PROP[4] = $arFields['OWNER_ID'];
		
		$arLoadProductArray = Array(
		"MODIFIED_BY"    => $arFields['OWNER_ID'],
		"IBLOCK_ID"      => 3,
		"PROPERTY_VALUES"=> $PROP,
		"NAME"           => $newName,
		"ACTIVE"         => "Y",            
		);
		$PRODUCT_ID = $el->Add($arLoadProductArray);
	}
}


?>