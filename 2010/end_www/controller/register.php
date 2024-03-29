<?php


if ($_GET['id'] == '1')
{
	$data = filter_array($_POST,'fname!,lname!,city,states,zip,phone,fax,street,email!,end_encode:password!');
	if ($data)
	{
		$data['status'] = ($_POST['type'] == 'wholesale')?'1':'0';
		$data['create_time'] = time();
		
		if (model('user')->exists(array('email'=>$data['email'])))
		{
			$view_data['msg'] = 'This email has been used!';
			$data = false;
		}
		
		if ($data && model('user')->add($data))
		{
			//注册成功 
			$email = str_replace( 
					array('{http_host}','{user_email}'),
					array($_SERVER['HTTP_HOST'],
					$data['email']),$config['reg_email']
				);
			
			end_mail($data['email'],'Registeration from '.$_SERVER['HTTP_HOST'],$email);
			
			
			
			
			echo '<script>';
			if ($_POST['type'] == 'wholesale')
			{
				echo 'alert("Your wholesale account is waiting for confirmation!");';
			}
			else
			{
				$_SESSION['user'] = $data;
				echo 'alert("Thank you! Your account has been created!");';
			}
			echo 'window.location = "./";';
			echo '</script>';
			die;
		}
	}
	else
	{
		$view_data['msg'] = 'All fields are required!';
	}
}

$view_data['title'] = 'Register an Account';