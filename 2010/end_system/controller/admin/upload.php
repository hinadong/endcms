<?php
END_MODULE != 'admin' && die('Access Denied');
$action = $_GET['action'];
$m = $_GET['m'];
$file = $_FILES['upfile'];
if (!is_dir(END_TOPPATH.END_UPLOAD_PATH)) end_mkdir(END_TOPPATH.END_UPLOAD_PATH,0777);
$err = true;

//权限检查
if (!$_SESSION['login_user']['rights']['upload_add'])
{
	die(LANG_ACCESS_DENIED);
}
if ($file['tmp_name'])
{
	$myfile=$file["tmp_name"];
	$ftype = getext($file['name']);
	if (!$ftype || !preg_match("/\*\.$ftype;/i",$config['upload_file_types']))
	{
		$err = true;
		$msg = lang('type_not_allowed');
	}
	else
	{
		//$file_url = END_UPLOAD_PATH.basename($file['name']);
		$file_url = END_UPLOAD_PATH.date('m_d_H_i_s_').rand(1111,9999).'.'.$ftype;
		if (@move_uploaded_file($myfile,END_TOPPATH.$file_url))
		{
			$err = false;
			$msg = lang('success');
		}
		else
		{
			$err = lang('failed');
		}
	}
	if (strpos(',jpg,jpeg,gif,png,bmp',','.$ftype.',') !== false)
	{
		$view_data['is_img'] = true;
		include_once('library/image.php');
		$img = new Image;
		$img->filepath = END_TOPPATH.$file_url;
		$img->resize_width($config['max_image_width']?$config['max_image_width']:500);
	}
	$view_data['file_url'] = $file_url;
	$view_data['filename'] = $file['name'];
	$view_data['err'] = $err;
	$view_data['msg'] = $msg;
}

$handler = @opendir(END_TOPPATH.END_UPLOAD_PATH);
$recent = array();
while(($val = readdir($handler)) !== false)
{
	if ($val == '.' || $val == '..' || !is_file(END_TOPPATH.END_UPLOAD_PATH.$val)) continue;
	$fname = $val;
	$ftype = strtolower(getext($val));
	$encode = (preg_replace('/[a-zA-Z0-9_\.\{\}\[\]\(\)]*/i','',$val) != '');
	$recent[] = array( 
		'name'=>$fname,
		'filepath'=>END_UPLOAD_PATH.$val,
		'mtime'=>filemtime(END_TOPPATH.END_UPLOAD_PATH.$val),
		'ftype'=>$ftype,
		'encode'=>$encode?'yes':'no',
		'isimg'=>(strpos(',jpg,jpeg,gif,png,bmp',','.$ftype.',') === false)?'no':'yes'
	 );
}
closedir($handler);
usort($recent,"cmp_time");
$view_data['recent'] = array();
$view_data['for'] = $_GET['for'];
foreach($recent as $arr)
{
	$view_data['recent'][] = $arr;
	if (count($view_data['recent']) > 20) break;
}
unset($recent);

function cmp_time($a,$b)
{
	if ($a['mtime'] == $b['mtime']) return 0;
	return ($a['mtime'] < $b['mtime'])?1:-1;
}
		
?>