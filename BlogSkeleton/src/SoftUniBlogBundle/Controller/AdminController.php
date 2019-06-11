<?php

namespace SoftUniBlogBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SoftUniBlogBundle\Entity\Models;
use SoftUniBlogBundle\Entity\Orders;
use SoftUniBlogBundle\Entity\User;
use SoftUniBlogBundle\Form\Model;
use SoftUniBlogBundle\Form\UserType;
use SoftUniBlogBundle\Repository\ModelsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller
{

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/profile", name="profile")
     */
    public function profileAction()
    {
        try {
            $user = $this->getUser();
            return $this->render("user/profile.html.twig", ['user' => $user]);
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/adminPanel", name="user_profile")
     */
    public function adminPanel()
    {
        try {
            $models = $this->getDoctrine()->getRepository(Models::class)->findAll();
            return $this->render("admin/adminPanel.html.twig", ['models' => $models]);
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/addModel", name="admin_addModel")
     */
    public function addModel(Request $request)
    {
        try {
            $product = new Models();
            $form = $this->createForm(Model::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                $products = $this->getDoctrine()->getRepository(Models::class)->findAll();

                foreach ($products as $prd) {
                    if ($prd->getModelNumber() == $product->getModelNumber()) {
                        return $this->redirectToRoute('profile');
                    }
                }
                $product->setBoughtCounter(0);
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();

                $img1 = "C:/Users/Adem/Desktop/asdddd/BlogSkeleton/web/img/uploaded/";

                $i = 0;
                $img = 0;
                for ($i = 0; $i < count($_FILES['file']['tmp_name']); $i++) {
                    $targetfile = $img1 . $product->getId() . "." . $i . ".jpg";
                    if ($_FILES['file']['tmp_name'][$i] != '') {
                        move_uploaded_file($_FILES['file']['tmp_name'][$img], $targetfile);
                        $img++;
                    }
                }

                return $this->render("admin/addModel.html.twig");
            };

            return $this->render("admin/addModel.html.twig");
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @Route("/editModel/{id}", name="admin_editModel")
     */
    public function editModel(Request $request, $id)
    {
        try {
            $user = $this->getUser();
            $product = new Models();
            $form = $this->createForm(Model::class, $product);
            $form->handleRequest($request);
            $producttoEdit = $this->getDoctrine()->getRepository(Models::class)->find($id);
            if ($form->isSubmitted()) {
                $producttoEdit->setTitle($product->getTitle());
                $producttoEdit->setModelNumber($product->getModelNumber());
                $producttoEdit->setColor($product->getColor());
                $producttoEdit->setSize($product->getSize());
                $producttoEdit->setPrice($product->getPrice());
                $producttoEdit->setCategory($product->getCategory());
                $producttoEdit->setDiscount($product->getDiscount());
                $producttoEdit->setDescription($product->getDescription());
                $em = $this->getDoctrine()->getManager();
                $em->persist($producttoEdit);
                $em->flush();

                return $this->redirectToRoute("user_profile");
            };

            return $this->render("admin/editModel.html.twig", ['producttoEdit' => $producttoEdit, 'user' => $user]);
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeNewOrders", name="admin_seeNewOrder")
     */
    public function seeOrder(Request $request)
    {
        try {
            $orders = $this->getDoctrine()->getRepository(Orders::class)->findAll();
            $newOrders = Array();
            foreach ($orders as $order) {
                if ($order->isNewOrArchived() === false) {
                    array_push($newOrders, $order);
                }
            }


            return $this->render("admin/ordersMessages.html.twig", ['orders' => $newOrders]);
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOldOrders", name="admin_seeOldOrder")
     */
    public function seeOldOrders(Request $request)
    {
        try {
            $orders = $this->getDoctrine()->getRepository(Orders::class)->findAll();
            $newOrders = Array();
            foreach ($orders as $order) {
                if ($order->isNewOrArchived() === true) {
                    array_push($newOrders, $order);
                }
            }


            return $this->render("admin/ordersMessages.html.twig", ['orders' => $newOrders]);
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOrderDetaily/{id}", name="seeOrderDetaily")
     * @param $id
     */
    public function seeOrderDetaily(Request $request, $id)
    {
        try {


            $order = $this->getDoctrine()->getRepository(Orders::class)->find($id);
            if (isset($_POST['firstName'])) {
                $order->setNewOrArchived(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();

            }
            return $this->render("admin/orderInDetails.html.twig", ['order' => $order]);
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }
}
