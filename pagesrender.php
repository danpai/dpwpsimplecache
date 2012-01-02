<?php

function dpscache_manage_option_page(){
	global $dpcache;
	global $USE_DB_SESSION_MANAGER;
	$cached_elements;
	$cache_action;
	$inner_html = "";
	if(isset($_GET['sessid'])){
		$dpcache->invalidate_single_session($_GET['sessid']);
		$_POST['cache_action'] = "showall";
	}
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
		if($cache_action === "showall"){
			$sessions = $dpcache->get_all_sessions();
			$inner_html = "<table width='100%' class='widefat'><thead><tr><th width='45%' align='center'>EXPIRATION</th><th width='45%' align='center'>REMOTE ADDRESS</th><th width='10%' align='center'></th></tr></thead>";
			foreach($sessions as $session){
				$inner_html .= "<tr><td width='45%'>" . $session['expire'] . "</td><td width='45%'>" . $session['ip'] . "</td><td width='45%'><a href='?page=simple_cache&sessid=" . $session['id'] . "'>DELETE</a></td></tr>";
			}
			$inner_html .= "</table>";
		}
		if($cache_action === "delete"){
			$dpcache->flush(false);
		}
		if($cache_action === "deleteall"){
			$dpcache->flush(true);
		}
	}
?>
	<div class="wrap"><h1>Manage Cache Content</h1>
		<h3>Sessions active:&nbsp;<i><?php echo $dpcache->get_sessions_number(); ?></i></h3>
		<form method="POST" action="">
			<input type="hidden" name="cache_action" value="show"><br>
			<input type="submit" value="Show Content" style="margin:10px 0 0 30px; width:200px">
		</form>
		<form method="POST" action="">
			<input type="hidden" name="cache_action" value="delete"><br>
			<input type="submit" value="Delete My Session Content" style="margin:10px 0 0 30px; width:200px">
		</form>
		<?php if($USE_DB_SESSION_MANAGER){ ?>
		<form method="POST" action="">
			<input type="hidden" name="cache_action" value="showall"><br>
			<input type="submit" value="Show All Sessions" style="margin:10px 0 0 30px; width:200px">
		</form>
		<form method="POST" action="">
			<input type="hidden" name="cache_action" value="deleteall"><br>
			<input type="submit" value="Delete All Sessions Content" style="margin:10px 0 0 30px; width:200px">
		</form>
		<?php } ?>
		<br>
		<?php echo $inner_html; ?>
	</div>
	
<?php
}

?>