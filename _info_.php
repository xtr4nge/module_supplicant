<?
$mod_name="supplicant";
$mod_version="1.3";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_panel="show";
$mod_logs_panel="disabled";
$mod_type="service";
$mod_alias="Supplicant";

# OPTIONS
$mod_supplicant_security="secure";
$mod_supplicant_ssid="";
$mod_supplicant_psk="";
$mod_supplicant_iface="-";
$mod_supplicant_dns="1";
$mod_supplicant_dns_value="8.8.8.8";

# EXEC
$bin_sudo = "/usr/bin/sudo";
$bin_ifconfig = "/sbin/ifconfig";
$bin_iw = "/sbin/iw";
$bin_iwlist = "/sbin/iwlist";
$bin_wpa_passphrase = "/usr/bin/wpa_passphrase";
$bin_wpa_supplicant = "/sbin/wpa_supplicant";
$bin_dhclient = "/sbin/dhclient";
$bin_sh = "/bin/sh";
$bin_echo = "/bin/echo";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
$bin_chmod = "/bin/chmod";
$bin_sed = "/bin/sed";
$bin_rm = "/bin/rm";
$bin_route = "/sbin/route";
$bin_perl = "/usr/bin/perl";
$bin_sleep = "/bin/sleep";
//$bin_nmcli = "/usr/share/fruitywifi/www/modules/nmcli/includes/NetworkManager/cli/src/nmcli";

# ISUP
$mod_isup="ps aux|grep -iEe 'wpa_supplicant.+FruityWiFI_SUPPLICANT.conf' | grep -v grep";
?>
