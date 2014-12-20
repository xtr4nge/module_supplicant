<?
$mod_name="supplicant";
$mod_version="1.1";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_panel="show";
$mod_logs_panel="disabled";
$mod_type="service";
$mod_alias="Supplicant";
$supplicant_ssid="";
$supplicant_psk="";
$supplicant_iface="wlan0";
$ss_mode = "mode_supplicant";

# EXEC
$bin_danger = "/usr/share/fruitywifi/bin/danger";
$bin_sudo = "/usr/bin/sudo";
$bin_ifconfig = "/sbin/ifconfig";
$bin_iwlist = "/sbin/iwlist";
$bin_sh = "/bin/sh";
$bin_echo = "/bin/echo";
$bin_grep = "/usr/bin/ngrep";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
$bin_chmod = "/bin/chmod";
$bin_sed = "/bin/sed";
$bin_rm = "/bin/rm";
$bin_route = "/sbin/route";
$bin_perl = "/usr/bin/perl";
$bin_sleep = "/bin/sleep";
$bin_nmcli = "/usr/share/fruitywifi/www/modules/nmcli/includes/NetworkManager/cli/src/nmcli";

# ISUP
$mod_isup="$bin_danger \"$bin_nmcli -n d | grep -iEe '^$supplicant_iface.+ connected'\"";
?>
