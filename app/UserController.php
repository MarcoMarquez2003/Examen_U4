<?php

class UserController
{
    private $apiUrl = "https://crud.jonathansoto.mx/api/users";
    private $token = '2165|OAdQbuEmRTdNA9YAv8xzkNt4jLNCDhTKhcBehRfV';

    public function details_user($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . '/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $this->token",
                "Accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode === 200 ? json_decode($response, true) : false;
    }

    public function getUser()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                "Accept: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            $errorMessage = curl_error($curl);
            curl_close($curl);
            return ['error' => true, 'message' => $errorMessage];
        }

        curl_close($curl);

        if ($httpCode === 200) {
            return json_decode($response, true); 
        }

        return ['error' => true, 'message' => 'HTTP Error: ' . $httpCode];
    }
    public function delete_user($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . '/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $this->token",
                "Accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode === 200;
    }
}
if (isset($_GET['action'])) {
    $UserController = new UserController();

    switch ($_GET['action']) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $lastname = $_POST['lastname'];
                $email = $_POST['email'];
                $phone_number = $_POST['phone_number'];
                $password = $_POST['password'];
                $role = $_POST['role'];

                if ($UserController->createUser($name, $lastname, $email, $phone_number, $password, $role)) {
                    header("Location: ../tpm/application/user.php");
                } else {
                    echo "Error al crear el usuario.";
                }
            }
            break;

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $id = $_POST['id'];
                $data = [
                    'name' => $_POST['name'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'phone_number' => $_POST['phone_number'],
                    'role' => $_POST['role'],
                    'password' => $_POST['password']
                ];

                if ($UserController->editarUsuario($id, $data)) {
                    header("Location: ../tpm/application/user.php");
                } else {
                    echo "Error al actualizar el usuario.";
                }
            }
            break;

        case 'delete':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                if ($UserController->delete_user($id)) {
                    header("Location: ../tpm/application/user.php");
                } else {
                    echo "Error al eliminar el usuario.";
                }
            }
            break;

        default:
            echo "Acción no válida.";
            break;
    }
}
?>

