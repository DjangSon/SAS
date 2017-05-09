<?php
	require './inc/config.php';
	require './inc/loading.php';
	
	if (empty($_COOKIE['admin'])){
		alertAndLoading("请先登录", "index.php");
	}
	$datas = $database -> select("staff", "*",[
			"username" => base64_decode(base64_decode($_COOKIE['admin']))
	]);
	$datas2 = $database -> select("staff", "*");
	if (!empty($_REQUEST['act'])){
		$act = $_REQUEST['act'];
		if ($act == "deleteteacher"){
			$database -> delete("staff", [
					"ID" => $_GET['id']
			]);
			alertAndLoading("删除成功", "teacher.php");
		}
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!empty($_REQUEST['act'])){
			$act = $_REQUEST['act'];
			if ($act == "addteacher"){
				if ($_POST['job']== 0){
					$job = "宿管";
				}else {
					$job = "物业";
				}
				$database -> insert("staff", [
						"username" => $_POST['username'],
						"realname" => $_POST['realname'],
						"password" => md5($_POST['password']),
						"phone" => $_POST['phone'],
						"job" => $job,
						"email" => $_POST['email']
				]);
				alertAndLoading("添加成功", "teacher.php");
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">

	<head>

		<!-- Basic -->
		<meta charset="UTF-8" />

		<title>员工信息管理</title>

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!--My-CSS-->
		<link rel="stylesheet" href="assets/css/add-style.css" />

		<!-- start: CSS file-->

		<!-- Vendor CSS-->
		<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/vendor/skycons/css/skycons.css" rel="stylesheet" />
		<link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="assets/vendor/css/pace.preloader.css" rel="stylesheet" />

		<!-- Plugins CSS-->
		<link href="assets/plugins/bootkit/css/bootkit.css" rel="stylesheet" />
		<link href="assets/plugins/jquery-ui/css/jquery-ui-1.10.4.min.css" rel="stylesheet" />

		<!-- Theme CSS -->
		<link href="assets/css/jquery.mmenu.css" rel="stylesheet" />

		<!-- Page CSS -->
		<link href="assets/css/style.css" rel="stylesheet" />
		<link href="assets/css/add-ons.min.css" rel="stylesheet" />
		<!-- Head Libs -->
		<script src="assets/plugins/modernizr/js/modernizr.js"></script>
	</head>

	<body>

		<!-- Start: Header -->
		<div class="navbar" role="navigation" style="line-height: 24px;padding: 13px 100px;">
			<div class="container-fluid container-nav pull-right">
				<span class="glyphicon glyphicon-user co mgl"></span>
				<span class="co">欢迎<span style="color: #FF4C4C;"><?php echo base64_decode(base64_decode($_COOKIE['admin']))?></span>登录</span>
				<a type="button" href="dormitory.php?act=exit" class="mybtn mybtn3" style="padding: 2px 6px;">安全退出</a>
			</div>
		</div>
		<!-- End: Header -->
		
		<!-- Start: Content -->
		<div class="container-fluid content">
			<div class="row">
				<!-- Sidebar -->
				<div class="sidebar">
					<div class="sidebar-collapse">
						<!-- Sidebar Header Logo-->
						<div class="sidebar-header" style="padding: 0;color: #FF4C4C;">
							<span class="glyphicon glyphicon-phone-alt" style="margin-right: 6px;"></span>热线电话：0593-123456
						</div>
						<!-- Sidebar Menu-->
						<div class="sidebar-menu">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-sidebar">
									<div class="panel-body text-center">
										<div class="flag" style="margin-top: -20px; color: #FFC926;font-size: 20px;">
											学生公寓管理系统
										</div>
									</div>
									<li>
										<a href="general.php">
											<i class="glyphicon glyphicon-bookmark" aria-hidden="true"></i><span>主要概况</span>
										</a>
									</li>
									<li>
										<a href="dormitory.php">
											<i class="glyphicon glyphicon-home" aria-hidden="true"></i><span>学生信息</span>
										</a>
									</li>
									<li class="active">
										<a href="teacher.php">
											<i class="glyphicon glyphicon-edit" aria-hidden="true"></i><span>员工信息管理</span>
										</a>
									</li>
									<li class="nav-parent">
										<a>
											<i class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></i><span>宿舍评比</span>
										</a>
										<ul class="nav nav-children">
											<li><a href="activity.php"><span class="text">发布活动</span></a></li>
											<li><a href="manage.php"><span class="text">管理活动</span></a></li>
										</ul>
									</li>
									<li class="nav-parent">
										<a>
											<i class="glyphicon glyphicon-wrench" aria-hidden="true"></i><span>物业</span>
										</a>
										<ul class="nav nav-children">
											<li><a href="repaired.php"><span class="text">已修理</span></a></li>
											<li><a href="unrepaired.php"><span class="text">待修理</span></a></li>
										</ul>
									</li>
									<li>
										<a href="message.php">
											<i class="glyphicon glyphicon-comment" aria-hidden="true"></i><span>查看/回复留言</span>
										</a>
									</li>
									<li>
										<a href="uploadfile.php">
											<i class="glyphicon glyphicon-sort" aria-hidden="true"></i><span>上传/下载</span>
										</a>
									</li>
									<li>
										<a href="distribution.php">
											<i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i><span>宿舍分配</span>
										</a>
									</li>
								</ul>
							</nav>
						</div>
						<!-- End Sidebar Menu-->
					</div>
					<!-- Sidebar Footer-->
					<div class="sidebar-footer"></div>
					<!-- End Sidebar Footer-->
				</div>
				<!-- End Sidebar -->

				<!-- Main Page -->
				<div class="main sidebar-minified">
					<!-- Page Header -->
					<div class="page-header">
						<div class="pull-left">
							<ol class="breadcrumb visible-sm visible-md visible-lg">
								<li><a href="index.html"><i class="icon fa fa-home"></i>信息管理</a></li>
							</ol>
						</div>
						<div class="pull-right">
							<h2>员工信息管理</h2>
						</div>
					</div>
					<!-- End Page Header -->
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default bk-bg-white">
								<div class="panel-heading bk-bg-white">
									<h6><i class="fa fa-table"></i><span class="break"></span>员工信息管理
										<button type="submit" class="btn btn-info" name="add" data-toggle="modal" data-target="#myModal" style="margin-left: 20px;">添加</button>
									</h6>
									<div class="panel-actions">
										<a href="#" class="btn-minimize"><i class="fa fa-caret-up"></i></a>
										<a href="#" class="btn-close"><i class="fa fa-times"></i></a>
									</div>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered bootstrap-datatable datatable text-center">
											<tr>
												<td>账号</td>
												<td>姓名</td>
												<td>邮箱</td>
												<td>电话</td>
												<td>职务</td>
												<td>操作</td>
											</tr>
											<?php 
											foreach ($datas2 as $data2){
												echo '<tr>
														<td>'.$data2['username'].'</td>
														<td>'.$data2['realname'].'</td>
														<td>'.$data2['email'].'</td>
														<td>'.$data2['phone'].'</td>
														<td>'.$data2['job'].'</td>
														<td>
															<a href="teacher.php?act=deleteteacher&id='.$data2['ID'].'" class="home-operate">删除</a>
														</td>
													  </tr>';
											}
											?>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
				<!-- End Main Page -->

				<!--添加弹窗-->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document" style="width: 560px;">
						<div class="modal-content text-center">
							<div class="modal-header">
								<div class="panel">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<span class="add-message">添加员工信息</span>
									<form method="post" action="teacher.php?act=addteacher">
									<div style="margin: 20px auto;">
										<label>账号：</label>
										<input type="text" class="plugin-input" name="username" style="padding: 6px 0;" />
										<label style="margin-left: 30px;">姓名：</label>
										<input type="text" class="plugin-input" name="realname" style="padding: 6px 0;" />
									</div>
									<div style="margin: 20px 0;">
										<label>密码：</label>
										<input type="password" class="plugin-input" name="password" style="padding: 6px 0;" />
										<label style="margin-left: 30px;">职务：</label>
										<select id="select" name="job" class="form-control student-wd1" style="display: inline-block;width: 150px;" size="1">
											<option value="0">宿管</option>
											<option value="1">物业</option>
										</select>
									</div>
									<div style="margin: 20px 0;">
										<label>电话：</label>
										<input type="text" class="plugin-input" name="phone" style="padding: 6px 0;" />
										<label style="margin-left: 30px;">邮箱：</label>
										<input type="email" class="plugin-input" name="email" style="padding: 6px 0;" />
									</div>
									<div style="text-align: center;">
										<button type="submit" class="mybtn mybtn1">确定</button>
									</div>
								</form>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Footer -->
				<div id="footer" class="text-center">
				<p style="margin:10px 0 0 0;">宁德师范学院<span style="margin-left: 15px;color: #ADADAD;">学生公寓管理系统</span></p>
				</div>
				<!-- End Footer -->

			</div>
		</div>
		<!--/container-->

		<div class="clearfix"></div>

		<!-- start: JavaScript-->

		<!-- Vendor JS-->
		<script src="assets/vendor/js/jquery.min.js"></script>
		<script src="assets/vendor/js/jquery-2.1.1.min.js"></script>
		<script src="assets/vendor/js/jquery-migrate-1.2.1.min.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/vendor/skycons/js/skycons.js"></script>
		<script src="assets/vendor/js/pace.min.js"></script>

		<!-- Plugins JS-->
		<script src="assets/plugins/jquery-ui/js/jquery-ui-1.10.4.min.js"></script>
		<script src="assets/plugins/sparkline/js/jquery.sparkline.min.js"></script>

		<!-- Theme JS -->
		<script src="assets/js/jquery.mmenu.min.js"></script>
		<script src="assets/js/core.min.js"></script>

		<!-- Pages JS -->
		<script src="assets/js/pages/table.js"></script>

		<!-- end: JavaScript-->

	</body>

</html>
