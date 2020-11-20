<?php

namespace App\Controller;


use App\Model\OwnerManager;
use App\Model\ProductManager;
use App\Model\ExchangeManager;

/**
 * Class ExchangeController
 *
 */
class ExchangeController extends AbstractController
{
    /**
     * Display exchange listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $exchangeManager = new ExchangeManager();
        $exchanges = $exchangeManager->selectAllWithDetail();
        
        return $this->twig->render('Exchange/index.html.twig', ['exchanges' => $exchanges]);
    }

    /**
     * Display exchange informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $exchangeManager = new ExchangeManager();
        $exchange = $exchangeManager->selectOneWithDetails($id);
        
        return $this->twig->render('Exchange/show.html.twig', ['exchange' => $exchange]);
    }
    
    /**
     * Display exchange edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $exchangeManager = new ExchangeManager();
        $exchange = $exchangeManager->selectOneWithDetails($id);
        
        $productManager = new ProductManager();
        $products = $productManager->selectAllWithDetail();
        
        $productManager = new ProductManager();
        $products = $productManager->selectAllWithDetail();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $exchange = [
                'id' => $_POST['id'],
                'quantity' => $_POST['quantity'],
                'total' => $_POST['total'],
                'product_id' => $_POST['product_id'],
            ];

            $exchangeManager->update($exchange);
            header('Location:/exchange/show/' . $id);
        }

        return $this->twig->render('Exchange/edit.html.twig', [
            'exchange' => $exchange,
            'products' => $products,
            'products' => $products
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
        $productManager = new ProductManager();
        $products = $productManager->selectAllWithDetail();

        $productManager = new ProductManager();
        $products = $productManager->selectAllWithDetail();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $exchangeManager = new ExchangeManager();
            $exchange = [
                'quantity' => $_POST['quantity'],
                'total' => $_POST['total'],
                'product_id' => $_POST['product_id'],
                'product_id' => $_POST['product_id'],
            ];
            $id = $exchangeManager->insert($exchange);
            header('Location:/exchange/show/' . $id);
        }
        return $this->twig->render('Exchange/add.html.twig', [
            'products' => $products,
            'products' => $products
        ]);
    }
    /**
     * Handle exchange deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $exchangeManager = new ExchangeManager();
        $exchangeManager->delete($id);
        header('Location:/exchange/index');
    }
}







