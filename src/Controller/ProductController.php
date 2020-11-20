<?php

namespace App\Controller;

use App\Model\OwnerManager;
use App\Model\ProductManager;
/**
 * Class ProductController
 *
 */
class ProductController extends AbstractController
{
    /**
     * Display product listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAllWithDetail();
        return $this->twig->render('Product/index.html.twig', ['products' => $products]);
    }
    /**
     * Display product informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $productManager = new ProductManager();
        $product = $productManager->selectOneWithDetails($id);
        return $this->twig->render('Product/show.html.twig', ['product' => $product]);
    }
    /**
     * Display product edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $productManager = new ProductManager();
        $product = $productManager->selectOneWithDetails($id);
        $ownerManager = new OwnerManager();
        $owner = $ownerManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = [
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'function' => $_POST['function'],
                'quantity' => $_POST['quantity'],
                'picture' => $_POST['picture'],
                'durability' => $_POST['durability'],
                'owner_id' => $_POST['owner_id'],
            ];
            $productManager->update($product);
            header('Location:/product/show/' . $id);
        }
        return $this->twig->render('Product/edit.html.twig', [
            'product' => $product,
            'owner' => $owner
        ]);
    }
    /**
     * Display specie creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $ownerManager = new OwnerManager();
        $owner = $ownerManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productManager = new productManager();
            $product = [
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'function' => $_POST['function'],
                'quantity' => $_POST['quantity'],
                'picture' => $_POST['picture'],
                'durability' => $_POST['durability'],
                'owner_id' => $_POST['owner_id'],
            ];
            $id = $productManager->insert($product);
            header('Location:/product/show/' . $id);
        }
        return $this->twig->render('Product/add.html.twig', [
            'product' => $product,
            'owner' => $owner
        ]);

    }
    /**
     * Handle product deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $productManager = new ProductManager();
        $productManager->delete($id);
        header('Location:/product/index');
    }
}