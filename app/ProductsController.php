<?php
class ProductsController
{
    private $apiUrl = 'https://crud.jonathansoto.mx/api/products';
    private $authHeader = 'Authorization: Bearer 1797|4x5bKd0YNYeohykmKVP6aEWlWbm5zcZTisNQz43e';

    public function getProduct()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                $this->authHeader
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function getProductId($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->apiUrl}/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                $this->authHeader
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function crearProducto($datos)
    {
        $curl = curl_init();
        $filePath = $_FILES['cover']['tmp_name'];
        $fileName = $_FILES['cover']['name'];

        $datos['cover'] = new CURLFile($filePath, mime_content_type($filePath), $fileName);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $datos,
            CURLOPT_HTTPHEADER => array(
                $this->authHeader
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function getbrands()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://crud.jonathansoto.mx/api/brands',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                $this->authHeader
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public function eliminarProducto($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->apiUrl}/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                $this->authHeader,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error en cURL: ' . curl_error($curl);
            return false;
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            return $response;
        } else {
            echo "Error al eliminar el producto. Código HTTP: $httpCode. Respuesta: $response";
            return false;
        }
    }

    public function actualizarProducto($id, $datos)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->apiUrl}/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => http_build_query($datos),
            CURLOPT_HTTPHEADER => array(
                $this->authHeader,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error en cURL: ' . curl_error($curl);
            return false;
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200 || $httpCode === 201) {
            return $response;
        } else {
            echo "Error al actualizar el producto. Código HTTP: $httpCode. Respuesta: $response";
            return false;
        }
    }
}

$ProductsController = new ProductsController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'create':
            $datos = [
                'name' => $_POST['name'],
                'slug' => $_POST['slug'],
                'description' => $_POST['description'],
                'features' => $_POST['features'],
                'cover' => $_FILES['cover']
            ];
            $response = $ProductsController->crearProducto($datos);

            $responseData = json_decode($response, true);
            if (isset($responseData['message']) && $responseData['message'] === 'Registro creado correctamente') {
                header("Location: /EXAMEN_U4/tpm/application/products.php");
                exit();
            } else {
                echo "Error al crear el producto: " . ($responseData['message'] ?? 'Error desconocido');
            }
            break;

        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $response = $ProductsController->eliminarProducto($id);

                if ($response) {
                    echo "Producto eliminado correctamente.";
                    header("Location: ../tpm/application/delete_product.php");
                    exit();
                } else {
                    echo "Error al eliminar el producto.";
                }
            } else {
                echo "ID de producto no proporcionado o método no permitido.";
            }
            break;

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $datos = [
                    'name' => $_POST['name'],
                    'slug' => $_POST['slug'],
                    'description' => $_POST['description'],
                    'features' => $_POST['features'],
                    'cover' => $_FILES['cover']
                    

                    
                ];

                $response = $ProductsController->actualizarProducto($id, $datos);

                $responseData = json_decode($response, true);
                if (isset($responseData['message']) && $responseData['message'] === 'Producto actualizado correctamente') {
                    header("Location: /EXAMEN_U4/tpm/application/products.php");
                    exit();
                } else {
                    echo "Error al actualizar el producto: " . ($responseData['message'] ?? 'Error desconocido');
                }
            } else {
                echo "Datos insuficientes o método no permitido.";
            }
            break;
    }
}
?>
