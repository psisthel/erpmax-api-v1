<?php
include_once '../db.php';

$keys = array('email','password');

for ($i = 0; $i < count($keys); $i++) {

	if(!isset($_POST[$keys[$i]])) {
		$response['error'] = true;
		$response['message'] = 'Required Filed Missed';
		echo json_encode($response);
		return;
	}

}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM pal990 WHERE AL_USER='" . $email . "' AND AL_PSW='" . $password . "'" ;
$stmt = $this->connect()->prepare($sql);
$stmt->execute([
    'id' => $id,
    'pass' => $pass
]);

if($stmt->num_rows > 0) {

    //$stmt->bind_result( $id, $name, $email, $password);
	$stmt->fetch();

	$user = array(
		'id'=>$stmt['AL_CODIGO'],
		'name'=>$stmt['AL_NOME']
        // 'mobile'=>$mobile,
		// 'password'=>$password,
		// 'email'=>$email
	);

	$stmt->close();
	$response['error'] = false;
	$response['message'] = 'User Loggedin';
	$response['data'] = $user;

} else {

	$response['error'] = true;
	$response['message'] = 'Invalid User Name or Mobile';
	$stmt->close();

}

echo json_encode($response);

?>