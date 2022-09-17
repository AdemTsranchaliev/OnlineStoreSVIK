<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\User;
use App\Entity\Review;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;

class AjaxController extends AbstractController
{
    

    /**
     * @Route("/ajaxSort")
     */
    public function ajaxAction(Request $request)
    {
        if ($request->isXmlHttpRequest() || $request
            ->query
            ->get('showJson') == 1) {
            $command = $_POST['command'];
            $category = $_POST['categoryName'];

            $arr2 = [];
            $categories = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();

            foreach ($categories as $prd) {
                if (strcmp($category, $prd->getName()) == 0) {
                    $arr2 = $prd->getProducts();
                    break;
                }
            }
            $arr = [];
            for ($i = 0;$i < count($arr2);$i++) {
                if ($arr2[$i]->getIsDeleted() == 0) {
                    array_push($arr, $arr2[$i]);
                }
            }

            if (strcmp($command, "price-low-to-high") == 0) {
                for ($i = 0;$i < count($arr);$i++) {
                    for ($j = 0;$j < count($arr) - $i - 1;$j++) {
                        if ($arr[$j]->getPrice() < $arr[$j + 1]->getPrice()) {
                            $temp = $arr[$j];
                            $arr[$j] = $arr[$j + 1];
                            $arr[$j + 1] = $temp;
                        }
                    }
                }
            }
            if (strcmp($command, "price-high-to-low") == 0) {
                for ($i = 0;$i < count($arr);$i++) {
                    for ($j = 0;$j < count($arr) - $i - 1;$j++) {
                        if ($arr[$j]->getPrice() > $arr[$j + 1]->getPrice()) {
                            $temp = $arr[$j];
                            $arr[$j] = $arr[$j + 1];
                            $arr[$j + 1] = $temp;
                        }
                    }
                }
            }
            if (strcmp($command, "by-popularity") == 0) {
                for ($i = 0;$i < count($arr);$i++) {
                    for ($j = 0;$j < count($arr) - $i - 1;$j++) {
                        if ($arr[$j]->getBoughtCounter() < $arr[$j + 1]->getBoughtCounter()) {
                            $temp = $arr[$j];
                            $arr[$j] = $arr[$j + 1];
                            $arr[$j + 1] = $temp;
                        }
                    }
                }
            }
            if (strcmp($command, "date") == 0) {
                for ($i = 0;$i < count($arr);$i++) {
                    for ($j = 0;$j < count($arr) - $i - 1;$j++) {
                        if ($arr[$j]->getId() < $arr[$j + 1]->getId()) {
                            $temp = $arr[$j];
                            $arr[$j] = $arr[$j + 1];
                            $arr[$j + 1] = $temp;
                        }
                    }
                }
            }

            $ready = '';

            $ready .= " <div class=\"ps-product__columns\" id=\"divSort\">";

            $i = 1;
            foreach ($arr as $prd) {
                $ready .= "<div class=\"ps-product__column\">
                            <div class=\"ps-shoe mb-30\">
                             <div class=\"ps-shoe__thumbnail\">";

                if ($prd->getIsNew() == 1) {
                    $ready .= "<div class=\"ps-badge\"><span>НОВО</span></div>";
                }

                if ($prd->getIsInPromotion() == 1) {
                    $ready .= "<div class=\"ps-badge ps-badge--sale ps-badge--2nd\">";

                    $ready .= "<span>SALE</span> </div>";
                }

                $ready .= "<a class=\"ps-shoe__favorite\" href=\"http://127.0.0.1:8000/singleProduct/" . $prd->getId() . "\"><i class=\"ps-icon-heart\"></i></a><img height=\"250\" src=\"/assets/img/uploads/" . $prd->getId() . ".0.jpg\" alt=\"\"><a class=\"ps-shoe__overlay\" href=\"http://127.0.0.1:8000/singleProduct/" . $prd->getId() . "\"></a></div>";

                $ready .= "
                <div class=\"ps-shoe__content\">
                <div class=\"ps-shoe__variants\">

                </div>
                <div class=\"ps-shoe__detail\"><a class=\"ps-shoe__name\" href=\"http://127.0.0.1:8000/singleProduct/" . $prd->getId() . "\">" . $prd->getTitle() . substr(0, 10) . "</a>
                 
                <span class=\"ps-shoe__price\">
                </br>
                ";

                if ($prd->getIsInPromotion() == 1) {
                    $ready .= number_format($prd->getPrice(), 2) . " лв <del> " . number_format($prd->getDiscountPrice(), 2) . " лв </del>";
                } else {
                    $ready .= number_format($prd->getPrice(), 2) . "лв";
                }

                $ready .= "</span>
                </div>
              </div>
              </div>
            </div>";
            }

            $ready .= "</div></div>";

            return new Response($ready);
        }
    }

    /**
     * @Route("/ajaxAddToCart")
     */
    public function ajaxAddToCart(Request $request)
    {
        if ($request->isXmlHttpRequest() || $request
            ->query
            ->get('showJson') == 1) {
            if (!isset($_COOKIE['_SC_KO'])) {
                setcookie('_SC_KO', bin2hex(random_bytes(10)), time() + (86400 * 30));
            }
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()
                ->getRepository(ShoppingCart::class)
                ->findBy(array(
                'coocieId' => $cookie
            ));

            $id = $_POST['id'];

            $size = $_POST['sizeSelected'];

            $number = $_POST['number'];

            $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($id);

            $response = '';

            if ($shoppingCart == null) {
                $shoppingCart = new ShoppingCart();

                $shoppingCart->addCartProduct($product);
                $shoppingCart->setProductId($id);
                $shoppingCart->setCoocieId($cookie);
                $shoppingCart->setModelSize($size);
                $shoppingCart->setPrice($product->getPrice() * $number);
                $shoppingCart->setQuantity($number);
            } else {
                $shoppingCart = $this->getDoctrine()
                    ->getRepository(ShoppingCart::class)
                    ->findOneBy(array(
                    'coocieId' => $cookie,
                    'modelSize' => $size,
                    'productId' => $product->getId()
                ));
                if ($shoppingCart == null) {
                    $shoppingCart = new ShoppingCart();

                    $shoppingCart->addCartProduct($product);
                    $shoppingCart->setCoocieId($cookie);
                    $shoppingCart->setModelSize($size);
                    $shoppingCart->setPrice($product->getPrice() * $number);
                    $shoppingCart->setQuantity($number);
                    $shoppingCart->setProductId($product->getId());
                    $em = $this->getDoctrine()
                        ->getManager();
                    $em->persist($shoppingCart);
                    $em->flush();
                    $shoppingCart = $this->getDoctrine()
                        ->getRepository(ShoppingCart::class)
                        ->findOneBy(array(
                        'coocieId' => $cookie,
                        'modelSize' => $size,
                        'productId' => $product->getId()
                    ));
                } else {
                    $shoppingCart->setPrice($product->getPrice() * ($shoppingCart->getQuantity() + $number));
                    $shoppingCart->setQuantity($shoppingCart->getQuantity() + $number);
                }
            }

            $em = $this->getDoctrine()
                ->getManager();
            $em->persist($shoppingCart);
            $em->flush();

            $shoppingCart = $this->getDoctrine()
                ->getRepository(ShoppingCart::class)
                ->findBy(array(
                'coocieId' => $cookie
            ));

            $response .= "
                         <div class=\"ps-cart\" id=\"cartProd\"><a class=\"ps-cart__toggle\" href=\"#\"><span><i>" . (count($shoppingCart)) . "</i></span><i class=\"ps-icon-shopping-cart\"></i></a>
                             <div class=\"ps-cart__listing\">
                             <div class=\".overflow-auto\" style=\"overflow : scroll; scrollbar-width: thin; height: 370px\">";

            $total = 0;
            $productsCount = 0;
            foreach ($shoppingCart as $product2) {
                $total += $product2->getPrice();
                $productsCount += $product2->getQuantity();
                $response .= "  <div class=\"ps-cart__content \">
                                 <div class=\"ps-cart-item\"><div class=\"ps-cart-item__close\" onclick=\"deleteProd(" . $product2->getProductId() . ",'" . strval($product2->getModelSize()) . "')\"></div>
                                  <div class=\"ps-cart-item__thumbnail\"><a href=\"http://localhost:8000/singleProduct/" . $product2->getProductId() . "\"></a><img src=\"/assets/img/small/" . strval($product2->getProductId()) . ".0.jpg\" alt=\"\"></div>
                                    <div class=\"ps-cart-item__content\"><a class=\"ps-cart-item__title\" href=\"http://localhost:8000/singleProduct/" . $product2->getProductId() . "\">" . $product2->getCartProduct() [0]
                    ->getTitle() . "</a>
                                         <p><span>Количество:<i>" . strval($product2->getQuantity()) . "</i></span></br><span>Общо:<i>" . strval(number_format($product2->getPrice(), 2)) . "</i></span></p>
                                     </div>
                                 </div>
                                 
                                </div>";
            }

            $response .= " </div><div class=\"ps-cart__total\">
            <p>Брой продукти:<span>" . $productsCount . "</span></p>
            <p>Общо: <span>" . strval(number_format($total, 2)) . " лв</span></p>
             </div>
             <div class=\"ps-cart__footer\"><a class=\"ps-btn\" href=\"/seeShoppingCart\">Виж количка<i class=\"ps-icon-arrow-left\"></i></a></div>
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
        if ($request->isXmlHttpRequest() || $request
            ->query
            ->get('showJson') == 1) {
            $email = $_POST['email'];
            $emailExist = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(array(
                'email' => $email
            ));

            if ($emailExist == null) {
                return new Response("no");
            } else {
                return new Response("yes");
            }
        }
    }

    /**
     * @Route("/sendMail")
     */
    public function sendMail(Request $request)
    {
        if ($request->isXmlHttpRequest() || $request
            ->query
            ->get('showJson') == 1) {
            if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $messagee = $_POST['message'];

                $to = "obuvkisvik@gmail.com";

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
        $id = $_POST['id'];
        $size = $_POST['size'];

        $cookie = $_COOKIE['_SC_KO'];

        $shoppingCart = $this->getDoctrine()
            ->getRepository(ShoppingCart::class)
            ->findOneBy(array(
            'coocieId' => $cookie,
            'productId' => $id,
            'modelSize' => $size
        ));

        if ($shoppingCart != null) {
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->remove($shoppingCart);
            $entityManager->flush();

            $shoppingCart = $this->getDoctrine()
                ->getRepository(ShoppingCart::class)
                ->findBy(array(
                'coocieId' => $cookie
            ));
            $response = '';

            $response .= "
                <div class=\"ps-cart\" id=\"cartProd\"><a class=\"ps-cart__toggle\" href=\"#\"><span><i>" . (count($shoppingCart)) . "</i></span><i class=\"ps-icon-shopping-cart\"></i></a>
                    <div class=\"ps-cart__listing\">
                    <div class=\".overflow-auto\" style=\"overflow : scroll; scrollbar-width: thin; height: 370px\">";

            if (count($shoppingCart) != 0) {
                $total = 0;
                $productsCount = 0;
                foreach ($shoppingCart as $product2) {
                    $total += $product2->getPrice();
                    $productsCount += $product2->getQuantity();
                    $response .= "  <div class=\"ps-cart__content \">
                                     <div class=\"ps-cart-item\"><div class=\"ps-cart-item__close\" onclick=\"deleteProd(" . $product2->getProductId() . ",'" . strval($product2->getModelSize()) . "')\"></div>
                                      <div class=\"ps-cart-item__thumbnail\"><a href=\"http://localhost:8000/singleProduct/" . $product2->getProductId() . "\"></a><img src=\"/assets/img/small/" . strval($product2->getProductId()) . ".0.jpg\" alt=\"\"></div>
                                        <div class=\"ps-cart-item__content\"><a class=\"ps-cart-item__title\" href=\"http://localhost:8000/singleProduct/" . $product2->getProductId() . "\">" . $product2->getCartProduct() [0]
                        ->getTitle() . "</a>
                                             <p><span>Количество:<i>" . strval($product2->getQuantity()) . "</i></span></br><span>Общо:<i>" . strval(number_format($product2->getPrice(), 2)) . "</i></span></p>
                                         </div>
                                     </div>
                                    
                                    </div>";
                }
                $response .= " </div> <div class=\"ps-cart__total\">
                    	            <p>Брой продукти:<span>" . $productsCount . "</span></p>
                    	            <p>Общо: <span>" . strval(number_format($total, 2)) . " лв</span></p>
                    	             </div>
                    	             <div class=\"ps-cart__footer\"><a class=\"ps-btn\" href=\"/seeShoppingCart\">Виж количка<i class=\"ps-icon-arrow-left\"></i></a></div>
                    	            </div>
                    	            </div>
                    	            </div>";
            } else {
                $response .= " <div class=\"ps-cart__total\">
                    <p>Няма продукти в количката</p>
                     </div>                   
                    </div>
                    </div>
                    </div>";
            }

            return new Response($response);
        } else {
            $shoppingCart = $this->getDoctrine()
                ->getRepository(ShoppingCart::class)
                ->findBy(array(
                'coocieId' => $cookie
            ));
            $response = '';

            $response .= "
                <div class=\"ps-cart\" id=\"cartProd\"><a class=\"ps-cart__toggle\" href=\"#\"><span><i>" . (count($shoppingCart)) . "</i></span><i class=\"ps-icon-shopping-cart\"></i></a>
                    <div class=\"ps-cart__listing\">
                    <div class=\".overflow-auto\" style=\"overflow : scroll; scrollbar-width: thin; height: 370px\">";

            if (count($shoppingCart) != 0) {
                $total = 0;
                $productsCount = 0;
                foreach ($shoppingCart as $product2) {
                    $total += $product2->getPrice();
                    $productsCount += $product2->getQuantity();
                    $response .= "  <div class=\"ps-cart__content \">
                                     <div class=\"ps-cart-item\"><div class=\"ps-cart-item__close\" onclick=\"deleteProd(" . $product2->getProductId() . ",'" . strval($product2->getModelSize()) . "')\"></div>
                                      <div class=\"ps-cart-item__thumbnail\"><a href=\"http://localhost:8000/singleProduct/" . $product2->getProductId() . "\"></a><img src=\"/assets/img/small/" . strval($product2->getProductId()) . ".0.jpg\" alt=\"\"></div>
                                        <div class=\"ps-cart-item__content\"><a class=\"ps-cart-item__title\" href=\"http://localhost:8000/singleProduct/" . $product2->getProductId() . "\">" . $product2->getCartProduct() [0]
                        ->getTitle() . "</a>
                                             <p><span>Количество:<i>" . strval($product2->getQuantity()) . "</i></span></br><span>Общо:<i>" . strval(number_format($product2->getPrice(), 2)) . "</i></span></p>
                                         </div>
                                     </div>
                                    
                                    </div>";
                }
                $response .= " </div> <div class=\"ps-cart__total\">
                    	            <p>Брой продукти:<span>" . $productsCount . "</span></p>
                    	            <p>Общо: <span>" . strval(number_format($total, 2)) . " лв</span></p>
                    	             </div>
                    	             <div class=\"ps-cart__footer\"><a class=\"ps-btn\" href=\"seeShoppingCart\">Виж количка<i class=\"ps-icon-arrow-left\"></i></a></div>
                    	            </div>
                    	            </div>
                    	            </div>";
            } else {
                $response .= " <div class=\"ps-cart__total\">
                    <p>Няма продукти в количката</p>
                     </div>                   
                    </div>
                    </div>";
            }

            return new Response($response);
        }

        return new Response("yes");
    }
    /**
     * @Route("/deleteProdCart")
     */
    public function deleteProdCart(Request $request)
    {
        $id = $_POST['id'];
        $size = $_POST['size'];

        $cookie = $_COOKIE['_SC_KO'];

        $shoppingCart = $this->getDoctrine()
            ->getRepository(ShoppingCart::class)
            ->findOneBy(array(
            'coocieId' => $cookie,
            'productId' => $id,
            'modelSize' => $size
        ));

        if ($shoppingCart != null) {
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->remove($shoppingCart);
            $entityManager->flush();

            $shoppingCart2 = $this->getDoctrine()
                ->getRepository(ShoppingCart::class)
                ->findBy(array(
                'coocieId' => $cookie
            ));
            if ($shoppingCart2 != null && count($shoppingCart2) != 0) {
                $response = '  <div class="ps-content pt-80 pb-80" id="toChange">
                <div class="ps-container">
                  <div class="ps-cart-listing">
                    <table class="table ps-cart__table">
                      <thead>
                        <tr>
                          <th>Продукти</th>
                          <th>Размери</th>
                          <th>Ед. цена</th>
                          <th>Количество</th>
                          <th>Общо</th>
                          <th></th>
                        </tr>
                      </thead>
                <tbody>';
                $sum = 0;
                foreach ($shoppingCart2 as $product) {
                    $sum += $product->getQuantity() * $product->getPrice();
                    $response .= "
                      <tr>
                      <td><a class=\"ps-product__preview\" href=\"/singleProduct/" . $product->getProductId() . "\"><img class=\"mr-15\" src=\"/assets/img/uploads/" . $product->getProductId() . ".0.jpg\" height=\"140\" width=\"140\" alt=\"\">" . $product->getCartProduct() [0]
                        ->getTitle() . "</a></td>
                      <td>(" . $product->getModelSize() . ")</td>
                      <td>" . round($product->getPrice(), 2) . " лв</td>
                      <td>
                        <div class=\"form-group--number\">
                          <input class=\"form-control\" type=\"text\" value=\"" . $product->getQuantity() . "\">
                        </div>
                      </td>
                      <td>" . round($product->getQuantity() * $product->getPrice(), 2) . " лв</td>
                      <td>
                        <div class=\"ps-remove\" onclick=\"deleteProdCart(" . $product->getProductId() . ",'" . $product->getModelSize() . "')\"></div>
                      </td>
                    </tr>";
                }
                $response .= '</tbody>
              </table>
			
              <div class="ps-cart__actions">
                <div class="ps-cart__promotion">
                  <div class="form-group">
                   
                  </div>
                  <div class="form-group">
                    <a href="/"><button class="ps-btn ps-btn--gray">Прадължи пазаруването</button></a>
                  </div>
                </div>
                <div class="ps-cart__total">
                  <h3>Общо: <span> ' . strval(round($sum, 2)) . ' лв</span></h3><a class="ps-btn" href="/ordering">Продължи към плъщане<i class="ps-icon-next"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>';
                return new Response($response);
            } else {
                $res = '	
                <div class="ps-content pt-80 pb-80" id="toChange">
                <div class="ps-container">
                    <div class="ps-cart-listing">
                        <table class="table ps-cart__table">
                            <thead>
                                <tr>
                                    <th>КОШНИЦА</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group--number">
                                            <h1>В кошницата няма продукти</h1>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>';
                return new Response($res);
            }
        }

        return new Response("yes");
    }
    /**
     * @Route("/addReview")
     */
    public function addReview(Request $request)
    {
        $user = $this->getUser();

        $model = new Review();

        $model->setRating($_POST["reviewerRating"]);
        $model->setComment($_POST["reviewerComment"]);
        
        $productId = $_POST["productId"];
        if($user!=null){
            $model->setName($user->getName().' '.$user->getSurname());
            $model->setEmail($user->getEmail());
            $model->setUser($user);
        }
        else{
            $model->setName($_POST["name"]);
            $model->setEmail($_POST["email"]);
        }
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($productId);

        $model->setProduct($product);
        $model->setPublishedOn(new \DateTime('NOW'));
        if($product->getAvgRating()==0){
            $product->setAvgRating($_POST["reviewerRating"]);
        }
        else{
            $product->setAvgRating(ceil(($product->getAvgRating() + $_POST["reviewerRating"]) / 2));
        }

        $entityManager = $this->getDoctrine()
            ->getManager();

        $entityManager->persist($model);
        $entityManager->persist($product);

        $entityManager->flush();

        return new Response("yes");
    }
}
