<?php
class ClientController
{
    private $apiUrl = 'https://crud.jonathansoto.mx/api/clients';
    private $authHeader = 'Authorization: Bearer 1797|4x5bKd0YNYeohykmKVP6aEWlWbm5zcZTisNQz43e';

    /**
     * Función para obtener todos los clientes.
     */
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

    /**
     * Función para obtener los detalles de un cliente específico por ID.
     */
    public function client_details($id) {
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
}
?>
