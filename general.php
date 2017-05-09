<?php 
	require './inc/config.php';
	require './inc/loading.php';
	
	if (empty($_COOKIE['admin'])){
		alertAndLoading("请先登录", "index.php");
	}

	$flag = 0;
	for ($i=1;$i<=12;$i++){
		$power[$flag] = $database -> sum("power", "money",["month" => $i]);
		$flag++;
	}
	$allpower="[[1, $power[0]], [2, $power[1]], [3, $power[2]], [4, $power[3]],[5, $power[4]],[6, $power[5]],[7, $power[6]],[8, $power[7]],[9, $power[8]],[10, $power[9]],[11, $power[10]],[12, $power[11]]]";
	$flag2 = 0;
	for ($j=1;$j<=12;$j++){
		$water[$flag2] = $database -> sum("water", "money",["month" => $j]);
		$flag2++;
	}
	$allwater="[[1, $water[0]], [2, $water[1]], [3, $water[2]], [4, $water[3]],[5, $water[4]],[6, $water[5]],[7, $water[6]],[8, $water[7]],[9, $water[8]],[10, $water[9]],[11, $water[10]],[12, $water[11]]]";
	$student = $database -> count("student");
	$staff = $database -> count("staff");
	$people = $staff + $student;
	$datas = $database -> select("notice", "*",["ORDER" => "posttime DESC"]);
	
	$dorm[2] = [0,0,0];
	$votes[2] = [0,0,0];
	$count = $database -> sum("vote","votes");
	$datas2 = $database -> select("vote", "*",[
			"ORDER"	=> "votes DESC",
			"flag" => 0
	]);
	if (!empty($datas2)){
		$flag3 = 0;
		foreach ($datas2 as $data){
			if ($flag3>=3){
				break;
			}
			$dorm[$flag3] = $data['dormnum'];
			$votes[$flag3] = ceil(($data['votes']/$count)*100);
			$flag3++;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>总体概况</title>
	 
		<!-- Mobile Metas -->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
	    <!-- start: CSS file-->
		
		<!--MyCSS-->
		<link href="assets/css/add-style.css" rel="stylesheet" />
		
		<!-- Vendor CSS-->
		<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/vendor/skycons/css/skycons.css" rel="stylesheet" />
		<link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="assets/vendor/css/pace.preloader.css" rel="stylesheet" />
		
		<!-- Plugins CSS-->
		<link href="assets/plugins/jquery-ui/css/jquery-ui-1.10.4.min.css" rel="stylesheet" />	
		<link href="assets/plugins/scrollbar/css/mCustomScrollbar.css" rel="stylesheet" />
		<link href="assets/plugins/bootkit/css/bootkit.css" rel="stylesheet" />
		<link href="assets/plugins/magnific-popup/css/magnific-popup.css" rel="stylesheet" />
		<link href="assets/plugins/fullcalendar/css/fullcalendar.css" rel="stylesheet" />
		<link href="assets/plugins/jqvmap/jqvmap.css" rel="stylesheet" />
		
		<!-- Theme CSS -->
		<link href="assets/css/jquery.mmenu.css" rel="stylesheet" />
		
		<!-- Page CSS -->		
		<link href="assets/css/style.css" rel="stylesheet" />
		<link href="assets/css/add-ons.min.css" rel="stylesheet" />
	
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
									<li class="active">
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
								<li><a href="index.html"><i class="icon fa fa-home"></i>首页</a></li>
								<li class="active"><i class="fa fa-laptop"></i>主要概况</li>
							</ol>						
						</div>
						<div class="pull-right">
							<h2>主要概况</h2>
						</div>					
					</div>
					<!-- End Page Header -->
					<div class="row">
						<!--开始 文明宿舍-->
						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
							<div class="panel bk-widget bk-border-off">
								<div class="panel-body text-left bk-bg-white bk-padding-top-10 bk-padding-bottom-10">
									<div class="row">
										<div class="col-xs-4 bk-vcenter text-center">
											<strong>文明宿舍评比</strong>
										</div>
										<div class="col-xs-8 text-left bk-vcenter text-center">
											<small><?php echo $dorm[0].':'.$votes[0];?>%</small>
											<div class="progress light progress-xs  progress-striped active bk-margin-bottom-10">
												<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $votes[0];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $votes[0];?>%;">
													
												</div>
											</div>
											<small><?php echo $dorm[1].':'.$votes[1];?>%</small>
											<div class="progress light progress-xs  progress-striped active bk-margin-bottom-10">
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $votes[1];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $votes[1];?>%;">
													
												</div>
											</div>
											<small><?php echo $dorm[2].':'.$votes[2];?>%</small>
											<div class="progress light progress-xs  progress-striped active bk-margin-bottom-10">
												<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $votes[2];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $votes[2];?>%;">
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>						
						<!--结束 文明宿舍-->
						
						<!--开始 总人数-->
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="panel bk-widget bk-border-off">
								<div class="panel-body bk-bg-very-light-gray" style="height: 150px;">
									<h4 class="bk-margin-off-bottom bk-docs-font-weight-300">总人数：</h4>
									<div class="clearfix  bk-padding-top-10">
										<p class="bk-margin-off-top pull-right" style="font-size: 36px;"><?php echo $people;?>&nbsp;人</p>
									</div>									
									<h6 class="text-right bk-padding-top-15 bk-margin-off">老师、学生、员工</h6>
								</div>
							</div>
						</div>
						<!--结束 总人数-->
						
						<!--开始 公告-->
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="panel bk-widget bk-border-off">
								<div class="panel-body bk-bg-very-light-gray" style="height: 150px;">									
									<h4 class="bk-margin-off-bottom bk-docs-font-weight-300">公告：<?php echo $datas[0]['title'];?></h4>
									<!--<div class="clearfix">-->
										<p style="margin-top: 5px;text-indent: 2em;"><?php echo $datas[0]['content'];?></p>
									<!--</div>-->
									<h6 class="text-right bk-padding-top-15 bk-margin-off"><?php echo $datas[0]['posttime']?></h6>
								</div>
							</div>
						</div>
						<!--结束 公告-->
					</div>
					
					<div class="row">
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
				<!-- End Main Page -->
				
				<!-- Footer -->
				<div id="footer" class="text-center">
				<p style="margin:10px 0 0 0;">宁德师范学院<span style="margin-left: 15px;color: #ADADAD;">学生公寓管理系统</span></p>
				</div>
				<!-- End Footer -->
			</div>
		</div><!--/container-->
		
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
		<script src="assets/plugins/scrollbar/js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="assets/plugins/bootkit/js/bootkit.js"></script>
		<script src="assets/plugins/magnific-popup/js/magnific-popup.js"></script>
		<script src="assets/plugins/moment/js/moment.min.js"></script>	
		<script src="assets/plugins/fullcalendar/js/fullcalendar.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.min.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.pie.min.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.resize.min.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.stack.min.js"></script>
		<script src="assets/plugins/flot/js/jquery.flot.time.min.js"></script>
		<script src="assets/plugins/flot-tooltip/js/jquery.flot.tooltip.js"></script>
		<script src="assets/plugins/chart-master/js/Chart.js"></script>
		<script src="assets/plugins/jqvmap/jquery.vmap.js"></script>
		<script src="assets/plugins/jqvmap/data/jquery.vmap.sampledata.js"></script>
		<script src="assets/plugins/jqvmap/maps/jquery.vmap.world.js"></script>
		<script src="assets/plugins/sparkline/js/jquery.sparkline.min.js"></script>
		
		<!-- Theme JS -->		
		<script src="assets/js/jquery.mmenu.min.js"></script>
		<script src="assets/js/core.min.js"></script>
		
		<!-- Pages JS -->

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
		
		<!-- end: JavaScript-->
		
	</body>
	
</html>
<?php
?>