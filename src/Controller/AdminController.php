<?php

namespace App\Controller;

use App\Entity\ImageResize;
use App\Entity\Category;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Product;
use App\Form\Products;
use App\Form\EditProduct;


class AdminController extends AbstractController
{

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted("ROLE_ADMIN")
     * @Route("/addProduct", name="addProduct")
     */
    public function addProduct(Request $request)
    {
        set_time_limit(600);
        ini_set('memory_limit', '1024M');

        $user = $this->getUser();
        $product = new Product();
        $form = $this->createForm(Products::class, $product);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $biggerImagesLocation = "/home/obuviyov/public_html/public/assets/images/uploads/";
            $smallerImagesLocation = "/home/obuviyov/public_html/public/assets/images/small/";

            //handle sizes
            $sizesQuantity = array();

            $quantity = $_POST['quantity'];
            $size = $_POST['size'];
            $sizeSantimeters = $_POST['sizeSantimeters'];


            for ($i = 0; $i < count($quantity); $i++) {
                $obj = ['size' => $size[$i], 'quantity' => $quantity[$i], 'sizeCm' => $sizeSantimeters[$i]];
                array_push($sizesQuantity, $obj);
            }

            $product->setSizes(json_encode($sizesQuantity));
            $product->setStatuses($this->getStatuses());
            //end handle sizes

            //handle images

            $images = array();
            for ($i = 0; $i < count($_FILES['file']['tmp_name']); $i++) {
                $filePath = $_FILES['file']['tmp_name'][$i];
                if ($filePath != '') {
                    $bytes = random_bytes(20);
                    $images[$i] = bin2hex($bytes);

                    $image = new ImageResize($filePath);
                    $image->resizeToBestFit(650, 650);
                    $image->save($smallerImagesLocation . bin2hex($bytes) . '.jpg');

                    move_uploaded_file($filePath, $biggerImagesLocation . bin2hex($bytes) . '.jpg');
                }
            }
            $product->setPictures(json_encode($images));
            //end handle images


            $op = new Category();
            foreach ($categories as $value) {
                if (strcmp($product->getCategory(), $value->getTag()) == 0) {
                    $op = $value;
                    break;
                }
            }
            $product->setCategoryR($op);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $img = 0;

            return $this->render("admin/addProduct.html.twig", ['user' => $user, 'productsCart' => null, 'categories' => $categories]);
        }


        return $this->render("admin/addProduct.html.twig", ['user' => null, 'productsCart' => null, 'categories' => $categories]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/seeProducts", name="seeProducts")
     */
    public function seeProducts()
    {
        //$models = $this->getDoctrine()->getRepository(Product::class)->findBy(array('category' => $category));
        $models = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render("admin/allProducts.html.twig", ['models' => $models, 'categories' => $categories]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @IsGranted("ROLE_ADMIN")
     * @Route("/editProduct/{id}", name="editProduct")
     */
    public function editModel(Request $request, $id)
    {
        $producttoEdit = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        if ($producttoEdit == null) {
            return $this->redirectToRoute('404');
        }

        $form = $this->createForm(EditProduct::class, $producttoEdit);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(array('tag' => $producttoEdit->getCategory()));
            $producttoEdit->setCategoryR($category);


            //handle sizes
            $sizesQuantity = array();

            $quantity = $_POST['quantity'];
            $size = $_POST['size'];
            $sizeSantimeters = $_POST['sizeSantimeters'];


            for ($i = 0; $i < count($quantity); $i++) {
                $obj = ['size' => $size[$i], 'quantity' => $quantity[$i], 'sizeCm' => $sizeSantimeters[$i]];
                array_push($sizesQuantity, $obj);
            }

            $producttoEdit->setSizes(json_encode($sizesQuantity));

            $em = $this->getDoctrine()->getManager();
            $em->persist($producttoEdit);
            $em->flush();

            return $this->redirectToRoute("editProduct", ['id' => $producttoEdit->getId()]);
        }

        return $this->render("admin/editProduct.html.twig", ['producttoEdit' => $producttoEdit, 'productsCart' => null, 'categories' => $categories, 'sizes' => json_decode($producttoEdit->getSizes(), true), 'pictures' => json_decode($producttoEdit->getPictures(), true)]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted("ROLE_ADMIN")
     * @Route("/seeOrders/{func}", name="seeOrders")
     * @param $func
     */
    public function seeOrders(Request $request, $func)
    {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        $newOrders = array();
        $title = '';
        if (strcmp($func, 'all') == 0) {
            $newOrders = $orders;
            $title = 'ВСИЧКИ ПОРЪЧКИ';
        }
        if (strcmp($func, 'confirmed') == 0) {
            foreach ($orders as $order) {
                if ($order->getStatus() == "confirmed") {
                    array_push($newOrders, $order);
                }
            }
            $title = 'ПОТВЪРДЕНИ ПОРЪЧКИ';
        }
        if (strcmp($func, 'new') == 0) {
            foreach ($orders as $order) {
                if ($order->getStatus() == "new") {
                    array_push($newOrders, $order);
                }
            }
            $title = 'НОВИ ПОРЪЧКИ';
        }
        if (strcmp($func, 'done') == 0) {
            foreach ($orders as $order) {
                if ($order->getStatus() == "done") {
                    array_push($newOrders, $order);
                }
            }
            $title = 'ИЗПЪЛНЕНИ ПОРЪЧКИ';
        }
        if (strcmp($func, 'returned') == 0) {
            foreach ($orders as $order) {
                if ($order->getStatus() == "returned") {
                    array_push($newOrders, $order);
                }
            }
            $title = 'ВЪРНАТИ ПОРЪЧКИ';
        }

        return $this->render("admin/seeOrders.html.twig", ['orders' => $newOrders, 'title' => $title, 'productsCart' => null]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOrderAdmin/{id}", name="seeOrderAdmin")
     * @param $id
     */
    public function seeOrderAdmin(Request $request, $id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);


        if ($order == null) {
            return $this->redirectToRoute('404');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($order->getConfirmed() == 0) {
                $order->setConfirmed(true);
            } else {
                $order->setNewOrArchived(true);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
        }

        return $this->render("admin/seeOrder.html.twig", ['order' => $order]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/changeOrderStatus", name="changeOrderStatus")
     * @param $id
     */
    public function changeOrderStatus(Request $request)
    {


   
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if(isset($_POST['id'])&&isset($_POST['status'])){

                $id = $_POST['id'];
                $status = $_POST['status'];

                $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

                if ($order == null) {
                    return $this->redirectToRoute('404');
                }

                $order->setStatus($status);
                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();

                return $this->redirectToRoute('seeOrderAdmin',array('id' => $id));
            }

        }
        
        return $this->redirectToRoute('/seeOrders');
    }

    // /**
    //  * @Route("/renumber")
    //  */
    // public function convertAllSizesInJson(Request $request)
    // {
    //     set_time_limit(50000);
    //     ini_set('memory_limit', '1024M');

    //     $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

    //     for ($i = 0; $i < count($products); $i++) {
    //         $sizeAndNumber = $products[$i]->getSizes();

    //         $sizeAndNumber = explode(" ", $sizeAndNumber);
    //         $sizeAndNumber = array_filter(array_map('trim', $sizeAndNumber));

    //         $sizesQuantity = array();
    //         foreach ($sizeAndNumber as $item) {
    //             $test = explode('-', $item);
    //             $obj = null;

    //             if (count(explode('(', $test[0])) > 1) {
    //                 $obj = ['size' => explode('(', $test[0])[0], 'quantity' => $test[1], 'sizeCm' => substr_replace(explode('(', $test[0])[1], "", -1)];
    //             } else {
    //                 $obj = ['size' => $test[0], 'quantity' => $test[1], 'sizeCm' => '-'];
    //             }

    //             array_push($sizesQuantity, $obj);
    //         }
    //         $products[$i]->setSizes(json_encode($sizesQuantity));
    //         $em = $this->getDoctrine()->getManager();

    //         $em->persist($products[$i]);
    //         $em->flush();
    //     }

    //     return null;
    // }

    // /**
    //  * @Route("/generateImagesJson")
    //  */
    // public function generateImagesJson(Request $request)
    // {
    //     set_time_limit(600);
    //     ini_set('memory_limit', '1024M');
    //     $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
    //     for ($i = 0; $i <= count($products); $i++) {
    //         $sizesQuantity = array();

    //         for ($j = 0; $j < 3; $j++) {
    //             $sizesQuantity[$j] = $products[$i]->getId() . '.' . $j;
    //         }

    //         $products[$i]->setPictures(json_encode($sizesQuantity));
    //         $em = $this->getDoctrine()->getManager();

    //         $em->persist($products[$i]);
    //         $em->flush();
    //     }
    // }

    // /**
    //  * @Route("/resize")
    //  */
    // public function resizeAllImages(Request $request)
    // {
    //     set_time_limit(600);
    //     ini_set('memory_limit', '1024M');
    //     $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

    //     for ($i = 0; $i <= count($products); $i++) {
    //         for ($j = 1; $j < $products[$i]->getPhotoCount(); $j++) {
    //             $filename1 = "/home/obuviyov/public_html/public/assets/img/uploads/" . $products[$i]->getId() . "." . $j . ".jpg";
    //             $filename2 = "/home/obuviyov/public_html/public/assets/img/small/" . $products[$i]->getId() . "." . $j . ".jpg";

    //             $image = new ImageResize($filename1);
    //             $image->resizeToBestFit(500, 500);
    //             $image->save($filename2);
    //         }
    //     }

    //     return $this->redirect("adminPanel");
    // }

    private function getStatuses()
    {

        $arr = ['isTrending' => false, 'isNew' => false, 'isBestSeller' => false];

        foreach ($arr as $key => $value) {
            if (isset($_POST[$key])) {
                $arr[$key] = true;
            }
        }
        return json_encode($arr);
    }
}
