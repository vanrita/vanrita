
<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="author" content="zvyoko.com">

	<title>zv_yoko</title>

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Favicons -->
	<link rel="icon" href="favicon.ico" type="image/x-icon" />

	<!-- CSS -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/flexslider.css"/>
	<link rel="stylesheet" href="css/animate.css"/>
	<link rel="stylesheet" href="css/supersized.css" media="screen" />

	<!-- Add custom CSS here -->
	<link href="css/custom.css" rel="stylesheet">

	<!--[if lt IE 9]>
      	<script src="./js/html5shiv.js"></script>
	      <script src="./js/respond.js"></script>
	<![endif]-->

</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-custom"  onLoad="$('#num').val(1);">

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content login">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title" id="myModalLabel">Login 登录</h4>
      	</div>
    	<form method="POST" action="{{ url('/user/login') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      		<div class="modal-body">
          		<div class="form-group">
    				<label for="exampleInputEmail1">用户名</label>
    				<input type="text" class="form-control" name="name" placeholder="username">
  				</div>
  				<div class="form-group">
    				<label for="exampleInputPassword1">密码</label>
    				<input type="password" class="form-control" name="password" placeholder="password">
  				</div>
      		</div>
     	 	<div class="modal-footer">
       			<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        		<button type="submit" class="btn btn-primary">登录</button>
      		</div>
  		</form>
    </div>
  </div>
</div>

<div class="body">

<!-- Header -->
<header>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header page-scroll">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
				<i class="fa fa-bars"></i>
				</button>
				<a class="navbar-brand" href="#page-top">
				<div class="logozi"><b>zv</b><i class="fa fa-heart"></i><b>yoko</b><br>everything for love</div>
				</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div id="header" class="collapse navbar-collapse navbar-right navbar-main-collapse top-menu">
				<ul class="nav navbar-nav">
					<!-- Hidden li included to remove active class from about link when scrolled up past about section -->
					<li class="page-scroll active">
						<a href="#home">Home <i>主页</i></a>
					</li>
					<li class="page-scroll">
						<a href="#memory">Mem <i>记忆</i></a>
					</li>
					<li class="page-scroll">
						<a href="#our">Our <i>我们</i></a>
					</li>
					<li class="page-scroll">
						<a href="#blog">Blog <i>博客</i></a>
					</li>
					<li class="page-scroll">
						<a href="#contact">About <i>关于</i></a>
					</li>
					<li id="user" class="page-scroll">
						@if (Session::get('isLogin') == false)
							<a data-toggle="modal" data-target="#myModal">Login <i>登录</i></a>
						@else
  							<a class="header-dropmenu">
    							{{ Session::get('UserName') }}<span class="caret"></span>
  							<ul class="drop-list" style="display: none;">
  								<li><a href="{{ url('/user/logout') }}">个人中心</a></li>
    							<li><a href="{{ url('/user/logout') }}">退出</a></li>
  							</ul>
							</a>
						@endif
					</li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>


		<!-- /.container -->
	</nav>

</header>
<!-- Header -->



<!-- Intro top content -->
<section class="intro" id="home">
	<div class="cover"></div>
	<div class="intro-body">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 animated text-slide">
					<div id="intro-slider" class="flexslider">
						<ul class="slides">
							<li>
								<h3 class="brand-heading">We Know That Love is...</h3>
								<div class="line"></div>
								<p class="intro-text yah">Appreciate each other &amp; Need each other</p>
							</li>
							<li>
								<h3 class="brand-heading">We will design our future</h3>
								<div class="line"></div>
								<p class="intro-text yah">我们的未来我们自己做主</p>
							</li>
							<li>
								<h3 class="brand-heading">WE CAN CREATE Amazing Experience</h3>
								<div class="line"></div>
								<p class="intro-text yah">未知的未来总令人期待</p>
							</li>
                            <li>
								<h3 class="brand-heading">We will stay together forever</h3>
								<div class="line"></div>
								<p class="intro-text yah">未来的每一天都有你的陪伴</p>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 more-detail">
			<div class="scrollDown">
				<span class="mouseBorder"></span>
		   		<span class="mouseWheel"></span>
			</div>
			<a class="" href="#" data-toggle="modal">Discover Now</a>
		</div>
	</div>
</section>
<!-- Intro top content -->

<!-- Welcome content -->
<section class="welcome-content">
	<div class="container">
		<div class="row">
			<div class="col-md-12 welcome-text animated">
				<h3>Welcome to our world</h3>
				<i class="icon-align-right"></i>
				<div class="line1"></div>
				<p><span class="yahei">人生如同故事，重要的并不在有多长，而是在有多好。
					<br>总不敢相信，是什么让我们相遇，一切可能都是命中注定，只是想把我们的点滴记录，不管是幸福、伤感还是任何的任何，都是属于我们的回忆，恋爱虽易，婚姻不易，且行且珍惜！</span>
                  	<br>
             		A wonderful, amazing, and happiness future is creating now...</p>
				<a class="btn1" href="#">Read More</a>
			</div>
		</div>
	</div>
</section>
<!-- Welcome content -->

<!-- Facts content -->
<section class="facts-content" id="memory" data-slide="1" data-stellar-background-ratio="0.5">
	<div class="cover"></div>
	<div class="container">
		<div class="row">
			<h3>Between Us <span class="yah">我们</span></h3>
			<div class="col-md-3 fact-info animated">
				<h4><em id="lines">0</em> <span>CITIES</span></h4>
				<div class="line2"></div>
				<p class="yah">从毕业那天起，拖着重重的行李送你到南站，泪光、道别，我们开始了漫长的异地，两个城市两个人一颗心。</p>
			</div>
			<div class="col-md-3 fact-info animated">
				<h4><em id="lines1">0</em> <span>KILOMETERS</span></h4>
				<div class="line2"></div>
				<p class="yah">从0KM到1084KM，再从1084KM到0KM，我们之间的距离总是这样忽远忽近，但是心的距离从未改变。 </p>
			</div>
			<div class="col-md-3 fact-info animated">
				<h4><em id="lines2">0</em> <span>DAYS</span></h4>
				<div class="line2"></div>
				<p class="yah">时间总不等人，但给我们带来回忆，在这积少成多的日子里，我们有快乐、有眼泪、有争吵，也有着数不完的未来。</p>
			</div>
			<div class="col-md-3 fact-info animated">
				<h4><em id="lines3">0</em> <span>LEFT</span></h4>
				<div class="line2"></div>
				<p class="yah">当你老了，头发白了，睡意昏沉；<br>当你老了，走不动了，炉火旁打盹，回忆青春；<br>当我老了，这辈子剩下的这些天，我陪你走过。</p>
			</div>
		</div>
	</div>
</section>
<!-- Facts content -->

<!-- Team content -->
<section class="team-content" id="our">
	<div class="container">
		<div class="row">
			<div class="fade-text animated">
				<h3>Our family</h3>
				<div class="line1"></div>
				<p><span class="yahei">豪华的海边别墅不是家，安逸的山间小屋不是家，家是和你还有可爱的鲁鲁在一起的地方，无论那个地方在哪里。</span><br>
			    Luxurious seaside villa is not home, The comfort of the Mountain Lodge is not home, home is the place where with you and cute lulu, no matter where it is.</p>
			</div>
			<div class="space90"></div>
			<div class="col-md-12 no-padding">
				<div class="col-md-4 staff-content animated">
					<div class="staff-img">
						<img src="images/our/1.jpg" class="img-responsive img-circle" alt="美呆了~~~"/>
					</div>
					<h4>Yoko <span>宙花小姐</span></h4>
				</div>
				<div class="col-md-4 staff-content animated">
					<div class="staff-img">
						<img src="images/our/2.jpg" class="img-responsive img-circle" alt="萌萌哒~~~"/>
					</div>
					<h4>bululu <span class="yah">Always cute</span></h4>
				</div>
				<div class="col-md-4 staff-content animated">
					<div class="staff-img">
						<img src="images/our/3.jpg" class="img-responsive img-circle" alt="一直在路上..."/>
					</div>
					<h4>Van <span>The man always be here</span></h4>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Team content -->

<!-- Services content -->
<section class="services-content" id="blog" data-slide="2" data-stellar-background-ratio="0.5">
	<div class="cover"></div>
	<div class="container">
		<div class="row">
			<div class="fade-text animated">
				<h3>Blog</h3>
				<div class="line3"></div>
				<p><span class="yah">用图像、文字、音频来记录下我们发生的点点滴滴，把每一个时刻都分享给家人，有你的地方才是家，有你的家才是幸福的。<br>
				</span>As honest folks, we promise to tell you whether or not we’re the best fit for the job. Below are a few things we know we’re good at.</p>
			</div>
			<div class="space60"></div>
			<div class="col-md-12 no-padding space30">
				<div class="col-md-4 service-content animated">
					<div class="service-content-inner">
						<img src="images/ico3.png" alt="说点什么"/>
						<h4>New Blog<br><span class="yah">说点什么</span></h4>
					</div>
				</div>
				<div class="col-md-4 service-content animated">
					<div class="service-content-inner">
						<img src="images/ico1.png" alt="加入我们"/>
						<h4>Join Us<br><span class="yah">加入我们</span></h4>
					</div>
				</div>
				<div class="col-md-4 service-content animated">
					<div class="service-content-inner">
						<img src="images/ico6.png" alt="查看更多"/>
						<h4>READ MORE<br><span class="yah">查看更多</span></h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Services content -->


<!-- Portfolio content -->


<!-- Contact content -->
<section class="contact-wrap" id="contact">
	<div class="container">
		<div class="row">
			<div class="fade-text animated">
				<h3><img src="images/getqrcode.jpg" width="152" height="152" alt="微信号zvyoko"></h3>
				<div class="line1"></div>
				<p><span class="yahei">没有什么能阻挡我们继续向前，每一天的太阳都是全新的，让我们一起为了明天努力。<br>
				</span>There’s nothing can stop us from moving forward, the sun is new everyday. Let’s make something great together for the future.</p>
			</div>
			<div class="space80"></div>
			<div class="col-md-12 no-padding">
				<div class="col-md-4 contact-info animated">
					<h5><i class="fa fa-envelope-o"></i> E-Mail</h5>
					<p>zvyoko@gmail.com<br>
					<span class="yahei">欢迎订阅及反馈</span></p>
				</div>
				<div class="col-md-4 contact-info animated">
					<h5><i class="fa fa-map-marker"></i> Address</h5>
					<p>BAIDU Building K-1,<br>SHANGDI,Haidian,Beijing<br>
					<span class="yahei">北京市海淀区上地十街</span></p>
				</div>
				<div class="col-md-4 contact-info animated">
					<h5><i class="fa fa-github"></i> Github</h5>
					<p>https://github.com/zvyoko<br>
					<span class="yahei">github主页</span></p>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Contact content -->

<!-- Footer content -->
<footer>
	<ul class="social yah">zv yoko</UL>
	<p class="copy">&copy; 2015 VAN SPUTNIK</p>
</footer>
<!-- Footer content -->

</div>

<!-- JavaScript Files -->
<script src="js/jquery-2.1.3.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.easing.min.js"></script>
<script src="js/jquery.mixitup.min.js"></script>
<script src="js/jquery.flexslider.js"></script>
<script src="js/supersized.3.2.7.min.js"></script>
<script src="js/jquery.animateNumber.js"></script>
<script src="js/jquery.appear.js"></script>
<script src="js/animations.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/main.js"></script>


<script type="text/javascript">
function showMK(){
	var num = $("#num").val();
	$("#MK_"+num).css('display','block');
	if(num == '3'){
		$("#more").css('display','none');
	}else{
		$("#num").val(parseInt(num) + 1);
	}
}
</script>

</body>
</html>
