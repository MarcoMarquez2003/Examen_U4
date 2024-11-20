<?php
class ProductsController
{
    private $apiUrl = 'https://crud.jonathansoto.mx/api/products';
    private $authHeader = 'Authorization: Bearer 1797|4x5bKd0YNYeohykmKVP6aEWlWbm5zcZTisNQz43e';

    public function obtenerProductos()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => array($this->authHeader),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response ? json_decode($response, true) : null;
    }

    public function obtenerProductoPorId($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->apiUrl}/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array($this->authHeader),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response ? json_decode($response, true) : null;
    }

    public function crearProducto($datos)
    {
        if (!isset($_FILES['cover']) || empty($_FILES['cover']['tmp_name'])) {
            return json_encode(['message' => 'No se ha proporcionado una imagen válida']);
        }

        $filePath = $_FILES['cover']['tmp_name'];
        $fileName = $_FILES['cover']['name'];
        $datos['cover'] = new CURLFile($filePath, mime_content_type($filePath), $fileName);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $datos,
            CURLOPT_HTTPHEADER => array($this->authHeader),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function obtenerMarcas()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://crud.jonathansoto.mx/api/brands',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array($this->authHeader),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response ? json_decode($response, true) : null;
    }

    public function actualizarProducto($id, $datos)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->apiUrl}/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($datos),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                $this->authHeader,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function eliminarProducto($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->apiUrl}/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array($this->authHeader),
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            echo "Error al eliminar el producto. Código HTTP: $httpCode.";
            return false;
        }
    }
}

$productsController = new ProductsController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'create':
            $datos = [
                'name' => $_POST['name'] ?? '',
                'slug' => $_POST['slug'] ?? '',
                'description' => $_POST['description'] ?? '',
                'features' => $_POST['features'] ?? '',
            ];
            $response = $productsController->crearProducto($datos);
            $responseData = json_decode($response, true);

            if (isset($responseData['message']) && $responseData['message'] === 'Registro creado correctamente') {
                header("Location: /EXAMEN_U4/tpm/application/products.php");
                exit();
            } else {
                echo "Error al crear el producto: " . ($responseData['message'] ?? 'Error desconocido');
            }
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if ($id) {
                $response = $productsController->eliminarProducto($id);
                if ($response) {
                    header("Location: /EXAMEN_U4/tpm/application/products.php");
                    exit();
                } else {
                    echo "Error al eliminar el producto.";
                }
            } else {
                echo "ID de producto no proporcionado.";
            }
            break;
    }
}
?>
