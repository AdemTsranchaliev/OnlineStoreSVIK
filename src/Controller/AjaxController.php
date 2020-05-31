<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;


class AjaxController extends AbstractController
{

    /**
     * @Route("/ajaxSort")
     */
    public function ajaxAction(Request $request) {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $command=$_POST['command'];
            $category=$_POST['categoryName'];

            $arr2=[];
            $categories = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();

            foreach ($categories as $prd) {

                if (strcmp($category, $prd->getName()) == 0) {
                    $arr2 = $prd->getProducts();
                    break;
                }
            }
            $arr=[];
            for ($i=0;$i<count($arr2);$i++)
            {
                if ($arr2[$i]->getIsDeleted()==0)
                {
                    array_push($arr,$arr2[$i]) ;
                }
            }

            if(strcmp($command,"price-low-to-high")==0)
            {
                for ($i = 0; $i < count($arr); $i++) {

                    for ($j = 0; $j < count($arr) - $i - 1; $j++)
                    {
                        if( $arr[$j]->getPrice() < $arr[$j+1]->getPrice() )
                        {
                            $temp = $arr[$j];
                            $arr[$j]=$arr[$j+1];
                            $arr[$j+1]=$temp;

                        }

                    }

                }
            }
            if(strcmp($command,"price-high-to-low")==0)
            {
                for ($i = 0; $i < count($arr); $i++) {

                    for ($j = 0; $j < count($arr) - $i - 1; $j++)
                    {
                        if( $arr[$j]->getPrice() > $arr[$j+1]->getPrice() )
                        {
                            $temp = $arr[$j];
                            $arr[$j]=$arr[$j+1];
                            $arr[$j+1]=$temp;

                        }

                    }

                }
            }
            if(strcmp($command,"by-popularity")==0)
            {
                for ($i = 0; $i < count($arr); $i++) {

                    for ($j = 0; $j < count($arr) - $i - 1; $j++)
                    {
                        if( $arr[$j]->getBoughtCounter() < $arr[$j+1]->getBoughtCounter() )
                        {
                            $temp = $arr[$j];
                            $arr[$j]=$arr[$j+1];
                            $arr[$j+1]=$temp;

                        }

                    }

                }
            }
            if(strcmp($command,"date")==0)
            {
                for ($i = 0; $i < count($arr); $i++) {

                    for ($j = 0; $j < count($arr) - $i - 1; $j++)
                    {
                        if( $arr[$j]->getId() < $arr[$j+1]->getId() )
                        {
                            $temp = $arr[$j];
                            $arr[$j]=$arr[$j+1];
                            $arr[$j+1]=$temp;

                        }

                    }

                }
            }


            $ready='';

            $ready.=" <div class=\"row\" id=\"divSort\">";

            $i=1;
            foreach ($arr as $prd) {

                $ready.="<a href=\"http://127.0.0.1:8000/singleProduct/".$prd->getId()."\">";
                $ready.="<div class=\"col-xl-4 col-lg-4 col-md-6\">
                            <div class=\"single-product mb-60\">
                                <div class=\"product-img\">";
                $ready.="<img src=\"/assets/img/uploads/".$prd->getId().".0.jpg\" alt=\"\">";
               if($prd->getIsNew()==1)
               {
                   $ready.=" <div class=\"new-product\">
                                 <span>Ново</span>
                             </div>";
               }
               $ready.="</div>";
               $ready.=" <div class=\"product-caption\">

                          <h4><a href=\"#\">".$prd->getTitle()."</a></h4>
                        <div class=\"price\">
                                        <ul>";
               if($prd->getIsInPromotion()==1)
               {
                   $ready.=" <li>".number_format($prd->getPrice(),2)." лв</li>
                             <li class=\"discount\">".number_format($prd->getDiscountPrice(),2)." лв</li>";
               }
               else
               {
                   $ready.=" <li>".number_format($prd->getPrice(),2)." лв</li>";
               }
               $ready.="               </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>";
                $i++;

            }
            $ready.=" </div>";
            return new Response($ready);
        } else {
            return $this->render('student/ajax.html.twig');
        }



    }


    /**
     * @Route("/ajaxAddToCart")
     */
    public function ajaxAddToCart(Request $request) {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            if (!isset($_COOKIE['_SC_KO']))
            {
                setcookie('_SC_KO',bin2hex(random_bytes(10)),time() + (86400 * 30));

            }
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));

            $id=$_POST['id'];

            $size=$_POST['sizeSelected'];



            $product=$this->getDoctrine()->getRepository(Product::class)->find($id);


            $response='';


            if($shoppingCart==null)
            {
                $shoppingCart=new ShoppingCart();

                $shoppingCart->addCartProduct($product);
                $shoppingCart->setProductId($id);
                $shoppingCart->setCoocieId($cookie);
                $shoppingCart->setModelSize($size);
                $shoppingCart->setPrice($product->getPrice());
                $shoppingCart->setQuantity(1);


            }
            else {
                $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findOneBy(array('coocieId'=>$cookie,'modelSize'=>$size,'productId'=>$product->getId()));
                if ($shoppingCart==null)
                {
                    $shoppingCart=new ShoppingCart();

                    $shoppingCart->addCartProduct($product);
                    $shoppingCart->setCoocieId($cookie);
                    $shoppingCart->setModelSize($size);
                    $shoppingCart->setPrice($product->getPrice());
                    $shoppingCart->setQuantity(1);
                    $shoppingCart->setProductId($product->getId());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($shoppingCart);
                    $em->flush();
                    $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findOneBy(array('coocieId'=>$cookie,'modelSize'=>$size,'productId'=>$product->getId()));

                }
                else
                {
                    $shoppingCart->setPrice($product->getPrice()*($shoppingCart->getQuantity()+1));
                    $shoppingCart->setQuantity($shoppingCart->getQuantity()+1);

                }

            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($shoppingCart);
            $em->flush();


            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            $response.="<div class=\"top-bar__item nav-cart\" id=\"cartIdnt\">
<a href=\"/seeShoppingCart\">
    <i class=\"ui-bag\"></i>(".count($shoppingCart).")
</a>
<div class=\"nav-cart__dropdown\">
    <div class=\"nav-cart__items\" style=\"overflow : scroll; scrollbar-width: thin; height: 370px\">";


            $total=0;
            foreach ($shoppingCart as $product2)
            {
                $total+=$product2->getPrice();
                $response.="<div class=\"nav-cart__item clearfix\">
    <div class=\"nav-cart__img\">
        <a href=\"#\">
            <img src=\"/img/uploads/".$product2->getCartProduct()[0]->getId().".0.jpg\" height=\"100\" width=\"60\" alt=\"\">
        </a>
    </div>
    <div class=\"nav-cart__title\">
        <a href=\"#\">
            ".$product2->getCartProduct()[0]->getTitle()."
        </a>
        <div class=\"nav-cart__price\">
            <span>".$product2->getQuantity()." x</span>
            <span>  ".number_format($product2->getCartProduct()[0]->getPrice(),2)."лв.</span>
        </div>
        <div class=\"nav-cart__price\">
            <span>Размер: </span>
            <span>".$product2->getModelSize()."</span>
        </div>
        <div class=\"nav-cart__price\">
            <span>Общо: </span>
            <span>".$product2->getPrice()." лв.</span>
        </div>
    </div>
    <div class=\"nav-cart__remove\">
        <a href=\"#\" onclick=\"deleteProd(".$product2->getCartProduct()[0]->getId().")\"><i class=\"ui-close\"></i></a>
    </div>
</div>
";
            }




            $response.="   </div> <!-- end cart items -->
<div class=\"nav-cart__summary\">
    <span>Общо за количка: </span>
    <span class=\"nav-cart__total-price\">".number_format($total,2)."лв</span>
</div>
<div class=\"nav-cart__actions mt-20\">
<a href=\"/seeShoppingCart\" class=\"btn btn-md btn-light\"><span>Виж количката</span></a>
<a href=\"/ordering\" class=\"btn btn-md btn-color mt-10\"><span>Към поръчка</span></a>
</div>
</div>
</div>
</div>";



            return new Response($response);
        }

    }

    /**
     * @Route("/checkIfMailExists")
     */
    public function checkIfMailExists(Request $request)
    {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $email=$_POST['email'];
            $emailExist=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('email'=>$email));

            if ($emailExist==null)
            {
                return new Response("no");
            }
            else
            {
                return new Response("yes");
            }
        }
    }

    /**
     * @Route("/sendMail")
     */
    public function sendMail(Request $request)
    {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {


            if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {


                $name = $_POST['name'];
                $email = $_POST['email'];
                $subject = $_POST['subject'];
                $messagee = $_POST['message'];


                $to = "ademcran4aliew@gmail.com";


                $message = "
<html>
<head>
    <title>Email от контактна форма</title>
</head>
<body>
<h1>Съобщение</h1>
<p>" . $messagee . "</p>
<table>
    <tr>
        <th>Име</th>
        <th>Email</th>
    </tr>
    <tr>
        <td>" . $name . "</td>
        <td>" . $email . "</td>

    </tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



              //  mail($to, $subject, $message, $headers);


                return new Response("yes");

            }
        }
        return new Response("no");

    }

    /**
     * @Route("/deleteProd")
     */
    public function deleteProd(Request $request)
    {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $id=$_POST['id'];


            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findOneBy(array('coocieId'=>$cookie,'id'=>$id));

            if ($shoppingCart!=null)
            {
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($shoppingCart);
                $entityManager->flush();

                $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));
                $response='';

                $response.="<tbody id=\"toChange\">";


                $total=0;
                foreach ($shoppingCart as $product2)
                {
                    $total+=$product2->getPrice();
                    $response.="   
                        <tr>
                            <td>
                                <a href=\"http://127.0.0.1:8000/singleProduct/".$product2->getId()."\">
                                <div class=\"media\">
                                    <div class=\"d-flex\">
                                        <img src=\"/assets/img/uploads/".$product2->getProductId().".0.jpg\" alt=\"\" height=\"140\" width=\"140\" />
                                    </div>
                                    <div class=\"media-body\">
                                        <p>".$product2->getCartProduct()[0]->getTitle()."</p>
                                    </div>
                                </div>
                                </a>
                            </td>
                            <td>
                                <h5>(".$product2->getModelSize().")</h5>
                            </td>
                            <td>
                                <h5>".number_format($product2->getCartProduct()[0]->getPrice(),2)." лв</h5>
                            </td>
                            <td>
                                <h5>".$product2->getQuantity()."</h5>
                            </td>

                            <td>
                                <h5>".number_format($product2->getPrice(),2)." лв</h5>
                            </td>
                            <td>
                                <div >
                                    <a href=\"#\" onclick=\"deleteProd(".$product2->getId().")\" style=\"color: #0b0b0b\"><span>&#10006;</span></a>
                                </div>
                            </td>

                        </tr>
                             ";
                }



                if ($total!=0) {
                           $response .= " <tr>

                            <td>
                                <h5>Общо за кошница</h5>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <h5>".number_format($total,2)." лв</h5>
                            </td>
                        </tr>
                        <tr class=\"bottom_button\">
                            <td>
                                <a class=\"btn_1\" href=\"http://127.0.0.1:8000\">Начало</a>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class=\"cupon_text float-right\">
                                    <a class=\"btn_1\" href=\"http://127.0.0.1:8000\ordering\">КЪМ ПОРЪЧКА</a>
                                </div>
                            </td>
                        </tr>
                        </tbody>";
                }
                else
                {
                       $response.=" <tr class=\"bottom_button\">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Във вашата кошница няма продукти</td>
                                    <td></td>
                                    <td></td>
                             </tr>
                             </tbody>";
                }


                return new Response($response);
            }


            return new Response("yes");
        }
    }



}