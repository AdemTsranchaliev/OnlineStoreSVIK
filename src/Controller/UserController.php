<?php

namespace App\Controller;
use App\Entity\ShoppingCart;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Form\Changeinfo;
use App\Form\Orders;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;


class UserController extends AbstractController
{


    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/myInformation", name="myInformation")
     */
    public function myInformation(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(Changeinfo::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render("user/myInformation.html.twig",['user'=>$user,'productsCart'=>$shoppingCart]);
            }

        }
        return $this->render("user/myInformation.html.twig",['user'=>$user,'productsCart'=>null]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/myOrders", name="myOrders")
     */
    public function myOrders(Request $request)
    {
        $user = $this->getUser();

        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render("user/myOrders.html.twig",['orders'=>$user->getOrders(),'productsCart'=>$shoppingCart]);
            }

        }
        return $this->render("user/myOrders.html.twig",['orders'=>$user->getOrders(),'productsCart'=>null]);

    }


    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOrder/{id}", name="seeOrder")
     * @param $id
     */
    public function seeOrder(Request $request, $id)
    {

        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        $user = $this->getUser();
        if ($order==null)
        {
            return $this->redirectToRoute('404');
        }
        if ($order->getUserId()==null)
        {
            return $this->redirectToRoute('404');
        }
        if ($order->getUserId()->getId()!=$user->getId())
        {
            return $this->redirect('myOrders');
        }


        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                $shoppingCart2=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$order->getCoocieId()));

                return $this->render("user/seeOrder.html.twig",['productsCart'=>$shoppingCart,'order' => $order,'shoppingCart'=>$shoppingCart2]);
            }

        }


        $shoppingCart2=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$order->getCoocieId()));


        return $this->render("user/seeOrder.html.twig",['productsCart'=>null,'order' => $order,'shoppingCart'=>$shoppingCart2]);
    }
    /**
     * @Route("/seeShoppingCart", name="seeShoppingCart")
     */
    public function seeShoppingCart(Request $request)
    {
        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render("user/seeCart.html.twig",['productsCart'=>$shoppingCart]);
            }

            return $this->render("user/seeCart.html.twig",['productsCart'=>null]);

        }
        return $this->render("user/seeCart.html.twig",['productsCart'=>null]);
    }
    /**
     * @Route("/ordering", name="ordering")
     */
    public
    function ordering(Request $request)
    {
        $user = $this->getUser();

        if ($user==null)
        {
            $user=new User();
        }
        $cookie='';
        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];
        }
        $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));

        if ($shoppingCart === null) {
            return $this->redirectToRoute('404');
        }

        $order= new Order();
        $form = $this->createForm(Orders::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $sum=0;
            $value=(new \DateTime())->format('Y-m-d H:i:s');
            $order->setOrderOn(new \DateTime('NOW'));
            for($i=0;$i<count($shoppingCart);$i++)
            {
                $sum+=$shoppingCart[$i]->getQuantity()*$shoppingCart[$i]->getcartProduct()[0]->getPrice();
            }
            $order->setPrice($sum);
            $order->setCoocieId($cookie);
            $order->setNewOrArchived(false);
            $order->setConfirmed(false);

            if ($user->getName()!=null)
            {
                $order->setUserId($user);

            }




            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            setcookie("_SC_KO", time() - 3600);


            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $message="<a href=\"www.obuvkisvik.com/seeOrderAdmin/".$order->getId()."\">Виж поръчка</a>";


            mail("obuvkisvik@gmail.com","Нова поръчка",$message,$headers);



            return $this->render('user/succsesfullOrder.html.twig',['order'=>$order,'user'=>$user,'cart'=>$shoppingCart]);
        }


        return $this->render('user/ordering.html.twig', ['productsCart'=>$shoppingCart,'user'=>$user]);

    }

}