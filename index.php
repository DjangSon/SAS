<?php
	require './inc/config.php';
	require './inc/loading.php';
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!empty($_POST['radio'])){
			if ($_POST['radio'] == "option1"){
				$datas = $database -> select("student", "*",[
						"number" => $_POST['username']
				]);
				if (!empty($datas)){
					foreach ($datas as $data){
						$password = $data['password'];
						$username = $data['susername'];
					}
					if (md5($_POST['password']) == $password){
						setcookie('username',base64_encode(base64_encode($username)),time()+3600,'/');
						loading("student.php");
					}
				}
			}elseif ($_POST['radio'] == "option0"){
				$datas2 = $database -> select("staff", "*",[
						"username" => $_POST['username']
				]);
				if (!empty($datas2)){
					foreach ($datas2 as $data2){
						$spassword = $data2['password'];
						$realname = $data2['realname'];
					}
					if (md5($_POST['password']) == $spassword){
						setcookie('admin',base64_encode(base64_encode($realname)),time()+3600,'/');
						loading("dormitory.php");
					}
				}
			}
		}else {
			alertAndLoading("请选择身份", "index.php");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>登录页</title>
	
		<!--My CSS-->
		<link rel="stylesheet" href="assets/css/add-style.css" />
		
	    <!-- start: CSS file-->
		
		<!-- Vendor CSS-->
		<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/vendor/skycons/css/skycons.css" rel="stylesheet" />
		<link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		
		<!-- Plugins CSS-->
		<link href="assets/plugins/bootkit/css/bootkit.css" rel="stylesheet" />	
		
		<!-- Theme CSS -->
		<link href="assets/css/jquery.mmenu.css" rel="stylesheet" />
		
		<!-- Page CSS -->		
		<link href="assets/css/style.css" rel="stylesheet" />
		<link href="assets/css/add-ons.min.css" rel="stylesheet" />
		
		<style>
			footer {
				display: none;
			}
		</style>
		<!-- Head Libs -->
		<script src="assets/plugins/modernizr/js/modernizr.js"></script>
	
	</head>

	<body>
		<!-- Start: Content -->
		<div class="container-fluid content">
			<div class="row">
				<!-- Main Page -->
				<div id="content" class="col-sm-12 full">
					<div class="row">
						<div class="login-box">
							<div class="panel">
								<div class="panel-body">								
									<div class="header bk-margin-bottom-20 text-center">										
										<img src="assets/img/logo.png" class="img-responsive" alt="" />
										<!--<h4>学生公寓管理系统</h4>-->
									</div>		
									<form class="form-horizontal login" action="" method="post">
										<div class="bk-padding-left-20 bk-padding-right-20">
											<div class="form-group">
												<label>账号（学号/工作号）</label>
												<div class="input-group input-group-icon">
													<input type="text" class="form-control bk-radius" id="username" name="username" placeholder="学号/工作号"/>
													<span class="input-group-addon">
														<span class="icon">
															<i class="fa fa-user"></i>
														</span>
													</span>
												</div>
											</div>											
											<div class="form-group">
												<label>密码</label>
												<div class="input-group input-group-icon">
													<input type="password" class="form-control bk-radius" id="password" name="password" placeholder="密码"/>
													<span class="input-group-addon">
														<span class="icon">
															<i class="fa fa-key"></i>
														</span>
													</span>
												</div>
											</div>
											<div class="text-with-hr">
												<span>选择</span>
											</div>	
											<div class="row bk-margin-top-20 bk-margin-bottom-10">
												<div class="col-sm-6">
													<div class="radio-custom radio-inline" style="margin-right: 60px;">
														<input type="radio" id="inline-radio1" name="radio" value="option0"> 
														<label for="inline-radio1" style="margin-bottom: 8px;"> 教职工</label>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="radio-custom radio-inline">
														<input type="radio" id="inline-radio2" name="radio" value="option1"> 
														<label for="inline-radio2" style="margin-bottom: 8px;"> 学生</label>
													</div>
												</div>
											</div>
											<div class="text-center">
												<button type="submit" style="width: 390px;" class="mybtn mybtn1 hidden-xs" name="login-btn">登录</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<p class="text-center text-muted">宁德师范学院学生公寓管理系统</p>			
						</div>
					</div>			
				</div>
				<!-- End Main Page -->
			</div>
		</div><!--/container-->
		
		<!-- start: JavaScript-->
		
		<!-- Vendor JS-->				
		<script src="assets/vendor/js/jquery.min.js"></script>
		<script src="assets/vendor/js/jquery-2.1.1.min.js"></script>
		<script src="assets/vendor/js/jquery-migrate-1.2.1.min.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/vendor/skycons/js/skycons.js"></script>	
		
		<!-- Plugins JS-->
		<script src="assets/plugins/bootkit/js/bootkit.js"></script>
		
		<!-- Theme JS -->		
		<script src="assets/js/jquery.mmenu.min.js"></script>
		<script src="assets/js/core.min.js"></script>
		
		<!-- Pages JS -->
		<script src="assets/js/pages/page-login.js"></script>
		
		<!-- end: JavaScript-->
		
	</body>
	
</html>
