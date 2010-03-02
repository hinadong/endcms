<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>TITLE -  后台</title>
<base href="<?php echo $_obj['url_base']; ?>" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo $_obj['css']; ?>/admin.css" type="text/css" media="all" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo $_obj['css']; ?>/style.css" type="text/css" media="all" />
<script language="JavaScript" src="public/js/jquery/jquery.js" type="text/javascript"></script>
<script language="JavaScript" src="public/js/common.js" type="text/javascript"></script>
<script>
can_not_update = false;
can_not_delete = false;
can_not_add = false;
</script>
</head>


<body>
<div class='maindiv'>

	<div class="headerdiv">
		<div class='logo'>
</div>

	<div class='navi'>
		<ul>
			
			
            <?php

		$display_controllers = array(
			'item',
			'category',
			'admin',
			'user',
			'rights',
			'account',
			'config',
			'comment',
			'page',
			'personpreview',
			'auction'
		);
		foreach($display_controllers as $_p)
		{
			if (!$_SESSION['login_user']['allowed_controllers'][$_p]) continue;
			echo "<li><a  ";
			if (END_CONTROLLER == $_p) echo " class='navi_on' ";
			echo "href='admin.php?p={$_p}'>".lang('NAVI_'.$_p)."</a></li>";
		}
		?>
            
            <li><a href='admin.php?p=login&m=logout&module=admin&backurl=index.php'> 注销登陆</a></li>
            
		</ul>
	</div>
	<script>
	<?php if (!check_show('update')):?>
	can_not_update = true;
	<?php endif;?>
	</script>

	</div>
	
	<div id='contentdiv'>
		<div id='left'>
	    	<div style="margin:10px;margin-top:30px;">
				
	        	<ul>
	                
	            </ul>
	    		
	        </div>
	    </div>
	    <div id='maindiv'>
			<div class="description">
				公告管理
	        </div>
	
	        <div class="real_content">
				<form style="margin:10px;" action="admin.php?p=programannounce&program_id=<?php echo $_obj['program_id']; ?>&action=add" method="post">
					<h1>"<?php echo $_obj['program']['title']; ?>" 的公告</h1>
					<?php if (check_show('add')){?>
					<h3>添加公告</h3>
					<textarea style="width:700px;height:100px;" name="content" id="editor_content" rich="true"></textarea>
					<input type="submit" value="提交" />
					<?php }?>
				</form>
					
				
	            
<table cellpadding="0" cellspacing="1" border="0" class="align_center list_table" id="admin_table">
            	<thead>
                   	<tr>
                        <th width="150">时间</th>
                   		<th >内容</th>
                        
                    </tr>
                </thead>
                <tbody>
                
          		  	<?php if (!empty($_obj['announces'])){if (!is_array($_obj['announces']))$_obj['announces']=array(array('announces'=>$_obj['announces'])); $_stack[$_stack_cnt++]=$_obj; $rowcounter = 0; foreach ($_obj['announces'] as $rowcnt=>$announces) { $announces['ROWCNT']=($rowcounter); $announces['ALTROW']=$rowcounter%2; $announces['ROWBIT']=$rowcounter%2; $rowcounter++;$_obj=&$announces; ?>
            		<tr>
                        <td><?php echo $_obj['created_at']; ?></td>
               		    <td><?php echo $_obj['content']; ?></td>
                        
               		</tr>
                    <?php } $_obj=$_stack[--$_stack_cnt];} ?>
                    
                </tbody>
                
</table>

<script>

function delete_user(id,value,o)
{
	$(o).attr('to_be_delete','yes').html(' ...').unbind('click');
	$.post('admin.php?p=ajax&m=delete&column=status&table=programannounce&id='+id,{ value:value },delete_callback);
}

function delete_callback(s)
{
	if(parseInt(s)>0)
		$('[to_be_delete=yes]').parent().parent().fadeOut();
		else
			alert(s);
}
</script>
				<div style="text-align:center"><?php echo $_obj['pager']; ?></div>
				
	        </div>
	        
	    </div>
	</div>
	
	
	
	
	<div class="footer">
Copyright @ <a href="http://longbill.cn" target="_blank">Longbill</a> (<?php echo $_obj['time_used']; ?>s) (<?php echo $_obj['total_query']; ?>)
</div>
<script src="end_system/plugin/ckeditor/ckeditor.js"></script>
<script>
$(function()
{
	//是否启动点击编辑功能
	if (!can_not_update) load_admin();
	//替换富文本编辑框
	if ($('textarea[rich]').length > 0)
	{
	
		$('textarea[rich]').each(function()
		{
			CKEDITOR.replace( this.id,
			{
				height:$(this).height(),
				skin:'v2',
				width:$(this).width()
			});
		});
	}
	//处理排序
	var url = window.location.href.toString();
	$('th[order]').each(function()
	{
		var order = $(this).attr('order');
		var text = $(this).html();
		if (!order || !text) return;
		var extra = '';
		$(this).html('');
		//新的排序
		var new_url = url.replace(/\&(order|desc|asc)=\w+/ig,'').replace(/\&page=\d+/ig,'');
		new_url+= '&order='+order;
		
		var reg = new RegExp("order\="+order,"i");
		if (reg.test(url)) //如果是用当前排序
		{
			if (/asc\=true/.test(url)) //如果倒序
			{
				new_url += '&desc=true';
				extra = $('<img src="end_system/view/admin/images/asc.gif" />');
			}
			else
			{
				new_url += '&asc=true';
				extra = $('<img src="end_system/view/admin/images/desc.gif" />');
			}			
		}
		$(this).append($('<a href="'+new_url+'">'+text+'</a>')).append(extra);
	});
});
</script>
<script>
$(function()
{
	$('#announce_content').focus();
});
</script>
</div>
</body>
</html>