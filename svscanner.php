<?php
error_reporting(0);
session_start();
define('SENDINBOX_PATH', realpath(dirname(__FILE__)));
require_once("Modules/database.php");
require_once("Modules/wploit-modules.php");
require_once("Modules/sdata-modules.php");
require_once("Modules/scanner.php");

require_once("Exploit/default-admin.php");
require_once("Exploit/plugins-email-subscribers.php");
require_once("Exploit/plugins-gravityforms.php");
/**
 * @Author: Eka Syahwan
 * @Date:   2017-12-11 17:01:26
 * @Last Modified by:   shor7cut
 * @Last Modified time: 2018-09-13 12:17:50
*/
class wploit
{
	function __construct()
	{

		mkdir("log");

		$this->wploit_modules 					= new wploit_modules;
		$this->database 						= new Database;
		$this->scanner 							= new Scanner;
		$this->ExploitDefaultAdmin 				= new ExploitDefaultAdmin;
		$this->Exploit_Plugins_emailsubscribers = new Exploit_Plugins_emailsubscribers;
		$this->Exploit_Plugins_gravityforms 	= new Exploit_Plugins_gravityforms;


	  	echo $this->wploit_modules->color("green","\n========================================================\r\n\n");
        echo $this->wploit_modules->color("green","┌─┐┬  ┬┌─┐┌─┐┌─┐┌┐┌┌┐┌┌─┐┬─┐ Version : 1.1\r\n");
		echo $this->wploit_modules->color("green","└─┐└┐┌┘└─┐│  ├─┤││││││├┤ ├┬┘ Author  : Eka Syahwan\r\n");
		echo $this->wploit_modules->color("green","└─┘ └┘ └─┘└─┘┴ ┴┘└┘┘└┘└─┘┴└─\r\n");
       	echo $this->wploit_modules->color("random","\r\n-= Scanner Vulnerability And MaSsive Exploit =-\r\n");
       	echo $this->wploit_modules->color("green","\n========================================================\r\n\n");

    

		#########################################
		$this->wploit_modules->delay 	= 0;
		$this->menu 					= $this->database->menu();
		$this->menu_exploit 			= $this->database->menu_exploit();
		#########################################
		foreach ($this->menu as $key => $value) {
			echo $this->wploit_modules->color("nevy","[SVScanner] [".$key."] ".$value['title']."\r\n");
		}
		echo $this->wploit_modules->color("random","\n========================================================\r\n\n");
		$select = $this->wploit_modules->stuck("Select Number : ");
		$threads = $this->wploit_modules->stuck("Threads : ");
		
		//$select  	= 3;
		//$threads  	= 500;

		$this->wploit_modules->threads 	= $threads;
		
		switch ($this->menu[$select]['action']) {
			case 'wordpress_scanner_plugin':
				$this->scanner_plugins();echo "\r\n";
			break;
			case 'wordpress_exploit_plugin_themes':
				echo "\r\n";
				echo $this->wploit_modules->color("random","\n========================[ MENU EXPLOIT ]================\r\n\n");
				foreach ($this->menu_exploit as $key => $value) {
					echo $this->wploit_modules->color("nevy","[SVScanner] [".$key."] ".$value['title']."\r\n");
				}
				echo $this->wploit_modules->color("random","\n========================================================\r\n\n");
				$select = $this->wploit_modules->stuck("Select Number : ");


				switch ($this->menu_exploit[$select]['action']) {
					case 'Email_Subscribers':
						$this->exploit_email_subscribers();
					break;
					case 'Gravity_Forms':
						$this->exploit_Gravity_Forms();
					break;
					default:
						die('!error!');
					break;
				}

			break;
			case 'scanner_cms_detector':
				$this->scanner_cms();echo "\r\n";
			break;
			case 'wordpress_exploit_defaultadmin':
				$this->exploit_defaultadmin();echo "\r\n";
			break;
			default:
				die('!error!');
			break;
		}
	}
	function filter_domain($url){
		$url = parse_url($url);
		return $url['scheme']."://".$url['host'];
	}
	function scanner_plugins(){
		$dataConfig = $this->wploit_modules->required();
		$xselc  = $this->wploit_modules->stuck("Total Request : ".(count($dataConfig['list'])*$dataConfig['threads'])." ( ".(count($this->database->wordpress_plugins())*$dataConfig['threads'])." / Request ), Keep going ? [0 = NO , 1 = YES] : ");echo "\r\n";
		if($xselc == 0){
			die('!error!');
		}
		foreach ($dataConfig['list'] as $keys => $dataurl) {
			$fopn = fopen("log/log-scannerPlugins-".$dataConfig['namafile'].".txt", "w");
			foreach ($dataurl as $ukey => $url) {
				foreach ($this->database->wordpress_plugins() as $key => $dbPlugins) {
					$config_url[] =  array('url' => $this->filter_domain($url)."/".$dbPlugins , 'plugin' => $key);
				}
				fwrite($fopn, $ukey."|".$url."\r\n");
			}
			fclose($fopn);
			$this->scanner->wordpress_plugins($config_url); unset($config_url);
			sleep($dataConfig['delay']);
		}
	}
	function scanner_cms(){
		$dataConfig = $this->wploit_modules->required();
		$xselc  	= $this->wploit_modules->stuck("Total Request : ".(count($dataConfig['list'])*$dataConfig['threads'])." , Keep going ? [0 = NO , 1 = YES] : ");echo "\r\n";
		if($xselc == 0){
			die('!error!');
		}

		$logScan = 0;

		foreach ($dataConfig['list'] as $keys => $dataurl) {
			$fopn = fopen("log/log-cmsdetector-".$dataConfig['namafile'].".txt", "w");
			foreach ($dataurl as $ukey => $url) {
				$logScan 	  = ($logScan+1);
				$config_url[] =  array('url' => $this->filter_domain($url) );
				fwrite($fopn, $ukey."|".$url."\r\n");
			}
			fclose($fopn);
			$this->scanner->cms_detector($config_url , "Line : ".$logScan." of ".ceil((count($dataConfig['list'])*$dataConfig['threads']))  ); unset($config_url);
			sleep($dataConfig['delay']);
		}
	}
	function exploit_defaultadmin(){
		$dataConfig = $this->wploit_modules->required();
		$xselc  	= $this->wploit_modules->stuck("Total Request : ".(count($dataConfig['list'])*$dataConfig['threads'])." , Keep going ? [0 = NO , 1 = YES] : ");echo "\r\n";
		if($xselc == 0){
			die('!error!');
		}
		foreach ($dataConfig['list'] as $keys => $dataurl) {
			$fopn = fopen("log/log-defaultadmin-".$dataConfig['namafile'].".txt", "w");
			foreach ($dataurl as $ukey => $url) {
				$config_url[] =  array('url' => $this->filter_domain($url));
				fwrite($fopn, $ukey."|".$url."\r\n");
			}
			fclose($fopn);
			$this->ExploitDefaultAdmin->scanner($config_url); unset($config_url);
			sleep($dataConfig['delay']);
		}
	}
	function exploit_email_subscribers(){
		$dataConfig = $this->wploit_modules->required();
		$xselc  	= $this->wploit_modules->stuck("Total Request : ".(count($dataConfig['list'])*$dataConfig['threads'])." , Keep going ? [0 = NO , 1 = YES] : ");echo "\r\n";
		if($xselc == 0){
			die('!error!');
		}
		foreach ($dataConfig['list'] as $keys => $dataurl) {
			$fopn = fopen("log/log-Exploit_Plugins_emailsubscribers-".$dataConfig['namafile'].".txt", "w");
			foreach ($dataurl as $ukey => $url) {
				$logScan 	  = ($logScan+1);
				$config_url[] =  array('url' => $this->filter_domain($url));
				fwrite($fopn, $ukey."|".$url."\r\n");
			}
			fclose($fopn);
			$this->Exploit_Plugins_emailsubscribers->scanner($config_url , "Line : ".$logScan." of ".ceil((count($dataConfig['list'])*$dataConfig['threads'])) ); unset($config_url);
			sleep($dataConfig['delay']);
		}
	}
	function exploit_Gravity_Forms(){
		$dataConfig = $this->wploit_modules->required();
		$xselc  	= $this->wploit_modules->stuck("Total Request : ".(count($dataConfig['list'])*$dataConfig['threads'])." , Keep going ? [0 = NO , 1 = YES] : ");echo "\r\n";
		if($xselc == 0){
			die('!error!');
		}
		foreach ($dataConfig['list'] as $keys => $dataurl) {
			$fopn = fopen("log/log-Exploit_Plugins_Gravity_Forms-".$dataConfig['namafile'].".txt", "w");
			foreach ($dataurl as $ukey => $url) {
				$logScan 	  = ($logScan+1);
				$config_url[] =  array('url' => $this->filter_domain($url));
				fwrite($fopn, $ukey."|".$url."\r\n");
			}
			fclose($fopn);
			$this->Exploit_Plugins_gravityforms->scanner($config_url , "Line : ".$logScan." of ".ceil((count($dataConfig['list'])*$dataConfig['threads'])) ); unset($config_url);
			sleep($dataConfig['delay']);
		}
	}
}
$wploit = new wploit;
$wploit->run();
