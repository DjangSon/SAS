<?php
	require './inc/config.php';
	require './inc/loading.php';
	
	if (empty($_COOKIE['admin'])){
		alertAndLoading("请先登录", "login.php");
	}
	
	$datas = $database -> select("allpost", "*",[
			"ORDER" => "posttime DESC"
	]);
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!empty($_REQUEST['act'])){
			$act = $_REQUEST['act'];
			if ($act == "postactivity"){
				$datas2 = $database -> select("allpost", "*",[
						"endtime" => "0000-00-00 00:00:00"
				]);
				if (empty($datas2)){
					$database -> insert("allpost", [
							"title" => $_POST['title'],
							"content" => $_POST['content'],
							"posttime" => date("Y/m/d H:i:s"),
							"poster" => base64_decode(base64_decode($_COOKIE['admin']))
					]);
					alertAndLoading("发布成功", "activity.php");
				}else {
					alertAndLoading("请先结束未完成活动", "activity.php");
				}
			}elseif ($act == "endactivity"){
				$database -> update("allpost", [
						"endtime" => date("Y/m/d H:i:s")
				],[
						"endtime" => "0000-00-00 00:00:00"
				]);
				$database -> update("vote", [
						"flag" => 1
				],[
						"flag" => 0
				]);
				alertAndLoading("结束活动成功", "activity.php");
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>宿舍评比|发布活动</title>
		
		<!-- Mobile Metas -->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
	    <!-- start: CSS file-->
	    
	    <!--My-CSS-->
		<link rel="stylesheet" href="assets/css/add-style.css" />
		
		<!-- Vendor CSS-->
		<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/vendor/skycons/css/skycons.css" rel="stylesheet" />
		<link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="assets/vendor/css/pace.preloader.css" rel="stylesheet" />
		
		<!-- Plugins CSS-->
		<link href="assets/plugins/bootkit/css/bootkit.css" rel="stylesheet" />
		<link href="assets/plugins/fullcalendar/css/fullcalendar.css" rel="stylesheet" />
		<link href="assets/plugins/jquery-ui/css/jquery-ui-1.10.4.min.css" rel="stylesheet" />					
		<link href="assets/plugins/dropzone/css/dropzone.css" rel="stylesheet" />		
		
		<!-- Theme CSS -->
		<link href="assets/css/jquery.mmenu.css" rel="stylesheet" />
		
		<!-- Page CSS -->		
		<link href="assets/css/style.css" rel="stylesheet" />
		<link href="assets/css/add-ons.min.css" rel="stylesheet" />
		
		<!-- end: CSS file-->	
	    
		
		<!-- Head Libs -->
		<script src="assets/plugins/modernizr/js/modernizr.js"></script>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->		
		
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
									<li class="nav-parent active">
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
				<div class="main ">
					<!-- Page Header -->
					<div class="page-header">
						<div class="pull-left">
							<ol class="breadcrumb visible-sm visible-md visible-lg">					
								<li><a href="#"><i class="icon fa fa-list-alt"></i>宿舍评比</a></li>
								<li class="active"><i class="fa fa-plus-square-o"></i>发布活动</li>								
							</ol>						
						</div>			
					</div>
					<!-- End Page Header -->
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default bk-bg-white">
								<div class="panel-heading bk-bg-white">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal" style="margin: 10px 0;">发布活动</button>
									<div class="panel-actions">
										<a href="#" class="btn-minimize"><i class="fa fa-caret-up"></i></a>
										<a href="#" class="btn-close"><i class="fa fa-times"></i></a>
									</div>
								</div>
								<div class="panel-body">									
									<div class="alert alert-info" style="text-align: center;font-size: 16px;font-weight: 800;">
										宁德师范学院往届比赛
									</div>										  
									<div class="form-group">
									<?php 
									
									foreach ($datas as $data){
										if ($data['endtime'] =="0000-00-00 00:00:00"){
											echo '
												<form method="post" action="activity.php?act=endactivity">
												<div class="controls">
													<span class="activity">'.$data['title'].'</span>&nbsp&nbsp&nbsp&nbsp<span class="co">'.$data['posttime'].'</span>
													<button type="submit" class="dor-acts mybtn mybtn1" style="padding: 3px 12px;">活动结束</button>
													<p class="dor-act student-hf1 dpd" data-toggle="modal" data-target="#myModal1">'.$data['content'].'</p>
												</div>
												</form>
												';
										}else {
											echo '<div class="controls">
													<span class="activity">'.$data['title'].'</span>&nbsp&nbsp&nbsp&nbsp<span class="co">'.$data['posttime'].'</span>
													<p class="dor-act student-hf1 dpd" data-toggle="modal" data-target="#myModal1">'.$data['content'].'</p>
												</div>';
										}
									}
									?>	
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Main Page -->		
		
				<!-- Footer -->
				<div id="footer" class="text-center">
				<p style="margin:10px 0 0 0;">宁德师范学院<span style="margin-left: 15px;color: #ADADAD;">学生公寓管理系统</span></p>
				</div>
				<!-- End Footer -->
			
			</div>
		</div><!--/container-->
			
		
		<div class="clearfix"></div>
		
		<!--开始 modal-发布活动-->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document" style="width: 700px;">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<!--<div class="panel">-->
									<div class="panel-body">
										<div class="tabs tabs-warning">
											<h3 class="student-h">发布活动</h3>
											<form method="post" action="activity.php?act=postactivity">
											<div class="tab-content">
												<div id="overview" class="tab-pane active">
													<div class="row">
														<div class="col-lg-12">
															<div class="panel panel-default bk-bg-white">
																<div class="panel-body">
																	<div class="student-bx">
																		<label>标题:</label>
																		<input type="text" name="title" class="form-control bk-radius" style="width: 200px;display: inline-block;" />
																	</div>
																	<div class="student-bx">
																	<label class="student-ve">内容:</label>
																	<textarea name="content" cols="60" rows="10"></textarea>
																	</div>
																	<div class="student-bx">
																		<button type="submit" class="mybtn mybtn1">确定</button>
																	</div>
																</div>
															</div>
														</div>
													<!--</div>-->
												</div>
											</div>
										</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		<!--结束 modal-发布活动-->

 <!--modal1-比赛详情--> 
<!-- 		<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> 
					<div class="modal-dialog" role="document" style="width: 700px;">
						<div class="modal-content"> 
 							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<!-- 								<div class="panel"> -->
<!-- 									<div class="panel-body"> -->
<!-- 										<div class="tabs tabs-warning"> -->
<!-- 											<h3 class="student-h">比赛详情</h3> -->
<!-- 											<div class="tab-content"> -->
<!-- 												<div id="overview" class="tab-pane active"> -->
<!-- 													<div class="row"> -->
<!-- 														<div class="col-lg-12"> -->
<!-- 															<div class="panel panel-default bk-bg-white"> -->
<!-- 																<div class="panel-body"> -->
<!-- 																	<div class="student-bx"> -->
<!-- 																		<label>标题:宁德师范学院第六届宿舍比赛</label> -->
<!-- 																	</div> -->
<!-- 																	<div> -->
<!-- 																	<label class="co">内容:</label> -->
<!-- 																	<p>宁德师范学院第六届宿舍比赛</p> -->
<!-- 																	<label class="co">规则:</label> -->
<!-- 																	<p>宁德师范学院第六届宿舍比赛</p> -->
<!-- 																	<label class="co">奖励:</label> -->
<!-- 																	<p>宁德师范学院第六届宿舍比赛</p> -->
<!-- 																	</div> -->
<!-- 																</div> -->
<!-- 															</div> -->
<!-- 														</div> -->
<!-- 													</div> -->
<!-- 												</div> -->
<!-- 											</div> -->
<!-- 										</div> -->
<!-- 									</div> -->
<!-- 								</div> -->
<!-- 							</div> -->
<!-- 						</div> -->
<!-- 					</div> -->
<!-- 				</div> -->

		
		
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
		<script src="assets/plugins/moment/js/moment.min.js"></script>	
		<script src="assets/plugins/fullcalendar/js/fullcalendar.min.js"></script>
		<script src="assets/plugins/dropzone/js/dropzone.min.js"></script>
		<script src="assets/plugins/sparkline/js/jquery.sparkline.min.js"></script>
		
		<!-- Theme JS -->		
		<script src="assets/js/jquery.mmenu.min.js"></script>
		<script src="assets/js/core.min.js"></script>
		
		<!-- Pages JS -->
		<script src="assets/js/pages/form-dropzone.js"></script>
		
		<!-- end: JavaScript-->
	
	</body>
	
</html>
