<?php 
	session_start();


	if (isset($_POST['action'])) {
		
		switch ($_POST['action']) {
			
			case 'login':
				
				$correo =  $_POST['email'];
				$contrasena = $_POST['password'];

				$authController = new AuthController();

				$authController->access($correo,$contrasena);

			break; 
		}
	}

	class AuthController
	{

		public function access($correo,$contrasena)
		{
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://crud.jonathansoto.mx/api/login',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS => array(
			  	'email' => $correo,
			  	'password' => $contrasena
			  ),
			));

			$response = curl_exec($curl); 
			curl_close($curl); 
			$response = json_decode($response);


			if (isset($response->data)  && is_object($response->data)) {
				

				$_SESSION['user_data'] = $response->data;

				header("Location: /EXAMEN_U4/tpm/dashboard/index.html");
			}else{
				header("Location: /EXAMEN_U4/index.html");
			}

		}


	}