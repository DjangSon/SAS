<?php
	require './inc/config.php';
	require './inc/loading.php';
	
	if (empty($_COOKIE['username'])){
		alertAndLoading("请先登录", "login.php");
	}
	
	$datas = $database -> select("allpost", "*",[
			"endtime" => "0000-00-00 00:00:00"
	]);
	if (!empty($datas)){
	    foreach ($datas as $data){
	    	$postactivity = $data['title'];
	    }
	    $datas2 = $database -> select("vote", "*",[
	    		"activity" => $postactivity
	    ]);
	}

	
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!empty($_REQUEST['act'])){
			$act = $_REQUEST['act'];
			if ($act == "vote"){
				$database -> update("vote", [
						"votes" => $_POST['votes']+1
				],[
						"ID" => $_POST['id']
				]);
				alertAndLoading("投票成功", "civilized.php");
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic -->
		<meta charset="UTF-8" />
		<title>
			文明宿舍-投票
		</title>
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
		<!-- Head Libs -->
		<script src="assets/plugins/modernizr/js/modernizr.js"></script>
	</head>
	<body>
		<!-- Start: Header -->
		<div class="navbar" role="navigation" style="line-height: 24px;padding: 13px 100px;">
			<div class="container-fluid container-nav pull-right">
				<span class="glyphicon glyphicon-user co mgl">
				</span>
				<span class="co btn-mg">欢迎<span style="color: #FF4C4C;"><?php echo base64_decode(base64_decode($_COOKIE['username']))?></span>登录</span>
				<a type="button" href="dormitory.php?act=exit" class="mybtn mybtn3" style="padding: 2px 6px;">
					安全退出
				</a>
			</div>
		</div>
		<!-- End: Header -->
		<!-- Start: Content -->
		<div class="container-fluid content">
			<!-- Main Page -->
			<div class="main" style="padding-left: 80px;">
				<!-- Page Header -->
				<div class="page-header" style="left: 0;">
					<div class="pull-left">
						<ol class="breadcrumb visible-sm visible-md visible-lg">
							<li>
								<a href="student.php">
									<i class="icon fa fa-home">
									</i>宿舍评比
								</a>
							</li>
							<li class="active">
								<i class="fa fa-picture-o">
								</i>文明宿舍
							</li>
						</ol>
					</div>
					<div class="pull-right">
						<h2>
							文明宿舍
						</h2>
					</div>
				</div>
				<!-- End Page Header -->
				<!--开始 图片-->
				<div class="media-gallery">
							<?php
							$flag = 0;
							if (!empty($datas2)) {
								foreach ($datas2 as $data2) {
									echo '
					<div class="isotope-item document col-sm-6 col-md-4 col-lg-3">
						<form action="civilized.php?act=vote" method="post">
							<div class="mg-files" data-sort-destination data-sort-id="media-gallery">
								<div class="thumbnail">
									<div class="thumb-preview">
										<input type="hidden" name="id" value="' . $data2['ID'] . '">
										<input type="hidden" name="votes" value="' . $data2['votes'] . '">
										<div class="photos">
											<img src="' . $data2['upload'] . '" width="16px" height="180px" alt="Image description" data-toggle="modal" data-target="#' . $data2['ID'] . '"/>
										</div>
										<div class="mg-option checkbox-inline">
											<input type="radio" id="' . $flag . '" name="file_1" style="width:18px;height:16px;vertical-align:top;" value="1">
											<label for="' . $flag . '"style="margin-top:4px;display:inline-block;">选择</label>
										</div>
										<div class="mg-description" style="padding: 0 20px;">
											<small class="pull-left text-muted bk-fg-warning">' . $data2['works'] . '</small>
											<small class="pull-right text-muted bk-fg-primary">' . $data2['dormnum'] . '</small>
										</div>
									</div>
									<div class="text-center bk-margin-top-10">
										<button type="submit" class="btn btn-twitter bk-margin-bottom-15 bk-margin-5">投票</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<!--开始 图片弹窗modal-->
								<div class="modal fade text-center" id="' . $data2['ID'] . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<img class="img-mgt" src="' . $data2['upload'] . '" />
								</div>
								<!--结束 图片弹窗modal-->	';
									$flag++;
								}
							} else {
								echo '暂无活动';
							}
							?>
					</div>
				<!--结束 图片-->
			</div>
		</div>
		</div>
		<!--/container-->
		<div class="clearfix"></div>
		<!--开始-modal4-修改密码-->
			<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document" style="width: 660px;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							
						</div>
					</div>
				</div>
			</div>
			<!--结束-modal4-修改密码-->
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