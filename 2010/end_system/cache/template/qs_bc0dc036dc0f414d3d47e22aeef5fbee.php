<div >
            <table cellpadding="0" cellspacing="0" border="0" id="item_table" class="list_table" >
            	<thead>
                   	<tr>
                		<th width="" order="title">问题</th>
						<th style="width:80px;" order="status">状态</th>
                        <th width="100"> 操作</th>
                    </tr>
                </thead>
                <tbody>
                
          		  	<?php if (!empty($_obj['items'])){if (!is_array($_obj['items']))$_obj['items']=array(array('items'=>$_obj['items'])); $_stack[$_stack_cnt++]=$_obj; $rowcounter = 0; foreach ($_obj['items'] as $rowcnt=>$items) { $items['ROWCNT']=($rowcounter); $items['ALTROW']=$rowcounter%2; $items['ROWBIT']=$rowcounter%2; $rowcounter++;$_obj=&$items; ?>
            		<tr>
               		 	
               		 	<td><div admin_type="text"
                        	admin_para="m=update&table=<?php echo $_stack[0]['table']; ?>&column=title&id=<?php echo $_obj['question_id']; ?>" 
                            admin_trigger="click"><?php echo $_obj['title']; ?></div>
                        </td>
                        
						<td><div admin_type="text"
		              	admin_para="m=update&table=<?php echo $_stack[0]['table']; ?>&column=status&id=<?php echo $_obj['question_id']; ?>" 
		                    admin_select_value="<?php echo $_obj['status']; ?>"
		                    admin_select_source_id="status_select"
		                    admin_trigger="click"
							><?php
echo show_status($_obj['status']);
?></div></td>
                        	<td>
								<?php if (check_show('update')):?>
	                        	<a href = "admin.php?p=item&action=edit_item&item_id=<?php echo $_obj['question_id']; ?>&category_id=<?php echo $_stack[0]['category_id']; ?>" > 编辑</a>
								<?php endif;?>&nbsp;
								<?php if (check_show('delete')):?>
	                            <a href="javascript:;" onclick="if (confirm(' 确定要删除吗？')) change_status(<?php echo $_obj['question_id']; ?>,2,this);"> 删除</a>
								<?php endif;?>&nbsp;
								<a href="admin.php?p=comment&itemid=<?php echo $_obj['question_id']; ?>&itemtype=question" target="_blank">评论</a>			
	                        </td>
               		</tr>
                    <?php } $_obj=$_stack[--$_stack_cnt];} ?>
                    
                </tbody>
                
            </table>
</div>
            <?php echo $_obj['pager']; ?>
            
            
<div style="display:none">
<select id="category_select">
	<?php echo $_obj['category_tree']; ?>
</select>
</div>


<div style="display:none">
<select id="status_select">
	<?php if (!empty($_obj['statuses'])){if (!is_array($_obj['statuses']))$_obj['statuses']=array(array('statuses'=>$_obj['statuses'])); $_stack[$_stack_cnt++]=$_obj; $rowcounter = 0; foreach ($_obj['statuses'] as $rowcnt=>$statuses) { $statuses['ROWCNT']=($rowcounter); $statuses['ALTROW']=$rowcounter%2; $statuses['ROWBIT']=$rowcounter%2; $rowcounter++;$_obj=&$statuses; ?>
	<option value="<?php echo $_obj['index']; ?>"><?php echo $_obj['value']; ?></option>
	<?php } $_obj=$_stack[--$_stack_cnt];} ?>
</select>
</div>