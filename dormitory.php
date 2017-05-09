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
		if ($act == "exit"){
			setcookie('admin',base64_encode(base64_encode($_COOKIE['admin'])),time()-3600,'/');
			alertAndLoading("退出成功", "index.php");
		}elseif ($act == "resetpwd"){
			$database -> update("student", [
					"password" => md5("123456")
			],[
					"susername" => $_GET['student']
			]);
			alertAndLoading("密码重置成功", "dormitory.php");
		}elseif ($act == "cleandorm"){
			if (!empty($_GET['dormid'])){
				$database -> delete("dormitory", [
						"ID" => $_GET['dormid']
				]);
				if (!empty($_GET['st1'])){
					$database -> delete("student", [
							"susername" => $_GET['st1']
					]);
				}
				if (!empty($_GET['st2'])){
					$database -> delete("student", [
							"susername" => $_GET['st2']
					]);
				}
				if (!empty($_GET['st3'])){
					$database -> delete("student", [
							"susername" => $_GET['st3']
					]);
				}
				if (!empty($_GET['st4'])){
					$database -> delete("student", [
							"susername" => $_GET['st4']
					]);
				}
				if (!empty($_GET['st5'])){
					$database -> delete("student", [
							"susername" => $_GET['st5']
					]);
				}
				if (!empty($_GET['st6'])){
					$database -> delete("student", [
							"susername" => $_GET['st6']
					]);
				}
				alertAndLoading("已清空该宿舍", "dormitory.php");
			}
		}
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($act == "migrantrecode"){
				if (!empty($_POST['dormnum'] && $_POST['name'] && $_POST['number'] && $_POST['phone'] && $_POST['accesstime'] && $_POST['endtime'] && $_POST['reason'])){
					$database -> insert("migrant", [
							"dormnum" => $_POST['dormnum'],
							"name" => $_POST['name'],
							"number" => $_POST['number'],
							"phone" => $_POST['phone'],
							"accesstime" => $_POST['accesstime'],
							"endtime" => $_POST['endtime'],
							"reason" => $_POST['reason']
					]);
					alertAndLoading("访问添加成功", "dormitory.php");
				}else {
					alertAndLoading("请填入访问人信息", "dormitory.php");
				}
			}elseif ($act == "notice"){
				$database -> insert("notice", [
						"title" => $_POST['title'],
						"content" => $_POST['content'],
						"posttime" => date("Y/m/d H:i:s"),
						"poster" => base64_decode(base64_decode($_COOKIE['admin']))
				]);
				alertAndLoading("发布成功", "dormitory.php");
			}
			if (!empty($_GET['student'])){
				if ($act == "update"){
					$database -> update("student", [
							"susername" => $_POST['susername'],
							"teacher" => $_POST['teacher'],
							"classnum" => $_POST['class'],
							"number" => $_POST['number'],
							"phone" => $_POST['phone'],
							"gohome" => $_POST['gohome']
					],[
							"ID" => $_GET['student']
					]);
					$database -> update("dormitory", [
							$_GET['st'] => $_POST['susername']
					],[
							$_GET['st'] => $_GET['stname']
					]);
					alertAndLoading("修改成功", "dormitory.php");
				}
			}
			if (!empty($_GET['dormnum'])){
				if ($act == "addnewst"){
					$database -> insert("student", [
							"susername" => $_POST['susername'],
							"password" => md5("123456"),
							"teacher" => $_POST['teacher'],
							"classnum" => $_POST['class'],
							"number" => $_POST['number'],
							"phone" => $_POST['phone'],
							"gohome" => $_POST['gohome'],
							"dormnum" => $_GET['dormnum']
					]);
					$database -> update("dormitory", [
							$_GET['st'] => $_POST['susername']
					],[
							"dormnum" => $_GET['dormnum'],
							$_GET['st'] => $_GET['stname']
					]);
					alertAndLoading("添加成功", "dormitory.php");
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

		<title>宿舍信息</title>

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
		<div class="navbar" role="navigation" >
		<button type="button"class="mybtn mybtn1 notice-btn" style="line-height: 24px;" data-toggle="modal" data-target="#myModals">发布公告</button>
		<div class="container-fluid container-nav pull-right">
				<span class="glyphicon glyphicon-user co mgl"></span>
				<span class="co">欢迎<span style="color: #FF4C4C;"><?php echo base64_decode(base64_decode($_COOKIE['admin']))?></span>登录</span>
				<a type="button" href="dormitory.php?act=exit" class="mybtn mybtn3" style="line-height: 24px;padding: 2px 6px;">安全退出</a>
			</div>
			<!-- Navbar Right -->
				<div class="navbar-right">
					<img src="assets/img/home1.png" class="legend-img" />
					<span class="legend-span">已住满宿舍</span>
					<img src="assets/img/home2.png" class="legend-img" />
					<span class="legend-span">未住满宿舍</span>
					<img src="assets/img/home3.png" class="legend-img" />
					<span class="legend-span">全空宿舍</span>
				</div>
				<!-- End Navbar Right -->
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
									<li class="active">
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
								<li><a href="index.html"><i class="icon fa fa-home"></i>学生公寓</a></li>
							</ol>
						</div>
						<div class="pull-right">
							<h2>宿舍信息</h2>
						</div>
					</div>
		<!--开始 宿舍 小房子-->
					<?php 
			$datas3 = $database -> select("dormitory", "*");
			foreach ($datas3 as $data3){
				if (!empty($data3['st1'])&&!empty($data3['st2'])&&!empty($data3['st3'])&&!empty($data3['st4'])&&!empty($data3['st5'])&&!empty($data3['st6'])){
					$picture = "assets/img/home1.png";
				}elseif (!empty($data3['st1'])||!empty($data3['st2'])||!empty($data3['st3'])||!empty($data3['st4'])||!empty($data3['st5'])||!empty($data3['st6'])){
					$picture = "assets/img/home2.png";
				}
			echo '
						<div class="col-sm-3 col-md-2 col-lg-1" data-toggle="modal" data-target="#'.$data3['ID'].'s">
							<div class="thumbnail text-center" style="border: none;background-color: #ecedf0;">
								<img src="'.$picture.'" class="img-dmgb mouse" />
								<span style="margin-left:-4px;">'.$data3['dormnum'].'</span>
							</div>
						</div>
						<!--开始 宿舍详情  Modal -->
						<div class="modal fade" id="'.$data3['ID'].'s" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document" style="width: 1200px;">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<div class="panel">
											<div class="panel-body">
												<div class="tabs tabs-warning">
													<ul class="nav nav-tabs">
														<li class="active">
															<a href="#'.$data3['ID'].'dor" data-toggle="tab">宿舍详情（'.$data3['dormnum'].'）</a>
														</li>
														<li>
															<a href="#'.$data3['ID'].'" data-toggle="tab">外来人员访问登记</a>
														</li>
													</ul>
													<div class="tab-content">
														<div id="'.$data3['ID'].'dor" class="tab-pane active">
															<div class="row">
																<div class="col-lg-12">
																	<div class="panel panel-default bk-bg-white">
																		<!--开始 宿舍详情 overview-->
																		<a href="dormitory.php?act=cleandorm&dormid='.$data3['ID'].'&st1='.$data3['st1'].'&st2='.$data3['st2'].'&st3='.$data3['st3'].'&st4='.$data3['st4'].'&st5='.$data3['st5'].'&st6='.$data3['st6'].'" class="mybtn mybtn1" style="margin-left: 16px;">清空宿舍</a>
																		<p class="display-p student-mg3"><span class="co">剩余电费：</span>'.$data3['power'].'<span class="co student-mg3">剩余水费：</span>'.$data3['water'].'</p>
																		<div class="panel-body">
																			<div class="table-responsive">
																				<table class="table table-striped table-bordered bootstrap-datatable datatable" style="text-align: center;">
																					<thead>
																						<tr>
																							<td>班级</td>
																							<td>辅导员</td>
																							<td>姓名</td>
																							<td>学号</td>
																							<td>晚归（次数）</td>
																							<td>电话</td>
																							<td>操作</td>
																						</tr>
																					</thead>';
																				$datas4 = $database -> select("student", "*",[
																						"dormnum" => $data3['dormnum']
																				]);
																				$flag = 1;
																				foreach ($datas4 as $data4){
																					
																				echo '
																					
																					<tbody>
																						<tr>
																						<form action="dormitory.php?act=update&student='.$data4['ID'].'&st=st'.$flag.'&stname='.$data4['susername'].'" method="post">
																							<td><input name="class" class="dor-inp" value="'.$data4['classnum'].'" type="text"/></td>
																							<td><input name="teacher" class="dor-inp" value="'.$data4['teacher'].'" type="text"/></td>
																							<td><input name="susername" class="dor-inp" value="'.$data4['susername'].'" type="text"/></td>
																							<td><input name="number" class="dor-inp" value="'.$data4['number'].'" type="text"/></td>
																							<td><input name="gohome" class="dor-inp" value="'.$data4['gohome'].'" type="text"/></td>
																							<td><input name="phone" class="dor-inp" value="'.$data4['phone'].'" type="text"/></td>
																							<td>
																								<button type="submit" class="home-operate">保存</button>
																								<a href="dormitory.php?act=resetpwd&student='.$data4['susername'].'" class="home-operate">密码重置</a>
																							</td>
																						</form>
																						</tr>
																					</tbody>
																					';
																					$flag++;
																				}
																				for ($i = 7;$i > $flag;$i--){
																					echo '<tbody>
																						<tr>
																						<form action="dormitory.php?act=addnewst&dormnum='.$data3['dormnum'].'&st=st'.$flag.'&stname='.$data4['susername'].'" method="post">
																							<td><input name="class" class="dor-inp" value="" type="text"/></td>
																							<td><input name="teacher" class="dor-inp" value="" type="text"/></td>
																							<td><input name="susername" class="dor-inp" value="" type="text"/></td>
																							<td><input name="number" class="dor-inp" value="" type="text"/></td>
																							<td><input name="gohome" class="dor-inp" value="" type="text"/></td>
																							<td><input name="phone" class="dor-inp" value="" type="text"/></td>
																							<td>
																								<button type="submit" class="home-operate">保存</button>
																								<a href="dormitory.php?act=resetpwd&student='.$data4['susername'].'" class="home-operate">密码重置</a>
																							</td>
																						</form>
																						</tr>
																					</tbody>';
																				}
																			echo '	</table>
																			</div>
																		</div>
																
																		<!--结束 宿舍详情 overview-->
																	</div>
																</div>
															</div>
														</div>
														
														<!--开始 访问登记edit-->
														<div id="'.$data3['ID'].'" class="tab-pane updateProfile">
															<div class="row">
																<div class="col-lg-12">
																	<div class="panel panel-default bk-bg-white">
																		<div class="panel-body">
																			<div class="table-responsive">
																				<table class="table table-striped table-bordered bootstrap-datatable datatable" style="text-align: center;">
																					<thead>
																						<tr>
																							<td>姓名</td>
																							<td>学号/身份证号</td>
																							<td>电话</td>
																							<td>访问时间</td>
																							<td>结束时间</td>
																							<td>访问原因</td>
																							<td>操作</td>
																						</tr>
																					</thead>
																					<tbody>
																						<form action="dormitory.php?act=migrantrecode" method="post">
																						<tr>
																							<input type="hidden" name="dormnum" class="dor-dis" value="'.$data3['dormnum'].'"/>
																							<td><input name="name" class="dor-inp" type="text"/></td>
																							<td><input name="number" class="dor-inp" type="text"/></td>
																							<td><input name="phone" class="dor-inp" type="text"/></td>
																							<td><input name="accesstime" class="dor-inp" type="text"/></td>
																							<td><input name="endtime" class="dor-inp" type="text"/></td>
																							<td><input name="reason" class="dor-inp" type="text"/></td>
																							<td><button class="co" type="submit">保存</button></td>
																						</tr>
																						</form>
																					</tbody>
																				</table>
																				<table class="table table-striped table-bordered bootstrap-datatable datatable" style="text-align: center;">
																					<thead>
																						<tr>
																							<td>姓名</td>
																							<td>学号/身份证号</td>
																							<td>电话</td>
																							<td>访问时间</td>
																							<td>结束时间</td>
																							<td>访问原因</td>
																						</tr>
																					</thead>
																					<tbody>';
																			$datas5 = $database -> select("migrant", "*",[
																					"dormnum" => $data3['dormnum'],
																					"ORDER" => "id DESC"
																			]);
																			if (!empty($datas5)){}
																				foreach ($datas5 as $data5){
																					
																				  echo '<tr>
																							<td>'.$data5['name'].'</td>
																							<td>'.$data5['number'].'</td>
																							<td>'.$data5['phone'].'</td>
																							<td>'.$data5['accesstime'].'</td>
																							<td>'.$data5['endtime'].'</td>
																							<td>'.$data5['reason'].'</td>
																						</tr>';
																				}
																			
																					echo '</tbody>
																				</table>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--结束 访问登记 edit-->
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--结束 宿舍详情  Modal -->';
						}
				?>
					
		<!--结束 宿舍 小房子-->
				</div>
			</div>
		</div>
			<!--/container-->
		<div class="clearfix "></div>
		
		
		<!--开始 发布公告 Modals -->
		<div class="modal fade" id="myModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document" style="width: 900px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">发布公告</h4>
					</div>
					<div class="modal-body">
						<form method="post" action="dormitory.php?act=notice">
							<div class="notice-div">
								<div class="notice-div1"><span class="notice-span">标题：</span>
									<input class="notice-input pd" name="title" type="text" />
								</div>
								<div style="text-align: center;margin: 15px;">
									<label class="student-ve">内容：</label>
									<textarea rows="10" name="content" cols="60"></textarea>
								</div>
								<div style="text-align: center;margin: 15px;">
									<button type="submit" class="mybtn mybtn1" >发布</button>
								</div>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
		<!--结束 发布公告 Modals -->
		
		<!--开始 分页-->
		<div class="navbar-fixed-bottom text-center">
			<ul class="pagination">
				<li class="disabled"><a href="#">&laquo;</a></li>
				<li class="active"><a href="#">1</a></li>
				<li><a href="#">2</a></li>
				<li><a href="#">3</a></li>
				<li><a href="#">4</a></li>
				<li><a href="#">5</a></li>
				<li><a href="#">6</a></li>
				<li><a href="#">&raquo;</a></li>
			</ul>
		</div>
		<!--结束 分页 -->
				
	    <!-- Footer -->
		<div id="footer" class="text-center">
			<p style="margin:10px 0 0 0;">宁德师范学院<span style="margin-left: 15px;color: #ADADAD;">学生公寓管理系统</span></p>
		</div>
		<!-- End Footer -->

		<div class="clearfix"></div>

		<!-- Vendor JS-->
		<script src="assets/vendor/js/jquery.min.js "></script>
		<script src="assets/vendor/js/jquery-2.1.1.min.js "></script>
		<script src="assets/vendor/js/jquery-migrate-1.2.1.min.js "></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.min.js "></script>
		<script src="assets/vendor/skycons/js/skycons.js "></script>
		<script src="assets/vendor/js/pace.min.js "></script>

		<!-- Plugins JS-->
		<script src="assets/plugins/jquery-ui/js/jquery-ui-1.10.4.min.js "></script>
		<script src="assets/plugins/magnific-popup/js/magnific-popup.js "></script>
		<script src="assets/plugins/isotope/js/jquery.isotope.js "></script>
		<script src="assets/plugins/sparkline/js/jquery.sparkline.min.js "></script>

		<!-- Theme JS -->
		<script src="assets/js/jquery.mmenu.min.js "></script>
		<script src="assets/js/core.min.js "></script>

		<!-- Pages JS -->
		<script src="assets/js/pages/gallery.js "></script>

		<!-- end: JavaScript-->

	</body>

</html>