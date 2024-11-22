<?php
class ClientController
{
    private $apiUrl = 'https://crud.jonathansoto.mx/api/clients'; // Cambié "products" por "clients"
    private $authHeader = 'Authorization: Bearer 1797|4x5bKd0YNYeohykmKVP6aEWlWbm5zcZTisNQz43e';

    public function getclients() {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                $this->authHeader, // Se usa $this->authHeader correctamente
                'Accept: application/json'
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
}
?>