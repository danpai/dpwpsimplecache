<?php

function dpscache_manage_option_page(){
	global $dpcache;
	$cached_elements;
	$cache_action;
	$inner_html = "";
	if(!empty($_POST)){
		$cache_action = $_POST['cache_action'];
		if($cache_action === "show"){
			$cached_elements = $dpcache->get_all_values();
			$inner_html = "<table width='100%' class='widefat'><thead><tr><th width='20%' align='center'>KEY</th><th width='80%' align='center'>VALUE</th></tr></thead>";
			foreach($cached_elements as $key => $value){
				$inner_html .= "<tr><td width='20%'>" . $key . "</td><td width='80%'>" . unserialize(base64_decode($value)) . "</td></tr>";
			}
			$inner_html .= "</table>";
		}
		if($cache_action === "delete"){
			$dpcache->flush();
		}
	}
?>
	<div class="wrap"><h1>Manage Cache Content</h1>
		<h3>Item cached:&nbsp;<i><?php echo $dpcache->get_statistics(); ?></i></h3>
		<form method="POST" action="">
			<input type="hidden" name="cache_action" value="show"><br>
			<input type="submit" value="Show Content" style="margin:10px 0 0 40px; width:100px">
		</form>
		<form method="POST" action="">
			<input type="hidden" name="cache_action" value="delete"><br>
			<input type="submit" value="Delete All Content" style="margin:10px 0 0 40px; width:100px">
		</form>
		<br>
		<?php echo $inner_html; ?>
	</div>
	
<?php
}

?>