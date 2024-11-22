<?php
class CouponController
{
    private $apiUrl = "https://crud.jonathansoto.mx/api/coupons";
    private $token = '2196|v2xRnsiM5HKJZaOdEdEjtzHnvupt7bfJ1CoOMqGz';

    public function coupon_details($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . '/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                "Accept: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response ? json_decode($response, true) : false;
    }

    public function getcoupon()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                "Accept: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response ? json_decode($response, true) : false;
    }
}
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new CouponController();

    switch ($action) {
       
        case 'details':
            if (isset($_GET['id'])) {
                header("Location: /EXAMEN_U4/tpm/application/coupon_details.php?id=" . $_GET['id']);
            }
            break;

        case 'delete':
            if (isset($_GET['id'])) {
                $result = $controller->delete_coupon($_GET['id']);
                if (isset($result['success']) && $result['success'] === true) {
                    header("Location: ../tpm/application/coupon.php?message=Cupón eliminado correctamente");
                } else {
                    echo "<h2>Error al eliminar el cupón.</h2>";
                }
            }
            break;
        default:
            echo "<h2>Acción no válida.</h2>";
            break;
    }
}
?>