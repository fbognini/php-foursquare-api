<?
	
	$fields = array("query" => "pizzeria","city" => "bergamo","limit" => 10);
	foreach($fields as $key=>$val) {
		// $message = $key." ".$_POST[$key];
		// echo "<script type='text/javascript'>alert('$message');</script>";
		$values[$key] = (isset($_POST[$key]) ? $_POST[$key] : $val);
	}
	
	$apiLink='https://api.foursquare.com/v2/venues/search?v=20161016&query='.$values["query"].'&limit='.$values["limit"].'&intent=checkin&client_id='.getenv('foursquare_id').'&client_secret='.getenv('foursquare_secret').'&near='.$values["city"].'';
	$ch = curl_init() or die(curl_error());
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL,$apiLink);
	
	$json=curl_exec($ch) or die(curl_error($ch));
	$data = json_decode($json);	
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Foursquare</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
    <link href='css/style.css' rel='stylesheet' type='text/css'>
  </head>
  <body>
	<div class="content">
		<div class="card header">
			<h2>Foursquare - API Places</h2>
		</div>

		<div class="card side-bar">
			<form method='POST' name='form' id='form'>
			<input type='hidden' id='status' name="status"/>
			<?
				echo '<div class="form-field"><input type="text" name="query" value="'.$values["query"].'" placeholder="Sto cercando..." required/></div>';
				echo '<div class="form-field"><input type="text" name="city" value="'.$values["city"].'" placeholder="Dove" required/></div>';
				echo '<div class="form-field"><input type="number" name="limit" value="'.$values["limit"].'" placeholder="Quante" required/></div>';
			?>
			<div class="form-field">
				<input type="submit" value="aggiorna tabella"/>
			</div>
		</form>
		</div>
		<div class="card content-body">
			<?
			
			echo '<table class="query-table"><thead><tr><th>Nome</th><th>Latitudine</th><th>Longitudine</th></tr></thead><tbody>';
			for($i=0; $i<$values["limit"]; $i++){	
				echo "<tr><td>".$data->response->venues[$i]->name."</td>";
				echo "<td>".$data->response->venues[$i]->location->lat."</td>";
				echo "<td>".$data->response->venues[$i]->location->lng."</td></tr>";
			}
			echo '</tbody></table>';
			
			/*
			echo 'Ecco cosa ho trovato per te: ';			
			echo '<ul>';
			for($i=0; $i<$values["limit"]; $i++){	
				echo '<li class="list-item">'.$data->response->venues[$i]->name.'<hr>'.$data->response->venues[$i]->location->lat.' - '.$data->response->venues[$i]->location->lng.'</li>';
			}
			echo '</ul>';
			*/
			echo curl_error($ch);
			curl_close($ch);

		?>

		</div>
		<div class="card footer">
			I sorgenti sono disponibili su <a href="https://github.com/fbognini/php-foursquare-api" target="_blank">GitHub</a>
		</div>
	</div>
  </body>
</html>