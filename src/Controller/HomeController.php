<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {

        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(array("isDeleted"=>0));
        $bestSellers = Array();
        $lastOnes = Array();

        for ($i = 0; $i < count($products); $i++) {
            for ($j = 0; $j < count($products) - $i - 1; $j++) {
                if ($products[$j]->getBoughtCounter() < $products[$j + 1]->getBoughtCounter()) {
                    $temp = $products[$j];
                    $products[$j] = $products[$j + 1];
                    $products[$j + 1] = $temp;
                }
            }
        }

        for ($i = 0; $i < 3; $i++) {
            array_push($lastOnes, $products[$i]);
        }

        for ($i = 0; $i < count($products); $i++) {
            for ($j = 0; $j < count($products) - $i - 1; $j++) {
                if ($products[$j]->getId() < $products[$j + 1]->getId()) {
                    $temp = $products[$j];
                    $products[$j] = $products[$j + 1];
                    $products[$j + 1] = $temp;
                }
            }
        }
        $cont=array(21,22,23,24,25,26);
        for ($i = 0; $i < count($products); $i++) {
            if (in_array($products[$i]->getId(),$cont))
            {
                array_push($bestSellers, $products[$i]);

            }
        }


        return $this->render('home/index.html.twig', ['lastOnes' => $lastOnes, 'bestSellers' => $bestSellers, 'productsCart' => null]);

    }

    /**
     * @Route("/singleProduct/{id}", name="singleProduct")
     * @param $id
     */
    public function singleProduct($id)
    {

        if (!isset($_COOKIE['_SC_KO'])) {
            setcookie('_SC_KO', bin2hex(random_bytes(10)), time() + (86400 * 30), '/');

        }
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product === null||$product->getIsDeleted()==1) {
            return $this->redirectToRoute('404');
        }
        if (isset($_POST['size__select'])||isset($_POST['noSize'])) {
            $s='';
            if (isset($_POST['size__select']))
            {
                $s = $_POST['size__select'];

            }
            $user = $this->getUser();
            if ($user == null) {
                $user = new User();
            }
            if (isset($_COOKIE['_SC_KO'])) {
                $cookie = $_COOKIE['_SC_KO'];

                $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


                if ($shoppingCart != null) {
                    return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s, 'user' => $user, 'productsCart' => $shoppingCart]);
                }

            }
            return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s, 'user' => $user, 'productsCart' => null]);
        }
        $sizeAndNumber = $product->getSizes();
        $sizeAndNumber = explode(" ", $sizeAndNumber);
        $sizeAndNumber = array_filter(array_map('trim', $sizeAndNumber));
        $size = Array();

        foreach ($sizeAndNumber as $item) {
            $test = explode('-', $item);
            if ($test[1] != 0) {
                array_push($size, $test[0]);
            }
        }




        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {
                return $this->render('home/singleProduct.html.twig', ['product' => $product, 'size' => $size, 'productsCart' => $shoppingCart]);
            }

        }
        return $this->render('home/singleProduct.html.twig', ['product' => $product, 'size' => $size, 'productsCart' => null]);
    }

    /**
     * @param $categoryName
     *
     * @Route("/catalog/{categoryName}", name="catalog")
     */
    public function catalog($categoryName)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $category = new Category();
        foreach ($categories as $value) {
            if (strcmp($categoryName, $value->getTag()) == 0) {
                $category = $value;
                break;
            }
        }

        $product=Array();
        $catName="";

        if(strcmp($categoryName,"newest")==0)
        {
            $catName="Най-нови";
            $allProducts=$this->getDoctrine()->getRepository(Product::class)->findAll();
            for ($i=0;$i<count($allProducts);$i++)
            {
                if ($allProducts[$i]->getIsNew()==1)
                {
                    array_push($product,$allProducts[$i]);
                }
            }
        }
        else {
            if ($category->getId() == null) {
                return $this->redirectToRoute('404');
            }
            $catName= $category->getName();
            for ($i = 0; $i < count($category->getProducts()); $i++) {
                if ($category->getProducts()[$i]->getIsDeleted() == 0) {
                    array_push($product, $category->getProducts()[$i]);
                }
            }
        }

        return $this->render('home/catalog.html.twig', ['products' => $product, 'category' => $catName, 'allCategories' => $categories, 'productsCart' => null]);
    }
    /**
     * @Route("/search", name="search")
     */
    public function search()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $findedProducts = Array();
        $products = Array();
        $category='Няма намерени резултати';
        if (isset($_POST['forSearch'])) {

            $search = $_POST['forSearch'];
            $category=$search;
            $products = $this->getDoctrine()->getRepository(Product::class)->findBy(array('title'=>$search,"isDeleted"=>0));
        }


        return $this->render('home/search.html.twig', ['productsCart' => null, 'products' => $products, 'allCategories' => $categories,'category'=>$category]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {

        return $this->render('home/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {

        return $this->render('home/contact.html.twig');
    }
    /**
     * @Route("/privacyPolicy", name="privacyPolicy")
     */
    public function privacyPolicy()
    {
        return $this->render('home/privacyPolicy.html.twig');
    }
    /**
     * @Route("/coockiesPolicy", name="coockiesPolicy")
     */
    public function coockiesPolicy()
    {
        return $this->render('home/cookiesPolicy.html.twig');
    }
    /**
     * @Route("/commonPolicy", name="commonPolicy")
     */
    public function commonPolicy()
    {
        return $this->render('home/policyCommon.html.twig');
    }

}