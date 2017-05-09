<?php 
	require './inc/config.php';
	require './inc/loading.php';
	
	if (empty($_COOKIE['username'])){
		alertAndLoading("请先登录", "index.php");
	}
		
	$datas = $database -> select("student", "*",[
			"susername" => base64_decode(base64_decode($_COOKIE['username']))
	]);
	
	$datas7 = $database -> select("student", "*",[
			"susername" => base64_decode(base64_decode($_COOKIE['username']))
	]);
	
	foreach ($datas7 as $data7){
		$dormnumget = $data7['dormnum'];
	}
	
	$flag = 0;
	for ($i=1;$i<=12;$i++){
		$power[$flag] = $database -> sum("power", "money",[ "AND" => ["month" => $i,"dormnum" => $dormnumget]]);
		$flag++;
	}
	$allpower="[[1, $power[0]], [2, $power[1]], [3, $power[2]], [4, $power[3]],[5, $power[4]],[6, $power[5]],[7, $power[6]],[8, $power[7]],[9, $power[8]],[10, $power[9]],[11, $power[10]],[12, $power[11]]]";
	$flag2 = 0;
	for ($j=1;$j<=12;$j++){
		$water[$flag2] = $database -> sum("water", "money",[ "AND" => ["month" => $j,"dormnum" => $dormnumget]]);
		$flag2++;
	}
	$allwater="[[1, $water[0]], [2, $water[1]], [3, $water[2]], [4, $water[3]],[5, $water[4]],[6, $water[5]],[7, $water[6]],[8, $water[7]],[9, $water[8]],[10, $water[9]],[11, $water[10]],[12, $water[11]]]";
	
	
	$watermost = $database -> select("water", "*",[
			"ORDER" => "money DESC",
			"month" => date("m")-1,
	]);
	$waterleast = $database -> select("water", "*",[
			"ORDER" => "money",
			"month" => date("m")-1
	]);
	
	$powermost = $database -> select("power", "*",[
			"ORDER" => "money DESC",
			"month" => date("m")-1
	]);
	$powerleast = $database -> select("power", "*",[
			"ORDER" => "money",
			"month" => date("m")-1
	]);
	
	$datas8 = $database -> select("dormitory", "*",[
			"dormnum" => $dormnumget
	]);
	
	foreach ($datas8 as $dataset){
		$power = $dataset['power'];
		$water = $dataset['water'];
	}
	
	if (!empty($_REQUEST['act'])){
		$act = $_REQUEST['act'];
		if ($act == "exit"){
			setcookie('username',base64_encode(base64_encode($_COOKIE['username'])),time()-3600,'/');
			alertAndLoading("退出成功", "index.php");
		}
	}
	
	$dorm[2] = [0,0,0];
	$votes[2] = [0,0,0];
	$count = $database -> sum("vote","votes");
	$getvotes = $database -> select("vote", "*",[
			"ORDER"	=> "votes DESC",
			"flag" => 0
	]);
	if (!empty($getvotes)){
		$flag3 = 0;
		foreach ($getvotes as $getvote){
			if ($flag3>=3){
				break;
			}
			$dorm[$flag3] = $getvote['dormnum'];
			$votes[$flag3] = ceil(($getvote['votes']/$count)*100);
			$flag3++;
		}
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!empty($_REQUEST['act'])){
			$act = $_REQUEST['act'];
			if ($act == "paid"){
				if ($_POST['select'] == "0"){
					$kinds = "water";
				}else {
					$kinds = "power";
				}
				$database -> insert($kinds, [
						"dormitoryNo" => '1n305',
						"money" => $_POST['money']
				]);
				alertAndLoading("缴费成功", "student.php");
			}elseif ($act == "changepwd"){
				if (!empty($datas)){
					foreach ($datas as $data){
						$oldpassword = $data['password'];
					}
					if ($oldpassword == md5($_POST['oldpassword'])){
						if ($_POST['newpassword1'] == $_POST['newpassword2']){
							$database -> update("student", [
									"password" => md5($_POST['newpassword1'])
							],[
									"susername" => base64_decode(base64_decode($_COOKIE['username']))
							]);
							alertAndLoading("修改成功", "student.php");
						}else {
							alertAndLoading("两次密码不同", "student.php");
						}
					}else {
						alertAndLoading("原始密码错误", "student.php");
					}
				}
			}elseif ($act == "property"){
				if (!empty($datas)){
					foreach ($datas as $data){
						$phone = $data['phone'];
						$dormnum = $data['dormnum'];
					}
					$database -> insert("property", [
							"dormnum" => $dormnum,
							"applicant" => base64_decode(base64_decode($_COOKIE['username'])),
							"phone" => $phone,
							"reason" => $_POST['reason'],
							"protime" => date("Y/m/d H:i:s")
					]);
					alertAndLoading("报修成功", "student.php");
				}
			}elseif ($act == "message"){
				$database -> insert("message", [
						"messager" => base64_decode(base64_decode($_COOKIE['username'])),
						"mestime" => date("Y/m/d H:i:s"),
						"content" => $_POST['content']
				]);
				alertAndLoading("留言成功", "student.php");
			}elseif ($act == "voteactivity"){
				if (!empty($datas)){
					foreach ($datas as $data){
						$dormnum = $data['dormnum'];
					}
					$path="activity/";        //上传路径
					//echo $_FILES["filename"]["type"];
					if(!file_exists($path)){
						//检查是否有该文件夹，如果没有就创建，并给予最高权限
						mkdir("$path", 0700);
					}
					//允许上传的文件格式
					$tp = array("image/gif","image/jpeg","image/png");
					//检查上传文件是否在允许上传的类型
					if(!in_array($_FILES["filename"]["type"],$tp)){
						alertAndLoading("格式不正确", "index.php");
					}
					//将文件名字改为当前路径+上传时间+用户名+原文件名
					if($_FILES["filename"]["name"]){
		    			$file1=$_FILES["filename"]["name"];
		    			$able = pathinfo($file1)['extension'];
		    			$basename = pathinfo($file1)['basename'];
		    			$file2 = $path.date("Ymdhisa").'.'.$able;
		    			
		    		}
		    		$datas4 = $database -> select("allpost", "*",[
		    				"endtime" => "0000-00-00 00:00:00"
		    		]);
		    		foreach ($datas4 as $data4){
		    			$postactivity = $data4['title'];
		    		}
		    		if (!empty($_POST['works'])){
						//特别注意这里传递给move_uploaded_file的第一个参数为上传到服务器上的临时文件
						$result=move_uploaded_file($_FILES["filename"]["tmp_name"],$file2);
						if($result){
							$database ->insert("vote", [
									"works" => $_POST['works'],
									"upload" => $file2,
									"dormnum" => $dormnum,
	 								"activity" => $postactivity
							]);
 							alertAndLoading("上传成功", "student.php");
						}
		    		}
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

		<title>学生主页</title>

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

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

		<!--My CSS-->
		<link rel="stylesheet" href="assets/css/add-style.css" />
		<!-- Head Libs -->
		<script src="assets/plugins/modernizr/js/modernizr.js"></script>
	</head>

	<body>

		<!-- Start: Header -->
		<div class="student-ad pd-15">
			<p class="display-p student-pd"><span class="glyphicon glyphicon-phone-alt"></span>热线电话：0593-123456</p>
			<a class="display-p co" style="margin-left: 30%;" href="http://www.ndsy.cn/">学校官网</a>
			<p class="display-p pull-right btn-mg">
			<span class="glyphicon glyphicon-user co"></span>
			<span class="co btn-mg">欢迎<span style="color: #FF4C4C;"><?php echo base64_decode(base64_decode($_COOKIE['username']))?></span>登录</span>
			<a type="button" href="student.php?act=exit" class="mybtn mybtn3" style="padding: 2px 6px;">安全退出</a>
			</p>
		</div>

		<div class="clearfix"></div>
		<!-- End: Header -->

		<!-- Start: Content -->
		<div class="container-fluid content">
			<!--开始 宿舍活动-->
		<div class="row">
			<!-- Main Page -->
			<div class="main sidebar-minified stu-dor">
				<!-- End Page Header -->
				<div class="row">
					<div class="col-lg-12">
					<!--<span class="co">宿舍综合操作</span>-->
						<div class="panel panel-default bk-bg-white bk-margin-top-15">
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered bootstrap-datatable datatable" style="text-align: center;">
										<tr>
											<!--<td>剩余电费</td>
											<td>剩余水费</td>-->
											<td class="notice-span">操作</td>
										</tr>
										<tr>
											<!--<td rowspan="6" style="vertical-align: middle;color:#FF4C4C;"><?php echo $power?></td>
											<td rowspan="6" style="vertical-align: middle;color:#FF4C4C;"><?php echo $water?></td>-->
											<td rowspan="6" style="vertical-align: middle;">
												<a class="mybtn mybtn1" href="#" data-toggle="modal" data-target="#myModal">
													<i class="fa">报修</i>
												</a>
												<?php 
													$datas5 = $database -> select("allpost", "*",[
															"endtime" => "0000-00-00 00:00:00"
													]);
													if (!empty($datas5)){
														echo '<a class="mybtn mybtn1" href="#" data-toggle="modal" data-target="#myModal3">
															<i class="fa">活动报名</i>
															</a>
															<a class="mybtn mybtn1" href="civilized.php">
																<i class="fa">活动投票</i>
															</a>';
													}else {
														echo '<a class="mybtn mybtn1 ban" href="#">
															<i class="fa">活动报名</i>
															</a>
															<a class="mybtn mybtn1 ban">
																<i class="fa">活动投票</i>
															</a>';
													}
												?>
												
												<a class="mybtn mybtn1" href="#" data-toggle="modal" data-target="#myModal4">
													<i class="fa">修改密码</i>
												</a>
												<a class="mybtn mybtn1" href="#" data-toggle="modal" data-target="#myModal5">
													<i class="fa">留言/回复</i>
												</a>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
							<div class="panel">
								<div class="panel-body">
									<!--用电量-->
									<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">							
										<span class="co">总用电费（元/月）</span>
										<div class="tabs tabs-bottom tabs-primary bk-margin-bottom-15 bk-margin-top-15">
											<div class="tab-content bk-bg-very-light-gray">
												<div id="adminCart" class="tab-pane active">
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div id="adminChartUpdate" style="height:198px"></div>															
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<!--用水量-->
									<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">							
										<span class="co">总用水费（元/月）</span>
										<div class="tabs tabs-bottom tabs-primary bk-margin-bottom-15 bk-margin-top-15">
											<div class="tab-content bk-bg-very-light-gray">
												<div class="tab-pane active">
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div id="adminChartUpdate1" style="height:198px"></div>															
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								
								</div>
							</div>
						</div>
				</div>
			
			<!--结束 宿舍活动-->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel-body">
						<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
							<!--开始 公告-->
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<span class="co">公告</span>
								<div class="student-ads" style="margin-top:15px;padding: 11px;">
								<?php 
									$datas6 = $database -> select("notice", "*",["ORDER" => "posttime DESC"]);
									if (!empty($datas6)){
										$flagnotice = 0;
										foreach ($datas6 as $data6){  
											if ($flagnotice>3){
												break;
											}
											echo '<p class="student-ads3 mouse" data-toggle="modal" data-target="#'.$data6['ID'].'t"><span class="co">'.$data6['posttime'].'</span><span class="student-mg2" color="red">'.$data6['title'].'</span></p>
												<!--开始-modal2-公告详情-->
												<div class="modal fade" id="'.$data6['ID'].'t" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
													<div class="modal-dialog" role="document" style="width: 660px;">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<div class="panel">
																	<div class="panel-body">
																		<h4 class="student-ads4 student-ads5">'.$data6['title'].'</h4>
																		<p class="student-ads4"><span class="co dmgr">发布时间：'.$data6['posttime'].'</span><span class="co">发布者：'.$data6['poster'].'</span> </p>
																		<p class="dor-act">'.$data6['content'].'</p>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!--结束-modal2-公告详情-->';
											$flagnotice++;
										}
									}
								?>
								</div>
								</div>
								
							<!--结束 公告-->
								<!--开始 文明宿舍评比-->
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<span class="co">文明宿舍评比</span>
								<div class="student-ads" style="margin-top:15px;padding: 11px;">
									
									<div class="row">
										<div class="col-xs-12 text-left bk-vcenter text-center">
											<small><?php echo $dorm[0].':'.$votes[0];?>%</small>
											<div class="progress light progress-xs  progress-striped active bk-margin-top-10 bk-margin-bottom-10">
												<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $votes[0];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $votes[0];?>%;">
													<span class="sr-only">60% Complete</span>
												</div>
											</div>
											<small><?php echo $dorm[1].':'.$votes[1];?>%</small>
											<div class="progress light progress-xs  progress-striped active bk-margin-top-10 bk-margin-bottom-10">
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $votes[1];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $votes[1];?>%;">
													<span class="sr-only">88% Complete</span>
												</div>
											</div>
											<small><?php echo $dorm[2].':'.$votes[2];?>%</small>
											<div class="progress light progress-xs  progress-striped active bk-margin-top-10 bk-margin-bottom-10">
												<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $votes[2];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $votes[2];?>%;">
													<span class="sr-only">60% Complete</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!--结束 文明宿舍评比-->
							</div>
						
						<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
						
						<!--开始 成员信息-->
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<span class="co">成员信息</span>
							<div class="panel panel-default bk-bg-white bk-margin-top-15">
							<div class="panel-body">
								<!--宿舍号-->
								<span class="co"><?php echo $dormnumget;?></span>
								<div class="table-responsive">
									<table class="table table-striped table-bordered bootstrap-datatable datatable stu-tab" style="text-align: center;">
										<thead>
										<tr>
											<td>宿舍成员</td>
											<td>学号</td>
											<td>晚归（次数）</td>
										</tr>
										</thead>
										<tbody>
										<?php 
											foreach ($datas8 as $data8){
												$datas9 = $database -> select("student", "*",[
														"susername" => $data8['st1']
												]);
												if (!empty($datas9)){
													foreach ($datas9 as $data9){
														$number = $data9['number'];
														$gohome = $data9['gohome'];
													}
													echo '<tr>
															<td>'.$data8['st1'].'</td>
															<td>'.$number.'</td>
															<td>'.$gohome.'</td>
													  	</tr>';
												}
												$datas9 = $database -> select("student", "*",[
														"susername" => $data8['st2']
												]);
												if (!empty($datas9)){
													foreach ($datas9 as $data9){
														$number = $data9['number'];
														$gohome = $data9['gohome'];
													}
													echo '
														<tr>
															<td>'.$data8['st2'].'</td>
															<td>'.$number.'</td>
															<td>'.$gohome.'</td>
														</tr>';
												}
												$datas9 = $database -> select("student", "*",[
														"susername" => $data8['st3']
												]);
												if (!empty($datas9)){
													foreach ($datas9 as $data9){
														$number = $data9['number'];
														$gohome = $data9['gohome'];
													}
													echo '
														<tr>
															<td>'.$data8['st3'].'</td>
															<td>'.$number.'</td>
															<td>'.$gohome.'</td>
														</tr>';
												}
												$datas9 = $database -> select("student", "*",[
														"susername" => $data8['st4']
												]);
												if (!empty($datas9)){
													foreach ($datas9 as $data9){
														$number = $data9['number'];
														$gohome = $data9['gohome'];
													}
													echo '
														<tr>
															<td>'.$data8['st4'].'</td>
															<td>'.$number.'</td>
															<td>'.$gohome.'</td>
														</tr>';
												}
												$datas9 = $database -> select("student", "*",[
														"susername" => $data8['st5']
												]);
												if (!empty($datas9)){
													foreach ($datas9 as $data9){
														$number = $data9['number'];
														$gohome = $data9['gohome'];
													}
													echo '
														<tr>
															<td>'.$data8['st5'].'</td>
															<td>'.$number.'</td>
															<td>'.$gohome.'</td>
														</tr>';
												}
												$datas9 = $database -> select("student", "*",[
														"susername" => $data8['st6']
												]);
												if (!empty($datas9)){
													foreach ($datas9 as $data9){
														$number = $data9['number'];
														$gohome = $data9['gohome'];
													}
													echo '
														<tr>
															<td>'.$data8['st6'].'</td>
															<td>'.$number.'</td>
															<td>'.$gohome.'</td>
														</tr>';
												}
											}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				<!--结束 成员信息-->
				</div>
						
						<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
							<!--开始 用电/水 排名-->
							<span class="co">上个月用电/水量前最多与最少</span>
							<div class="student-ads6 bk-margin-top-15">
								<div class="tabs tabs-warning">
									<ul class="nav nav-tabs text-center">
										<li class="active">
											<a href="#electricm" data-toggle="tab">用电最多</a>
										</li>
										<li>
											<a href="#electricl" data-toggle="tab">用电最少</a>
										</li>
										<li>
											<a href="#waterm" data-toggle="tab">用水最多</a>
										</li>
										<li>
											<a href="#waterl" data-toggle="tab">用水最少</a>
										</li>
									</ul>
									<div class="tab-content">
										<!--开始 用电最多-->
										<div id="electricm" class="tab-pane active">
											<div class="row">
												<div class="col-lg-12">
													<div class="panel panel-default bk-bg-white">
														<div class="panel-body">
															<div class="table-responsive">
																<table class="table table-striped table-bordered bootstrap-datatable datatable" style="text-align: center;">
																	<thead>
																		<tr>
																			<td>序号</td>
																			<td>宿舍号</td>
																			<td>电费</td>
																		</tr>
																	</thead>
																	<tbody>
																	<?php 
																		$flag = 1;
																		foreach ($powermost as $powerm){
																			if ($flag > 5){
																				break;
																			}
																			echo '<tr>
																					<td>'.$flag.'</td>
																					<td>'.$powerm['dormnum'].'</td>
																					<td>'.$powerm['money'].'</td>
																				</tr>';
																			$flag++;
																		}
																	?>
																		
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--结束 用电最多-->
										<!--开始 用电最少-->
										<div id="electricl" class="tab-pane updateProfile">
											<div class="row">
												<div class="col-lg-12">
													<div class="panel panel-default bk-bg-white">
														<div class="panel-body">
															<div class="table-responsive">
																<table class="table table-striped table-bordered bootstrap-datatable datatable" style="text-align: center;">
																	<thead>
																		<tr>
																			<td>序号</td>
																			<td>宿舍号</td>
																			<td>电费</td>
																		</tr>
																	</thead>
																	<tbody>
																		<?php 
																		$flag2 = 1;
																		foreach ($powerleast as $powerl){
																			if ($flag2 > 5){
																				break;
																			}
																			echo '<tr>
																					<td>'.$flag2.'</td>
																					<td>'.$powerl['dormnum'].'</td>
																					<td>'.$powerl['money'].'</td>
																				</tr>';
																			$flag2++;
																		}
																	?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--结束 用电最少-->
										<!--开始 用水最多-->
										<div id="waterm" class="tab-pane updateProfile">
											<div class="row">
												<div class="col-lg-12">
													<div class="panel panel-default bk-bg-white">
														<div class="panel-body">
															<div class="table-responsive">
																<table class="table table-striped table-bordered bootstrap-datatable datatable" style="text-align: center;">
																	<thead>
																		<tr>
																			<td>序号</td>
																			<td>宿舍号</td>
																			<td>水费</td>
																		</tr>
																	</thead>
																	<tbody>
																		<?php 
																		$flag3 = 1;
																		foreach ($watermost as $waterm){
																			if ($flag3 > 5){
																				break;
																			}
																			echo '<tr>
																					<td>'.$flag3.'</td>
																					<td>'.$waterm['dormnum'].'</td>
																					<td>'.$waterm['money'].'</td>
																				</tr>';
																			$flag3++;
																		}
																	?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--结束 用水最多-->
										<!--开始 用水最少-->
										<div id="waterl" class="tab-pane updateProfile">
											<div class="row">
												<div class="col-lg-12">
													<div class="panel panel-default bk-bg-white">
														<div class="panel-body">
															<div class="table-responsive">
																<table class="table table-striped table-bordered bootstrap-datatable datatable" style="text-align: center;">
																	<thead>
																		<tr>
																			<td>序号</td>
																			<td>宿舍号</td>
																			<td>水费</td>
																		</tr>
																	</thead>
																	<tbody>
																		<?php 
																		$flag4 = 1;
																		foreach ($waterleast as $waterl){
																			if ($flag4 > 5){
																				break;
																			}
																			echo '<tr>
																					<td>'.$flag4.'</td>
																					<td>'.$waterl['dormnum'].'</td>
																					<td>'.$waterl['money'].'</td>
																				</tr>';
																			$flag4++;
																		}
																	?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--结束 用水最少-->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
		
			<!-- End Main Page -->

			<!--开始-modal-物业报修-->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<div class="panel">
								<div class="panel-body">
									<div class="tabs tabs-warning">
										<h3 class="student-h">物业报修</h3>
										<form method="post" action="student.php?act=property">
											<div class="tab-content">
												<div id="overview" class="tab-pane active">
													<div class="row">
														<div class="col-lg-12">
															<div class="panel panel-default bk-bg-white">
																<div class="panel-body">
																	<div class="student-bx">
																		<label class="student-ve">报修原因:</label>
																		<textarea name="reason" cols="50" rows="12"></textarea>
																	</div>
																	<div class="student-bx">
																		<button type="submit" class="mybtn mybtn1 bk-margin-bottom-15 bk-margin-5">确定</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
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
			<!--结束-modal-物业报修-->
			
			<!--开始-modal3-活动报名-->
		<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<div class="panel">
							<div class="panel-body">
								<div class="tabs tabs-warning">
									<h3 class="student-h">活动报名</h3>
										<div class="tab-content">
											<div id="overview" class="tab-pane active">
												<div class="row">
													<div class="col-lg-12">
														<div class="panel panel-default bk-bg-white">
															<div class="panel-body">
															<form enctype="multipart/form-data" action="student.php?act=voteactivity" method="post">
															<div class="student-bx" style="text-align: left;margin-left:44px;">
																	<label class="display-p student-mg2">作品名:</label>
																	<input type="text" name="works" class="form-control bk-radius student-wd2" style="display: inline-block;"/>
																</div>
																<div class="student-bx text-center">
																	<label class="student-ve" >作品上传:</label>
																	<div class="student-img student-al">
																		<img src="assets/img/s4.jpg" width="180" height="200" style="padding-top: 20px;" />
																	</div>
																	<input type="file" name="filename" id="filename" style="width: 70px;overflow: hidden;display: inline-block;margin-top: 80px;"/>
																</div>
																<div class="clearfix"></div>
																<div class="student-bx">
																	<button type="submit" class="mybtn mybtn1 bk-margin-bottom-15 bk-margin-5">确定</button>
																</div>
															</form>															</div>
														</div>
													</div>
												</div>
											</div>
										</div>	
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--结束-modal3-活动报名-->
			<!--开始-modal4-修改密码-->
			<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<div class="panel">
								<div class="panel-body">
									<div class="tabs tabs-warning">
										<h3 class="student-h">修改密码</h3>
										<form method="post" action="student.php?act=changepwd">
										<div class="tab-content">
											<div id="overview" class="tab-pane active">
												<div class="row">
													<div class="col-lg-12">
														<div class="panel panel-default bk-bg-white">
															<div class="panel-body" style="margin:0 120px;">
																<label class="display-p student-mg3">原密码:</label>
																<input type="password" name="oldpassword" class="form-control bk-radius student-wd1" style="display: inline-block;" />
																<div style="margin: 20px auto;">
																	<label class="display-p student-mg3">新密码:</label>
																	<input type="password" name="newpassword1" class="form-control bk-radius student-wd1" style="display: inline-block;" />
																</div>
																<label class="display-p student-mg5">确认密码:</label>
																<input type="password" name="newpassword2" class="form-control bk-radius student-wd1" style="display: inline-block;" />
																<div class="student-bx student-mg4">
																	<button type="submit" class="mybtn mybtn1 bk-margin-bottom-15 bk-margin-5">确定</button>
																</div>
															</div>
														</div>
													</div>
												</div>
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
			<!--结束-modal4-修改密码-->
			<!--开始-modal5-留言/回复-->
			<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<div class="panel">
								<div class="panel-body">
									<div class="tabs tabs-warning">
										<h3 class="student-h">留言/回复</h3>
										<form method="post" action="student.php?act=message">
										<div class="tab-content">
											<div id="overview" class="tab-pane active">
												<div class="row">
													<div class="col-lg-12">
													<?php 
														$datas3 = $database -> select("message", "*",[
																"replyID" => 0
														]);
														foreach ($datas3 as $data3){
															$datas2 = $database -> select("message", "*",[
																	"replyID" => $data3['ID']
															]);
															if (empty($datas2)){
																if ($data3['replyID'] == 0){
																	echo '<div class="student-hf">
																			<p class="student-hf1"><span class="student-hf2">'.$data3['messager'].'留言：</span>'.$data3['content'].'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$data3['mestime'].'</p>
																			<p class="student-hf4">暂无回复</p>
																			<div class="clearfix"></div>
																		</div>';
																}
															}else {
																foreach ($datas2 as $data2){
																	$replymessager = $data2['messager'];
																	$replycontent = $data2['content'];
																}
																	echo '<div class="student-hf">
																			<p class="student-hf1"><span class="student-hf2">'.$data3['messager'].'留言：</span>'.$data3['content'].'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$data3['mestime'].'</p>
																			<p class="student-hf4"><span class="student-hf3">'.$replymessager.'回复：</span>'.$replycontent.'</p>
																			<div class="clearfix"></div>
																		</div>';
															}
														}
													?>
														<div style="margin: 20px 0;">
															<label style="vertical-align: top;">留言:</label>
															<textarea id="textarea-input" name="content" rows="5" class="form-control" style="display: inline-block;"></textarea>
															<div class="student-bx">
																<button type="submit" class="mybtn mybtn1 bk-margin-bottom-15 bk-margin-5">确定</button>
															</div>
														</div>
													</div>
												</div>
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
		</div>
		<!--结束-modal5-留言/回复-->
		
		<!-- Footer -->
		<div id="footer" class="text-center">
			<p style="margin:10px 0 0 0;">宁德师范学院<span style="margin-left: 15px;color: #ADADAD;">学生公寓管理系统</span></p>
		</div>
		<!-- End Footer -->

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
		<script src="assets/plugins/flot/js/jquery.flot.min.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.pie.min.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.resize.min.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.stack.min.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.time.min.js"></script>
		<script src="assets/plugins/flot-tooltip/js/jquery.flot.tooltip.js"></script>
		<script src="assets/plugins/chart-master/js/Chart.js"></script>

		<!-- Theme JS -->
		<script src="assets/js/jquery.mmenu.min.js"></script>
		<script src="assets/js/core.min.js"></script>

		<!-- Pages JS -->
		<script src="assets/js/pages/table.js"></script>

		<!-- end: JavaScript-->

		<script type="text/javascript">
			/*
			FlotChart (Fire Admin Update)
			*/
				
			$(document).ready(function(){
				
				if($("#adminChartUpdate").length)
				{	
					//chart表横坐标
					
					var likes = <?php echo $allpower;?>
			
					var plot = $.plot($("#adminChartUpdate"),
						   [ { data: likes} ], {
							   series: {
								   lines: { show: true,
											lineWidth: 2,
											fill: false, fillColor: { colors: [ { opacity: 0.5 }, { opacity: 0.2 } ] }
										 },
								   points: { show: true, 
											 lineWidth: 1 
										 },
								   shadowSize: 0
							   },
							   grid: { hoverable: true, 
									   clickable: true, 
									   tickColor: "#ECECFB",
									   borderWidth: 0,
									   backgroundColor: '#FFF'
									 
									 },
							   colors: ["#99CCFF"],
								xaxis: {ticks:8, tickDecimals: 0},
								yaxis: {ticks:5, tickDecimals: 0},
								
							 });
			
					function showTooltip(x, y, contents) {
						$('<div id="tooltip">' + contents + '</div>').css( {
							position: 'absolute',
							display: 'none',
							top: y + 5,
							left: x + 5,
							border: '2px solid #fff',
							padding: '5px',
							'background-color': '#FFBFBF',
							'color': '#fff',
							opacity: 0.90
						}).appendTo("body").fadeIn(200);
					}
			
					var previousPoint = null;
					$("#adminChartUpdate").bind("plothover", function (event, pos, item) {
						$("#x").text(pos.x.toFixed(2));
						$("#y").text(pos.y.toFixed(2));
			
							if (item) {
								if (previousPoint != item.dataIndex) {
									previousPoint = item.dataIndex;
			
									$("#tooltip").remove();
									var x = item.datapoint[0].toFixed(0),
										y = item.datapoint[1].toFixed(0);
			
									showTooltip(item.pageX, item.pageY,
												item.series.label + " of " + x + " = " + y);
								}
							}
							else {
								$("#tooltip").remove();
								previousPoint = null;
							}
					});
				
				}
				
				
				if($("#adminChartUpdate1").length)
				{	
					//chart表横坐标
					
					var likes = <?php echo $allwater;?>
			
					var plot = $.plot($("#adminChartUpdate1"),
						   [ { data: likes} ], {
							   series: {
								   lines: { show: true,
											lineWidth: 2,
											fill: false, fillColor: { colors: [ { opacity: 0.5 }, { opacity: 0.2 } ] }
										 },
								   points: { show: true, 
											 lineWidth: 1 
										 },
								   shadowSize: 0
							   },
							   grid: { hoverable: true, 
									   clickable: true, 
									   tickColor: "#ECECFB",
									   borderWidth: 0,
									   backgroundColor: '#FFF'
									 
									 },
							   colors: ["#99CCFF"],
								xaxis: {ticks:8, tickDecimals: 0},
								yaxis: {ticks:5, tickDecimals: 0},
								
							 });
			
					function showTooltip(x, y, contents) {
						$('<div id="tooltip1">' + contents + '</div>').css( {
							position: 'absolute',
							display: 'none',
							top: y + 5,
							left: x + 5,
							border: '2px solid #fff',
							padding: '5px',
							'background-color': '#FFBFBF',
							'color': '#fff',
							opacity: 0.90
						}).appendTo("body").fadeIn(200);
					}
			
					var previousPoint = null;
					$("#adminChartUpdate1").bind("plothover", function (event, pos, item) {
						$("#x").text(pos.x.toFixed(2));
						$("#y").text(pos.y.toFixed(2));
			
							if (item) {
								if (previousPoint != item.dataIndex) {
									previousPoint = item.dataIndex;
			
									$("#tooltip1").remove();
									var x = item.datapoint[0].toFixed(0),
										y = item.datapoint[1].toFixed(0);
			
									showTooltip(item.pageX, item.pageY,
												item.series.label + " of " + x + " = " + y);
								}
							}
							else {
								$("#tooltip1").remove();
								previousPoint = null;
							}
					});
				}	
			});
		</script>
	</body>

</html>
