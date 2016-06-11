<? 
/*
    Copyright (C) 2013-2016 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWiFi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script src="includes/scripts.js"></script>

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?
include "../../login_check.php";
include "../../config/config.php";
include "_info_.php";
include "../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
	regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_POST["service"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
$tempname = $_GET["tempname"];
$service = $_POST["service"];

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "$bin_rm ".$mod_logs_history.$logfile.".log";
    exec_fruitywifi($exec);
}

// SET MODE
if ($_POST["change_mode"] == "1") {
    $ss_mode = $service;
    $exec = "/bin/sed -i 's/ss_mode.*/ss_mode = \\\"".$ss_mode."\\\";/g' _info_.php";
    $output = exec_fruitywifi($exec);
}

?>

<div class="rounded-top" align="left">&nbsp;<b><?=$mod_alias?></b> </div>
<div class="rounded-bottom">

    &nbsp;&nbsp;&nbsp; version <?=$mod_version?><br>
    <? 	
	if (file_exists($bin_wpa_supplicant)) { 
        echo "&nbsp;$mod_alias <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp;$mod_alias <a href='includes/module_action.php?install=install_$mod_name' style='color:red'>install</a><br>";
    } 
	
    ?>
    
	<?
	$exec = "$bin_iw $mod_supplicant_iface link | grep -iEe 'connected to.+$mod_supplicant_iface'";
    $ismoduleup = exec("$exec");
    if ($ismoduleup != "") {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; link  <font color='lime'><b>enabled</b></font><br>";
    } else { 
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; link  <font color='red'><b>disabled</b></font><br>"; 
    }
    ?>
	
	<?
	$exec = "ps aux | grep -iEe 'dhclient $mod_supplicant_iface' | grep -v grep";
    $ismoduleup = exec("$exec");
    if ($ismoduleup != "") {
        echo "&nbsp;&nbsp; dhclient <font color='lime'><b>enabled</b></font><br>";
    } else { 
        echo "&nbsp;&nbsp; dhclient <font color='red'><b>disabled</b></font><br>"; 
    }
    ?>
	
    <?
    $ismoduleup = exec("$mod_isup");
    if ($ismoduleup != "") {
        echo "&nbsp;$mod_alias  <font color='lime'><b>enabled</b></font> | <a href='includes/module_action.php?service=nmcli&action=stop&page=module'><b>stop</b></a>";
    } else { 
        echo "&nbsp;$mod_alias  <font color='red'><b>disabled</b></font> | <a href='includes/module_action.php?service=nmcli&action=start&page=module'><b>start</b></a>"; 
    }
    ?>

</div>

<br>


<div id="msg" style="font-size:larger;">
	Loading, please wait...
</div>

<div id="body" style="display:none;">

    <div id="result" class="module">
        <ul>
			<li><a href="#tab-output">Output</a></li>
            <li><a href="#tab-options">Options</a></li>
			<li><a href="#tab-history">History</a></li>
            <li><a href="#tab-about">About</a></li>
        </ul>
        
		<!-- OUTPUT -->
        <div id="tab-output">
            <form id="formLogs-Refresh" name="formLogs-Refresh" method="POST" autocomplete="off" action="index.php">
            <input type="submit" value="refresh">
            <br><br>
            <?
                if ($logfile != "" and $action == "view") {
                    $filename = $mod_logs_history.$logfile.".log";
                } else {
                    $filename = $mod_logs;
                }
            
                $data = open_file($filename);
                
                // REVERSE
                //$data_array = explode("\n", $data);
                //$data = implode("\n",array_reverse($data_array));
                
            ?>
            <textarea id="output" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
            <input type="hidden" name="type" value="logs">
            </form>
            
        </div>
        <!-- END OUTPUT -->
		
        <!-- OPTIONS -->
        <div id="tab-options" class="history">
		
				<h4>
					WPA Supplicant
				</h4>
				<div style="width: 50px; display: inline-block">Security</div>			
				<div class="btn-group btn-group-sm" data-toggle="buttons">
					<label class="btn btn-default <? if ($mod_supplicant_security == "open") echo "active" ?>">
					  <input type="radio" name="mod_supplicant_security" id="open" autocomplete="off" checked> Open
					</label>
					<label class="btn btn-default <? if ($mod_supplicant_security == "secure") echo "active" ?>">
					  <input type="radio" name="mod_supplicant_security" id="secure" autocomplete="off"> Secure
					</label>
				</div>
			
			<br><br>
			
	
			<form action="includes/save.php" method="POST" autocomplete="off">
				
				<div style="width: 50px; display: inline-block">SSID</div><input class="form-control input-sm" placeholder="SSID" style="width: 150px; display: inline-block; " c-lass="input" name="supplicant_ssid" value="<?=$mod_supplicant_ssid?>">
				<br>
				<div style="width: 50px; display: inline-block">PSK</div><input class="form-control input-sm" placeholder="PSK" style="width: 150px; display: inline-block; " c-lass="input" type="password" name="supplicant_psk" value="<?=$mod_supplicant_psk?>">
				<br>
				<?
				$ifaces = exec("/sbin/ifconfig -a | cut -c 1-8 | sort | uniq -u |grep -v lo|sed ':a;N;$!ba;s/\\n/|/g'");
				$ifaces = str_replace(" ","",$ifaces);
				$ifaces = explode("|", $ifaces);
				?>
				<div style="width: 50px; display: inline-block">IFACE</div><select class="form-control input-sm" style="width: 150px; display: inline-block; " c-lass="input" name="supplicant_iface" >
				<option>-</option>
				<?
				for ($i = 0; $i < count($ifaces); $i++) {
					if (strpos($ifaces[$i], "mon") === false) {
					if ($mod_supplicant_iface == $ifaces[$i]) $flag = "selected" ; else $flag = "";
					echo "<option $flag>$ifaces[$i]</option>";
					}
				}
				?>
				</select>
				<input class="btn btn-default btn-sm" c-lass="input" type="submit" value="Save">
				<input type="hidden" name="type" value="save_supplicant">
				
				<br><br>
				
			</form>
				<? /*
				<input id="supplicant_dns" type="checkbox" name="my-checkbox" <? if ($mod_supplicant_dns == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_supplicant_dns')" > DNS
				<br>
				<input id="supplicant_dns_value" class="form-control input-sm" placeholder="DNS" value="<?=$mod_supplicant_dns_value?>" style="width: 200px; display: inline-block; " type="text" />
				<input class="btn btn-default btn-sm" type="submit" value="save" onclick="setOption('supplicant_dns_value', 'mod_supplicant_dns_value')">
				*/ ?>
		</div>
		<!-- END OPTIONS -->
	
		<!-- HISTORY -->
        <div id="tab-history" class="history">
            <input type="submit" value="refresh">
            <br><br>
            
            <?
            $logs = glob($mod_logs_history.'*.log');
            //print_r($a);

            for ($i = 0; $i < count($logs); $i++) {
                $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=3'><b>x</b></a> ";
                echo $filename . " | ";
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
                echo "<br>";
            }
            ?>
            
        </div>
        <!-- END HISTORY -->
		
		<!-- ABOUT -->
		<div id="tab-about" class="history">
			<? include "includes/about.php"; ?>
		</div>
		<!-- END ABOUT -->
        
    </div>

    <div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
        Loading...
    </div>

    <?
    if ($_GET["tab"] == 1) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 0 });";
        echo "</script>";
    } else if ($_GET["tab"] == 2) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 1 });";
        echo "</script>";
    } else if ($_GET["tab"] == 3) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 2 });";
        echo "</script>";
    } else if ($_GET["tab"] == 4) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 3 });";
        echo "</script>";
    } 
    ?>

</div>

<script type="text/javascript">
    $('#loading').hide();
    $(document).ready(function() {
        $('#body').show();
        $('#msg').hide();
    });
</script>

<script>
    $('.btn-default').on('click', function(){
        //alert($(this).find('input').attr('name'));
        //alert($(this).find('input').attr('id'));
        $(this).addClass('active').siblings('.btn').removeClass('active');
        param = ($(this).find('input').attr('name'));
        value = ($(this).find('input').attr('id'));
        //setOption(param, value);
        $.getJSON('../api/includes/ws_action.php?api=/config/module/supplicant/'+param+'/'+value, function(data) {});
    }); 
</script>

</body>
</html>
