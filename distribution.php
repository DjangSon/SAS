<?php 
	require './inc/config.php';
	require './inc/loading.php';
	
	if (empty($_COOKIE['admin'])){
		alertAndLoading("请先登录", "login.php");
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!empty($_REQUEST['act'])){
			$act = $_REQUEST['act'];
			if ($act == "distribute"){
				if (!empty($_POST['susername1'])&&!empty($_POST['number1'])&&!empty($_POST['class1'])
						&&!empty($_POST['sex1'])&&!empty($_POST['phone1'])&&!empty($_POST['dormnum1'])
//						&&!empty($_POST['susername2'])&&!empty($_POST['number2'])&&!empty($_POST['class2'])
//						&&!empty($_POST['sex2'])&&!empty($_POST['phone2'])&&!empty($_POST['dormnum2'])
//						&&!empty($_POST['susername3'])&&!empty($_POST['number3'])&&!empty($_POST['class3'])
//						&&!empty($_POST['sex3'])&&!empty($_POST['phone3'])&&!empty($_POST['dormnum3'])
//						&&!empty($_POST['susername4'])&&!empty($_POST['number4'])&&!empty($_POST['class4'])
//						&&!empty($_POST['sex4'])&&!empty($_POST['phone4'])&&!empty($_POST['dormnum4'])
//						&&!empty($_POST['susername5'])&&!empty($_POST['number5'])&&!empty($_POST['class5'])
//						&&!empty($_POST['sex5'])&&!empty($_POST['phone5'])&&!empty($_POST['dormnum5'])
//						&&!empty($_POST['susername6'])&&!empty($_POST['number6'])&&!empty($_POST['class6'])
//						&&!empty($_POST['sex6'])&&!empty($_POST['phone6'])&&!empty($_POST['dormnum6'])
						&&!empty($_POST['president'])&&!empty($_POST['prephone'])){
							$database -> insert("student", [
									"susername" => $_POST['susername1'],
									"password" => md5("123456"),
									"number" => $_POST['number1'],
									"classnum" => $_POST['class1'],
									"phone" => $_POST['phone1'],
									"teacher" => $_POST['teacher1'],
									"dormnum" => $_POST['dormnum1']
							]);
							$database -> insert("student", [
									"susername" => $_POST['susername2'],
									"password" => md5("123456"),
									"number" => $_POST['number2'],
									"classnum" => $_POST['class2'],
									"phone" => $_POST['phone2'],
									"teacher" => $_POST['teacher2'],
									"dormnum" => $_POST['dormnum2']
							]);
							$database -> insert("student", [
									"susername" => $_POST['susername3'],
									"password" => md5("123456"),
									"number" => $_POST['number3'],
									"classnum" => $_POST['class3'],
									"phone" => $_POST['phone3'],
									"teacher" => $_POST['teacher3'],
									"dormnum" => $_POST['dormnum3']
							]);
							$database -> insert("student", [
									"susername" => $_POST['susername4'],
									"password" => md5("123456"),
									"number" => $_POST['number4'],
									"classnum" => $_POST['class4'],
									"phone" => $_POST['phone4'],
									"teacher" => $_POST['teacher4'],
									"dormnum" => $_POST['dormnum4']
							]);
							$database -> insert("student", [
									"susername" => $_POST['susername5'],
									"password" => md5("123456"),
									"number" => $_POST['number5'],
									"classnum" => $_POST['class5'],
									"phone" => $_POST['phone5'],
									"teacher" => $_POST['teacher5'],
									"dormnum" => $_POST['dormnum5']
							]);
							$database -> insert("student", [
									"susername" => $_POST['susername6'],
									"password" => md5("123456"),
									"number" => $_POST['number6'],
									"classnum" => $_POST['class6'],
									"phone" => $_POST['phone6'],
									"teacher" => $_POST['teacher6'],
									"dormnum" => $_POST['dormnum6']
							]);
							$database ->insert("dormitory", [
									"st1" => $_POST['susername1'],
									"st2" => $_POST['susername2'],
									"st3" => $_POST['susername3'],
									"st4" => $_POST['susername4'],
									"st5" => $_POST['susername5'],
									"st6" => $_POST['susername6'],
									"president" => $_POST['president'],
									"dormnum" => $_POST['dormnum1'],
									"prephone" => $_POST['prephone']
							]);
							alertAndLoading("宿舍分配成功", "distribution.php");	
						}else {
							alertAndLoading("请填入至少一个学生的信息与舍长信息", "distribution.php");
						}
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>宿舍分配</title>
	  
		<!-- Mobile Metas -->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		 <!-- my css-->
	    <link rel="stylesheet" href="assets/css/add-style.css" />
		
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
									<li>
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
									<li class="active">
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
								<li><a href="#"><i class="icon fa fa-table"></i>宿舍分配</a></li>
							</ol>						
						</div>
						<div class="pull-right">
							<h2>宿舍分配表</h2>
						</div>					
					</div>
					<!-- End Page Header -->
					<div class="row">		
						<div class="col-lg-12">
							<div class="panel panel-default bk-bg-white">
								<div class="panel-heading bk-bg-white">
									<h6><i class="fa fa-table"></i><span class="break"></span>宿舍分配表</h6>
									<div class="panel-actions">
										<a href="#" class="btn-minimize"><i class="fa fa-caret-up"></i></a>
										<a href="#" class="btn-close"><i class="fa fa-times"></i></a>
									</div>
									<!--开始 类别-->
<!-- 									<div class="dmgb"> 
										所属系别：<select id="select" name="depart" class="form-control" size="1" style="width: 100px;display: inline-block;" >
													<option value="0">计算机</option>
													<option value="1">外语</option>
												</select>
										性别：<select id="select1" name="sex" class="form-control" size="1" style="width: 100px;display: inline-block;" >
													<option value="0">男</option>
													<option value="1">女</option>
												</select>
										宿舍楼：<select id="select2" name="floor" class="form-control" size="1" style="width: 100px;display: inline-block;" >
													<option value="0">1号楼</option>
													<option value="1">2号楼</option>
												</select>
									       宿舍情况：<select id="select3" name="select" class="form-control" size="1" style="width: 100px;display: inline-block;" >
													<option value="0">全空宿舍</option>
													<option value="1">未满宿舍</option>
												</select>
										<input type="submit" class="btn-add" name="btn-add" style="width: 50px; height: 31px;display: inline-block;" value="查询" />
									</div> 
-->
									<!--结束 类别-->
								</div>
								<!--开始 信息-->
								<form method="post" action="distribution.php?act=distribute">
								<div class="panel-body">
									<div class="table-responsive">	
										<table class="table table-striped table-bordered bootstrap-datatable datatable text-center">
											<thead>
												<tr>
													<td>姓名</td>
													<td>学号</td>
													<td>班级</td>
													<td>性别</td>
													<td>电话</td>
													<td>辅导员</td>
													<td>宿舍号</td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><input name="susername1" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="number1" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="class1" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="sex1" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="phone1" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="teacher1" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="dormnum1" class="dis-inp text-center" value="" type="text"/></td>
												</tr>
												<tr>
													<td><input name="susername2" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="number2" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="class2" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="sex2" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="phone2" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="teacher2" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="dormnum2" class="dis-inp text-center" value="" type="text"/></td>
												</tr>
												<tr>
													<td><input name="susername3" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="number3" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="class3" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="sex3" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="phone3" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="teacher3" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="dormnum3" class="dis-inp text-center" value="" type="text"/></td>
												</tr>
												<tr>
													<td><input name="susername4" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="number4" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="class4" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="sex4" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="phone4" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="teacher4" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="dormnum4" class="dis-inp text-center" value="" type="text"/></td>
												</tr>
												<tr>
													<td><input name="susername5" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="number5" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="class5" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="sex5" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="phone5" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="teacher5" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="dormnum5" class="dis-inp text-center" value="" type="text"/></td>
												</tr>
												<tr>
													<td><input name="susername6" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="number6" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="class6" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="sex6" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="phone6" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="teacher6" class="dis-inp text-center" value="" type="text"/></td>
													<td><input name="dormnum6" class="dis-inp text-center" value="" type="text"/></td>
												</tr>
											</tbody>
										</table>
										舍长：<input class="plugin-input dpd" name="president" type="text"> 
										舍长电话：<input class="plugin-input dpd" name="prephone" type="text">
									</div>
									<div class="text-center"><button  type="submit" class="mybtn mybtn1" >确定</button></div>
								</div>
								</form>
								<!--结束 信息-->
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
