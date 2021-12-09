<?php

	$postData = array(  
	  'filial' => $_REQUEST['filial'],
	  'comanda' => $_REQUEST['comanda'],
	  'produto'  => $_REQUEST['produto'],
	  'qtde'  => $_REQUEST['qtde'],
	  'preco'  => $_REQUEST['preco'],
	  'garcom'  => $_REQUEST['garcom']
  );
  
  $ch = curl_init();  
  curl_setopt($ch, CURLOPT_URL, "https://demo.sisthel.pe/api/v1/orders/Api.php?url=order");
  curl_setopt($ch, CURLOPT_HEADER, false);  
  curl_setopt($ch, CURLOPT_POST, true);  
  //http_build_query => Generar una cadena de consulta codificada estilo URL a partir de array  
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
  $data = curl_exec($ch);  
  print_r($data);  
  curl_close($ch);  
  
?>