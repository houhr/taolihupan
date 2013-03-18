<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="zh-cn" lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>桃李湖畔 - 内蒙古大学详细介绍</title>
	<link href="css/other.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="bookmark" href="/favicon.ico" type="image/x-icon" />
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script language="Javascript" type="text/javascript">
		//<![CDATA[
		function initialize() {
			var myLatlng = new google.maps.LatLng(40.813848,111.737795);
			var myOptions = {
				zoom: 12,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}
			};
			map = new google.maps.Map(document.getElementById("map"), myOptions);
			
			//校本部
			var imuMain=new google.maps.Marker(
			{
				position: new google.maps.LatLng(40.812195,111.690652),
				map: map,
				icon: "http://www.google.cn/mapfiles/markerA.png"
			});
			
			var imuMainInfo=new google.maps.InfoWindow(
			{
				content:'内蒙古大学校本部'
			});
			
			google.maps.event.addListener(imuMain, 'click', function() {
				imuMainInfo.open(map,imuMain);
			});

			//东校区标记
			var imuEast=new google.maps.Marker(
			{
				position: new google.maps.LatLng(40.814659,111.699818),
				map: map,
				icon: "http://www.google.cn/mapfiles/markerB.png"
			});
			
			var imuEastInfo=new google.maps.InfoWindow(
			{
				content:'内蒙古大学东校区（宿舍区）',
			});
			
			google.maps.event.addListener(imuEast, 'click', function() {
				imuEastInfo.open(map,imuEast);
			});
			
			//南校区标记
			var imuSouth=new google.maps.Marker(
			{
				position: new google.maps.LatLng(40.759036,111.685594),
				map: map,
				icon: "http://www.google.cn/mapfiles/markerC.png"
			});
			
			var imuSouthInfo=new google.maps.InfoWindow(
			{
				content:'内蒙古大学南校区',
			});
			
			google.maps.event.addListener(imuSouth, 'click', function() {
				imuSouthInfo.open(map,imuSouth);
			});
			
			//车站标记
			var station=new google.maps.Marker(
			{
				position: new google.maps.LatLng(40.830133,111.665508),
				map: map,
				icon: "http://www.google.cn/mapfiles/markerD.png"
			});
			
			var stationInfo=new google.maps.InfoWindow(
			{
				content:'火车站以及汽车站',
			});
			
			google.maps.event.addListener(station, 'click', function() {
				stationInfo.open(map,station);
			});
			
			//机场标记
			var airport=new google.maps.Marker(
			{
				position: new google.maps.LatLng(40.855359,111.821719),
				map: map,
				icon: "http://www.google.cn/mapfiles/markerE.png"
			});
			
			var airportInfo=new google.maps.InfoWindow(
			{
				content:'白塔机场',
			});
			
			google.maps.event.addListener(airport, 'click', function() {
				airportInfo.open(map,airport);
			});
		}

		//]]>
		</script>
</head>
<body onload="initialize()">
	<div id="wrapper">
		<div id="navi">
			<a href="http://<?php echo $_SERVER['HTTP_HOST'].'/taolihupan';?>/"><div id="logo"></div></a>
			
		</div>

		<div id="content" class="floatLeft">
			
			<div>
				<h1>站长带你游内大</h1>
				<p>　　各位新生，你们好！我是桃李湖畔的站长，欢迎来到桃李湖畔！</p>
				<p>　　刚刚走下高考战场的你们，想必此刻正享受着漫长的假期。无论你此时是否已经拿到了期盼已久的录取通知书，既然你会来到这里，说明你俨然已是内大的一员了，欢迎你的到来！下面就让我来带你游览一下内蒙古大学——你未来的母校，提前感受一下内蒙古大学的魅力吧！</p>
				<p>　　内蒙古大学位于内蒙古自治区首府呼和浩特市，如今分为校本部、东区和南区三大区。让我们来一一揭开她们神秘的面纱。</p>
				<h2 class="center attention">校本部</h2>
				<p>　　校本部坐落于呼和浩特市赛罕区，这里是绝对的市中心，北倚满都海公园，环境优美，南与内蒙古农业大学、内蒙古师范大学毗邻，人文气息浓郁。校本部是学校理工科院系的大本营，来到这里，见到这里的学生，你一定会感受到理工科学生们所特有的严谨与认真。本部有南门（正门）和东门可供出入，两个校门都是呼市重要的公交车站，公交线路众多，四通八达，交通十分便利。</p>
				<img src="images/newcomer/1.jpg" alt="南门" />
				<p class="center tips">南门</p>
				<p>　　走进南门，迎面看到的便是主楼。楼内主要是一些学校领导机构以及学生组织的办公室，如团委、职业发展联盟等，另外南区的各文科类学院也分别在主楼设有办事处，方便同学们在大二选择双学位时联系相关事宜。楼内还有为数不多的自习室以及一些对外教学的专用教室，主楼比较老旧，所以平时来这里自习的人也是少之又少。</p>
				<img src="images/newcomer/2.jpg" alt="主楼" />
				<p class="center tips">主楼</p>
				<p>　　主楼的东西两侧分别为经管楼和生科楼，两楼正对，遥相呼应。经管楼可不一般，这里可是可以授予传说中的MBA的哦，生科楼里则更是卧虎藏龙，生物可是内蒙古大学最厉害的专业，实力与清华不相上下，楼内还有各式动物标本，林林总总，数不胜数。</p>
				<img src="images/newcomer/3.jpg" alt="经管楼" />
				<p class="center tips">经管楼</p>
				<img src="images/newcomer/4.jpg" alt="生物楼" />
				<p class="center tips">生物楼</p>
				<p>　　沿着主楼右侧的小路向北走去，会看到一栋小楼，那便是数学科学学院，楼的一层是学校的保卫处，如果在校园内遇到了一些紧急情况可以到这里报警或寻求帮助。</p>
				<img src="images/newcomer/5.jpg" alt="数学科学学院" />
				<p class="center tips">数学科学学院</p>
				<p>　　让我们继续一路向北。踏上一条林荫小路，此时你会被万绿环绕，肥硕的喜鹊不时地从你的头顶掠过，千万别为这醉人的绿色所迷倒，向东望去，你会看到一栋玲珑剔透的六层楼宇，上面有四个金光闪闪的大字——研究生楼。这是本部最新、设施最好、人气最旺的教学楼，一到四楼全部都是自习室，五楼是研究生的管理机构。可别因为她“研究生楼”的名字而失望哦，她很包容，本科生一样可以进去自习，而且本科生的很多课也都在这里开设。</p>
				<img src="images/newcomer/6.jpg" alt="研究生楼" />
				<p class="center tips">研究生楼</p>
				<p>　　让我们回过头来继续向前，走到林荫小路的尽头，你便会看到录取通知书上那座本部最雄伟的标志性建筑——综合楼。综合楼共十一层高，楼顶有三个蒙古包样式的镂空毡包，极富民族特色，全楼通体枣红色，犹如一匹枣红的高头大马，驰骋于塞北草原。综合楼的一到五楼是我们平时上课、自习的地方，七楼有物理实验室，六楼、八楼、九楼、十楼、十一楼是自动化、电子、通信等各理工科学院的办公室。</p>
				<img src="images/newcomer/7.jpg" alt="夜色中的综合楼" />
				<p class="center tips">夜色中的综合楼</p>
				<p>　　很多新生都有爬到顶楼向北鸟瞰校园的经历，那么北边到底有什么呢，是什么令他们那么兴奋？倘若你也来到顶楼，一潭硕大而碧绿的湖水会顿时出现在你的眼前，她有个美丽的名字——桃李湖。如果天气尚好，阳光充足，你甚至可以看清湖中游弋的鱼群。湖心的小岛宛如一盆盆栽，安宁而可爱，任鱼群绕膝、飞鸟息肩。那湖畔的金柳，是夕阳中的新娘，波光里的艳影，在你的心头荡漾……</p>
				<img src="images/newcomer/8.jpg" alt="鸟瞰" />
				<p class="center tips">鸟瞰</p>
				<p>　　一个大学里最重要的地方是什么？当然是图书馆，看到那个正面像一张机器人脸的白色建筑物了吗？那便是内大的图书馆。图书馆是我们探索知识的乐园，也是自习的佳地，当然比较头疼的就是每天人满为患，尤其临近考试，真可谓一座难求。西侧紧挨图书馆的是计算机学院，学院的四五楼是机房，这里的机房对外开放，计算机基础知识等公共课的上机、考试等也都在这里。</p>
				<img src="images/newcomer/9.jpg" alt="夜色中的计算机学院和图书馆" />
				<p class="center tips">夜色中的计算机学院和图书馆</p>
				<p>　　经过一天的学习后，怎能少得了体育锻炼呢？！本部的文体馆位于桃李湖西侧，内部主要设有篮球场、乒乓球场地、羽毛球场地等，设施相较于新区（南区）的文体馆可能有些差距，但也为我们的日常体育活动提供了充分的场地，此外这里也经常会举办一些大型演讲、歌友会等活动，如SHE、张靓颖、许巍等许多大牌歌手都曾来过这里，毕竟内蒙古大学是内蒙古最好的大学，近水楼台先得月，许多名人来内蒙古的大学首选便是内大，相信你也会在这里见到你的偶像的。文体馆外还另设有平整的排球场、被爬山虎包围的网球场、热闹的篮球场、游泳池以及足球场。</p>
				<img src="images/newcomer/10.jpg" alt="文体馆" />
				<p class="center tips">文体馆</p>
				<p>　　本部最后一个值得要介绍的地方便是桃李园。桃李园地处计算机学院北侧，风景秀丽，树木繁茂，桃李园是个值得你探索的地方，里面有各式各样的植物，很多都挂着它们自己的名片，什么科、什么属等等，深处还藏有我们历届学长学姐们留下的毕业林。</p>
				<img src="images/newcomer/11.jpg" alt="毕业林" />
				<p class="center tips">毕业林</p>
				<p>　　关于本部的概括就先介绍到这里，还有许多没有提到的，例如每天必去的食堂等等，这些就留给大家自己来后再去探索吧，下面我们去宿舍看看，我知道你已经迫不及待了。</p>
				<h2 class="center attention">东区</h2>
				<p>　　从本部的东门出来，经过天桥，一直向东走大概十多分钟，你便会看到四栋颜色鲜艳、造型活泼的建筑，这里便是宿舍区，也就是传说中的东区了。东区其实就是本部学生的住宿区，四栋宿舍楼中，蓝色和黄色的楼男生楼，绿色和红色的是女生楼。楼宇都是围回来的样式，中间空地一般都是自行车的停车场。楼下有篮球场、开水房、超市以及一个食堂。东区设有广播站，平日里可以收听新闻或者点歌，每逢节假日这里也会有异彩纷呈的各类活动，每年热闹的社团招新也在这里举行。别光站外边，进楼里来看看。</p>
				<img src="images/newcomer/12.jpg" alt="二号宿舍楼" />
				<p class="center tips">二号宿舍楼</p>
				<p>　　每层楼有四个卫生间，卫生间内有投币式洗衣机供学生们使用。宿舍都是四人间，内设两个洗漱池，可以上网，大多数宿舍内都是下边桌子上边床的，但也有的女生宿舍是上下铺、独立书桌。</p>
				<img src="images/newcomer/13.jpg" alt="男生宿舍内" />
				<p class="center tips">男生宿舍内</p>
				<p>　　东区大概就是这样，让我们回到天桥下，坐上62路或者55路，前往下一个目的地——新校区南区。</p>
				<h2 class="center attention">南区</h2>
				<p>　　经过近一个小时的车程，我们来到了南区，南区位于市郊的小黑河附近，周边有南湖湿地公园，环境也不错，只是没有市中心的方便与热闹，不过利于安心治学。南区一期工程于2009年正式落成，设施先进完备，目前在这里生活的是大部分文科专业、土木工程专业、艺术学院、创业学院等学院的同学们。</p>
				<img src="images/newcomer/14.jpg" alt="南区综合楼" />
				<p class="center tips">南区综合楼</p>
				<p>　　南区同样有座综合楼，远远望去和本区的综合楼有着异曲同工之妙，相似的风格，只是要比本部的还要雄伟数倍。南区面积巨大，图书馆、体育馆、宿舍楼等散布各地，丝毫不觉拥挤。南区宿舍的建设要比本部的东区好上很多，虽然这里变成了六人间，但宿舍更加热闹的同时并没有变得拥挤，部分宿舍还拥有自己的小阳台，阳光非常充足，内部的设置也采用了下面桌柜上面床的安排方式，有效利用了所有的空间。</p>
				<img src="images/newcomer/15.jpg" alt="南区宿舍楼" />
				<p class="center tips">南区宿舍楼</p>
				<p>　　尽管南区刚刚落成，草木还不甚茂盛，但一直都在增添新绿，郁郁葱葱的草坪，娇艳的鲜花，时时伴你左右。南区里艺术学院是个亮点，不说别的，光他们那镂空的圆润楼宇便为南区增添了一份艺术气息，如果说本部是一种严谨，那么南区弥漫的则是一种感性与浪漫。</p>
				<img src="images/newcomer/16.jpg" alt="青春在这里绽放" />
				<p class="center tips">青春在这里绽放</p>
				<p>　　感受着这份浪漫，我们的游园之旅也该落下帷幕了。母校是永远都说不完、写不完、看不完的，你需要切身地来到这里，接受她对你的教诲、熏陶，待你将你四年的青春都燃烧于此、又从她这里带走知识、阅历、经验、记忆之后，你才能知道大学这本书里的真正含义。</p>
				<p>　　快来吧！我们在这里等着你！</p>
				<h2 class="center attention">结束语</h2>
				<p>　　由于你现在还没有自己的学号，所以暂时还不能在桃李湖畔注册，别着急，春天已经来了，冬天还会远吗？桃李湖畔里有页面告诉你内大每天会有哪些讲座或活动，也有个人的页面帮你结识新朋友，更有过来人的学习经验与你分享……总之这是一个分享大于一切的电子社区。送人玫瑰，手留余香。相信你会喜欢上这里的。</p>
				<p>　　欢迎你拿到学号后第一时间回来注册！我们在这里等你！</p>
				<h2 class="center attention">附录A：如何到达各个校区</h2>
				<div id="map" style="width:600px;height:500px;">Loading...</div>
				<p>　　呼和浩特市的汽车站和火车站是挨着的，报名两天内一般有校车接站，如果没有，可坐34路（站牌在汽车站正门门口）到本部南门，或坐37路（站牌在汽车站西侧一百米左右）到本部东门。到南区的同学可以坐1路（站牌在汽车站正门门口）。打的到本部一般不超过10元，南区太远，不建议打的去。</p>
				<p>　　坐飞机来的同学，可先坐机场巴士到终点站新华广场，再改乘公交或打的即可。</p>
				<p>　　自驾车来的同学，本部的地址是——内蒙古呼和浩特市大学西路235号，南区的地址是——呼和浩特市玉泉区昭君路南湖湿地公园东南侧。</p>
				<h2 class="center attention">附录B：招新纳贤</h2>
				<p>　　无论你是什么专业，如果你能用Photoshop做出漂亮的图片，或者会用flash做出流畅的动画，或者对网页制作、网站运营与维护有浓厚的兴趣甚至有很好的技术，并且你有奉献和分享的精神，欢迎你申请加入桃李湖畔团队，请将自己的简介（最好可以附带一件自己的作品）发邮件到<a href="mailto:admin@taolihupan.com">admin@taolihupan.com</a>，我们将给你一个展现自己的舞台，让我们一起为内大学子提供一个更好的电子社区而努力！</p>
				<p>&nbsp; <span class="date floatRight">更新于2010-7-19</span></p>
			</div>
			
			<div id="pageNavi"><a href="#" class="center">返回顶部</a></div>
		</div>

		<div id="sideBar" class="floatRight">
	
		</div>

		<div id="footer">
			&#169; 2010 桃李湖畔<span class="floatRight">创建于2010年暑假</span>
		</div>
		
	</div>
	
	<script type="text/javascript">
		
	</script>
</body>
</html>