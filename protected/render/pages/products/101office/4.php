<?
// Получение информации о пользователе
$user_email = APP::Module('DB')->Select(
    APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
    ['email'], 'users',
    [['id', '=', $data['user_id'], PDO::PARAM_INT]]
);

$cr_date = APP::Module('DB')->Select(
    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
    ['UNIX_TIMESTAMP(cr_date) AS cr_date'], 'tunnels_tags',
    [
        ['user_tunnel_id', '=', $data['id'], PDO::PARAM_INT],
        ['label_id', '=', 'sendmail', PDO::PARAM_STR],
        ['token', '=', '44', PDO::PARAM_STR]
    ]
);

// Формирование диапазона дат
$action_start = $cr_date;
$action_end = strtotime('+84 hours', $action_start);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
     "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <title>101 РЕЦЕПТ СТИЛЬНОГО ГАРДЕРОБА В ОФИС</title>
   <link rel="stylesheet" type="text/css" href="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/css/style.css"/>
   
   
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js" type="text/javascript"></script>
	
	<script type='text/javascript' src='<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/js/jquery.scrollTo-min.js'></script>
	
	
	<script src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/js/main.js"></script>
   <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/flashtimer/compiled/flipclock.css">
   <script src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/flashtimer/compiled/flipclock.js"></script>
	
	
</head>
    
  
<body>

<div class="container" id="point1">
	<div class="menu">
		<div class="ins">
			<ul>
				<li><a class="a1" href="#point1">Главная</a></li>
				<li><a class="a2" href="#point2">Автор тренинга</a></li>
				<li><a class="a3" href="#point3">Бонусы</a></li>
				<li><a class="a4" href="#point4">Как проходит тренинг</a></li>
				<li><a class="a5" href="#point5">Записаться</a></li>
				<li><a class="a6" href="#point6">Отзывы</a></li>
				<li>&nbsp;</li>
			</ul>
		</div>
	</div>
	
	<div class="header">
		<div class="bl_name"><span class="sp1">101</span> РЕЦЕПТ<span class="sp2">СТИЛЬНОГО ГАРДЕРОБА В ОФИС</span></div>
		<div class="slogan">"Когда вы войдете в новом образе в офис, самым громким звуком<br>   будет стук вашего сердца от волнения и тиканье часов на стене"</div>
	</div>
	
	<div class="block1">
		<div class="ins">
			<div class="txt1">Это ваш уникальный шанс настолько изменить ваш офисный гардероб, затратив всего 10 вечеров, что вы будете считаться самой стильной в офисе!</div>
			<div class="break"></div>
			<div class="txt2">Позвольте мне объяснить!</div>
			<div class="break"></div>
			<div class="txt3">
				<div class="corn"></div>
				<div class="shad"></div>
				<div class="inn">
				<p>Не имеет значения…</p>
				<ul>
					<li class="li1">вы блондинка или брюнетка, худенькая или полненькая, сколько вам лет, какую должность занимаете и сколько зарабатываете...</li>
					<li class="li2">насколько однообразный у вас сейчас гардероб, сколько времени и сил вы тратите на подбор одежды, а также умеете ли вы компоновать цвета, подбирать аксессуары и составлять комплекты...</li>
				</ul>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt4">
				<div class="inn">Я УВЕРЕНА, ЧТО ВАШ ТЕКУЩИй ОФИСНЫй ГАРДЕРОБ РАБОТАЕТ НА ВАС МАКСИМУМ НА 10% - ПРОСТО ПОТОМУ ЧТО ВАМ НЕ РАССКАЗЫВАЛИ СЕКРЕТЫ СТИЛЬНОГО ГАРДЕРОБА В ОФИС.</div>
			</div>
			<div class="break"></div>
			<div class="txt5">
				<span>Просто сейчас вы не знаете </span>КАК сделать ваш гардероб в офис стильным, индивидуальным, интересным и с изюминкой. Я знаю, как его изменить, чтобы он работал на все 100.
			</div>
			<div class="break"></div>
			<div class="bg"></div>
		</div>
	</div>
	
	<div class="block2">
		<div class="main_btn"><a href="#">Записаться на тренинг</a><div class="inn"></div><div class="shad"></div></div>
	</div>
	
	<div class="block3">
		<div class="ins">
			<div class="block">
				<div class="txt1">Стильный гардероб в офис - это как некий магический трюк. Вы можете в нем разобраться всего за 5 минут. А потом использовать всю жизнь!</div>
				<div class="txt2">
					<p>Да! Всего за 5 минут! Давайте я объясню, как именно.</p><br>
					<p>Вы получите от меня 101 рецепт стильного офисного образа. И разобраться в каждом рецепте можно всего за 5 минут. Потом вам нужно лишь по готовым шагам собрать себе образ на новый день.</p><br>
					<p>И получить стильный образ может каждый - вам не надо быть гуру моды. </p><br>
					<p>Вы получите рецепты, о существовании которых вы даже не мечтали! Они настолько просты и действенны, что вы можете проверить их не рискуя ни копейкой. Как это? Об этом я рассказываю ниже.</p>
				</div>
				<div class="txt3">
					<div class="shad"></div>
					<div class="inn">
					Моя работа связана с общением с успешными людьми, и мне важно знать, что я «вписываюсь» по имиджу, и отношение сразу соответствующее. Уже можно решать вопросы, и люди готовы обсуждать и сотрудничать. Ну и конечно, в личной жизни. Когда идешь на свидание, и чувствуешь себя прекрасной, сексуальной, но при этом полностью одетой — это здорово))<br>- Елена К.
					</div>
				</div>
			</div>	
		</div>
	</div>
	
	<div class="block4">
		<div class="main_btn"><a href="#">Записаться на тренинг</a><div class="inn"></div><div class="shad"></div></div>
	</div>
	
	<div class="block5">
		<div class="ins">
			<div class="block">
				<div class="txt1">Уже после первого дня вы сможете появиться в офисе в новом образе и <span>даже собирать комплименты и изучающие взгляды.</span></div>
				<div class="txt2">
					<p>Позвольте мне пригласить вас на мой собственный риск в один из самых увлекательных тренингов, в которых вы когда-либо участвовали. </p><br>
					<p>Будьте готовы выделить для себя несколько вечеров. Попросите вас не беспокоить. Возьмите чашечку чая, устройтесь поудобнее перед компьютером и приготовьтесь погрузиться в мир элегантности и стиля - мир вашего будущего гардероба.  </p><br>
					<p>И это реально благодаря тому, что вы получите простые работающие инструменты по преобразованию вашего образа.</p>
				</div>
				<div class="txt3">
					<div class="shad"></div>
					<div class="inn">
					Женщина может лукавить по любому поводу! Но когда она хорошо выглядит и знает об этом, тогда легкая походка, блеск в глазах, ощущение «все могу» мгновенно расскажут самую большую правду-эта женщина нравится самой себе! А у окружающих просто не будет выбора! Такая женщина любит и любима, привлекательна и привлекает. А это самое ценное!<br>- Юлия, Минск

					</div>
				</div>
			</div>
			<div class="break"></div>
		</div>
	</div>
	
	<div class="block6">
		<div class="ins">
			<div class="txt1"><span>ПРЕДУПРЕЖДЕНИЕ:</span><br>Когда ваш муж увидит ваши новые образы, это может вызвать разнообразные реакции. Он может проявить к вам повышенный интерес, начнет уделять вам больше времени и внимания или даже ревновать. Поэтому не удивляйтесь!</div>
			<div class="txt2 t1">
				<div class="shad"></div>
				<div class="inn">
				Сходила с мужем в ресторан. Он был горд от того, что рядом с ним женщина, на которую окружающие бросают внимательные и изучающие взгляды… Муж признался, что теперь я выгляжу так, как он любит… Безусловно, приятно, что наши вкусы и предпочтения в этом вопросе совпадают, но для меня все-таки главное, что Я ВЫГЛЯЖУ ТАК, КАК НРАВИТСЯ МНЕ и мне от этого ХОРОШО!!!<br>- Екатерина Л.
				</div>
			</div>
			<div class="break"></div>
			<div class="txt2 t2">
				<div class="shad"></div>
				<div class="inn">
				...и даже муж, мне кажется почувствовал и стал как будто ухаживать за мной, то дверь в машине откроет, хотя раньше не открывал, то чего-нибудь купит вкусненького специально для меня!!!! :))))<br>- Анна Слаева
				</div>
			</div>
			<div class="break"></div>
			<div class="txt2 t3">
				<div class="shad"></div>
				<div class="inn">
				Помнишь, я говорила, что у меня настроение поднялось…так странно у меня до сих пор такое хорошее настроение. Все время улыбаюсь!!!!! Муж не понимает что со мной…но ему нравиться мое настроение)))<br>- Анна Слаева
				</div>
			</div>
			<div class="break"></div>
			<div class="bg"></div>
		</div>
	</div>
	
	<div class="block7">
		<div class="ins">
			<div class="txt1"><span>Каждый вечер</span> в течение 10 дней мы будем<br> совместно проходить <span>по 10 рецептов.</span></div>
			<div class="txt2">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="num1"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/num1.png" alt="" /></div>
				<div class="inn">
				Если у вас нет вечера на изучение первых 10 рецептов, вы можете просто взять рецепт #6 и составить образ с юбкой КАК МЫ ПОКАЖЕМ и уже на следующий день вы можете пожинать плоды своей креативности.
				</div>
			</div>
			<div class="txt3">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="num1"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/num2.png" alt="" /></div>
				<div class="inn">
				Но это только начало! Со второго дня возьмите рецепт #11. Посмотрите на сочетание цветов, которое мы предлагаем. Появитесь в этом в офисе на следующий день. И отметьте для себя, насколько эффектно вы будете смотреться. 
				</div>
			</div>
			<div class="txt4">
				<div class="shad"></div>
				<div class="inn">
				Сегодня на работе все на меня пялятся… Комплименты говорят иногда. Но по большей части пялятся девушки … молча и оценивающе)))) Только одна прокомментировала: "Что-то ты в последнее время все лучше и лучше выглядишь .. что денег больше на шмотки тратишь что ли?"… Мамочкииии…. Это и есть женская завить, о которой мы говорили?<br>- Оля
				</div>
			</div>
			<div class="txt3">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="num1"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/num3.png" alt="" /></div>
				<div class="inn">
				А потом обратите внимание на совет #54. Вы увидите как такая небольшая деталь, как туфли, может вывести ваш гардероб на новый уровень.
				</div>
			</div>
			<div class="bg"></div>
		</div>
	</div>	
	
	<div class="block2">
		<div class="main_btn"><a href="#">Записаться на тренинг</a><div class="inn"></div><div class="shad"></div></div>
	</div>
	
	<div class="block8">
		<div class="ins">
			<div class="block">
				<div class="txt1">А теперь смотрите как <span>вы будете блистать на встречах, презентациях, да и просто в кругу коллег!</span></div>
				<div class="txt2">
					<p>Просто представьте! С этого момента вам надо будет всего лишь выбрать новый рецепт, выделить 5 минут, чтобы разобраться в нем, далее применить к своему гардеробу и новый образ готов!</p><br>
					<p>Вам не обязательно применять все 101 рецепт к своему гардеробу. </p><br>
					<p>Ведь это как с кулинарной книгой. Чтобы удивить гостей бывает достаточно одного хорошо приготовленного блюда. Так и хорошо выверенный образ может решить судьбу вашего продвижения или заключенного контракта. </p><br>
					<p>А чтобы разнообразить ежедневное меню, достаточно 15 новых рецептов. Так и вы, применив всего 15 рецептов из нашего тренинга, сделаете ваш офисный гардероб элегантным, стильным, интересным, индивидуальным и с изюминкой. </p>
				</div>
				<div class="txt3 t1">
					<div class="shad"></div>
					<div class="inn">
					Если в прошлый раз (после летнего шопинга) со стороны коллег и подруг была зависть, то в этот раз наоборот — молча разглядывали комплекты, а потом спустя несколько дней просили у меня совета и рекомендаций по своему внешнему виду и что им нужно изменить или улучшить, чтобы выглядеть привлекательней. Чтобы я взяла над ними «шефство»))))<br>Грабовская Янина, секретарь, г. Москва
					</div>
				</div>
				<div class="txt3 t2">
					<div class="shad"></div>
					<div class="inn">
					Подружки спрашивают совета, а я, гордо поправляя воображаемые очки, даю им советы «По Кате»:) Мне кажется это удивительным, но со мной стали знакомиться мужчины, чего раньше никогда не было.<br>- Юля
					</div>
				</div>
				<div class="break"></div>
				<div class="txt2">
					<p>Первые вспышки изменения в вашем гардеробе через некоторое время приведут вас к новому имиджу, который даст вам новые возможности карьерного роста и который будет отражать вашу индивидуальность. </p>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt4">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="num1"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/num4.png" alt="" /></div>
				<div class="inn">
				<span>Например,</span>
				Вы любите носить джинсы, но не знаете как составить элегантный образ с ними, кот будет уместен на работе в пятницу? Тогда используйте советы #4, #14, #58 и #69 .... Они позволят вам составить стильный, элегантный, но при этом расслабленный образ с джинсами, в котором вы сможете встретиться с друзьями, сходить в кино, посидеть в кафе. 
				</div>
			</div>
			<div class="txt5">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="num1"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/num5.png" alt="" /></div>
				<div class="inn">
				<p>Вы не умеете выбирать аксессуары в офис? Думаете, как бы не надеть чрезмерное и будет ли это уместно? Тогда откройте советы #20, #28, #33, #42, #47, #56, #75, #77 и #98.</p><br>
				<p>Применив их, вы увидите как небольшой акцент может полностью изменить образ и добавить ему изюминку. С помощью правильно выбранных аксессуаров вы сможете выгодно выделяться в офисе. И вы увидите, что ваш гардероб на уровень превосходит гардероб ваших коллег.</p>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt3">
				<div class="shad"></div>
				<div class="inn">
				Меня заставили обратиться к Вам удивление и некоторая зависть, которые я испытала, наблюдая, как стала все лучше и лучше выглядеть одна из моих коллег. У нее появились удивительно идущие ей ансамбли, в которых сочетались казалось бы не сочетаемые вещи, как по цвету, так и по фактуре.<br>- Татьяна

				</div>
			</div>
			<div class="txt3 t3">
				<div class="shad"></div>
				<div class="inn">
				По секрету — работать не могу…сегодня людно в моем уголке)))) Мальчики заходят один за другим, крутятся вокруг, что-то говорят, спрашивают, отвлекают… СМОТРЯТ!!! О_о Катя, платье творит чудеса! (скрестила пальцы) Девочки восхощаются вслух и (как вчера) про себя — вижу по взглядам. Улыбаюсь))) Мне нравится! Хочу, чтоб скорее тепло пришло, чтобы можно было ТАК по улицам ходить)))) муууррррррррк)))<br>- Оля
				</div>
			</div>
			<div class="txt6">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="num1"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/num6.png" alt="" /></div>
				<div class="inn">
				<p>Вы считаете, что ваш гардероб полон скучных, избитых и банальных сочетаний? Внесите в него свежую струю с помощью рецептов #63, #72, #95.</p><br>
				<p>Они позволят с одной стороны составить новые свежие сочетания с вашими любимыми предметами гардероба, а с другой стороны позволят ввести новые предметы, которые станут стилеобразующими в вашем гардеробе. </p>
				</div>
			</div>
			<div class="break"></div>
			<div class="bg1"></div>
			<div class="bg2"></div>
		</div>
	</div>
	
	<div class="block4">
		<div class="main_btn"><a href="#">Записаться на тренинг</a><div class="inn"></div><div class="shad"></div></div>
	</div>
	
	<div class="block9" id="point2">
		<div class="ins">
			<div class="block">
				<div class="bl_name">А кто ведет тренинг?</div>
				<p>Автор тренинга Екатерина Малярова – известный московский имиджмейкер, автор сайта Гламурненько.RU</p><br>
				<p>Всего за несколько лет работы Екатерина стала одним из самых востребованных имиджмейкеров Москвы. Запись к ней на шоппинг-сопровождение открывается за полгода. Часто в день проводит по несколько шоппингов.</p><br>
				<p>Одевала клиентов на красную ковровую дорожку, на экономический форум в Санкт-Петербурге, на встречу с президентом…</p><br>
				<p>Автор нескольких тренингов по персональному имиджу, автор Школы Имиджмейкеров, а также ведущая нескольких десятков семинаров.</p>
			</div>
			<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava1.jpg" alt="" /></div>
			<div class="break"></div>
		</div>
	</div>	
<?
if (APP::Module('DB')->Select(
    APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
    ['COUNT(id)'], 'users_tags',
    [
        ['user', '=', $data['user_id'], PDO::PARAM_INT],
        ['item', '=', 'evelina', PDO::PARAM_STR]
    ]
)) {
?>
<div class="block3" style="background: url('<?= APP::Module('Routing')->root ?>public/modules/pages/products/garderob100/images/bg4.jpg') repeat;    border-bottom: 2px dotted #cacbca;height: 442px;padding: 60px 0 0px 0;">
		<div class="ins">
			<div class="pic" style="margin-bottom: 50px;"><center><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/garderob100/images/ava4.png" alt=""></center></div>
			<div class="bl2" style="
    margin: 0 auto;
    width: 880px;
    font-size: 24px;
    line-height: 30px;
    color: #29302d;
">
<center><p style="
    font-weight: normal;
">"Женщина любого размера и любого возраста может выглядеть великолепно. Главное — правильно подобрать одежду"</p>
				<p style="
    font-weight: bold;
    margin-top: 40px;
	text-decoration: underline;
	color: -webkit-link;
">Эвелина Хромченко</p>
				<p style="
    font-size: 18px;
">fashion expert, TV-presenter, journalist</p></center>
			</div>
		</div>
	</div>
<div class="block3" style="background: url('<?= APP::Module('Routing')->root ?>public/modules/pages/products/garderob100/images/bg4.jpg') repeat; height: 442px;padding: 60px 0 0px 0;">
		<div class="ins">
			<div class="pic" style="margin-bottom: 50px;"><center><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/garderob100/images/ava3.png" alt=""/></center></div>
			<div class="bl2" style="
    margin: 0 auto;
    width: 880px;
    font-size: 24px;
    line-height: 30px;
    color: #29302d;
">
<center><p style="
    font-weight: normal;
">"Хочу вас познакомить с талантливым стилистом Катей! Очень рекомендую заглянуть к ней на страничку и пройти тест по стилю. Узнаете много нового и полезного! По крайней мере я узнала"</p>
				<p style="
    font-weight: bold;
    margin-top: 40px;
"><a href="https://www.instagram.com/p/_rDrEkrJTN/" target="_blank">Эвелина Блёданс</a></p>
				<p style="
    font-size: 18px;
">Российская актриса театра и кино, певица, телеведущая</p></center>
			</div>
		</div>
	</div>
<? } ?>	
	
	<div class="block2">
		<div class="main_btn"><a href="#">Записаться на тренинг</a><div class="inn"></div><div class="shad"></div></div>
	</div>
	
	<div class="block10">
		<div class="ins">
			<div class="bl_name">Почему этот тренинг работает?</div>
			<div class="txt1">То, что я вам описала на этой странице, это лишь небольшая часть нового тренинга "101 рецепт стильного гардероба в офис"</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i1">
				В конце концов это практический, легкий в восприятии и понимании тренинг, состоящий из конкретных и подробно описанных рецептов, которые работают!
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i2">
				В нем нет скучных, ненужных, неприменимых и оторванных от жизни теорий. 
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i3">
				Он создан на основе 7-летней практической работы с клиентами. За ним стоят тысячи часов шоппингов в Москве и в Италии с более чем 4 сотнями клиентов. 
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i4">
				Специально для этого тренинга я переработала весь свой практический опыт в простые и практичные рецепты. Они легко применимы даже для человека, не имеющего отношения к моде и не любящего ходить по магазинам.
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i5">
				Это как кулинарный рецепт. Собираете ингридиенты, выполняете указания типа "что и сколько варить". И результат неизбежен. 
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i6">
				Поскольку я много работаю с клиентами и общаюсь с ними, я очень хорошо понимаю в чем ваши сложности. 
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i7">
				Я испробовала большинство этих рецептов на клиентах. Часто встречаются ситуации, когда клиенту нужны подобные легкопримениемые даже без моего участия советы. 
				<ul>
					<li class="li1">Например, когда нужна срочная помощь, а у меня ближайшие несколько недель расписаны. </li>
					<li class="li2">Или когда человек едет в командировку в Европу или Америку и там планирует самостоятельный шоппинг. </li>
					<li class="li3">Или когда клиенту нужно легкое обновление гардероба и устраивать полноценный шоппинг ради пары комплектов нет смысла. </li>
				</ul>
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i9">
				Эти рецепты работают во всех этих ситуациях, потому что они очень конкретны, просты и понятны для любой женщины. 
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i10">
				Секрет их успеха в том, что они даны исходя не из позиции теории, а из позиции практики.
				</div>
			</div>
			<div class="txt2">
				<div class="shad"></div>
				<div class="inn">
				На работе у меня не принято делать комплименты, но по взглядам мужчин поняла — оценили. А один из директоров не удержался и сделал-таки комплимент, что я сегодня выгляжу (дословно) "сногсшибательно" и мне это платье очень идет. Очень приятно, что заметили не просто красивое платье, а МЕНЯ в нем. Значит, ВСЕ УДАЛОСЬ!!!!<br>- Светлана

				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i11">
				Тренинг работает для любой женщины - не важно 25 вам или 50. Менеджер вы или руководитель. 42 размер или 48. 
				</div>
			</div>
			<div class="txt3">Это может быть только началом в череде изменений, которые приведут к более уверенной, счастливой и прекрасной женщине. </div>
		</div>
	</div>	
	
	<div class="block4">
		<div class="main_btn"><a href="#">Записаться на тренинг</a><div class="inn"></div><div class="shad"></div></div>
	</div>
	
	<div class="block11">
		<div class="ins">
			<div class="bl_name">Примите участие в тренинге, ничем не рискуя!</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i1">
				Цена этого тренинга - намного меньше, чем вы тратите на одежду, которую даже не носите.  
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i2">
				Но что более важно - это безусловная гарантия. Мы понимаем, что вам в тренинге важно всё, что мы пообещали. Поэтому мы даем вам возможность изучать тренинг полностью без риска в течение 30 дней!
				</div>
			</div>
			<div class="item">
				<div class="shad"></div>
				<div class="inn i3">
				Если в конце этого времени вы не будете удовлетворены, тогда мы просто <span>вернем вам деньги.</span> Без лишних вопросов. Только вы судья!
				</div>
			</div>
			<div class="txt1">
				<div class="inn">К сожалению, в этом случае, мы вам больше ничего не продадим в будущем, чтобы не тратить ваше и наше время.</div>
			</div>
			<div class="break"></div>
			<div class="txt2">
				<div class="shad"></div>
				<div class="inn">
				Что касается денежных затрат, то честно вам скажу… лишние денежные затраты – это когда ты ходишь по магазинам для подбора летнего гардероба, начиная с конца апреля и заканчивая августом почти каждую неделю, тратишь на это уйму времени и, что самое обидное, нервов, в итоге покупаешь вещи, которые уже на следующий день ты отправляешь на пожизненный отдых в шкафу, выбрасывая при этом деньги в никуда.<br>- Екатерина Харина, Москва
				</div>
			</div>
			<div class="bg"></div>
		</div>
	</div>	
	
	<div class="block12">
		<div class="main_btn"><a href="#">Записаться на тренинг</a><div class="inn"></div><div class="shad"></div></div>
	</div>
	
	<div class="block13" id="point3">
		<div class="ins">
			<div class="bl_name">Бонусы для участников тренинга</div>
			<div class="item i4">
				<div class="label"></div>
				<div class="left"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/box2.png" alt=""/></div>
				<div class="right">
					<div class="name">Как собрать офисный гардероб из 15 вещей</div>
					<p>Я покажу на конкретном примере как собрать гардероб в офис из 15 вещей и как их грамотно дополнить аксессуарами. Это позволит вам появляться на работе каждый день в новом стильном и интересном комплекте в течение 2 недель. Вы узнаете как не забивая шкаф вещами и не тратя много денег, составить стильный и разнообразный гардероб в офис. </p><br>
				</div>
				<div class="break"></div>
			</div>
			<div class="break"></div>
			<div class="item i5">
				<div class="label"></div>
				<div class="left"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/box4.png" alt=""/></div>
				<div class="right">
					<div class="name">Как выбрать правильный образ для собеседования</div>
					<p>Вы узнаете несколько вариантов комплектов, которые позволят вам выглядеть на собеседовании элегантно, но в то же время стильно и интересно. Это позволит вам показать свою индивидуальность и запомниться, при этом оставаясь в рамках дресс-кода и не перейти тонкую грань.</p><br>
				</div>
				<div class="break"></div>
			</div>
			<div class="break"></div>
			<div class="item i6">
				<div class="label"></div>
				<div class="left"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/box5.png" alt=""/></div>
				<div class="right">
					<div class="name">Как преобразовать офисный наряд в наряд на выход</div>
					<p>Наверное, у вас бывают ситуации, когда вы после работы идете на какое-либо мероприятие. Это может быть поход в ресторан, в театр, на выставку, презентацию. Или просто встреча с друзьями в баре или посещение кинотеатра. И у вас просто нет времени, чтобы после работы заехать домой и переодеться </p><br>

					<p>Я расскажу как можно преобразовать офисный комплект в комплект на выход. Мы рассмотрим несколько вариантов образов. Одни подойдут для встречи в баре с друзьями или кино. Другие - для похода в театр или на выставку. </p>
				</div>
				<div class="break"></div>
			</div>
			<div class="break"></div>
			<div class="item i7">
				<div class="label"></div>
				<div class="left"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/box8.png" alt=""/></div>
				<div class="right" style="padding-top: 40px;">
					<div class="name">Как определить свой цветотип и цвета, которые вам идут</div>
					<p>На тренинге “101 рецепт стильного гардероба в офис” вы узнаете рецепты стильных образов в офис. Но как правильно выбрать цвета для этих комплектов, которые идут именно вам? В этом вопросе вам поможет разобраться бонусный семинар “Как определить свой цветотип и цвета, которые вам идут”</p>
					<p>Из этого семинара вы узнаете:<br>
- как определить свой цветотип<br>
- правила определения цветотипов зима, лето, весна и осень, а также их подтипов.<br>
- какие цвета подходят каждому из цветотипов<br>
- как выбрать цвета для своего гардероба</p>
				</div>
				<div class="break"></div>
			</div>
			<div class="break"></div>
			<div class="item i8">
				<div class="label"></div>
				<div class="left"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/box3.png" alt=""/></div>
				<div class="right">
					<div class="name">Определение вашего цветотипа по фотографии</div>
					<p>Имиджмейкер нашей команды определит ваш цветотип по фотографиям и пришлет отчет по цветам, которые вам идут</p><br>
				</div>
				<div class="break"></div>
			</div>
			<div class="break"></div>
		</div>
	</div>	
	
	<div class="block14" id="point4">
		<div class="ins">
			<div class="bl_name">КАК ПРОХОДИТ ТРЕНИНГ</div>
			<div class="txt1 t1" style="left: 250px;">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ico"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ico18.png" alt="" /></div>
				<div class="inn">
				Вы получаете доступ в закрытый раздел на сайте, где можете смотреть и скачивать обучающие видео. В удобном для вас темпе вы смотрите видео и применяете шаблоны к своему гардеробу.
				</div>
			</div>
			<div class="break"></div>
			<div class="txt1 t3">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ico"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ico20.png" alt="" /></div>
				<div class="inn">
				Ваши домашние задания вы можете размещать в любое время закрытом разделе. Вы гарантированно получите ответ, даже если вы решите пройти тренинг не сразу (гарантированный период проверки ДЗ - 2 мес. Потом вы можете докупить проверку).
				</div>
			</div>
			<div class="txt1 t4">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ico"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ico21.png" alt="" /></div>
				<div class="inn">
				Если вы еще опасаетесь за какие-либо технические моменты, пожалуйста, доверьтесь нам. Мы проводим тренинги и семинары через интернет уже несколько лет и максимально упростили для вас процесс. А служба поддержки оперативно поможет, если у вас останутся вопросы.
				</div>
			</div>
			<div class="break"></div>
		</div>
	</div>	
	
	<div class="block12">
		<div class="main_btn"><a href="#">Записаться на тренинг</a><div class="inn"></div><div class="shad"></div></div>
</div>
	
	<div class="block15" id="point5">
		<div class="ins">
			<div class="bl_name">Записывайтесь прямо сейчас!</div>

				<center><p style="font-size: 25px;">Успейте записаться со скидкой, пока время на таймере не истечет</p>
                                    <br><div class="clock" style="width: 630px;"></div>
<div class="message" style="margin: 0 auto;"></div>
	
	
<script type="text/javascript">
var date  = new Date(<?= $action_end ?>*1000);
var now   = new Date();
var diff  = date.getTime()/1000 - now.getTime()/1000;

var clock = $('.clock').FlipClock(diff, {
    clockFace: 'DailyCounter',
    countdown: true,
    language: 'ru'
}); 
</script> </center>	
			<div class="note"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr class="tr1">
					<td class="td1" style="width: 400px;"></td>
					<td class="td2"></td>
				</tr>
				<tr>
					<td class="td1"><span class="sp1">ОСНОВНЫЕ МАТЕРИАЛЫ:</span></td>
					<td class="td2"></td>
				</tr>
				<tr>
					<td class="td1">"101 рецепт стильного гардероба в офис"</td>
					<td class="td2"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/yes.png" alt="" /></td>
				</tr>
				<tr>
					<td class="td1">Проверка ДЗ<br/><span style="font-size: 18px">* имиджмейкером команды Гламурненько.ру</span></td>
					<td class="td2">2 месяца</td>
				</tr>
				<tr>
					<td class="td1"></td>
					<td class="td2"></td>
				</tr>
				<tr>
					<td class="td1"><span class="sp1">БОНУСЫ:</span></td>
					<td class="td2"></td>
				</tr>
				<tr>
					<td class="td1" style="padding-bottom: 4px; padding-top: 3px;">Видео-семинар "Как определить свой цветотип и цвета, которые вам идут"</td>
					<td class="td2"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/yes.png" alt="" /></td>
				</tr>
				<tr>
					<td class="td1" style="padding-bottom: 4px; padding-top: 3px;">Определение вашего цветотипа по фотографии</td>
					<td class="td2"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/yes.png" alt="" /></td>
				</tr>
				<tr>
					<td class="td1" style="padding-bottom: 4px; padding-top: 3px;">Видео-семинар "Как собрать офисный гардероб из 15 вещей"</td>
					<td class="td2"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/yes.png" alt="" /></td>
				</tr>
				<tr>
					<td class="td1" style="padding-bottom: 4px; padding-top: 3px;">Видео-семинар "Как выбрать правильный образ для собеседования"</td>
					<td class="td2"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/yes.png" alt="" /></td>
				</tr>
				<tr>
					<td class="td1" style="padding-bottom: 4px; padding-top: 3px;">Видео-семинар "Как преобразовать офисный наряд в наряд на выход"</td>
					<td class="td2"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/yes.png" alt="" /></td>
				</tr>
				<tr>
					<td class="td1"></td>
					<td class="td2"></td>
				</tr>
				<tr>
					<td class="td1">Стандартная цена</td>
					<td class="td2"><s>9 900 руб</s></td>
				</tr>
				<tr>
					<td class="td1"><span class="sp1">Ваша цена</span></td>
					<td class="td2"><span class="sp2">4 900 руб</span></td>
				</tr>
				<tr>
					<td class="td1"></td>
					<td class="td2"><a href="http://www.glamurnenko.ru/orders/order1.php?token=9T222SBlNyaHVq2Z0Zuq4ZAtjmYg0KbYWOd8PqR3IU8&tr_e=<?= $user['email'] ?>" class="btn"  target="_blank"></a></td>
				</tr>
			</table>
			<div class="note1"></div>
		</div>
	</div>
	
	<div class="block16">
		<div class="ins">
			<div class="block">
				<div class="bl_name">Быстрая помощь службы поддержки</div>
				<p>Участницы тренинга могут при необходимости получить помощь от нашей службы поддержки.</p><br>
				<p>Сотрудники службы поддержки оперативно ответят на все вопросы и разберутся со случайными ошибками и неувязками. Сделают максимум возможного, чтобы все участницы ощущали себя комфортно и не оставались один на один с нерешенными проблемами.</p><br>
				<p>Связаться со службой поддержки можно со страницы:<br><a href="http://www.glamurnenko.ru/blog/contacts/">http://www.glamurnenko.ru/blog/contacts/</a></p>
			</div>
		</div>
	</div>	
	
	<div class="block17" id="point6">
		<div class="ins">
			<div class="bl_name">отзывы</div>
				<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava101.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Практически каждый день я получала комплименты от коллег, зам. генерального директора «оценила» мой внешний вид, сказав, что выгляжу элегантно, со вкусом и по-деловому. <br><br>На одной деловой поездке с директором, получив от него комплимент, состоялся разговор, что мне надо сделать, чтобы получить повышение! На работе – комплименты; стали относиться серьезнее, что очень важно для молодого специалиста! Очень чувствуется мужское внимание!</span>
				Здравствуйте, Катя и команда Glamurnenko!<br><br>

Я работаю в Москве в сфере строительства специалистом по согласованию. Мое представление об офисном гардеробе ограничивалось белыми рубашками, черными аксессуарами и бесформенным скучным костюмом. Чтобы получить какой-то карьерный рост, нужно выглядеть «с иголочки», поэтому я решила кардинально заняться своим имиджем.<br><br>

До тренинга в моем гардеробе были только белые рубашки, черная обувь, 2 сумки: бежевая и черная, практически вся одежда бесформенная и не интересная. Настроение и уверенность в себе были на соответствующем уровне. Вещи покупались из раза в раз одинаковые, серые и безликие.<br><br>

По мере прохождения тренинга, я составляла комплекты, добавляя какие-то украшения, делая образы лаконичными, женственными и насколько возможно интересными. Практически каждый день я получала комплименты от коллег, зам. генерального директора «оценила» мой внешний вид, сказав, что выгляжу элегантно, со вкусом и по-деловому.<br><br>

На одной деловой поездке с директором, получив от него комплимент, состоялся разговор, что мне надо сделать, чтобы получить повышение! Теперь я очень хорошо прочувствовала, как важен стиль, дресс-код и аккуратность в одежде.<br><br>

Я основательно почистила гардероб, отдала все вещи, которые подчеркивают какие-то мои недостатки. Избавилась примерно от 2/3 своих вещей! Сейчас составила список must have, буду потихоньку добавлять их в свой гардероб, предстоит большооой shoooopping Теперь при виде вещи в голове рождается образ, какие комплекты можно составить.<br><br>

Тренинг помог мне не только с разбором гардероба, но и появилось «чуткость к цветам»: в ванной была сделана интересная переделка, всего лишь с помощью баллончика краски и капельки фантазии. Хочется добавить цветов в интерьер. Хочется заниматься своей внешностью: ноготочки; делать прически ежедневно, а не только по праздникам; макияж. В общем, есть, где развернуться и применять новые знания!<br><br>

Тренинг погрузил меня в совершенно другой мир: жакеты, блейзеры, футлярные платья и лодочки. Раньше в моем лексиконе даже таких слов не было. Были совершенно новыми материалы по цветотипу: никогда не следовала этим правилам, хотя и замечала, что некоторые цвета совершенно не идут к лицу. Информации очень много, интересной и разнообразной.<br><br>

На работе – комплименты; стали относиться серьезнее, что очень важно для молодого специалиста! Очень чувствуется мужское внимание!<br><br>

Тема с подходящими цветами довольно глубокая. За одно занятие все не изучить. Буду переслушивать занятия, учиться составлять интересные образы не только в офис, но и в повседневной жизни.
Я очень благодарна Екатерине за интересный результативный тренинг!

<center><table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-3.jpg" alt="" width="300" height="400" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-4.jpg" alt="" width="300" height="400" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-5.jpg" alt="" width="300" height="400" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-6.jpg" alt="" width="300" height="532" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-7.jpg" alt="" width="600" height="689" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-8.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Evgenia-Bocharova-9.jpg" alt="" width="600" height="454" /></a></center></td>
</tr>
</tbody>
</table></center>
				<span class="sp2">Евгения Бочарова, Москва<br>
Работает в сфере строительства специалистом по согласованию</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Реакция окружающих была очень положительная. Могу рассказать на примере.<br><br>У нас была встреча в Московским руководством и нужно было быть на высоте. <br><br>Составила комплект с жакетом, ярким топом и юбкой с принтом + ожерелье. Молодая коллега по работе, попросила сфотографировать меня, чтобы показать своей маме, которая уверяет, дамам с формами невозможно подобрать красивую одежду))</span>
				Главная проблема гардероба- однообразность. Решить не пыталась.<br><br>

Я прохожу уже не первый тренинг. Он является для меня вдохновением, возможностью задуматься над своим гардеробом, поработать над ним.<br><br>

Интересно послушать о правилах составления комплектов и о новинках в мире моды.<br><br>

Теперь в магазине не бросаюсь на каждую вещь, а анализирую, составляю мысленно комплекты и т.д.<br>
Реакция окружающих была очень положительная. Могу рассказать на примере.<br>
У нас была встреча в Московским руководством и нужно было быть на высоте. Составила комплект с жакетом, ярким топом и юбкой с принтом + ожерелье.<br><br>

Молодая коллега по работе, попросила сфотографировать меня, чтобы показать своей маме, которая уверяет, дамам с формами невозможно подобрать красивую одежду))<br>
Составила список покупок на будующее. Теперь точно знаю, что обязательно нужно, а что можно и пропустить. Буду искать, т.к. конечно с моим размером действительно сложно.<br><br>
Спасибо большое Катя за хорошее настроение и уверенность в себе!!!!!!

<center>
<table>
<tbody>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Svetlana-Okulova3-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Svetlana-Okulova3-1.jpg" alt="" width="300" height="487" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Svetlana-Okulova-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Svetlana-Okulova-1.jpg" alt="" width="600" height="618" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Светлана О., Санкт-Петербург<br>
Дизайнер по текстилю в интерьерном салоне</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">В процессе тренинга составляла комплекты из одежды какая есть, и вот какое же было мое удивление, что даже из того что у меня есть можно составлять комплекты, добавляя аксессуары, сумки, сочетая по-другому цвета, раньше никогда бы так не додумалась. Было очень приятно, познавательно и занимательно.</span>
				В данный момент проживаю во Владикавказе (часто переезжаю по работе мужа). Сейчас не работаю, точнее работаю домохозяйкой, планирую выходить на работу. поэтому офисного гардероба собственно и не было))<br><br>

В процессе тренинга составляла комплекты из одежды какая есть, и вот какое же было мое удивление, что даже из того что у меня есть (а я считала, что за продолжительное время сидя дома у меня вообще носить нечего) можно составлять комплекты, добавляя аксессуары, сумки, сочетая по другому цвета, раньше никогда бы так не додумалась. Оказывается, у меня еще есть в чем ходить))<br><br>

Это очень радует. А если представить, что еще раз проработать тренинг уже более осмысленно и докупить недостающие вещи, то вообще будет все замечательно)) Из за того что долго сидишь дома, кажется, что уже и одеваешься не так и отстала ты совсем от всех, а на тренинге начинаешь анализировать, присматриваться к другим людям вокруг и оказывается , что все не так уж и плохо.<br><br>

Спасибо вам Катя за тренинг, и всем участницам тоже. Было очень приятно, познавательно и занимательно. Конечно еще много нужно сделать, по гардеробу, но теперь я хотя бы знаю куда и как двигаться.))
				<span class="sp2">Галина С., Владикавказ<br>
домохозяйка</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava102.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Мне очень понравилось как информация подавалась и объяснялась.<br><br>Я получила много комплиментов от мужа и сына (даже не знала что он обращает столько внимания на то во что я одета!), а одна знакомая сказала: «ты всегда так хорошо выглядишь!».<br><br>В результате тренинга я смогла составить новые интересные комплекты, некоторые из которых применимы и в повседневной жизни. <br><br>Я обязательно их буду носить. Катя как всегда была на высоте! Браво!! Её энтузиазм заряжает и вдохновляет.</span>
				Добрый вечер Катя и девочки!<br><br>

Я из города Питерсфилд (Англия), сейчас нахожусь в отпуске по уходу за младшим ребёнком и учусь на имиджмейкера. До тренинга у меня было много офисной одежды , которую я отложила за ненадобностью.<br><br>

Мне было очень интересно постараться интегрировать ее в мой гардероб, а также потренироваться в составлении интересных комплектов для офиса. Когда я работала у меня никогда не было строгого дрес кода, но я почему то сама себе его придумала и одевалась достаточно скучно.<br><br>

В результате тренинга я смогла составить новые интересные комплекты, некоторые из которых применимы и в повседневной жизни. Я обязательно их буду носить. Катя как всегда была на высоте! Браво!! Её энтузиазм заряжает и вдохновляет.<br><br>

Мне очень понравилось как информация подавалась и объяснялась. Ведь самое главное понять основные принципы компоновки в костюмный ансамбль — и вперёд! Мне кажется этот тренинг вполне можно использовать и для повседневного гардероба, во всяком случае большую часть из 101 (!!) рецептов.<br><br>

Я получила много комплиментов от мужа и сына (даже не знала что он обращает столько внимания на то во что я одета!), а одна знакомая сказала: «ты всегда так хорошо выглядишь!».<br><br>

В ближайших планах — переслушать весь тренинг включая разбор всех домашних заданий так как на это у меня не всегда хватало времени и навести порядок в шкафу (там после примерок такое твориться!).<br><br>

Ещё раз большое спасибо, Катя, и так держать!

<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/2bb0dd18c509faa92c929c2a44c18517.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/2bb0dd18c509faa92c929c2a44c18517.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/2ea4acdd063753f1c33a671ad6368301.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/2ea4acdd063753f1c33a671ad6368301.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/4b5a4620db1d4d21d0911fca18ee05f0.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/4b5a4620db1d4d21d0911fca18ee05f0.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/5ea4b6de6ae14894353cbedede4fdae9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/5ea4b6de6ae14894353cbedede4fdae9.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/7a953fe9c09e223ddb426bd35257c5dc.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/7a953fe9c09e223ddb426bd35257c5dc.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/7add0187a8638a23ea8b36c11a550d18.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/7add0187a8638a23ea8b36c11a550d18.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/7d65d5173ed4b87ab0ee8ece9920948f.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/7d65d5173ed4b87ab0ee8ece9920948f.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/8c6e75dfc0023566b024e6be025c43c9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/8c6e75dfc0023566b024e6be025c43c9.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/9a517f2c1932f44691f0e0415be75597.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/9a517f2c1932f44691f0e0415be75597.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/9f4ecbabd92aa9898a61a8bc9b44fef9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/9f4ecbabd92aa9898a61a8bc9b44fef9.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/12f72b05c535834c04a8e57cc8b81923.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/12f72b05c535834c04a8e57cc8b81923.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/43e9c803069c301921bf51df329c46c5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/43e9c803069c301921bf51df329c46c5.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/64f395179c4dc192fd574df4b2dc2fe2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/64f395179c4dc192fd574df4b2dc2fe2.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/82ebf066f15efed6cfbf6b76b46ce4d4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/82ebf066f15efed6cfbf6b76b46ce4d4.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/122cdf82a319305cf3b2190fc6e7f506.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/122cdf82a319305cf3b2190fc6e7f506.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/380a76edc209f0e77266b001e246476d.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/380a76edc209f0e77266b001e246476d.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/387cb5b2e9863e02829bb2e1b6544871.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/387cb5b2e9863e02829bb2e1b6544871.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/632dcf959311ddb1e42b119239a5251c.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/632dcf959311ddb1e42b119239a5251c.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/603b4e717608d8cb10a8f157ed784549.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/603b4e717608d8cb10a8f157ed784549.jpg" alt="" width="600" height="442" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/690bd0018b549beea09a63a9246fa355.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/690bd0018b549beea09a63a9246fa355.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/2197f210b1f194de24d1787b697185dc.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/2197f210b1f194de24d1787b697185dc.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/9873b289e18bfc76d85d2c3b0ccefe07.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/9873b289e18bfc76d85d2c3b0ccefe07.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/426646bf51e1cfe445fb026bf855eca1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/426646bf51e1cfe445fb026bf855eca1.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/50955267f5df83bd110e79b24d9d2591.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/50955267f5df83bd110e79b24d9d2591.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/a194d857b5b5336c032c892a07d870aa.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/a194d857b5b5336c032c892a07d870aa.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/ac38978e90c5e12a6b7495ae99a08aac.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/ac38978e90c5e12a6b7495ae99a08aac.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/b5ca7bab456a69ba4620b060868273dd.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/b5ca7bab456a69ba4620b060868273dd.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/ba15dcf59b308863f0249fbc1863c333.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/ba15dcf59b308863f0249fbc1863c333.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/bcfe04fae854b19f744d875c0595168a.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/bcfe04fae854b19f744d875c0595168a.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/bfee9f62a0b2e47e44bb0e408516f351.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/bfee9f62a0b2e47e44bb0e408516f351.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/cb458229a1bd0ac035550f126cdc8800.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/cb458229a1bd0ac035550f126cdc8800.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/c6635f29c397cba6d215abf6a76f9352.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/c6635f29c397cba6d215abf6a76f9352.jpg" alt="" width="600" height="343" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/d42cee0bdeb7248bb28cc00446644ace.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/d42cee0bdeb7248bb28cc00446644ace.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/d189dfaab968ac6bdbf68e123f2061c3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/d189dfaab968ac6bdbf68e123f2061c3.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/d6473281da142046605b81b194331bd5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/d6473281da142046605b81b194331bd5.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/e2464bc04131b45a68dcd571c50ba0d9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/e2464bc04131b45a68dcd571c50ba0d9.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/ebb0d2dc790bb59d9e404717d5d9291d.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/ebb0d2dc790bb59d9e404717d5d9291d.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/ef9c8b2cfabf2156940f141e24e31dc2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/ef9c8b2cfabf2156940f141e24e31dc2.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/f19b90d24ce6b094a8505234fb07057f.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/f19b90d24ce6b094a8505234fb07057f.jpg" alt="" width="300" height="533" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/f349e6ed0dd317f99b34e96ae297dcaf.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/f349e6ed0dd317f99b34e96ae297dcaf.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/fcd71ed20d2e733c12d3c994f4712377.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/fcd71ed20d2e733c12d3c994f4712377.jpg" alt="" width="300" height="533" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Татьяна Гарлант, Питерсфилд (Англия)<br>
в отпуске по уходу за младшим ребёнком и учится на имиджмейкера</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">После погружения в мир красоты с вашей помощью успокоилась, расставила приоритеты, посмотрела примеры, услышала конкретные решения. Теперь продавцы одежды меня не пугают, и я смело могу выбирать, мерить и даже разговаривать об образах и тенденциях. Прорыв для меня!!!<br><br>Муж стал присматриваться и разглядывать с подозрением, но я вижу, что он мной гордиться. Какие эмоции были при прохождении тренинга? Облегчение, удовольствие и радость, гордость за себя. Внимание мужа усилилось, дети меня хвалят, просто люди рассматривают.</span>
				Работала бухгалтером, 3 года нахожусь в декретном отпуске. К сожалению образов не собрала, хотелось прослушать до конца, чтобы не ошибиться. Поторопилась с покупкой черного пальто чуть ниже колен, а потом прослушала рецепт о нем и расстроилась, что длину взяла не свою (рост 160) и цвет (я лето) мне не идет.<br><br>

В магазины страшно было заходить, т.к. в последнее время основная моя одежда джинсы и кеды. Скоро на работу, у меня была паника по поводу внешнего вида, что носить и с чем носить представления не имела.<br><br>

После тренинга поняла, что даже будучи работающей молодой девушкой одевалась в офис в корне неправильно и пошло. Никто не учил и не подсказывал. Просто ходила в магазин покупала очередную блузку, мрачные брюки, готовые костюмы. Сумки и туфли черные и белые. все скучное и неинтересное.<br><br>

После погружения в мир красоты с вашей помощью успокоилась, расставила приоритеты, посмотрела примеры, услышала конкретные решения. Все четко и понятно: что модно, какие вещи стоит покупать, что обязательно нужно купить, как выбирать и сочетать цвета.<br><br>

Теперь продавцы одежды меня не пугают и я смело могу выбирать, мерить и даже разговаривать об образах и тенденциях. Прорыв для меня!!! Открытием для меня стали аксессуары. Не носила и не знаю, как выбирать, чтобы выглядело не дешево, а очень хочется использовать браслеты и колье.<br><br>

Я пошла в магазин купила себе юбку — карандаш, топ, 2 платья (правда не футлярных), пудровые туфли, кардиган, приобрела пару-тройку платков. Каждый день выбираю время для магазина и потихоньку собираю себе комплекты. На очереди брюки и белая блузка. Муж стал присматриваться и разглядывать с подозрением, но я вижу, что он мной гордиться.<br><br>

Какие эмоции были при прохождении тренинга? Облегчение, удовольствие и радость, гордость за себя. Подруги при встрече сказали, что обязательно оденут платья на нашу традиционную встречу. Внимание мужа усилилось, дети меня хвалят, просто люди рассматривают.<br><br>

Если честно так и не поняла весна или лето мой цветотип. Трудности с бижутерией, какова стоимость не примитива, но в рамках обыкновенной женщины со среднестатистическим доходом.
				<span class="sp2">Наталья Т., Новокузнецк<br>
Бухгалтер, 3 года в декретном отпуске</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">После прослушивания лекций, разобрала свою одежду, составила план покупок. Насколько это легче, приятней, эффективней. Насколько более уверенно я стала себя чувствовать, т.к. ЗНАЮ, что мне нужно. Открываю для себя необычные сочетания цветов. А это так прекрасно! Эмоции от занятий самые положительные. <br><br>Теперь хочется поскорее создать свой индивидуальный образ. Уже одеваюсь по вашим советам и стала получать комплименты-какая ты сегодня красивая.</span>
				Живу в Лиссабоне, Португалия.<br><br>

Ранее одевалась по интуиции и на что денег хватало. Об аксессуарах думала немного. Разве что серьги и ожерелье. Туфель 10 разных оттенков и цветов не имела, также, как и сумок. Поэтому черный, коричневый- на зиму, белый, бежевый -на весну-осень. Иногда другой цвет.<br><br>

Поход в магазин завершался головной болью и усталостью, т.к. всего много, конкретной идеи нет, смотришь все подряд, что красиво вообще, а не идет тебе лично.<br><br>

Сейчас, после прослушивания лекций, разобрала свою одежду, составила план покупок. Вхожу в магазин, выбираю, во-первых, цвет, потом фасон и, в конце уже мой размер. Насколько это легче, приятней, эффективней. Насколько более уверенно я стала себя чувствовать, т.к. ЗНАЮ, что мне нужно. Спасибо, Катюша!<br><br>

Я плохо проработала аксессуары в т.ч. обувь, сумочки. Нет комплектов, где гармонично сочетаются цвета и фактуры тканей. Открываю для себя необычные сочетания цветов. А это так прекрасно! Да работы непочатый край! Пересматривать надо почти все.<br><br>

Вообще после ваших лекций будто солнышко внутри меня засияло. Большое спасибо Вам, Катюша, за тот творческий стимул, что привнесли в мою жизнь!<br>
Эмоции от занятий самые положительные. Теперь хочется поскорее создать свой индивидуальный образ. Уже одеваюсь по вашим советам и стала получать комплименты-какая ты сегодня красивая.<br><br>

Мне нужно поработать с сочетанием цветов, с подбором аксессуаров
				<span class="sp2">Татьяна Р., Лиссабон</span>
				</div>
			</div>
			<div class="break"></div>			
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Это уже не первый тренинг у Кати, все они мне очень нравятся. Поскольку тренинг не первый, то окружающие отмечают, что я стала лучше выглядеть. Тренинг очень хороший, даны конкретные рекомендации по составлению комплектов.</span>
				Офисного гардероба не было, потому что в компании где я работаю нет дресс-кода, поэтому проблем тоже не было, просто интересно для себя меняться и развиваться.<br><br>

Тренинг очень хороший, даны конкретные рекомендации по составлению комплектов, хотелось бы чтобы примеры были на примере реальных клиентов, так более понятно, их проще применять для составления своих комплектов, чем фотографии моделей и примеры из Поливори.<br><br>

Пока только прослушала тренинг, делать было некогда, поэтому буду слушать еще раз и тогда уже составлять комплекты.<br><br>

Это уже не первый тренинг у Кати, все они мне очень нравятся, единственное сложно совмещать с основной работой выполнение домашних заданий, очень быстрый темп, поэтому еще раз все прослушаю и буду составлять комплекты в своем темпе. Еще очень понравился бонус по составлению покупок на шоппинг, хотелось бы больше подобной практической работы с Катей.<br><br>

Поскольку тренинг не первый, то окружающие отмечают, что я стала лучше выглядеть.<br><br>

Я все только прослушала, поэтому считаю, что это еще не проработано, буду слушать еще раз и тогда на практике составлять комплекты.<br><br>

Огромное спасибо за тренинг! Хотелось бы еще аналогичный тренинг для свободного времени и больше примеров на реальных клиентах, огромное спасибо за вебинар с разбором комплектов реальных клиентов Светланы и Нины (с фотографии моделей и примеры из Поливори сложнее адаптировать к своему гардеробу), сразу все становиться более легко и понятно.<br><br>

Еще раз спасибо!
				<span class="sp2">Елена К., Москва<br>
бухгалтер</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Как будто раньше действовала вслепую, а теперь глаза открылись. Захватывающе интересно и вдохновляюще, начинаешь видеть перед собой перспективу.<br><br>Напрямую комплиментов не услышала, но коллеги потянулись ко мне – больше желающих пообщаться со мной, подойти – по малейшему поводу. Заметно, что видеть меня им стало приятно. Женщины (некоторые) начинают «глазеть» — разглядывать.</span>
				Были трудности с составлением комплектов – что к чему подходит и сочетается.<br><br>

Появились четкие рецепты, следуя которым комплекты стала составлять более осмысленно. Как будто раньше действовала вслепую, а теперь глаза открылись.<br><br>

Гардероб очень обширный, но использовалась малая часть, что раздражало – куплены хорошие вещи, мне идут, мне нравятся, а я их почему-то не ношу. Теперь начинаю вводить в действие, комбинируя с уже испытанными или составляя новые комплекты. Немного пока, всего четыре образа, но уже приятно: могу, значит! Знаю в каком направлении двигаться.<br><br>

Захватывающе интересно и вдохновляюще, начинаешь видеть перед собой перспективу.<br><br>

Напрямую комплиментов не услышала, но коллеги потянулись ко мне – больше желающих пообщаться со мной, подойти – по малейшему поводу. Заметно, что видеть меня им стало приятно. Женщины (некоторые) начинают «глазеть» — разглядывать.<br><br>

Пока неуверенно чувствую себя с украшениями – на картинке все нравится, а на себя ожерелье надеть – кажется крупновато, аляповато и т.д. Что-то выбираю поскромнее – жемчуг под горло, кулончик на цепочке и т.д. Правда, кольца и браслеты выбираю легче.<br><br>

Еще момент – отношение со стороны мужа не изменилось (оно, конечно, и так хорошее), но на работе меня он не видит, а домашний гардероб не изменился. Так что последнее время я как будто примелькалась – а хочется чтобы глазами провожал (как читала в отзывах).
				<span class="sp2">Елена В., Киров<br>
Юрист (патентовед)</span>
				</div>
			</div>
			<div class="break"></div>			
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Стало легче подбирать новую офисную одежду и составлять интересные комплекты из своего гардероба. Испытала восторг, т.к можно просто и в тоже время интересные собрать комплекты. Реакция окружающих - удивление и восторг.</span>
				Были проблемы с выбором элегантного фасона одежды и подбора удачного сочетания цветов, покупала брючный костюм, блузки.<br><br>

Стало легче подбирать новую офисную одежду и составлять интересные комплекты из своего гардероба.<br><br>

Составила список новых офисных покупок осень-зима, вернувшись из командировки пойду на шопинг.<br><br>

Испытала восторг, т.к можно просто и в тоже время интересные собрать комплекты.<br>
Реакция окружающих — удивление и восторг.<br><br>

В планах более смелые и разнообразные составить комплекты.

<center><table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Lipina.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Lipina.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Lipina2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Lipina2.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Lipina3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Lipina3.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
</tbody>
</table></center>
				<span class="sp2">Екатерина Л., Москва<br>
ген. директор юр. фирмы</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Пересмотрела весь свой гардероб и поняла, что необходимо изменить. С нетерпением ждала каждого дня тренинга. Я — в восторге!!!! От окружающих восхищения, комплименты!</span>
				Я Закройщик-портной женской одежды. Начинающий стилист-имиджмейкер.<br>
Все комплекты были не настолько интересными как хотелось бы. Очень хотелось цветового разнообразия. Скопировать могла всё, что угодно, а вот составить что-то своё-весьма затруднительно (было). Теперь понимаю, как работать с цветом.<br><br>

Пересмотрела весь свой гардероб и поняла, что необходимо изменить. С нетерпением ждала каждого дня тренинга. Я — в восторге!!!! От окружающих восхищения, комплименты! В плане отработка всего материала на своих клиентах.

<center><table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Svetlana-Knyazeva.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Svetlana-Knyazeva.jpg" alt="" width="300" height="402" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Svetlana-Knyazeva2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Svetlana-Knyazeva2.jpg" alt="" width="300" height="397" /></a></center></td>
</tr>
</tbody>
</table></center>
				<span class="sp2">Светлана К., Ульяновск<br>
Закройщик-портной женской одежды. Начинающий стилист-имиджмейкер</span>
				</div>
			</div>
			<div class="break"></div>			
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Тренинг помог наметить план первоочередных покупок для формирования гармоничного гардероба, удалось собрать несколько комплектов из существующих вещей. Реакция окружающих на мои новые образы - некоторые даже решили, что это новые купленные вещи</span>
				До тренинга был негармоничный гардероб, вещи плохо сочетались, было сложно собрать комплекты. Тренинг помог наметить план первоочередных покупок для формирования гармоничного гардероба, удалось собрать несколько комплектов из существующих вещей.<br><br>

Пока только составила список, покупки займут намного больше времени. В подобранных комплектах сходила на работу, коллеги оценили.<br>
Какие эмоции были при прохождении тренинга? Удивление от того, что некоторые советы очень просты и логичны, удивительно, что я сама до этого не додумалась.<br><br>

Реакция окружающих на мои новые образы — три подобранных комплекта очень понравились, некоторые даже решили, что это новые купленные вещи
				<span class="sp2">Анастасия Прокопенко, Москва<br>
Менеджер в промышленной компании</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava103.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Я увидела офисный гардероб в новом свете и это очень вдохновляет. А также очень признательна за бонусы.</span>
				Добрый день, Екатерина!<br><br>

Я начинающий стилист-имиджмейкер. На данный момент переехала в Башкирию, веду семинары по имиджу.<br><br>

Очень благодарна Вам за приобретенные знания, которые могу использовать в своей работе. Я увидела офисный гардероб в новом свете и это очень вдохновляет. А так же очень признательна за бонусы.

<center><img class="aligncenter" title="" src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/kirsanova-2-e1418069467997.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></center>
				<span class="sp2">Татьяна Кирсанова, Башкирия<br>
Начинающий стилист-имиджмейкер</span>
				</div>
			</div>
			<div class="break"></div>			
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava104.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">С помощью этого тренинга я на примерах увидела, что имея немного вещей, в том числе цветных, можно выглядеть достойно, по-разному, эффектно. И это очень интересно, оказывается!!! И я больше не хочу быть незаметной. У меня начал рождаться гардероб. Окружающие делают комплименты, смотрят с интересом. Сейчас я с уверенностью могу сказать, что не зря прошла этот тренинг.</span>
				Екатерина, здравствуйте! Я живу в г. Иркутске. Работаю в гос. учреждении. Работа сидячая с документами и компьютером.<br><br>

Проблемы с гардеробом были. На тот момент слово гардероб к моей одежде вообще не подходило. Это был просто набор вещей, которые нужны, чтобы не ходить голой. Холодно же. Чёрно-серая гамма.<br><br>

Аксессуары практически отсутствовали. Мне кажется, слово «аксессуары» я тоже узнала и прочувствовала на тренингах. Одежду для себя покупала, чтобы было удобно, чтобы не выделяться и быть незаметной.<br><br>

С помощью этого тренинга я на примерах увидела, что имея немного вещей, в том числе цветных, можно выглядеть достойно, по-разному, эффектно. И это очень интересно, оказывается!!! И я больше не хочу быть незаметной. У меня проблемы с ногами, не могу носить каблуки, хотя их очень люблю. В тренинге увидела, что обувь тоже может быть разной, комфортной и интересной. СПАСИБО Вам за это.<br><br>

Начала приобретать аксессуары. Это так здорово. Одно платье, рубашка, а с разными аксессуарами ты каждый раз разная. У меня начал рождаться гардероб. И в нём даже уже есть 2 кожаных ремня, сумки, обувь, бижутерия и золото.<br><br>

Окружающие делают комплименты, смотрят с интересом. Знакомые заметили положительные перемены и даже уже спрашивают совета при выборе вещей. Я никогда не думала, что буду ходить по магазинам спокойно, не нервничая, как это было раньше.<br><br>

Сейчас поход по магазинам превратился в приятное время препровождение.<br><br>

Я понимаю, что мне это нравится, нравится близким и окружающим. И я хочу дальше с вами совершенствоваться.<br><br>

Хочется отметить, что это был не первый тренинг, который я посетила. Вначале были сомнения, нужно ли на него идти. Сейчас я с уверенностью могу сказать, что не зря прошла этот тренинг.<br><br>

ЕКАТЕРИНА, СПАСИБО ВАМ БОЛЬШОЕ.<br><br>

С уважением. Оксана.
				<span class="sp2">Оксана Кузнецова, Иркутск<br>
Работает в гос. учреждении</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava105.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Думаю, приобретя эти знания, смогу быть более заметной в коллективе и в обществе и достигну успеха в поиске другой достойной работы. Одобрительные комментарии Екатерины по домашнему заданию внушали уверенность, что все получится. Глядя на мои новые образы, мне сделали комплименты и женщины и мужчины, также замечала, что разговаривая со мной, коллеги меня рассматривают</span>
				Понимаю, что до тренинга одевалась либо скучно, либо вообще неуместно для офиса и должности. Думаю, приобретя эти знания, смогу быть более заметной в коллективе и в обществе и достигну успеха в поиске другой достойной работы.<br><br>

Всё, что пока успела внедрить: приобрела красные туфли, синюю сумку размером больше клатча, но меньше обычной сумки, с ней комбинирую свои синие украшения, которые раньше одевала только на выход. красными туфлями освежаю свой серый брючный костюм. Впереди еще много работы над образом!<br><br>

Во время тренинга было и удивление некоторым новым знаниям, было и удовлетворение, если понимала, что часть моего гардероба правильно использовалась мною, одобрительные комментарии Екатерины по домашнему заданию внушали уверенность, что все получится.<br><br>

Глядя на мои новые образы, мне сделали комплименты и женщины и мужчины, также замечала, что разговаривая со мной, коллеги меня рассматривают

<center><table>
<tbody>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-3.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-4.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-5.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-6.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-7.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-8.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-9.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-10.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2014/12/inutkina-2.jpg" alt="" width="300" height="453" data-mce-width="300" data-mce-height="453" /></a></td>
<td></td>
</tr>
</tbody>
</table></center>
				<span class="sp2">Светлана Инюткина, Рязань<br>
Финансовый директор в небольшой частной компании.</span>
				</div>
			</div>
			<div class="break"></div>			
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">На тренинге разобралась с сочетанием цветов и составлением комплектов. Ликвидировала завалы в шкафу. Было очень приятно учиться у человека, который «горит». Превыше всего ценен профессионализм и, конечно же, доброе отношение к нам, желающим разобраться в этом удивительном мире моды.</span>
				Меня зовут Милованова Екатерина. Я живу в Москве. Всю жизнь занимаюсь финансами.<br>
Сейчас работаю в режиме неполной рабочей недели. Одеваюсь по настроению.<br><br>

В начале тренинга было большое желание одеваться изысканно. На тренинге разобралась с сочетанием цветов и составлением комплектов. Ликвидировала завалы в шкафу. Оказалось, что часть одежды мне просто не подходила по цветотипу. Выбросила устаревшую одежду. Теперь знаю, в каких магазинах что присмотреть.<br><br>

Было очень приятно учиться у человека, который «горит». Превыше всего ценен профессионализм и, конечно же, доброе отношение к нам, желающим разобраться в этом удивительном мире моды.<br><br>

За время семинара начала составлять комплекты. Что-то из своего гардероба, а что-то начала докупать. О своем новом увлечении никому пока не говорю. Но уже есть новые темы для обсуждения с подругами и знакомыми. Дочь (25 лет) внимательно ко мне присматривается и даже сделала комплимент. Проявляет заинтересованность.<br><br>

На цветовых сочетаниях пока «зависаю». Надо натренировать. По-видимому — это дело времени и опыта. Очень приятный тренинг. Прекрасная организация. Очень профессионально.

<center>
<table>
<tbody>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-2.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-3.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-4.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-5.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-6.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-7.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-8.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-9.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/Milanova-10.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Екатерина Милованова, Москва<br/>
финансист</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava106.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Участвую уже во втором тренинге. Уверенно продвигаюсь в направлении четкого структурирования гардероба. После тренингов более тщательно отбираю вещи. База — качественная и лаконичная, дополнения — яркие, интересные, необычные, женственные (по крайней мере, стремлюсь к этому). Появился даже намек на талию, о которой уже давно не мечтаю. Эмоции после тренинга: желание экспериментировать, совершенствоваться (хотя возраст уже не шуточный).<br><br>Впечатления от тренинга самые положительные. Несмотря на то, что он заканчивался поздно, а после этого надо было сделать еще что-то по дому и подготовиться к следующему рабочему дню, я с нетерпением ждала 22:00 следующего дня.</span>
				Добрый день!<br><br>

Я учитель средней школы. Участвую уже во втором тренинге. Первый — «Базовый гардероб». Уверенно продвигаюсь в направлении четкого структурирования гардероба. До этого было много вещей, которые не носила.<br><br>

Проблемы с гардеробом: хочется иметь гардероб, состоящий из небольшого количества качественных вещей, которые мне идут и позволяют хорошо выглядеть в любой ситуации. Из-за большого размера и нехватки времени одеваюсь в одежду из немецких каталогов. Но и там выбор качественной одежды на большой размер невелик.<br><br>

Еще до тренинга поняла, как важны в создании образа обувь, сумки, украшения. Теперь, когда просматриваю каталоги, взгляд падает только на те вещи, с которыми можно составить несколько интересных комплектов. Понимаю скрытое очарование неброских по декору вещей с четкими линиями кроя и из натуральных тканей. С цветотипом определилась еще до тренинга.<br><br>

Недовольна тем, как на мне сидит юбка-карандаш. Всегда отбрасывала для себя идею носить ботильоны. Сомневалась в том, как обыграть тренч. Считала, что для полных женщин в возрасте больше подходит плащ-трапеция.<br><br>

После тренинга решила сделать еще одну попытку подобрать юбку-карандаш, более качественную. Очень захотелось поискать подходящие ботильоны, цветное футлярное платье. Сейчас понимаю, что ботильоны выглядят более стильно, чем высокие сапоги.<br><br>

Жакет в стиле Шанель носила всегда, считаю, что это — моё. Стараюсь выбирать украшения, имитирующие полудрагоценные камни, эмаль, перламутр, драгоценные металлы. Платкам и шарфам всегда уделяла много внимания. Нужно докупить еще несколько шелковых платков пэйсли.<br><br>

В планах все-таки начать посещать торговые центры (Катя и девочки-участницы тренинга дали много подсказок по брендам) и покупать вещи только после тщательной примерки. Думаю, что к следующей осени удастся создать более элегантный и цельный образ. Спасибо Кате за то, что чётко озвучила список «вечных» вещей, которые никогда не выйдут из моды.<br><br>

После тренингов более тщательно отбираю вещи. База — качественная и лаконичная, дополнения — яркие, интересные, необычные, женственные (по крайней мере, стремлюсь к этому). Конкретно: приобрела более качественную юбку-карандаш с корректирующей стрейчевой подкладкой; купила ботильоны со скошенным верхом, футлярное платье на сезон осень-зима (хотя по-прежнему больше люблю брючные костюмы). Стало все понятно про цветотипы и правила сочетания цветов. Со второго раза усваивается гораздо лучше.<br><br>

Появился даже намек на талию, о которой уже давно не мечтаю.<br><br>

Все, кто впервые видел меня в этом комплекте, были поражены внешними изменениями, а именно: коллеги, сын, соседка и даже продавцы в магазине, где я обычно после работы покупаю продукты, не удержались от положительных комментариев.<br><br>

То есть на тему одежды со мной заговаривали люди, с которыми я никогда эту тему не обсуждала.<br><br>

Эмоции после тренинга: желание экспериментировать, совершенствоваться (хотя возраст уже не шуточный).<br><br>

Впечатления от тренинга самые положительные. Несмотря на то, что он заканчивался поздно, а после этого надо было сделать еще что-то по дому и подготовиться к следующему рабочему дню, я с нетерпением ждала 22:00 следующего дня.<br><br>

От окружающих особой реакции пока не вижу. Семья сначала была недовольна, что вечерами сижу за компьютером и занимаюсь с их точки зрения «не делом», потом махнули на меня рукой и оставили в покое.<br><br>

Обязательно еще раз пересмотрю тренинг в записи. И до тренинга меня очень интересовали вопросы стиля, теперь критическая оценка стиля стала моим образом мыслей.<br><br>

Спасибо Кате и всем участницам за очень полезные и приятные десять вечеров!
				<span class="sp2">Галина Сергеева, Москва <br>
учитель средней школы</span>
				</div>
			</div>
			<div class="break"></div>			
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava107.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Записалась на тренинг и ни минутки не пожалела. 10 дней прошло как мгновение. Огромное спасибо Кате за интересную, полезную и «красивую» информацию. Я узнала, как правильно сочетать цвета между собой, какие тенденции актуальны, как комбинировать одежду и аксессуары, чтоб смотрелось элегантно и стильно.</span>
				Добрый день.<br/><br/>

Я из города Тернополь (Украина). Вот уже 5 лет нахожусь в декретном отпуске. Скоро выходить на роботу, а гардероба нет совсем. Несколько вещей 5-ти летней давности. Поэтому записалась на тренинг и ни минутки не пожалела. 10 дней прошло как мгновение.<br/><br/>

Сейчас буду составлять новый гардероб. Огромное спасибо Кате за интересную, полезную и «красивую» информацию. Я узнала, как правильно сочетать цвета между собой, какие тенденции актуальны, как комбинировать одежду и аксессуары, чтоб смотрелось элегантно и стильно.<br/><br/>

Еще заметила, что большинство вещей в моем гардеробе темного цвета и много низов. Буду искать подходящие мне верха и новые аксессуары.<br/><br/>

Огромное спасибо Кате и команде Гламурненько!!!

<center>
<table>
<tbody>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-2.jpg" target="_blank"><img class="aligncenter" title="" src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-2.jpg" alt="" width="300" height="470" data-mce-width="300" data-mce-height="470" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-3-e1423077749192.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-3-e1423077749192.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-4-e1423077775732.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-4-e1423077775732.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-5.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-6.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-7.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-8.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/kozak-9.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Ирина Козак, Тернополь</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava108.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Я давно не получала такого удовольствия от тренинга. В моей жизни их было много разных по разным поводам и с различной тематикой, но этот меня действительно увлек! Аж дух захватывало от того, сколько нужной и полезной информации Екатерина давала! Как замечательно она свой материал представляла, как терпеливо отвечала на порой (пардон!) дурацкие вопросы! <br><br>Тренинг не только дал много полезной информации и научил таким вещам, о которых я слышала впервые, но и помог мне переосмыслить представление о собственной внешности! Тренинг вызвал массу положительных эмоций, просто восторг!<br><br>Реакция окружающих — муж сказал, что я похорошела! Дочь, которая живет в Москве в ответ на присланные мной фото сказала, что я выгляжу супер!</span>
				Я живу в Великобритании, в Южном Уэльсе, ближайший большой город Кардифф, столица Уэльса. Но мы живем в Понтипуле, в горах в небольшом древнем городке.<br><br>

Я физиотерапевт и рефлексотерапевт, кандидат мед.наук, но здесь в Южном Уэльсе я Холистический терапевт (акупенктура, различные виды лечебных массажей, ароматерапия, Рейки, лечение физическими факторами — ультразвук, лазер, магниты и т.д.,имею частную практику. Но в силу обстоятельств у меня сейчас мало пациентов, и я думаю о другой профессии. У меня их всего 3 — врач, переводчик (у меня кроме английского еще венгерский, и итальянский, сейчас изучаю Уэльский) и дизайнер.<br><br>

Я занималась в основном дизайном помещений и помогала мужу в его работе. Но меня всегда привлекала профессия имиджмейкера. Я даже хотела поступать в Текстильный институт, но семейная профессия пересилила — я врач в третьем поколении.<br><br>

У меня не было особых проблем с офисным гардеробом, как я считала. У меня нет жесткого дресс-кода, а когда я делаю процедуры своим пациентам, я надеваю белую тунику. Но в результате этого замечательного тренинга оказалось, что мой гардероб либо вообще не офисный, либо не такой «вкусный», либо в нем мало комплектов. Я пыталась сама найти какие-то более вкусные сочетания и по цветам, и по фактуре и т.д., но на Тренинге все стало понятно!<br><br>

Я давно не получала такого удовольствия от тренинга. В моей жизни их было много разных по разным поводам и с различной тематикой, но этот меня действительно увлек! Аж дух захватывало от того, сколько нужной и полезной информации Екатерина давала! Как замечательно она свой материал представляла, как терпеливо отвечала на порой (пардон!) дурацкие вопросы! Тренинг не только дал много полезной информации и научил таким вещам, о которых я слышала впервые, но и помог мне переосмыслить представление о собственной внешности!<br><br>

Тренинг вызвал массу положительных эмоций, просто восторг!<br><br>

Реакция окружающих — муж сказал, что я похорошела! Дочь, которая живет в Москве в ответ на присланные мной фото сказала, что я выгляжу супер! То же самое сказала приехавшая недавно подруга. Люди в нашем городке и в Кардиффе обращают внимание, несмотря на то, что в этой стране вообще никто не обращает внимание ни на что.<br><br>

Также оказалось, что у меня напрочь отсутствуют в гардеробе светлые однотонные жакеты, а то что есть из всего количества, все с принтом или в мелкую клетку. То же самое можно сказать о светлых брюках. Буду докупать.<br><br>

Спасибо огромное Катеньке и всей команде за чудесный тренинг! Благодарю от всей души!

<center>
<table>
<tbody>
<tr>
<td><a href="/blog/wp-content/uploads/2015/02/povel-2-e1423078492147.jpg" target="_blank"><img src="/blog/wp-content/uploads/2015/02/povel-2-e1423078492147.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
<td><a href="/blog/wp-content/uploads/2015/02/povel-3.jpg" target="_blank"><img src="/blog/wp-content/uploads/2015/02/povel-3.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Ирина Пауэлл, Великобритания<br>
физиотерапевт и рефлексотерапевт, кандидат мед.наук</span>
				</div>
			</div>
			<div class="break"></div>			
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava109.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Тренинг помог понять, что такое повседневный костюм, и чем он отличается от выходного или делового. Помог увидеть, что кроме черного цвета есть другие цвета, которые можно и нужно носить. Вдохновил на покупки ).<br/><br/>Эмоции от тренинга самые положительные, как будто узнаешь то, что всегда было Must have to know, но никто не говорил))))).</span>
				Проблем с офисным гардеробом было много, поскольку в основном, я работала на переговорах, выставках и часто путала очень нарядные или официальные вещи с повседневными. Доходило даже до того, что могла прийти на пробы в кино в вечернем платье и кофточке с леопардовым принтом))))<br/><br/>

Тренинг помог понять, что такое повседневный костюм, и чем он отличается от выходного или делового. Помог увидеть, что кроме черного цвета есть другие цвета, которые можно и нужно носить. Вдохновил на покупки ).<br/><br/>

Я составляла много комплектов в polyvore и собрала несколько из своих вещей. Теперь понятно, как нужно ходить на работу).<br/><br/>

Эмоции от тренинга самые положительные, как будто узнаешь то, что всегда было Must have to know, но никто не говорил))))).<br/><br/>

Реакция окружающих пока достаточно скромная (так как пока не покупала новые вещи).<br/><br/>

После тренинга я хорошо поняла лаконичный стиль. Разобралась с жакетами, обувью, брюками.<br/><br/>

Пока еще недостаточно могу составлять интересные цветовые сочетания . Получается только один цвет и ахром пока. 2 цвета — это пока предел. И очень тяжело понять какие вещи по фасонам с какими могут сочетаться. Например, какие блузки лучше подбирать к каким жакетам, пальто или кардиганом.

<center>
<table>
<tbody>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/aleksandrova-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/aleksandrova-2.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/aleksandrova-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/aleksandrova-3.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/aleksandrova-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/aleksandrova-4.jpg" alt="" width="300" height="300" data-mce-width="300" data-mce-height="300" /></a></td>
<td></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Марина Александрова, Москва<br>
журналист, гид- переводчик.</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava110.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Очень порадовало, что девушки имиджмейкеры выкладывали свои домашние работы. Спасибо им и всем! Чувствуешь, разницу, так красиво и понимаешь куда идти и к чему стремиться. <br><br>У меня не получается результат мгновенно. Но благодаря не первому тренингу я постепенно меняю одежду, которую ношу. И те вещи, которые я покупала в последнее время стали более универсальными, красивыми и рациональными в моём шкафу.<br><br>Я могу сказать, что я уже одеваю те комплекты которые создала. Реакция есть и от совсем незнакомых людей на улице и от коллег по работе, чувствуешь себя увереннее.</span>
				У меня сложился стереотип, что офисный гардероб — это только чёрный жакет и белая рубашка. Мне казалось, что дресс-код — это очень скучно и однообразно. А в особенности осенне-зимний офисный стиль. В гардеробе очень много вещей, но это в основном трикотаж. У меня была ещё одна проблема отсутствие лаконичности в одежде, одежда была со множеством деталей и отделки.<br><br>

На тренинге я постаралась собрать вещи, как из своего гардероба, так и сделать коллажи с помощью программы polyvore. Много работала и контролировала себя в момент подбора одежды в программе.<br><br>

Постаралась применить как можно больше рецептов из тренинга, благодаря чему я взглянула по другому на свой гардероб. Мне жалко было всегда расставаться с вещами из своего гардероба, а теперь понимаю, что я из них просто «выросла» и с ними можно легко расстаться без сожаления. Выявила вещи, которых мне не хватает и в ближайшем будущем обязательно их куплю. Это блейзер, цветные туфли, светлая юбка, цветная юбка, ботильоны.<br><br>

Много информации очень хотелось всё успеть и освоить. Жаль, что мало времени на домашнее задание, но с другой стороны рамки и границы помогают сосредоточиться. Очень хочется прям сразу всё заново пересмотреть и освоить, но что-то говорит, что нужно недельку оклематься.<br><br>

Обязательно переслушаю все записи и пересмотрю домашние работы. Очень порадовало, что девушки имиджмейкеры выкладывали свои домашние работы. Спасибо им и всем! Чувствуешь, разницу, так красиво и понимаешь куда идти и к чему стремиться.<br><br>

У меня не получается результат мгновенно. Но благодаря не первому тренингу я постепенно меняю одежду, которую ношу. И те вещи, которые я покупала в последнее время стали более универсальными, красивыми и рациональными в моём шкафу. Я могу сказать, что я уже одеваю те комплекты которые создала. Реакция есть и от совсем незнакомых людей на улице и от коллег по работе, чувствуешь себя увереннее.<br><br>

Есть не большое чувство неудовлетворённости — мне не хватило просто сил. Хотелось больше проработать над созданием и сетов и комплектов из своих вещей, но я довольно результатом. Лаконичность в моих комплектах появилась. Хотя понимаю что моя цель пока ещё не достигнута и это только первая ступенька.<br><br>

Хочется внимательно ещё пересмотреть, проанализировать, послушать, тренинг. Пойти в магазин и с новыми знаниями взглянуть на одежду.
Спасибо огромное за тренинг Всем!!! И поддержке тоже! Спасибо что помогаете в трудную минуту!)

<center>
<table>
<tbody>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-2.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-3.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-4.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-5.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-6.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-7.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-8.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-9.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-10.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-11.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-11.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-12.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/rubio-12.jpg" alt="" width="300" height="400" data-mce-width="300" data-mce-height="400" /></a></td>
<td></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Ирина Рубио, Москва<br>
Работает в школе художником по костюму</span>
				</div>
			</div>
			<div class="break"></div>			
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">За время декрета разучилась немного одеваться в подходящем для офиса стиле. Теперь я очень четко понимаю, как я должна выглядеть на работе. Эмоции самые положительные. Тренинг очень интересный, информативный, практичный, отлично организованный (в том числе и техподдержка порадовала)</span>
				За время декрета разучилась немного одеваться в подходящем для офиса стиле. Теперь я очень четко понимаю, как я должна выглядеть на работе. Выбрала подходящие для меня рецепты, написала список покупок — какие вещи мне следует докупить.<br><br>

Эмоции самые положительные. Тренинг очень интересный, информативный, практичный, отлично организованный (в том числе и техподдержка порадовала)<br>
Планирую поплотнее поработать над цветовыми сочетаниями.

<center>
<table>
<tbody>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-1.jpg" alt="" width="300" height="503" data-mce-width="300" data-mce-height="503" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-2.jpg" alt="" width="300" height="505" data-mce-width="300" data-mce-height="505" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-3.jpg" alt="" width="300" height="437" data-mce-width="300" data-mce-height="437" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-4.jpg" alt="" width="300" height="408" data-mce-width="300" data-mce-height="408" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-5.jpg" alt="" width="300" height="550" data-mce-width="300" data-mce-height="550" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-6.jpg" alt="" width="300" height="431" data-mce-width="300" data-mce-height="431" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-7.jpg" alt="" width="300" height="259" data-mce-width="300" data-mce-height="259" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/natalia-k-8.jpg" alt="" width="300" height="484" data-mce-width="300" data-mce-height="484" /></a></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Наталья К., Украина</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">слышать комплименты, чувствовать одобрение близких, которые рады произошедшим во мне переменам! <br><br>Тренинг помог мне избавиться от стереотипа «на работу — черное/белое», найти для себя яркие цветовые решения. Я узнала много нового о цветовой гармонии, своем цветотипе, о том, как правильно подбирать комплекты.<br><br>Я очень благодарна Екатерине за вдохновение и четкие инструкции, которые помогли мне измениться!</span>
				Здравствуйте!<br><br>

Я из Тюмени, работаю врачом.<br><br>

Моя проблема была в том, что я всегда стремилась выбрать максимально удобную одежду под медицинский халат. Незадолго до тренинга я пересмотрела все свои старые фото и очень огорчилась, увидев свой серый и безликий образ. Конечно, я задумалась о том, как это можно изменить.<br><br>

И тут же столкнулась еще с двумя проблемами: 1) ничего стоящего в моем гардеробе нет, 2) в магазинах я выбираю одежду, подобную той, что носила раньше.<br><br>

Тренинг помог мне избавиться от стереотипа «на работу — черное/белое», найти для себя яркие цветовые решения. Я узнала много нового о цветовой гармонии, своем цветотипе, о том, как правильно подбирать комплекты.<br><br>

Я составила список базовых вещей, которые мне необходимо приобрести, и уже начала воплощать свои идеи в жизнь; с удовольствием смотрю на свое отражение и получаю комплименты от коллег!))). Мне очень нравится экспериментировать с образами, выбирать для себя то, что раньше по каким-то причинам считала неудобным, неуместным и т.п.<br><br>

Тренинг я прослушивала в записи, и мне он очень понравился! Я и раньше участвовала в семинарах Екатерины, однако именно этот тренинг дал мне вдохновение и четкое руководство к действию! Мне очень приятно слышать комплименты, чувствовать одобрение близких, которые рады произошедшим во мне переменам!<br><br>

У меня очень много планов по составлению гардероба, пока что я не совсем четко представляю свой образ и стиль в деталях, однако я на пути к его созданию! Возможно, к каким-то материалам нужно будет вернуться, столько полезной и важной информации сложно усвоить с первого раза)))<br><br>

Я очень благодарна Екатерине за вдохновение и четкие инструкции, которые помогли мне измениться!
				<span class="sp2">Наталья Кузьмина, Тюмень<br>
врач</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava111.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Я увидела в своем гардеробе новые возможности-) Составила интересные комплекты, мой гардероб получил вторую жизнь-). Во время прохождения тренинга хотелось поскорей прослушать записи, начать делать. Во время прослушивания уроков испытывала счастье, что купила этот тренинг, столько полезной информации и легкое изложении Кати было на высшем уровне! <br><br>Я заметила заинтересованные взгляды мужчин, чувствую уверенность в себе. Женщины тоже обращают внимание, виден их интерес к моим образам, сочетаниям. И это я еще ничего себе не покупала-))</span>
				Работаю в финансовой компании, параллельно планирую заниматься имиджмейкерством.<br><br>

До тренинга было однообразие вариантов в гардеробе, они были скучными, безликими, как у всех. Решить проблему решила обучением на стилиста в этом году.<br><br>

Благодаря вашей пошаговой инструкции я наконец-то освоила Polyvore, у меня была программа в смартфоне, но как их сохранять и дальше пересылать, а еще и получать за это лайки, смогла только после вашей инструкции. Составление комплектов в Polyvore отличная тренировка умения по отработке правил сочетания цветов, составлению комплектов для разных типов фигур.<br><br>

Я увидела в своем гардеробе новые возможности-) Составила интересные комплекты, мой гардероб получил вторую жизнь-) А то я думала, что придется радикально закупаться, но после тренинга «101 рецепт….» обнаружила, что не так все плохо, можно новые образы составить с моими вещами. Но новые вещи я уже знаю какие буду покупать.<br><br>

Составила новые образы со своими имеющимися вещами. Составила по домашним заданиям луки, выкладывать не успевала, т.к. слушать в записи смогла только после 5 дня начала тренинга и накопилось там уже 50 пунктов-). Но для себя я их делала и продолжаю доделывать, вижу, как навык укладывается в голове. И чувствую во время составления луков себя настоящим имиджмейкером.<br><br>

Во время прохождения тренинга хотелось поскорей прослушать записи, начать делать. Во время прослушивания уроков испытывала счастье, что купила этот тренинг, столько полезной информации и легкое изложении Кати было на высшем уровне! И когда выполняла Д/З эмоции радости от предвкушения какой образ я сама придумаю, подберу по заданию, несмотря на малое кол-во времени, не хотелось отрываться от выполнения этих упражнений-)!<br><br>

Я заметила заинтересованные взгляды мужчин, чувствую уверенность в себе. Женщины тоже обращают внимание, виден их интерес к моим образам, сочетаниям. И это я еще ничего себе не покупала-)) Я думаю после покупок, внимания будет больше.

<center>
<table>
<tbody>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-2-e1423080879289.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-2-e1423080879289.jpg" alt="" width="300" height="534" data-mce-width="300" data-mce-height="534" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-3-e1423080896944.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-3-e1423080896944.jpg" alt="" width="300" height="534" data-mce-width="300" data-mce-height="534" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-4-e1423080917475.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-4-e1423080917475.jpg" alt="" width="300" height="534" data-mce-width="300" data-mce-height="534" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-5-e1423080950669.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-5-e1423080950669.jpg" alt="" width="300" height="534" data-mce-width="300" data-mce-height="534" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-6-e1423080967397.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-6-e1423080967397.jpg" alt="" width="300" height="534" data-mce-width="300" data-mce-height="534" /></a></td>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-7-e1423080985194.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-7-e1423080985194.jpg" alt="" width="300" height="534" data-mce-width="300" data-mce-height="534" /></a></td>
</tr>
<tr>
<td><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-8-e1423081004949.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/02/adilbekova-8-e1423081004949.jpg" alt="" width="300" height="534" data-mce-width="300" data-mce-height="534" /></a></td>
<td></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Асель Адильбекова, Алма-Ата<br>
Работает в финансовой компании, параллельно планирует заниматься имиджмейкерством</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava112.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Во время тренинга узнала много интересных рецептов, как сочетать между собой вещи, теперь смогу разбавить гардероб цветом!<br><br>Была положительная оценка новых образов со стороны близких и клиентов. <br><br>Очень понравился тренинг, ждала следующего дня с нетерпением, все доступно, понятно, всё по полочкам! Реакция окружающих - оборачиваются в след не только мужчины, но и женщины</span>
				Здравствуйте!<br><br>

Я визажист-стилист, мастер по наращиванию ресниц, год назад получила диплом имидж-консультант. Совершенствую знания у имиджмейкера Екатерины Маляровой!
Был скучноватый офисный гардероб, хотелось интересных цветовых сочетаний.<br><br>

Во время тренинга узнала много интересных рецептов, как сочетать между собой вещи, -теперь смогу разбавить гардероб цветом! Пересмотрела свой гардероб и составила новые комплекты для встреч с клиентами. Составила список покупок, пополню гардероб интересными вещами!<br><br>

Была положительная оценка новых образов со стороны близких и клиентов.<br><br>

Очень понравился тренинг, ждала следующего дня с нетерпением, все доступно, понятно, всё по полочкам!<br><br>

Реакция окружающих — оборачиваются в след не только мужчины, но и женщины-среди клиентов появились желающие разобрать свой гардероб!!
<center>
<table>
<tbody>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-2.jpg" alt="" width="600" height="800" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-3.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-4.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-5.jpg" alt="" width="600" height="399" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-6.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-7.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-8.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-9.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya10.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-11.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Yulia-Rovenskaya-11.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
</tbody>
</table>

</center>
				<span class="sp2">Юлия Ровенская, Таллин<br><br>
визажист-стилист, мастер по наращиванию ресниц, год назад получила диплом имидж-консультант.</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava113.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Простота, лёгкость, понятность изложения материалов не оставляет шанса на то, чтобы не понять, не проникнуться. <br><br>С самого первого занятия испытывала исключительно положительные эмоции. Вас очень приятно слушать: всё обстоятельно, подробно.<br><br>Вы щедро делитесь с нами своими знаниями, фишками. Комплименты получаю периодически. <br><br>Важнее, что я сама к себе стала относиться менее критически как это делала раньше. Это для меня большое достижение.</span>
				Работаю главным бухгалтером. Вопросом формирования правильного офисного гардероба начала заниматься много лет назад, но всё было немного бессистемно. Нравится вещь, но не знаю, подойдёт или нет.<br><br>

Больше всего вопросов у меня возникало с подбором аксессуаров к офисным нарядам. На мой взгляд, без украшений: платка, ожерелья, браслетов, колец даже правильно подобранная одежда выглядит скучновато.<br><br>

Наконец информация из Ваших ежедневных занятий легла на правильную почву и пришло просветление)) Как-будто что-то щелкнуло в голове: «Да-к вот ведь как надо!».
Простота, лёгкость, понятность изложения материалов не оставляет шанса на то, чтобы не понять, не проникнуться.<br><br>

Катя, Вы абсолютно правы, когда пытаясь достучаться до нас, говорите, что инвестиции в себя — это всерьез и надолго, это в будущем экономия собственных же денег, времени и нервов при подборе гардероба.<br><br>

Благодаря разбору домашних заданий утвердилась в мысли, что некоторые из самостоятельно составленных образов очень хороши для работы. НО! есть куда расти и дополнять гардероб.<br><br>

Много лет получаю рассылки новостей с Вашего сайта, читаю статьи. Но поучаствовать в семинаре решилась впервые. С самого первого занятия испытывала исключительно положительные эмоции. Вас очень приятно слушать: всё обстоятельно, подробно. Вы щедро делитесь с нами своими знаниями, фишками.<br><br>

Сложно сказать, что я резко изменилась после семинара. Комплименты получаю периодически. Важнее, что я сама к себе стала относиться менее критически как это делала раньше. Это для меня большое достижение.<br><br>

В связи с наличием временной разницы не получалось слушать Ваши занятия в прямом эфире, поэтому опаздывала с выполнением домашних заданий. Продолжаю работать над материалом, слушаю, записываю, составляю коллажи.<br><br>
Спасибо Вам, Катя, большое! Профессионал в наше время — это огромная редкость.<br>
Успехов Вам и Вашей команде!

<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-2.jpg" alt="" width="300" height="400" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-3.jpg" alt="" width="300" height="400" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-4.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-5.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-6.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-7.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-8.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-9.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Bondarenko-10.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Ольга Бондаренко, Пермь <br>
Работает главным бухгалтером</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Я увидела массу вариантов изменения и построения комплектов, которые достаточно просто воплотить! Гардероб уже поменяла на 80% благодаря курсам. Я покупаю вещи, на которые раньше не обращала внимания и просто проходила мимо, пробую новое. <br><br>Я себе нравлюсь, чувствую себя более уверенной и красивой. При прохождении тренинга испытывала только положительные эмоции и желание действовать! Реакция окружающих на новые образы - делают комплименты!</span>
				Здравствуйте!<br><br>
Я из Красноярска. На данный момент я домохозяйка.<br>
Когда я ходила на работу, то одевалась однотипно, серо и скучно, как и большинство сотрудниц. Я не решала эту проблему, не думала над ней. Сейчас хоть я и не хожу на работу, но хочу выглядеть стильно, интересно, привлекательно.<br><br>

Я увидела массу вариантов изменения и построения комплектов, которые достаточно просто воплотить!<br>
Я пишу список вещей, которые планирую приобрести. Иду в магазин со списком. Обращаю внимание на цвет, фасон, стиль одежды при примерке. Анализирую с чем еще можно сочетать выбранную вещь.<br><br>

Гардероб уже поменяла на 80% благодаря курсам. Я покупаю вещи, на которые раньше не обращала внимания и просто проходила мимо, пробую новое. Я себе нравлюсь, чувствую себя более уверенной и красивой.<br><br>

При прохождении тренинга испытывала только положительные эмоции и желание действовать!<br>
Реакция окружающих на новые образы — делают комплименты!<br>
Я пока очень мало сделала. Денег на все проработки не хватает. Выпишу себе то, что хотела бы применить и буду постепенно покупать вещи, аксессуары.
				<span class="sp2">Мария Морозова, Красноярск<br>
домохозяйка</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava114.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">После тренинга есть новые идеи, прорабатываю сейчас поиск тканей для них, подбор моделей для пошива. Самое новое достижение — новое платье, стройнит невероятно. <br><br>Эмоции самые замечательные, тема интересная и нужная — и качество подачи материала на высоте. <br><br>Катя, ваше умение подать нужную информацию вызывает искреннее восхищение! <br><br>Окружающие реагируют комплиментами, причем искренними.</span>
				Работаю руководителем отдела в крупном госучреждении.<br><br>
Основная проблема с офисным гардеробом была — это несистематизированное знание, стандартные клише по идеям, кое-какие из них, правда, уже благополучно ушли, благодаря Вашим, Катя, предыдущим тренингам и занятиям имидж-клуба.<br><br>

Но хотелось еще более глубокого, детального погружения в тематику офисного имиджа (учитывая, что на работе проводится фактически большая часть времени).<br><br>

После тренинга есть новые идеи, прорабатываю сейчас поиск тканей для них, подбор моделей для пошива.<br><br>

Самое новое достижение — новое платье, дошитое как раз под просмотр материалов (к сожалению, пока нет фото, но на работе получила массу комплиментов), стройнит невероятно, плюс сочетание классической английской клетки и крупного пейсли с использованием вертикальных линий — получилось, прямо-таки, микс из нескольких рецептов этого тренинга.<br><br>

Эмоции самые замечательные, тема интересная и нужная — и качество подачи материала на высоте. Катя, ваше умение подать нужную информацию вызывает искреннее восхищение!<br><br>

Окружающие реагируют комплиментами, причем искренними.<br><br>
В ближайших планах дополнение как раз офисного гардероба на предстоящий сезон, в необходимостях — белая рубашка, юбка-карандаш, и выбор качественного трикотажа, очень хочу порадовать себя кашемиром.

<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Koval-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Koval-4.jpg" alt="" width="300" height="401" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Koval-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Koval-5.jpg" alt="" width="300" height="401" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Наталья Коваль, Иркутск <br>
руководитель отдела в крупном госучреждении</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Составила несколько новых комплектов из вещей которые до тренинга не подумала бы комбинировать. <br><br>Занятия тренинга были очень интересными и полезными. Окружающим понравилось. Но больше всего довольна я сама. Деньги на тренинг были потрачены с пользой :)</span>
				Я из Минска. Консультант по красоте, визажист (подбираю клиентам средства для ухода за кожей и макияжа). Проблем с гардеробом не было.<br><br>

Я из тех, кто подходя к шкафу знает, что наденет Но ведь всегда можно сделать лучше. Поэтому и записалась на тренинг, надеялась получить новые идеи для составления образов. Так и получилось.<br><br>

Составила несколько новых комплектов из вещей которые до тренинга не подумала бы комбинировать. Улучшила, те, которые уже были.<br><br>

И все образы получаются более гармоничными и стильными. Занятия тренинга были очень интересными и полезными. Окружающим понравилось.<br><br>

Но больше всего довольна я сама Деньги на тренинг были потрачены с пользой :).<br><br>

Хорошо проработать тренинг не удалось, т.к. нет времени делать домашнее задание и фотографировать результаты.<br><br>

Планирую еще несколько раз прослушать все дни тренинга — продумаю, какие еще варианты комплекты можно составить, уточню список того, что понадобится докупить.

<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Irina-Nagornaya-2-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Irina-Nagornaya-2-1.jpg" alt="" width="300" height="567" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Irina-Nagornaya-3-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Irina-Nagornaya-3-1.jpg" alt="" width="300" height="562" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Irina-Nagornaya-4-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Irina-Nagornaya-4-1.jpg" alt="" width="300" height="564" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Irina-Nagornaya-6-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Irina-Nagornaya-6-1.jpg" alt="" width="300" height="567" /></a></center></td>
</tr>
</tbody>
</table>
				<span class="sp2">Ирина Н., Минск<br>
Консультант по красоте</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Когда увидела предложение пройти тренинг по идеям офисного гардероба, ни секунды не сомневалась — в офисе проходит, практически, вся жизнь. <br><br>Катя, ваши занятия — это волшебный «эликсир» или пинок, простите за сравнение, во всяком случае для меня, после которого летишь-летишь, воплощаешь идеи, бегаешь по магазинам (и время находишь, как это ни странно, вот что значит адреналин, впрыснутый Мастером) <br><br>Приятно, когда новые молодые сотрудницы, узнав, сколько мне лет, округляют глаза — все это благодаря тем знаниям и рекомендациям, которые я получаю, проходя тренинги у Екатерины Маляровой.</span>
				Здравствуйте, Катя и участницы тренинга!<br><br>

Я из Краснодара, работаю гл.бухгалтером, работы очень много, поэтому часто просто нет ни времен, ни сил ходить по магазинам — ведь процесс это не механический, нужно вдохновение, сильное желание, да и картинку бы нового образа «сочинить» перед походом…<br><br>

Поэтому, когда увидела предложение пройти тренинг по идеям офисного гардероба, ни секунды не сомневалась — в офисе проходит, практически, вся жизнь.<br><br>

Катя, ваши занятия — это волшебный «эликсир» или пинок, простите за сравнение, во всяком случае для меня, после которого летишь-летишь, воплощаешь идеи, бегаешь по магазинам (и время находишь, как это ни странно, вот что значит адреналин, впрыснутый Мастером), мучительно соображаешь подойдет эта сумочка или туфельки к тому, что есть, или надо бежать дальше.<br><br>

Одним словом, спасибо огромное за тренинг, за массу идей, за разбуженное вдохновение!<br><br>

Что касается реакции окружающих, еще два года назад, после Школы имиджмейкеров, мне делали комплименты, что помолодела на десять лет (а для моего возраста это серьезно) и с цветом появились комплекты. И сейчас, конечно, приятно, когда новые молодые сотрудницы, узнав, сколько мне лет, округляют глаза — все это благодаря тем знаниям и рекомендациям, которые я получаю, проходя тренинги у Екатерины Маляровой.<br><br>

Конечно, прорабатывать еще много чего надо, пройтись по всем рецептам, ведь нет предела совершенству, еще нужно продумать и приобрести обувь на грядущий сезон, верхнюю одежду — это задачи ближайшего будущего.<br><br>
Попробовала пока сфотографировать себя в том, что есть. Составляла комплекты, учитывая полученные знания

<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina-1.jpg" alt="" width="300" height="342" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina-2.jpg" alt="" width="300" height="312" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina3-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina3-3.jpg" alt="" width="440" height="304" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina3-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina3-4.jpg" alt="" width="440" height="346" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina4-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina4-1.jpg" alt="" width="440" height="284" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina5-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina5-1.jpg" alt="" width="440" height="211" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina6.jpg" alt="" width="440" height="304" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina7.jpg" alt="" width="440" height="259" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina9.jpg" alt="" width="440" height="242" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina11.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina11.jpg" alt="" width="440" height="242" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina13.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina13.jpg" alt="" width="440" height="213" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina15.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Elena-Kazarina15.jpg" alt="" width="440" height="257" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Елена К., Краснодар<br>
Главный бухгалтер</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Я УВИДЕЛА вещи, все, о чем Вы говорили, Катя, я УВИДЕЛА. Раньше для меня это была просто масса одежды. <br><br>Результат после тренинга: покупка оксофодов себе и ветровки маме, которая ее освежила цветом и придала женственности своим оригинальным воротом. <br><br>Для женщины 78 лет (моей мамы) не так просто найти красивую одежду. у нас получилось. Не жду реакций, самое главное моя возвращающаяся уверенность и прилив вдохновения!</span>
				Здравствуйте, Екатерина и все-все-все!<br><br>
Живу и работаю в Волгограде. Коммерческий директор.<br><br>

У нас нет дресс-кода, но проблем с гардеробом и без этого достаточно, да и положение обязывает: вещей много, но вместе они плохо сочетаются.<br><br>

Аксессуары не использовала совсем. Сумка одна на все случаи жизни. меняла раз в год. поход в магазин «для себя» был всегда мукой, не умела я покупать вещи для себя, поэтому делала это редко по необходимости.<br><br>

Удачными были покупки только, если я заранее представляла свой новый образ, но для этого мне нужно было часами сидеть в интернете и изучать модные тенденции.
Тренинг проходила в записи. Еще не все проштудировала. но на днях была в торговом ценре, прошла по бутикам, купила только оксфорды (на фото), но вот, что заметила — я УВИДЕЛА вещи, Все, о чем Вы говорили, Катя, я УВИДЕЛА.<br><br>

Раньше для меня это была просто масса одежды. Два месяца до тренинга я познакомилась с сайтом Гламурненько, читала все рассылки. И, однажды купила чудесную трикотажную юбку-карандаш с принтом»огурец» и кофточку к ней. Потратила 1200 руб и полчаса времени. Летом это был мой самый любимый комплект.<br><br>

Спасибо Вам, Катя!
Результат после тренинга: покупка оксофодов себе и ветровки маме, которая ее освежила цветом и придала женственности своим оригинальным воротом. для женщины 78 лет не так просто найти красивую одежду. у нас получилось.<br><br>

Для меня одеть себя- это наука, эмоции старательного ученика в ожидании прорыва. одно открытие я сделала — образ не может быть законченным без подходящей сумки.<br><br>
Не жду реакций, самое главное моя возвращающаяся уверенность и прилив вдохновения!<br>
в планах подобрать к моему многочисленному низу верха и, конечно, сумки!!!!!

				<span class="sp2">Наталья В., Волгоград<br>
Коммерческий директор</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Тренинг дал четкие правила, рецепты, которые легко применить в жизни. Использовала массу рецептов из тренинга. Реакция окружающих на новые образы - восхищение, комплименты.</span>
				Были проблемы с офисным гардеробом — однообразие, скучно, наблюдала как одеваются другие, пыталась составлять комплекты самостоятельно.<br><br>

Тренинг дал четкие правила, рецепты, которые легко применить в жизни.<br><br>

Использовала массу рецептов из тренинга (пастельные цвета в офис, элегантное пальто, элегантная сумка, пудровые туфли, сочетания цветов, джинсы+жакет в пт и др).<br><br>

Эмоции при прохождении тренинга только положительные. Большое спасибо за четкие правила и примеры.<br><br>

Реакция окружающих на новые образы — восхищение, комплименты.<br><br>

Слушаю тренинг в записи, еще не все правила освоила, планирую детально все изучить и использовать.

<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N2-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N2-1.jpg" alt="" width="300" height="684" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N6-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N6-1.jpg" alt="" width="300" height="685" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N4-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N4-1.jpg" alt="" width="300" height="621" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N5-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N5-1.jpg" alt="" width="300" height="621" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N8-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N8-1.jpg" alt="" width="300" height="725" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N9-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N9-1.jpg" alt="" width="300" height="727" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N3-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N3-1.jpg" alt="" width="300" height="588" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N7-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N7-1.jpg" alt="" width="300" height="664" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N10-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nadezhda-N10-1.jpg" alt="" width="300" height="834" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Надежда Н., Новосибирск<br>
инженер, промышленность</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Я прошла все Катины тренинги, поэтому многое уже знакомо. Но были очень полезные прикладные вещи. Эмоции были при прохождении тренинга отличные. Реакция окружающих на новые образы была хорошая.</span>
				Проблема гардероба — хотелось побольше разнообразия в комплектах.<br><br>

Я прошла все Катины тренинги, поэтому многое уже знакомо. Но были очень полезные прикладные вещи, типа цвета джинс<br><br>

Раньше не носила браслеты, теперь купила и планирую носить.<br>
Эмоции были при прохождении тренинга отличные.<br>
Реакция окружающих на новые образы была хорошая.<br>
Планирую проработать цветовые сочетания, необходима практическая работа

				<span class="sp2">Екатерина В., Москва<br>
юрист</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Научилась составлять более минималистичные комплекты, деталей меньше, но интереснее цвет. Осознала, что в моем гардеробе правильно, а что надо дополнить.<br><br>Дало мне много идей для клиентов, как можно сделать простой и одновременно элегантный комплект.<br><br>Эмоции от тренинга самые положительные, с нетерпением ждала каждую лекцию. Окружающие говорят - интересная, элегантная и энергичная! <br><br>А недавно сказали — в чем тебя не увижу, всегда классно выглядишь! Так приятно…</span>
				Проблема: Составлять больше вариаций с простыми вещами в офис. Как избежать перегрузки образа деталями.<br><br>

Решение: Взять тренинг 101 интересный офисный комплект.<br><br>

Научилась составлять более минималистичные комплекты, деталей меньше, но интереснее цвет. Использование часов как аксессуара. Нашла интересные, но при этом простые по составлению комплектов.<br><br>

Составляла комплекты из своих вещей и новые сочетания в Поливоре. Часть поставила на ФБ, сразу же получила комментарии — классный комплект. Выкладывала свои ДЗ и внимательно слушала замечание на ДЗ остальных участниц. После ДЗ дописывала пометки, что-то, что не сразу поняла на лекции. По новому взглянула на белый цвет.<br><br>

Осознала, что в моем гардеробе правильно, а что надо дополнить.<br>
Дало мне много идей для клиентов, как можно сделать простой и одновременно элегантный комплект.<br>
Отдельное спасибо за мелкие замечания по поводу костюмов для мужчин… Я как раз встречалась с одним потенциальным клиентом, он был в шоке от моих замечаний…<br><br>

Эмоции от тренинга самые положительные, с нетерпением ждала каждую лекцию. Поняла что слушать живую лекцию интереснее чем запись… Интеракция, возможность спросить.<br>
Огромное спасибо, Екатерина, ваш опыт вдохновляет на получение собственного опыта!<br>
Особенно понравился совет про витрино-терапию. Пойду тоже мерять, без покупать. Составить лук на себе…<br><br>

Окружающие говорят — интересная, элегантная и энергичная!<br>
А недавно сказали — в чем тебя не увижу, всегда классно выглядишь ! Так приятно…<br>
Часть образов не проработала. Планы- обработать и составить по всем пунктам.

<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man2.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man3.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man4.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man5.jpg" alt="" width="300" height="265" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man6.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man7.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man8.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Natalia-Man9.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Наталья М., Хайфа<br>
имиджмейкер</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Подчерпнула очень много интересных идей и рецептов как разнообразить свой гардероб. Эмоции были положительные. Каждый вечер с нетерпением ждала новых идей и рецептов для стильного гардероба.</span>
				Работаю продавцом в универмаге, теперь стилист в женском отделе.<br>
У меня лично никаких проблем с офисным гардеробом не было ( т. к работаю не в офисе).<br><br>

Прохождение тренинга прежде всего нужно для моих будущих клиентов. А для себе подчерпнула очень много интересных идей и рецептов как разнообразить свой гардероб.<br><br>

Я составила список покупок для своего гардероба.<br><br>

Эмоции были положительные. Каждый вечер с нетерпением ждала новых идей и рецептов для стильного гардероба.<br><br>

Освоила Polyvore и в будущем буду уже составлять коллажи со своей одеждой. Больше хотелось бы проработать правила, связанные с цветами.<br><br>

Катя, огромное спасибо за тренинг!!!
<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish2-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish2-2.jpg" alt="" width="300" height="332" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish20-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish20-1.jpg" alt="" width="267" height="332" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish21-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish21-1.jpg" alt="" width="300" height="382" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish22-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish22-1.jpg" alt="" width="288" height="382" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish23-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish23-1.jpg" alt="" width="300" height="385" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish24-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish24-1.jpg" alt="" width="300" height="381" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish3.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish4.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish6.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish5.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish7.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish8.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish10.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish11.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish11.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish12.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish12.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish13.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish13.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish14.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish14.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish15.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish15.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish16.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish16.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish17.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish17.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish18.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish18.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish19.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Kristina-Gailish19.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Kristina G., Таллинн, Эстония<br>
продавец в универмаге, теперь работает стилистом в женском отделе.</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Тренинг помог структурировать разрозненные знания о цветовых сочетаниях, помог понять какой гардероб я хочу, и что мне нужно докупить. <br><br>Эмоции были при прохождении тренинга только положительные. Очень все понравилось. <br><br>Комплименты получала и раньше, но сейчас нравлюсь себе больше.</span>
				Как такового дресс-кода на работе нет, поэтому 85% офисного гардероба составляли образы с джинсами. Хотелось разнообразить гардероб и поменять стиль в сторону элегантности.<br><br>

Тренинг помог структурировать разрозненные знания о цветовых сочетаниях, помог понять какой гардероб я хочу, и что мне нужно докупить. Проанализировала имеющиеся вещи и составила шоп-лист на осень/весну.<br><br>

Эмоции были при прохождении тренинга только положительные. Очень все понравилось, хотелось бы еще прослушать курс по созданию повседневного гардероба.<br><br>

Комплименты получала и раньше, но сейчас нравлюсь себе больше.<br><br>

Нужно еще поработать над цветовым сочетанием и грамотным подбором аксессуаров<br><br>

Спасибо большое Екатерине и тех. поддержке! Все прошло замечательно!:)

<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna2-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna2-1.jpg" alt="" width="279" height="542" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna3-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna3-1.jpg" alt="" width="300" height="542" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna4-1.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna4-1.jpg" alt="" width="300" height="815" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna5.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna6.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna7.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna9.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna10.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna11.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna11.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna12.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna12.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna13.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Nikolaevna-Anna13.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Анна, Москва<br>
начальник отдела продаж</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Появилось огромное поле для деятельности. поняла, что не все уж у меня так плохо, появилась смелость в сочетании вещей. От тренинга один сплошной позитив. Я себя начала чувствовать уверенно.</span>
				В гардеробе была стандартность комплектов, большое количество серых и темных вещей. Хотелось внести яркость, позитив. Появилось огромное поле для деятельности. поняла, что не все уж у меня так плохо, появилась смелость в сочетании вещей.<br><br>

Вспомнила о жакетах, которые висели в шкафу, добавила к ним яркие топы, немного разобралась с правилами сочетания цветов. От тренинга один сплошной позитив.<br><br>

У близких, окружающих реакции особой нет, т.к. и до тренинга комплекты у меня получались достаточно элегантные, пусть и не яркие. Однако я себя начала чувствовать уверенно.
<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko-2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko-2.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko-3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko-3.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko-4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko-4.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko-5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Shyravko-5.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Ольга Ш., Томск<br>
бухгалтер</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">После тренинга появилось огромное количество идей достаточно легко осуществимых!!! Эмоции во время прохождения тренинга только положительные!!!! Появилась уверенность в собственных силах.</span>
				Работаю инженером в строительной компании<br>
Проблема заключалась в однообразной одежде, отсутствии цвета.<br><br>

После тренинга появилось огромное количество идей достаточно легко осуществимых!!! Составляю список покупок, надеюсь с ним шопинг принесет удовольствие.<br><br>

Эмоции во время прохождения тренинга только положительные!!!! Появилась уверенность в собственных силах. Пока не могу составить полные комплекты, нужно докупать вещи. Нужно еще проработать аксессуары, особенно украшения.

<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova2.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova3.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova4.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova5.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova6.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova7.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova8.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova9.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova10.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova11.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova11.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova12.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova12.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova13.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova13.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova14.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova14.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova15.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova15.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova16.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova16.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova17.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova17.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova18.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova18.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova19.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova19.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova20.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Balashova20.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Ольга Б., Санкт-Петербург<br>
инженер в строительной компании</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Узнала новые приемы, изменила мнение на счет ожерелий и шарфиков/платков. Впервые за несколько лет купила свитер, легкий вязаный в небольшую сетку – прямо согласно тенденциям), отлично смотрится с брюками и рубашкой или джинсами.</span>
				Не скажу, что были какие-либо существенные проблемы. Но мне всегда интересно узнавать что-то новое и применять это на практике. Узнала новые приемы, изменила мнение на счет ожерелий и шарфиков/платков.<br><br>

Впервые за несколько лет купила свитер, легкий вязаный в небольшую сетку – прямо согласно тенденциям), отлично смотрится с брюками и рубашкой или джинсами. Сейчас в процессе подбора шарфиков, чтобы освежить образы в пальто, плаще.<br><br>

Эмоции во время прохождения тренинга положительные. Пожелание – делать 1 день перерыв через каждые 4-5 дней (хочется иногда вечером с друзьями встретиться – а ни как).<br><br>

Я стараюсь хорошо выглядеть всегда и часто слышу комплименты в свой адрес.<br>
Не все отложилось в голове, нужно еще раз прослушать в записи. Недостаточно освоилась с ожерельями — т.к. не оч. люблю украшения на шее носить, но образы стилистов с ожерельями нравятся, моя шея позволяет мне их носить, хочу попробовать. И шарфы/платки – опять же была не любитель, но сейчас осваиваю
				<span class="sp2">Александра С., Санкт-Петербург<br>
Автобизнес, руководитель нескольких направлений</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Перестала «бояться» ходить по магазинам и купить что-нибудь не то))) сейчас мой выбор подкреплён знаниями, что добавило уверенности и радости от шоппинга и покупок))) стало наконец-таки интересно ходить по магазинам)) с радостью ощутила, что наконец-то для меня открылись двери выражения своей личности и внутреннего мира через одежду. <br><br>Стала ощущать себя более стильной и красивой, что придало уверенности в себе и ощущения счастья!!!</span>
				Добрый день, Катя, Ваша команда и участницы тренинга. Живу в Беларуси, в областном городе Могилёве. Работаю в ресторанном бизнесе на руководящей должности.<br><br>

Столкнулась с проблемой, что внутренне я уже выстроила свой стиль и образ, знаю, как хочу выглядеть, что сообщать своим видом клиентам, партнёрам и подчинённым. Но не хватало смелости, знаний и уверенности, что могу правильно подбирать одежду и «складывать» её в комплекты, тем более что статус обязывает выглядеть на все 100!!!<br><br>

Считаю своими успехами за время прохождения тренинга:<br>
— перестала «бояться» ходить по магазинам и купить что-нибудь не то)))<br>
— раньше подбирала одежду интуитивно, сейчас мой выбор подкреплён знаниями, что добавило уверенности и радости от шоппинга и покупок))) стало наконец-таки интересно ходить по магазинам))<br>
— с интересом наблюдаю, как одеты люди на улице, разбираю их образы на комплектующие, ищу удачные сочетания, стала больше видеть действительно стильно одетых людей.<br>
— с радостью ощутила, что наконец-то для меня открылись двери выражения своей личности и внутреннего мира через одежду.<br>
— стала гораздо смелее сочетать цвета (именно этого навыка мне не хватало)<br>
— стала ощущать себя более стильной и красивой, что придало уверенности в себе и ощущения счастья!!!<br><br>

Постоянно получаю комплименты от окружающих.<br>
Планов громадьё: углублять свои знания по этой теме, учиться использовать интересные цветовые сочетания, создавать разные стилевые направления, помогать другим в выборе одежды и т.д.<br><br>

Катенька, спасибо Вам за очень ценный труд, который помогает женщинам чувствовать себя счастливыми!!! Больших Вам успехов и ждём от Вас новых знаний.
				<span class="sp2">Ольга А., Могилев <br>
Руководитель в ресторанном бизнесе</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Я получила конкретные практические идеи, а также мощный стимул для самостоятельной работы над собственным имиджем. Несмотря на то, что у меня занятия начинались в 12 ночи, я всегда старалась послушать их онлайн, чтобы иметь возможность задать вопросы и зарядиться энергией в прямом эфире. Каждый раз после занятия я ощущала прилив творческих сил и вдохновение!!!</span>
				Проблемы с гардеробом вообще были связаны, прежде всего, с неумением сочетать вещи, а ещё красиво комбинировать цвета. Хотя я никогда не ходила во всём чёрном или чёрно-белом, мне всегда было сложно составить комплекты: у меня к каждому низу был только один, максимум, два варианта верха.<br><br>

Поэтому я просто покупала платья и ходила почти только в них. Или с тёмными джинсами носила цветные блузки, но у меня никогда не получалось прийти к единству ансамбля, к гармонии в сочетаниях.<br><br>

Я получила конкретные практические идеи, а также мощный стимул для самостоятельной работы над собственным имиджем. На данном этапе я просмотрела сайты рекомендованных Катей марок и внимательно изучила предложения разных брендов. Я досконально осмотрела всё: начиная от фасонов и цвета, заканчивая ткани и уходом за ней.<br><br>

Так, мне удалось решить ещё одну проблему: я ненавижу ходить по магазинам, особенно по торговым центрам, т.к. там я чувствую упадок сил и полностью теряю энергию. Теперь я поняла, что мне нужно просто изучить весь ассортимент в интернет-магазине, предположительно в уме составить комплект и идти уже за конкретными вещами, которые, как мне кажется, должны мне подойти по цвету и фасону.<br><br>

Несмотря на то, что у меня занятия начинались в 12 ночи, я всегда старалась послушать их онлайн, чтобы иметь возможность задать вопросы и зарядиться энергией в прямом эфире. Каждый раз после занятия я ощущала прилив творческих сил и вдохновение!!! И ещё безумно понравилось делать коллажи в Polyvore!!! Это мне напомнило игру из перестроечного детства «Юный модельер», только намного больше возможностей! )))<br><br>

Пока у меня не было возможности пройтись по магазинам.<br><br>
Думаю, что мне нужно работать над преодолением «цветового барьера». Я имею в виду — научиться сочетать цвета не только с ахромами и внутри монохромной гармонии, но и вкусно сочетать их друг с другом.
<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko2.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko3.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko4.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko5.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko6.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko7.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko9.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko10.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko11.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko11.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko12.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko12.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko15.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko15.jpg" alt="" width="600" height="600" /></a></center></td>
</tr>
</tbody>
</table>
</center>

				<span class="sp2">Ольга Т., Екатеринбург<br>
Преподаватель языков</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Тренинг помог по-другому взглянуть на выбор вещей в магазина. Поняла насколько важны аксессуары. Тренинг очень понравился. Было интересно узнавать новое</span>
				Нет вообще офисного гардероба. До тренинга смотрела модные журналы, как одеты другие женщины. Тренинг помог по-другому взглянуть на выбор вещей в магазина. Поняла насколько важны аксессуары.<br><br>

Пока ничего конкретно не сделала. Хотя нет купила брюки. Лет 10 не носила и не покупала. Хочу сделать комплект с ними. Тренинг очень понравился. Было интересно узнавать новое. Дочь была удивлена и рада моей покупке (Брюки). Хочу прослушать записи. Спокойно подобрать себе комплекты и найти их в магазине
				<span class="sp2">Валентина А., Москва<br>
Индивидуальный предприниматель</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Мне нужен был образ для того, чтобы пойти на свадьбу. Воспользовалась вариантами цветовых сочетаний, о которых говорилось на тренинге. Эмоции во время тренинга были положительные, много нового и интересного.</span>
				C офисным гардеробом проблем не было, поскольку не работаю в офисе.<br>
Мне нужен был образ для того, чтобы пойти на свадьбу. Воспользовалась вариантами цветовых сочетаний, о которых говорилось на тренинге.<br><br>

Собрала образ для свадебного торжества.<br>
Эмоции во время тренинга были положительные, много нового и интересного.<br>
Буду пересматривать все занятия, чтобы лучше усвоился материал.

				<span class="sp2">Ольга Р., Испания<br>
Hand Made</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Впервые слушаю такой курс, очень много интересных идей нашла для себя и своих будущих клиентов. Эмоции: ждала каждый вечер, с большим интересом слушала и записывала, удовлетворена данным материалом и самое главное чувствовалось, как Екатерина не жадничает материала, отвечает терпеливо на вопросы.<br><br>Ощущение, что с тобой говорят на равных, это дает уверенность в собственных силах. Если это кто-то смог сделать, то и я это смогу.</span>
				Добрый день Екатерина и все участницы!<br><br>
Впервые слушаю такой курс, очень много интересных идей нашла для себя и своих будущих клиентов. Я конструктор и технолог женской одежды, много лет изготавливала одежду по индивидуальным заказам, теперь решила освоить новую профессию.<br><br>

Для меня очень много интересных идей в компановке в ансамбль, разбор цветотипов и подбор цветовой гаммы.
Главная проблема-соединить все, что знаю и читала в единый ансамбль. Подобрать правильную сумку и обувь, сейчас это понятнее. Спасибо Кате и ее Команде! Сделать собственный образ интереснее и изысканнее. Пыталась соединить в комплект в основном одежду, не всегда использовала аксессуары, боялась перегруза образа.<br><br>

Оказывается, они могут быть лаконичными и уместными. Но самая главная проблема, это цветовые сочетания, комбинации цветов, это самые нужные знания! Катя так просто поделилась этим «секретом», что очень понятно и доступно. Только практикуйся.
Вижу пути развития себя в этой отрасли. Востребованность этой интересной, не заурядной профессии.<br><br>

Конкретно: важная информация по цветовым сочетаниям. Эти знания дают свободу, это одно из ценных приобретений на этом курсе.
Тенденции сезона, анализ данной информации был очень полезен.<br><br>

Составляла комплекты из своего гардероба, увидела пробелы, запланировала покупку сумки на сезон осень-зима, нескольких платьев, сапожки, брюки с принтом (а то только в клетку), и очень понравился Total look в светлой гамме.<br><br>

Эмоции: ждала каждый вечер, с большим интересом слушала и записывала, удовлетворена данным материалом и самое главное чувствовалось, как Екатерина не жадничает материала, отвечает терпеливо на вопросы. Ощущение, что с тобой говорят на равных, это дает уверенность в собственных силах. Если это кто-то смог сделать, то и я это смогу.<br><br>

Реакция окружающих положительная. Разглядывают внимательнее.
Цветотипы-это пока самый большой вопрос для меня. И работа в poly vore необходимо осваивать.<br><br>

Планирую покупку платков для определения цветотипа.
И уже начинать работать с клиентами.<br><br>

Желаю Вам новых идей для вдохновения!
				<span class="sp2">Жанна Б., Санкт-Петербург<br>
визажист и консультант по красоте</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">С нетерпением ждала каждого занятия, но, к сожалению, из-за занятости, не всегда успевала посещать занятия. После тренинга начала понимать, что значит «элегантно одеться». Катя рассказывала очень полезную информацию, в том числе, отвечая на вопросы (где купить, сколько примерно стоит и т.д.).<br><br>Большое удовольствие получила от тренинга. Ощущение, как будто тебе открываются простые, но не многим известные истины.</span>
				Были проблемы с выбором элегантного фасона одежды и подбора удачного сочетания цветов, покупала брючный костюм, блузки.<br><br>

После тренинга начала понимать, что значит «элегантно одеться», узнала о разнообразии моделей одежды, которые можно преобразить и дотянуть до офисного гардероба, а также узнала, как грамотно можно комбинировать цветовые сочетания.<br><br>

Пока не было возможности применить для своего гардероба, т.к. нахожусь в отпуске, но я отметила для себя те решения для гардероба, которые можно применить в будущем, а также составила примерный список вещей, которые нужно будет докупить. Катя рассказывала очень полезную информацию, в том числе, отвечая на вопросы (где купить, сколько примерно стоит и т.д.).<br><br>

С нетерпением ждала каждого занятия, но, к сожалению, из-за занятости, не всегда успевала посещать занятия. Парочку из них придется смотреть в записи. Большое удовольствие получила от тренинга. Ощущение, как будто тебе открываются простые, но не многим известные истины.<br><br>

Пока не применяла на практике.<br>
Нужно разобрать гардероб, поработать над сочетаниями цветов, подумать, какие вещи понадобятся в первую очередь и будут универсальными.
<center>
<table>
<tbody>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko2.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko2.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko3.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko4.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko5.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko6.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko7.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko9.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko9.jpg" alt="" width="300" height="300" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Olga-Taranenko10.jpg" alt="" width="300" height="300" /></a></center></td>
</tr>
<tr>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova3.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova3.jpg" alt="" width="300" height="431" /></a></center></td>
<td><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova5.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova5.jpg" alt="" width="300" height="400" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova.jpg" alt="" width="600" height="467" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova4.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova4.jpg" alt="" width="600" height="523" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova6.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova6.jpg" alt="" width="600" height="576" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova7.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova7.jpg" alt="" width="600" height="592" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova8.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova8.jpg" alt="" width="600" height="669" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova10.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova10.jpg" alt="" width="300" height="459" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova12.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova12.jpg" alt="" width="600" height="465" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova13.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova13.jpg" alt="" width="600" height="492" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova14.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova14.jpg" alt="" width="600" height="552" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova15.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova15.jpg" alt="" width="600" height="505" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova16.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova16.jpg" alt="" width="600" height="503" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova17.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova17.jpg" alt="" width="600" height="478" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova18.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova18.jpg" alt="" width="600" height="641" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova19.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova19.jpg" alt="" width="600" height="503" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova20.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova20.jpg" alt="" width="600" height="476" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova21.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova21.jpg" alt="" width="600" height="465" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova22.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova22.jpg" alt="" width="600" height="542" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova23.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova23.jpg" alt="" width="600" height="542" /></a></center></td>
</tr>
<tr>
<td colspan="2"><center><a href="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova24.jpg" target="_blank"><img src="http://www.glamurnenko.ru/blog/wp-content/uploads/2015/06/Ekaterina-Smirnova24.jpg" alt="" width="600" height="540" /></a></center></td>
</tr>
</tbody>
</table>
</center>
				<span class="sp2">Екатерина С., Екатеринбург<br>
менеджер проектов</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Из тренинга узнала разные правила сочетания цветов — это очень интересная и полезная информация. Эмоции во время тренинга только положительные.</span>
				Работаю в социальной сфере.<br><br>

Особого дресс-кода у нас на работе нет. Мои комплекты всегда состояли из того, что интуитивно подходит: верх и низ, светлая и чёрная обувь, т.же сумки.<br><br>

В нашем городе всегда присутствовали в основном чёрные, серые цвета — много сажи (завозили то, что ходовое). А меня всегда интересовал цвет в одежде, так как в чёрном и белом цвете одежду не ношу — не идёт. Сейчас выбор больше.<br><br>

Всегда тянуло к красоте и гармоничности в одежде. Сочетать всегда стремилась одинаковые цвета в одежде. Из тренинга узнала разные правила сочетания цветов — это очень интересная и полезная информация.<br><br>

Проходила тренинг в своём темпе, т.к. присутствовать онлайн из-за разницы во времени не было возможности. Пересмотрела свой гардероб, но откорректировать пока не удаётся (на мою з/п не разгонишься). Занимаюсь на курсах кройки и шитья, буду стараться использовать материал из тренинга в создании своей одежды.<br><br>

Насчёт аксессуаров надо подумать. Я их никогда не носила и не ношу. Коллеги по работе тоже. Материальная экономия не позволяла — не рационально.<br><br>

Эмоции во время тренинга только положительные. Я конечно сомневаюсь, что в нашем городе есть в магазинах одежда таких брендов, которые перечислялись в тренинге, во всяком случае я не встречала (люблю ходить в некоторые магазины, чтобы посмотреть на красивую одежду). Постараюсь познакомиться с ними онлайн.<br><br>

Стало очень грустно, что купить 10 вещей я пока что ещё не могу себе позволить не только в сезон, но даже 1 раз в 2-3 года. Вещи всегда приобретались по мере необходимости и носились не менее 3-7 лет))).<br><br>

Есть над чем работать — многослойность в комплектах, использование аксессуаров, использование в комплектах обуви и сумок в цвете…
				<span class="sp2">Светлана, Новокузнецк<br>
соцработник</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Тренинг задал основные направления движения в сторону расширения гардероба и его универсализации в моей жизни. В основном одностороннее общение с Екатериной всегда вызывает у меня положительные эмоции, желание творить, изменять себя и свою жизнь к лучшему. Близкие очень живо реагируют на любые изменения в моей внешности в последнее время.</span>
				Проблемы с гардеробом связаны с затянувшимся декретным отпуском, отсутствием новых, модных, стильных и взаимодополняющих вещей. Тренинг задал основные направления движения в сторону расширения гардероба и его универсализации в моей жизни.<br><br>

Наконец купила пудровые лодочки, чему обрадовался больше мой муж, отметивший длину моих ног.<br><br>
В основном одностороннее общение с Екатериной всегда вызывает у меня положительные эмоции, желание творить, изменять себя и свою жизнь к лучшему.<br><br>

Близкие очень живо реагируют на любые изменения в моей внешности в последнее время.<br><br>
Нужно расширить гардероб за счет ультрамодных, наиболее актуальных в данный момент вещей.
				<span class="sp2">Надежда К., Москва<br>
Бухгалтер на дому</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Тренинг помог структурировать разрозненные знания о цветовых сочетаниях, помог понять какой гардероб я хочу, и что мне нужно докупить.</span>
				У меня был скучный, одноцветный гардероб. Однотипные вещи. Похожа на одного из миллионов рядовых сотрудников. Нет интересных украшений, цветов, чтобы разбавить гардероб. Нет знаний, чтобы улучшить ситуацию.<br><br>

После тренинга появились практические знания, стала лучше понимать, что мне идет. К сожалению, сделать что-то на практике пока очень сложно — нет времени. К сожалению, сделать что-то на практике пока очень сложно — нет времени. Но в программе собирала очень неплохие комплекты, на мой взгляд.<br><br>

Я стала верить себе и в себя. И поняла, что могу быть очень красивой, выглядеть лучше и устроить свою карьеру, ведь внешний вид мне очень в этом поможет
				<span class="sp2">Мария В., Кострома</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Огромная благодарность за систематизацию. Разложили всё полочкам. Очень много информации получено, но тяжести в голове нет. Есть уверенность.</span>
				До тренинга проблем с гардеробом не было, но знаний в таком объеме, а главное систематизированных тоже. Над решением проблем еще работать и работать.
Революционная ситуация-дисгармония между внешним и внутренним. Сменила прическу. Пока я -накопитель информации.<br><br>

Огромная благодарность за систематизацию. Разложили всё полочкам. Очень много информации получено, но тяжести в голове нет. Есть уверенность. Огромная просьба сделать аналогичное для мужчин.
				<span class="sp2">Наталья П., Бийск<br>
специалист в области электронных компонентов.</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Наступил новый период в жизни и хочется перемен теперь. Эмоции были при прохождении тренинга – «Ух ты, как классно!» Идеи луков, цвета, фасоны – все такое красивое стильное! Наслаждаюсь качественной работой и Кати (подача, обратная связь, примеры), и тех. поддержки.</span>
				Офисного гардероба как такового и нет – работа не связана с пребыванием в офисе, но иногда хожу на встречи, редко езжу в командировки и там нужно выглядеть «офисно». Купила тренинг для идей луков – как выглядеть современно, интересно, т.к. нынешний гардероб скучноват – обилие темных и скучных цветов, трикотаж + брюки/джинсы, мало аксессуаров отсутствие жакетов, вкусных цветов.<br><br>

Наступил новый период в жизни и хочется перемен теперь.<br><br>

Прослушала всего два дня в записи пока + бонус про модные тенденции, но уже вдохновилась идеями и понятно, что нужно докупить, чтобы выглядеть интересно.<br><br>

Освоила Polyvore + начала делать ДЗ (для себя – потренироваться в подборе вещей и аксессуаров), увидела, какие есть подходящие моему цветотипу ЗИМА интересные цветовые сочетания (сам цветотип определила ранее с помощью местного стилиста).<br><br>

Посмотрела свой гардероб: что можно использовать для представленных на тренинге идей, составила список ближайших покупок + ходила по магазинам, обращала внимание на цветные вещи, актуальные тенденции.<br><br>

Эмоции были при прохождении тренинга – «Ух ты, как классно!» Идеи луков, цвета, фасоны – все такое красивое стильное! Наслаждаюсь качественной работой и Кати (подача, обратная связь, примеры), и тех. поддержки. Параллельно смотрю маркетинговые «фишки», используемые в работе Кати.<br><br>

При хождении по магазинам – все не так радостно: доступные вещи выглядят не таким классными по фасонам/цветам/качеству, но думаю, если приложить побольше усилий, и среди них можно найти подходящее.<br><br>

Новых образов пока нет, но думаю, со стороны окружающих удивление будет как минимум. А то и восхищение. А у меня – горящие глаза. Так было, когда я летний гардероб прорабатывала.<br><br>

Планирую во-первых, дослушать все дни. Во-вторых, сделать в Polyvore те рекомендации, которые выбрала для себя, чтобы запомнились. В третьих, — в дальнейшем реализовать рекомендации покупками в магазинах.
				<span class="sp2">Татьяна П., Омск<br>
специалист по рекламе + организация участия в выставках предприятия.</span>
				</div>
			</div>
			<div class="break"></div>
			<div class="txt r">
				<div class="shad"></div>
				<div class="arr"></div>
				<div class="ava"><img src="<?= APP::Module('Routing')->root ?>public/modules/pages/products/101office/images/ava_none.png" alt=""/></div>
				<div class="inn">
				<span class="sp1">Прошла тренинг у Кати в 2013 году. Начала работать над личностным ростом и .. перемены не заставили себя ждать! Начала применять более яркие краски в одежде, менять стиль самих нарядов.<br><br>Мне очень нравится, как Катя ведет свои тренинги — обстоятельно. со множеством примеров. Видно, что она не просто «отчитывается», а старается помочь каждой из своих слушательниц. В ходе нынешнего тренинга я рискнула ввести в свой гардероб одежду с принтами. Всем понравилось! А главное — я сама себе нравлюсь в этой одежде!</span>
				Я живу в Казани, столице Республики Татарстан, работаю на государственной службе.<br>
Не могу сказать,что я совсем ничего не знала о правилах гардероба.. Сколько себя помню — всегда старалась идти в ногу со сременем. НО после смерти мужа на некоторое время выпала из социума, перестала следить за модными тенденциями.<br><br>

Мои проблемы были из разряда излишней классичности и сдержанности образа,хотя иногда надоедал дресс-код и я одевала что-нибудь эдакое.. А год назад решила пересмотреть свои подходы к формированию собственного имиджа. Прошла тренинг у Кати в 2013 году. Начала работать над личностным ростом..И .. перемены не заставили себя ждать.! начала применять более яркие краски в одежде..менять стиль самих нарядов.<br><br>

Мне очень нравится, как Катя ведет свои тренинги — обстоятельно. со множеством примеров. Видно, что она не просто «отчитывается», а старается помочь каждой из своих слушательниц.<br><br>

В ходе нынешнего тренинга я рискнула ввести в свой гардероб одежду с принтами..Всем понравилось! А главное — я сама себе нравлюсь в этой одежде! Жаль, по магазинам особо ходить некогда, хотя мне это очень нравится, как и любой из нас, наверное!<br><br>

К сожалению, в силу загруженности на службе, не успела освоить программу по составлению капсул (одежды), но я обязательно в ней разберусь!<br>
А Катиными рекомендациями я обязательно воспользуюсь в ближайшее время!
				<span class="sp2">Марина Ф., Казань<br>
госслужащая</span>
				</div>
			</div>
			<div class="txt l">
				<div class="shad"></div>
				<div class="inn">
<?php
/*$link = mysql_connect("localhost", "admin_glamdb", "jZOmgN8Ia8") or trigger_error(mysql_error());
mysql_select_db('admin_glam-blog', $link) or trigger_error(mysql_error());
mysql_query("set character_set_results=utf8;",    $link);
mysql_query("set character_set_connection=utf8;", $link);
mysql_query("set character_set_client=utf8;",     $link);
mysql_query("set character_set_database=utf8;",   $link);
	$query = "SELECT SQL_NO_CACHE count(*) as C FROM `aa_posts`
left join `aa_term_relationships` on `id`=`object_id`
left join `aa_terms` on `term_taxonomy_id`=`term_id`
where `post_type`='reviews' ";
$result = mysql_query($query) or trigger_error(mysql_error());
$row = mysql_fetch_array($result);
$C = $row['C'];
mysql_close($link);*/
?>
				<span class="sp1" style=" padding: 0px; text-align: center; ">			<a href="http://www.glamurnenko.ru/blog/reviews/" target="_blank">прочитать все отзывы ( <?=$C; ?> шт. )</a></span>
				<span class="sp2"></span>
				</div>
			</div>
			<div class="break"></div>
		</div>
	</div>	
	
	<div class="footer">
			По всем вопросам вы можете писать в службу поддержки:<br><a href="http://www.glamurnenko.ru/blog/contacts/" target="_blank">http://www.glamurnenko.ru/blog/contacts/</a> tel.: +7(499)350-23-35 <br>© <?= date('Y') ?>, ИП Косенко Андрей Владимирович, ОГРН 308614728400011
	</div>	
</div>
</body>
</html>
  
