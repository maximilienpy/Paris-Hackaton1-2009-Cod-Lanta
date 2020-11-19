<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

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
        $products = $productManager->selectAll();

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
        $product = $productManager->selectOneById($id);


        return $this->twig->render('Product/show.html.twig', [
            'product' => $product,
            ]);
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
        $productManager= new ProductManager();
        $product = $productManager->selectOneById($id);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activated = isset($_POST['is_activated']) ? true : false;
            $product = [
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'category_id' => $_POST['category_id'],
                'size_id' => $_POST['size_id'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity'],
                'is_activated' => $activated,
                'artist_id' => $_POST['artist_id'],
            ];

            $productManager->update($product);
            header('Location:/product/show/' . $id);
        }

        return $this->twig->render('Product/edit.html.twig', [
            'product' => $product,

        ]

          
    /**
     * Display item creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAll();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activated = isset($_POST['is_activated']) ? true : false;
            $productManager = new ProductManager();
            $product = [
                'name' => $_POST['name'],
                'function' => $_POST['function'],
                'quantity' => $_POST['quantity'],
                'picture' => $_POST['picture'],
                'durability' => $_POST['durabilty'],
                'quantity' => $_POST['quantity'],
                'is_activated' => $activated,
                'owner_id' => $_POST['owner_id'],
            ];
            $id = $productManager->insert($product);
            header('Location:/product/show/' . $id);
        }

        return $this->twig->render('Product/add.html.twig');
    }
}
