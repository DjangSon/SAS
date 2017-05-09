<?php 
	require './inc/config.php';
	require './inc/loading.php';
	
	if (empty($_COOKIE['admin'])){
		alertAndLoading("请先登录", "index.php");
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!empty($_REQUEST['act'])){
			$act = $_REQUEST['act'];
			if ($act == "delete"){
//				$flag = count($_POST['fileID']);
//				for ($i = 0;$i < $flag;$i++){
					if(!empty($_POST['fileID'])){
						$database -> delete("vote", [
								"ID" => $_POST['fileID']
						]);
						alertAndLoading("删除成功","manage.php");
					}else{
						alertAndLoading("请先选择图片", "manage.php");
					}
//				}
				
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>宿舍评比-管理活动</title>

		<!-- Mobile Metas -->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		<!--My-CSS-->
		<link rel="stylesheet" href="assets/css/add-style.css" />
		
		<!-- Vendor CSS-->
		<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/vendor/skycons/css/skycons.css" rel="stylesheet" />
		<link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="assets/vendor/css/pace.preloader.css" rel="stylesheet" />
		
		<!-- Plugins CSS-->
		<link href="assets/plugins/bootkit/css/bootkit.css" rel="stylesheet" />
		<link href="assets/plugins/magnific-popup/css/magnific-popup.css" rel="stylesheet" />
		<link href="assets/plugins/isotope/css/jquery.isotope.css" rel="stylesheet" />
		<link href="assets/plugins/jquery-ui/css/jquery-ui-1.10.4.min.css" rel="stylesheet" />	
		<link rel="stylesheet" href="assets/css/add-style.css" />
		
		<!-- Theme CSS -->
		<link href="assets/css/jquery.mmenu.css" rel="stylesheet" />
		
		<!-- Page CSS -->		
		<link href="assets/css/style.css" rel="stylesheet" />
		<link href="assets/css/add-ons.min.css" rel="stylesheet" />
		
		<!--picture-->
		<link rel="stylesheet" href="assets/css/default-skin/default-skin.css" />
		<link rel="stylesheet" href="assets/css/photoswipe.css" />
		 <style type="text/css">
            .pnav{margin-top:30px;text-align:center;line-height:24px; font-size:16px}
            .pnav a{padding:4px}
            .pnav a.cur{background:#007bc4;color:#fff;}
            .demo{width:80%; margin:10px auto}
            #photos{width:150px; border:1px solid #d3d3d3;margin:20px auto; text-align:center;padding:4px;cursor:pointer;position:relative}
            #photos p{position:absolute; bottom:0;left:0;padding:4px;background:#000;color:#fff}
            .my-gallery {width: 100%;float: left;}
            .my-gallery img {width: 100%;height: auto;}
            .my-gallery figure {display: block;float: left;margin: 0 5px 5px 0;width: 150px;}
            .my-gallery figcaption {display: none;}
        </style>
	
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
									<li  class="active">
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
								<li><a href="index.html"><i class="icon fa fa-home"></i>宿舍评比</a></li>
								<li class="active"><i class="fa fa-picture-o"></i>管理活动-文明宿舍</li>
							</ol>						
						</div>
						<div class="pull-right">
							<h2>文明宿舍</h2>
						</div>					
					</div>
					<!-- End Page Header -->
					<!-- <button type="submit" name="add"  class="btn btn-success" onclick="show(1)">添加</button>
					<button type="submit" name="modify" class="btn btn-info" onclick="show(2)">修改</button> -->
					<!--开始-作品-->
					<div class="media-gallery">
						<div class="mg-main">
							<form action="manage.php?act=delete" method="post">

							<?php 
								$datas2 = $database -> select("allpost", "*",[
										"endtime" => "0000-00-00 00:00:00"
								]);
								if (!empty($datas2)){
									foreach ($datas2 as $data2){
										$postactivity = $data2['title'];
									}
									
									$datas = $database -> select("vote", "*",[
											"activity" => $postactivity
									]);
									$flag = 0;
									echo '<button type="submit" class="btn btn-warning" onclick="show(3)">删除</button>
											<div class="row mg-files" data-sort-destination data-sort-id="media-gallery" width="16px" height="16px">
										';
									if (!empty($datas)){
										foreach ($datas as $data){
											echo '
													<div class="isotope-item document col-sm-6 col-md-4 col-lg-3" >
														<div class="thumbnail" >
															<div class="thumb-preview" >
																<div id="photos" >
					                     							<img src="'.$data['upload'].'" name="dormnum1" data-toggle="modal" data-target="#'.$data['ID'].'" alt="Image description" width="16px" height="180px"/>
					                							</div>
					                							<div class="mg-option checkbox-inline">
																	<input type="radio" name="fileID" id="'.$flag.'" value="'.$data['ID'].'">
																	<label for="'.$flag.'">选择</label>
																</div>
																<div class="mg-description" style="margin-left:17px;">
																	<small class="pull-left text-muted bk-fg-warning">'.$data['works'].'</small>
																	<small class="pull-right text-muted bk-fg-primary">'.$data['dormnum'].'</small>
																</div>
															</div>
													    </div>
													</div>
													<!--开始 图片弹窗modal-->
												<div class="modal fade text-center" id="'.$data['ID'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<img class="img-mgt" src="'.$data['upload'].'" />	
												</div>
											<!--结束 图片弹窗modal-->
											';
									$flag++;
										}
									}
								}
							?>
							</div>
							</form>
						</div>
					</div>		   
				</div>
				<!--结束 作品-->
			</div>
		</div><!--/container-->
			
		<div class="clearfix"></div>	
		
		<!-- Footer -->
				<div id="footer" class="text-center">
				<p style="margin:10px 0 0 0;">宁德师范学院<span style="margin-left: 15px;color: #ADADAD;">学生公寓管理系统</span></p>
				</div>
				<!-- End Footer -->
		
			
		
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
		<script src="assets/plugins/magnific-popup/js/magnific-popup.js"></script>
		<script src="assets/plugins/isotope/js/jquery.isotope.js"></script>
		<script src="assets/plugins/sparkline/js/jquery.sparkline.min.js"></script>
		
		<!-- Theme JS -->		
		<script src="assets/js/jquery.mmenu.min.js"></script>
		<script src="assets/js/core.min.js"></script>
		
		<!-- Pages JS -->
		<script src="assets/js/pages/gallery.js"></script>
		
		<!-- end: JavaScript-->
		
	</body>
	
</html>
<?php

?>