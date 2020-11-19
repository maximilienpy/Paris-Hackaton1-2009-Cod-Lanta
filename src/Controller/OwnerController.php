<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\OwnerManager;

/**
 * Class OwnerController
 *
 */
class OwnerController extends AbstractController
{


    /**
     * Display owner listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $ownerManager = new OwnerManager();
        $owners = $ownerManager->selectAll();

        return $this->twig->render('Owner/index.html.twig', ['owners' => $owners]);
    }


    /**
     * Display owner informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $ownerManager = new OwnerManager();
        $owner = $ownerManager->selectOneById($id);

        return $this->twig->render('Owner/show.html.twig', ['owner' => $owner]);
    }


    /**
     * Display owner edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $ownerManager = new OwnerManager();
        $owner = $ownerManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $owner['firstname'] = $_POST['firstname'];
            $owner['lastname'] = $_POST['lastname'];
            $owner['description'] = $_POST['description'];
            $owner['picture'] = $_POST['picture'];
            $owner['castle'] = $_POST['castle'];
            $ownerManager->update($owner);
        }

        return $this->twig->render('Owner/edit.html.twig', ['owner' => $owner]);
    }


    /**
     * Display owner creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ownerManager = new OwnerManager();
            $owner = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'description' => $_POST['description'],
                'picture' => $_POST['picture'],
                'castle' => $_POST['castle'],
            ];
            $id = $ownerManager->insert($owner);
            header('Location:/owner/show/');
        }

        return $this->twig->render('Owner/add.html.twig');
    }


    /**
     * Handle owner deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $ownerManager = new OwnerManager();
        $ownerManager->delete($id);
        header('Location:/owner/index');
    }
}