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
<?
include "../../../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
    regex_standard($iface_supplicant, "../msg.php", $regex_extra);
    regex_standard($supplicant_ssid, "../msg.php", $regex_extra);
    regex_standard($supplicant_psk, "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$install = $_GET['install'];

//if($service == "nmcli" and $ss_mode == "mode_supplicant") {
if($service == "nmcli") {
    
    if ($action == "start") {
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            exec_fruitywifi($exec);
            
            $exec = "$bin_echo '' > $mod_logs";
            exec_fruitywifi($exec);
        }
		
		//KILL WPS_SUPPLICANT		
		$exec = "ps aux|grep -iEe 'FruityWiFI_SUPPLICANT.conf' | grep -v grep | awk '{print $2}'";
		exec($exec,$output);
		$exec = "kill " . $output[0];
		exec_fruitywifi($exec);
		
		unset($output);
		
		//KILL DHCLIENT
		$exec = "ps aux|grep -iEe 'dhclient $mod_supplicant_iface' | grep -v grep | awk '{print $2}'";
		exec($exec,$output);
		$exec = "kill " . $output[0];
		exec_fruitywifi($exec);
		
		//SETUP & START
		$exec = "$bin_wpa_passphrase '$mod_supplicant_ssid' '$mod_supplicant_psk' > FruityWiFI_SUPPLICANT.conf";
		exec_fruitywifi($exec);
		
		if ($mod_supplicant_security == "open") {
			$exec = "$bin_sed -i 's/psk=.*/key_mgmt=NONE/g' FruityWiFI_SUPPLICANT.conf";
			$output = exec_fruitywifi($exec);
		}
		
		$exec = "$bin_ifconfig $mod_supplicant_iface up";
		exec_fruitywifi($exec);
		$exec = "$bin_iwlist $mod_supplicant_iface scan";
		exec_fruitywifi($exec);
		$exec = "$bin_wpa_supplicant -i $mod_supplicant_iface -f $mod_logs -t -D wext -c FruityWiFI_SUPPLICANT.conf > /dev/null 2 &";
		exec_fruitywifi($exec);
		$exec = "nohup bash -c '$bin_dhclient $mod_supplicant_iface -d' > /dev/null 2 &"; //ALTERNATIVE
		//$exec = "sudo tmux new -s DHCLIENT -d '$bin_dhclient $mod_supplicant_iface -d'"; //ALTERNATIVE
        exec_fruitywifi($exec);
		
		//$exec = "$bin_sed -i '1i nameserver 8.8.8.8' /etc/resolv.conf";
		//exec_fruitywifi($exec);
		
		$wait = 3;
		
		/*
        $exec = "$bin_ifconfig $iface_supplicant up";
        exec_fruitywifi($exec);
        $exec = "$bin_nmcli -n d disconnect iface $iface_supplicant";
        exec_fruitywifi($exec);
        $exec = "$bin_nmcli -n c delete id nmcli_raspberry_wifi";
        exec_fruitywifi($exec);
        		
        $exec = "$bin_iwlist $iface_supplicant scan";
        exec_fruitywifi($exec);
        
        $exec = "$bin_nmcli -n dev wifi connect '$supplicant_ssid' password '$supplicant_psk' iface $iface_supplicant name nmcli_raspberry_wifi";
        exec_fruitywifi($exec);
        */
		
    } else if($action == "stop") {
        // STOP MODULE
		
		//KILL WPS_SUPPLICANT		
		$exec = "ps aux|grep -iEe 'FruityWiFI_SUPPLICANT.conf' | grep -v grep | awk '{print $2}'";
		exec($exec,$output);
		$exec = "kill " . $output[0];
		exec_fruitywifi($exec);
		
		unset($output);
		
		//KILL DHCLIENT
		$exec = "ps aux|grep -iEe 'dhclient $mod_supplicant_iface' | grep -v grep | awk '{print $2}'";
		exec($exec,$output);
		$exec = "kill " . $output[0];
		exec_fruitywifi($exec);
		
		$exec = "$bin_ifconfig $mod_supplicant_iface 0.0.0.0";
		exec_fruitywifi($exec);
		
		$exec = "$bin_ifconfig $mod_supplicant_iface down";
		exec_fruitywifi($exec);
		
		/*
        $exec = "$bin_nmcli -n d disconnect iface $iface_supplicant";
        exec_fruitywifi($exec);
        $exec = "$bin_nmcli -n c delete id nmcli_raspberry_wifi";
        exec_fruitywifi($exec);
        */
		
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            exec_fruitywifi($exec);
            
            $exec = "$bin_echo '' > $mod_logs";
            exec_fruitywifi($exec);
        }
    }
}

if ($install == "install_$mod_name") {

    $exec = "$bin_chmod 755 install.sh";
    exec_fruitywifi($exec);
    
    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    exec_fruitywifi($exec);

    header('Location: ../../install.php?module='.$mod_name);
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php?wait='.$wait);
} else if ($page == "config") {
    header('Location: ../../../page_config.php');
} else {
    header('Location: ../../action.php?page='.$mod_name.'&wait='.$wait);
}

?>
