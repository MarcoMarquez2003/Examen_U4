<?php

class UserController
{
    private $apiUrl = "https://crud.jonathansoto.mx/api/users";
    private $token = '2165|OAdQbuEmRTdNA9YAv8xzkNt4jLNCDhTKhcBehRfV';

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
}
?>

