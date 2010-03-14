<?php
/**********************************
*     		EndCMS
*       www.endcms.com
*         ©2008-now
* under Creative Commons License
**********************************/

/**
 * 载入一个model
 *
 * @param string $f 
 * @return model class
 */
function model($f)
{
	//缓存
	if (isset($GLOBALS['end_model_instance_'.$f])) return $GLOBALS['end_model_instance_'.$f];
	
	//优先使用end_models目录下的model
	if (file_exists($_file = END_MODEL_PATH.$f.'/'.$f.'.model.php'))
		include_once($_file);
	//其次是end_system/model目录下的
	else if (file_exists($_file = END_BASEPATH.'model/'.$f.'.model.php'))
		include_once($_file);
	else
		die("load model error! Model file not found: $f.model.php");
	
	if (class_exists($_class_name = 'MODEL_'.strtoupper($f)))
		return $GLOBALS['end_model_instance_'.$f] = new $_class_name;
	else
		die("Load model error! Class '$_class_name' not found in file $_file");
}

/**
 * 创建一个模板对象
 *
 * @param string $f 
 * @param string [$viewdir]
 * @return template class
 */
function template($f,$viewdir = false)
{
	global $_debug;
	$_template = new QuickSkin($f);
	$_template->set( 'reuse_code', !$_debug );
	$_template->set( 'extensions_dir', 'library/quickskin_extensions/' );	
	$_template->set( 'template_dir', $viewdir?$viewdir:END_VIEWER_DIR );
	$_template->set( 'temp_dir', 'cache/template/' );
	$_template->set( 'cache_dir', 'cache/' );
	//$_template->set( 'cache_lifetime', 200 );
	return $_template;
}

/**
 * 非对称加密
 *
 * @param string $s 
 * @return string encoded string
 */
function end_encode($s)
{
	$s = md5($s).$s.'something very very very very very very very very long';
	return sha1($s);
}


/**
 *过滤数组
 *	input example: md5:key1,key2,base64_encode:key3!,intval:key4
 *	! represents must, if it equals null then return false
 *	function_name:key, handle key to function_name
 *	key2=key1, rename key1 to key2
 *	
 * 	e.g. id=intval:item_id!
*/
function filter_array($arr,$keys,$write_global = false)
{
	$re = array();
	$key_arr = explode(',',$keys);
	foreach($key_arr as $key)
	{
		$key = trim($key);
		if (!$key) continue;
		$_must = false;
		$_func = false;
		$_key = false;
		if (strpos($key,'=') !== false)
		{
			$_arr = explode('=',$key);
			$_key = $_arr[0];
			$key = $_arr[1];
		}
		if (strpos($key,'!') !== false)
		{
			$_must = true;
			$key = str_replace('!','',$key);
		}
		if (strpos($key,':') !== false)
		{
			$_arr = explode(':',$key);
			$_func = $_arr[0];
			$key = $_arr[1];
		}
		!$_key && $_key = $key;
		if ($_func)
			$arr[$key] = $_func($arr[$key]);
		if ($_must && !$arr[$key]) 
			return false;
		else
			$re[$_key] = isset($arr[$key])?$arr[$key]:NULL;
	}
	if ($write_global)
	{
		foreach($re as $key => $val)
		{
			$GLOBALS[$key] = $val;
		}
	}
	return $re;
}

/**
 * 载入一个语言文件
 *
 * @param string $path 
 * @return void
 */
function language($path)
{
	if (strpos($path,'.') === false) $path.= '.lang';
	$_f = END_LANGUAGE_DIR.END_LANGUAGE.'/'.$path;
	if (file_exists($_f))
	{
		$lines = file($_f);
		foreach($lines as $line)
		{
			$line = str_replace(array("\r","\n"),'',$line);
			if (preg_match('/^([a-zA-Z]{1}[a-zA-Z0-9_]*)\s*=(.*)$/',$line,$ms))
				define('LANG_'.strtoupper($ms[1]),$ms[2]);
		}
	}
}

/**
 * 获得一个语言字符串
 *
 * @param string $key 
 * @return string language string
 */
function lang($key)
{
	$name = 'LANG_'.strtoupper($key);
	if (defined($name))
		return constant($name);
	else
	{
		//here do some thing log
		return $key;
	}
}