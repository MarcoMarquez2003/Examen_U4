<?php
class ClientController
{
    private $apiUrl = 'https://crud.jonathansoto.mx/api/clients';
    private $authHeader = 'Authorization: Bearer 1797|4x5bKd0YNYeohykmKVP6aEWlWbm5zcZTisNQz43e';

    public function getClients()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                $this->authHeader
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode == 200) {
            $clients = json_decode($response, true);
            return $clients['data'] ?? null;
        }

        return "Error al obtener los clientes. Código: $httpCode Respuesta: $response";
    }

    public function client_details($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . "/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                $this->authHeader
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode == 200) {
            $client = json_decode($response, true);
            return $client['data'] ?? null;
        }

        return [
            'error' => true,
            'message' => "Error al obtener los detalles del cliente. Código: $httpCode Respuesta: $response"
        ];
    }

    public function add_client($name, $email, $password, $phone_number) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'phone_number' => $phone_number
            ]),
            CURLOPT_HTTPHEADER => [
                $this->authHeader,
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode == 200 ? "Cliente creado con éxito." : "Error al crear el cliente. Código: $httpCode Respuesta: $response";
    }

    public function delete_client($id)
    {
        $apiUrl = $this->apiUrl . "/$id";
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => [
                $this->authHeader
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode == 200;
    }
}

if (isset($_GET['action'])) {
    $ClientController = new ClientController();

    switch ($_GET['action']) {
        case 'delete':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                if ($ClientController->delete_client($id)) {
                    header("Location: ../tpm/application/client.php");
                } else {
                    echo "Error al eliminar";
                }
            } else {
                echo "ID del cliente no especificado.";
            }
            break;

        default:
            echo "Acción no válida.";
            break;
    }
}
?>
