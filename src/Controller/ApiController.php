<?php   


namespace App\Controller;


class ApiController extends AbstractController
{

    public function all()
    {
        $productManager = new ProductManager();
        return json_encode($productManager->selectAll());
    }

    public function create()
    {
        $productManager = new ProductManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            try{
                $product[
                    'name' = $_POST['name'],
                    'function' = $_POST['function'],
                    'quantity' = $_POST['quantity'],
                    'picture' = $_POST['picture'],
                    'durability' = $_POST['durability'],
                    'owner_id' = $_POST['owner_id']
                ],
                $id = $prodcutManager->insert($product);
                return json_encode($id, 255);
            } catch (Exception $e) { 
                return json_encode($e->getMesssage()); 
            }
        }
    }

    public function update($id)
    {
        $productManager = new ProductManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            try{
                $product = [
                    'id' = $_POST['id'],
                    'name' = $_POST['name'],
                    'function' = $_POST['function'],
                    'quantity' = $_POST['quantity'],
                    'picture' = $_POST['picture'],
                    'durability' = $_POST['durability'],
                    'owner_id' = $_POST['owner_id']
                ];
                $productManager->update($product);
                return json_encode($id." updated", 255);
            } catch (Exception $e) {
                return json_encode($e->getMessage());
            }
        }

    }

    public function delete($id)
    {
        $productManager = new ProductManager();
        try{
            $productManager->delete($id);
            return json_encode($id." deleted", 255);
        } catch (exception $e) {
            return json_encode($e->getMessage());
        }
    }





}