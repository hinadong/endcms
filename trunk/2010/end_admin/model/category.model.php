<?php
class MODEL_CATEGORY extends MODEL
{
	var $categories_list;
	function MODEL_CATEGORY()
	{
		$this->table = END_MYSQL_PREFIX.'category';
		$this->order_id = 'order_id';
		$this->id = 'category_id';
	}

	function delete($id)
	{
		check_allowed_category($id,END_RESPONSE == 'text');
		return parent::delete($id);
	}
	
	function add($data)
	{
		if ($data['parent_id'])
		{
			check_allowed_category($data['parent_id'],END_RESPONSE == 'text');
		}
		$re = parent::add($data);
		if (!$data['create_time']) $data['create_time'] = time();
		return $re;
	}

	function update($id,$data)
	{
		check_allowed_category($id,END_RESPONSE == 'text');
		if (!$data['update_time']) $data['update_time'] = time();
		return parent::update($id,$data);
	}

	function position_category($id)
	{
		$cond = is_array($id)?$id:array($this->id=>$id); 
		$re = array();
		while($cond[$this->id])
		{
			$arr = $this->get_one($cond);
			if (!$arr || (is_array($arr) && count($arr)==0)) break;
			$id = $arr['category_id'];
			if (END_MODULE == 'admin' && $_SESSION['login_user']['rights']['limit_category_id'] && $_SESSION['login_user']['allowed_categories'])
			{
				if (strpos(','.$_SESSION['login_user']['allowed_categories'].',',','.$id.',') !== false)
					$re[] = $arr;
			}
			else
				$re[] = $arr;
			$cond[$this->id] = $arr['parent_id'];
		}
		$re = array_reverse($re);
		return $re;
	}
	
	function tree_category($data=0)
	{
		if (!is_array($data)) $data = array('parent_id'=>intval($data));
		$parent_id = intval($data['parent_id']);
		unset($data['parent_id']);
		$_all = $this->get_list($data);
		$all_categories = array();
		foreach($_all as $_cat)
		{
			$all_categories[$_cat['category_id']] = $_cat;
		}
		unset($_all);
		return $this->_tree_category($all_categories,$parent_id);
	}
	
	function _tree_category($all_categories,$parent_id,$depth = 0)
	{
		$re = array();
		foreach($all_categories as $cat)
		{
			if ($cat['parent_id'] == $parent_id)
			{
				$re[$cat['category_id']] = $cat;
				$re[$cat['category_id']]['depth'] = $depth;
				$re[$cat['category_id']]['children'] = $this->_tree_category($all_categories,$cat['category_id'],$depth+1);
			}
		}
		return $re;
	}

	function flat_tree($tree,&$re = array())
	{
		foreach($tree as $c)
		{
			$re[] = $c;
			$re[count($re)-1]['children'] = null;
			if (count($c['children'])>0) $this->flat_tree($c['children'],$re);
		}
	}
	
	function get_list($data=array())
	{
		if (END_MODULE == 'admin' && $_SESSION['login_user']['rights']['limit_category_id'] && $_SESSION['login_user']['allowed_categories'])
		{
			$data['where'] && $data['where'] .= ' AND ';
			$data['where'] .= 'category_id IN ('.$_SESSION['login_user']['allowed_categories'].')';
		}
		return parent::get_list($data);
	}
}
?>