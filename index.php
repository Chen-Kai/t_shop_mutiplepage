<?php
//ob_start();
//session_start();

include("./sql/dbconnection.php");
include("./tpl/class.TemplatePower.inc.php");

$tpl = new TemplatePower("./html/index.html");//建立一個 TemplatePower 物件

$tpl->prepare();//開始解析樣板;此函數會對您的樣板進行解析!形成樣板物件 ,才可以使用!

$tpl->assign("web_title","最新商品分頁");
$tpl->assign("shop_idex","最新商品分頁");

//預設每頁幾筆
$pageRow_records = 10;

//預設頁數
$num_pages = 1;

//頁數
if(isset($_GET['page'])){
	$num_pages = $_GET['page'];
}

//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;

$select_ecs_goods_sql = "SELECT * FROM  `ecs_goods` WHERE  `parent_id` =287 ORDER BY cat_id ASC";

 //加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$select_ecs_goods_sql_limit = $select_ecs_goods_sql." LIMIT ".$startRow_records.", ".$pageRow_records;
$select_ecs_goods_rows = mysql_query($select_ecs_goods_sql_limit);

$all_select_ecs_good_sql = mysql_query($select_ecs_goods_sql);

//計算總筆數
$total_records = mysql_num_rows($all_select_ecs_good_sql);

//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);

	while($select_ecs_goods = mysql_fetch_assoc($select_ecs_goods_rows)){
		//顯示商品資料
		$tpl->newBlock("markettop");
		$tpl->assign("shop_title",$select_ecs_goods["goods_name"]);
		
		$market_code_price = $select_ecs_goods["market_price"];
		$shop_code_price = $select_ecs_goods["shop_price"];
		if($market_code_price <=0 && $shop_code_price <=0 ){
			$tpl->assign("price_info","<span><p id=\"img_co_price_center\">新增價格</p></span>");
		}else{
			$tpl->assign("price_info","<span class=\"img_co_price_left\"><P id=\"pic1text\">市價:".$select_ecs_goods["market_price"]."</P></span><span class=\"img_co_price_right\"><P id=\"pic1text\">優惠價:".$select_ecs_goods["shop_price"]."</P></span>");
		}	
		$tpl->assign("sho_img_url","http://192.168.1.235/".$select_ecs_goods["goods_thumb"]);
	}
	
	//顯示頁碼
	$tpl->newBlock("pagenumber");
	
	//範圍
	$range = 3;
	
	//collect code 
	$page_number_output;
	if ($num_pages > 1){  // 若不是第一頁則顯示 
		$num_min_pages = $num_pages-1;
		$page_number_output .="<li><a href=\"".$_SERVER['PHP_SELF']."?page=1\">第一頁</a></li>";
		$page_number_output .="<li><a href=\"".$_SERVER['PHP_SELF']."?page=".$num_min_pages."\">上一頁</a></li>";
		
	}	

	for ($x = (($num_pages - $range) - 1); $x < (($num_pages + $range) + 1); $x++) {
		if (($x > 0) && ($x <= $total_pages)) {
			if ($x == $num_pages) {
				$page_number_output .="<li><a href=\"".$_SERVER['PHP_SELF']."?page=".$x."\" class=\"active\">".$x."</a></li>";
			}else {
				// 顯示連結
				$page_number_output .="<li><a href=\"".$_SERVER['PHP_SELF']."?page=".$x."\">".$x."</a></li>";
			} 
		}
	} 
	
	
	
	if ($num_pages < $total_pages){  // 若不是最後一頁則顯示 
		$num_plus_pages = $num_pages+1;
		$page_number_output .="<li><a href=\"".$_SERVER['PHP_SELF']."?page=".$num_plus_pages."\">下一頁</a></li>";
		$page_number_output .="<li><a href=\"".$_SERVER['PHP_SELF']."?page=".$total_pages."\">最後一頁</a></li>";
	}
	
	$tpl->assign("page_info",$page_number_output);

 
$tpl->printToScreen();//輸出解析完的樣板結果
?>