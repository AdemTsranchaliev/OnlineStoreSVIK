<?php

namespace SoftUniBlogBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Cache;
use phpDocumentor\Reflection\Types\Array_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use function Sodium\add;
use SoftUniBlogBundle\Entity\Models;
use SoftUniBlogBundle\Entity\Orders;
use SoftUniBlogBundle\Form\Model;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class HomeController extends Controller
{
    /**
     * @Route("/", name="blog_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        try {
            $products = $this->getDoctrine()->getRepository(Models::class)->findAll();
            $bestSellers = Array();
            $lastOnes = Array();
            while (count($bestSellers) != 8) {
                $max = 0;
                $shoes = new Models();
                foreach ($products as $pr) {
                    $num = $pr->getBoughtCounter();
                    if (intval($num) >= $max) {
                        $max = count($pr);
                        $shoes = $pr;
                    }
                }
                array_push($bestSellers, $shoes);
                unset($products[array_search($shoes, $products)]);
            }
            $products = $this->getDoctrine()->getRepository(Models::class)->findAll();
            while (count($lastOnes) != 8) {
                $max = 0;
                $shoes = new Models();
                foreach ($products as $pr) {
                    $num = $pr->getId();
                    if (intval($num) >= $max) {
                        $max = count($pr);
                        $shoes = $pr;
                    }
                }
                array_push($lastOnes, $shoes);
                unset($products[array_search($shoes, $products)]);
            }
            return $this->render('blog/index.html.twig', ['lastOnes' => $lastOnes, 'bestSellers' => $bestSellers]);
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Route("/singleProduct/{id}", name="singleProduct")
     * @param $id
     *
     */
    public function singleProduct($id)
    {
        try {
            $product = $this->getDoctrine()->getRepository(Models::class)->find($id);
            if ($product === null) {
                return $this->render('commonFiles/404.html.twig');
            }
            $sizeAndNumber = $product->getSize();
            $sizeAndNumber = explode(" ", $sizeAndNumber);
            $sizeAndNumber = array_filter(array_map('trim', $sizeAndNumber));
            $size = Array();
            $similar = Array();
            foreach ($sizeAndNumber as $item) {
                $test = explode('-', $item);
                if ($test[1] != 0) {
                    array_push($size, $test[0]);
                }
            }
            $allModels = $this->getDoctrine()->getRepository(Models::class)->findAll();
            foreach ($allModels as $item) {
                if (strcmp($item->getCategory(), $product->getCategory()) === 0 && $item->getId() != $product->getId()) {
                    array_push($similar, $item);
                }
                if (count($similar) == 6) {
                    break;
                }
            }
            if (count($similar) < 6) {
                if (strpos($product->getCategory(), "дамски") !== false) {
                    foreach ($allModels as $item) {
                        if (strpos($item->getCategory(), "дамски") !== false && !in_array($item, $similar)) {
                            array_push($similar, $item);
                        }
                        if (count($similar) == 6) {
                            break;
                        }
                    }
                } else if (strpos($product->getCategory(), "мъжки") === true) {
                    foreach ($allModels as $item) {
                        if (strpos($item->getCategory(), "мъжки") !== false && !in_array($item, $similar)) {
                            array_push($similar, $item);
                        }
                        if (count($similar) == 6) {
                            break;
                        }
                    }
                }
            }
            if (count($similar) < 6) {
                foreach ($allModels as $item) {
                    if (!in_array($item, $similar) && $item->getId() != $product->getId()) {
                        array_push($similar, $item);
                    }
                    if (count($similar) == 6) {
                        break;
                    }
                }
            }
            if (isset($_POST['size__select'])) {
                $s = $_POST['size__select'];
                return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s]);
            }
            return $this->render('user/viewModel.html.twig', ['product' => $product, 'size' => $size, 'similar' => $similar]);
        } catch (\NotFoundHttpException $e) {
            return $this->render('commonFiles/404.html.twig');
        } catch (\ResourceNotFoundException $e) {
            return $this->render('commonFiles/404.html.twig');
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Route("/infoForm/{id}", name="buy")
     * @param $id
     * @param $number
     */
    public
    function buy($id)
    {
        try {
            $product = $this->getDoctrine()->getRepository(Models::class)->find($id);
            if ($product === null) {
                return $this->render("commonFiles/404.html.twig");
            }
            $price = $product->getPrice();
            if (isset($_POST['firstName'])) {
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $wayToDeliver = $_POST['wayToDelivery'];
                $townAdress = $_POST['townAdress'];
                $postalCode = $_POST['postCode'];
                $adress = $_POST['adress'];
                $townOfEkontOffice = $_POST['townOfEkontOffice'];
                $ekontOffice = $_POST['ekontOffice'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $comment = $_POST['comment'];
                $modelNumber = $_POST['modelNumber'];
                $modelColor = $_POST['modelColor'];
                $modelTitle = $_POST['modelTitle'];
                $modelPrice = $_POST['modelPrice'];
                $modelSize = $_POST['modelSize'];

                $order = new Orders();

                $order->setName($firstName);
                $order->setSurname($lastName);
                if (strcmp($wayToDeliver, "Доставка с куриер до адрес") == 0) {
                    $order->setPopulatedPlace($townAdress);
                    $order->setPostalCode($postalCode);
                    $order->setAdress($adress);
                } else if (strcmp($wayToDeliver, "Вземане лично от офис на куриер") == 0) {
                    $order->setPopulatedPlace($townOfEkontOffice);
                    $order->setEcontOffice($ekontOffice);
                }
                $order->setPhone($phone);
                $order->setEmail($email);
                $order->setAddedInfo($comment);
                $order->setModelNumber($modelNumber);
                $order->setModelId($id);
                $order->setPriceOrdered($modelPrice);
                $order->setModelNumberOrdered($modelSize);
                $order->setNewOrArchived(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();
                $model = $this->getDoctrine()->getRepository(Models::class)->find($id);
                $count = $model->getBoughtCounter();
                $count = intval($count) + 1;
                $model->setBoughtCounter($count);
                $emc = $this->getDoctrine()->getManager();
                $emc->persist($model);
                $emc->flush();
                return $this->render('user/succesfulOrder.html.twig');
            }
            return $this->render('user/buyProduct.html.twig', ['price' => intval($price), 'product' => $product]);
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Route("/contactForm", name="contactForm")
     */
    public
    function contactForm()
    {
        try {
            if (isset($_POST['name'])) {
                $to = "obuvkisvik@abv.bg";
                $subject = $_POST['subject'];
                $from = $_POST['name'];
                $email = $_POST['email'];
                $txt = 'Message from' . $from . '  Email' . $email . ' Message' . $_POST['message'];
                $headers = "From: " . $email . "\r\n";
                mail($to, $subject, $txt, $headers);
                return $this->render('commonFiles/contactForm.html.twig');
            }
            return $this->render('commonFiles/contactForm.html.twig');
        } catch (\Exception $e) {
            return $this->render('commonFiles/404.html.twig');
        }
    }

    /**
     * @Route("/about", name="about")
     */
    public
    function about()
    {
        return $this->render('commonFiles/about.html.twig');
    }

    /**
     * @Route("/termsAndConditions", name="termsAndConditions")
     */
    public
    function termsAndConditions()
    {
        return $this->render('commonFiles/termsAndConditions.html.twig');
    }
    
}
