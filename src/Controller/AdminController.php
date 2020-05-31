<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\ShoppingCart;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product;
use App\Form\Products;
use App\Repository\ProductRepository;

class AdminController extends AbstractController
{

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted("ROLE_ADMIN")
     * @Route("/addProduct", name="addProduct")
     */
    public function addProduct(Request $request)
    {
        $user = $this->getUser();
        $product = new Product();
        $form = $this->createForm(Products::class, $product);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
            foreach ($products as $prd) {
                if ($prd->getModelNumber() == $product->getModelNumber()) {
                    return $this->redirectToRoute('profile');
                }
            }
            $product->setIsNew(1);
            $product->setBoughtCounter(0);

            $img1 = "C:/Users/Asus/Desktop/SVIKSHOES/my_project/public/assets/img/uploads/";

            $img = 0;
            for ($i = 0; $i < count($_FILES['file']['tmp_name']); $i++) {
                if ($_FILES['file']['tmp_name'][$i] != '') {
                    $img++;
                }
            }
            $product->setPhotoCount($img);
            $product->setIsDeleted(0);
            $product->setDiscountPrice(0);





            $op=new Category();
            foreach ($categories as $value)
            {
                if (strcmp($product->getCategory(), $value->getTag()) == 0)
                {
                    $op=$value;
                    break;
                }
            }
            $product->setCategoryR($op);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $img = 0;
            for ($i = 0; $i < count($_FILES['file']['tmp_name']); $i++) {
                $targetfile = $img1 . $product->getId() . "." . $i . ".jpg";
                if ($_FILES['file']['tmp_name'][$i] != '') {
                    move_uploaded_file($_FILES['file']['tmp_name'][$img], $targetfile);
                    $img++;
                }
            }
          // if (isset($_COOKIE['_SC_KO']))
          // {
          //     $cookie=$_COOKIE['_SC_KO'];

          //     $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


          //     if ($shoppingCart!=null)
          //     {
          //         return $this->render("admin/addModel.html.twig", ['user' => $user,'productsCart'=>$shoppingCart,'categories'=>$categories]);
          //     }

          // }
            return $this->render("admin/addProduct.html.twig", ['user' => $user,'productsCart'=>null,'categories'=>$categories]);
        }
     // if (isset($_COOKIE['_SC_KO']))
     // {
     //     $cookie=$_COOKIE['_SC_KO'];

     //     $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


     //     if ($shoppingCart!=null)
     //     {
     //         return $this->render("admin/addModel.html.twig", ['user' => $user,'productsCart'=>$shoppingCart,'categories'=>$categories]);
     //     }

     // }//
        return $this->render("admin/addProduct.html.twig", ['user' => null,'productsCart'=>null,'categories'=>$categories]);

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/seeProducts", name="seeProducts")
     */
    public function seeProducts()
    {

        $models = $this->getDoctrine()->getRepository(Product::class)->findAll();
      // if (isset($_COOKIE['_SC_KO']))
      // {
      //     $cookie=$_COOKIE['_SC_KO'];

      //     $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


      //     if ($shoppingCart!=null)
      //     {
      //         return $this->render("admin/adminPanel.html.twig", ['models' => $models,'productsCart'=>$shoppingCart]);
      //     }

      // }
        return $this->render("admin/allProducts.html.twig",  ['models' => $models,'productsCart'=>null]);

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
        $categories=$this->getDoctrine()->getRepository(Category::class)->findAll();
        if ($producttoEdit==null)
        {
            return $this->redirectToRoute('404');
        }

        $product = new Product();
        $form = $this->createForm(Products::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $producttoEdit->setTitle($product->getTitle());
            $producttoEdit->setModelNumber($product->getModelNumber());
            $producttoEdit->setColor($product->getColor());
            $producttoEdit->setPrice($product->getPrice());
            $producttoEdit->setCategory($product->getCategory());
            $producttoEdit->setSizes($product->getSizes());
            $producttoEdit->setDiscountPrice($product->getDiscountPrice());
            $producttoEdit->setIsInPromotion($product->getIsInPromotion());
            $producttoEdit->setDescription($product->getDescription());

            $category = $this->getDoctrine()->getRepository(Category::class)-> findOneBy(array('tag' => $product->getCategory()));

            $producttoEdit->setCategoryR($category);
            $em = $this->getDoctrine()->getManager();
            $em->persist($producttoEdit);
            $em->flush();
            return $this->redirectToRoute("seeProducts");
        };
        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render("admin/editProduct.html.twig", ['producttoEdit' => $producttoEdit,'productsCart'=>$shoppingCart,'categories'=>$categories]);
            }

        }
        return $this->render("admin/editProduct.html.twig", ['producttoEdit' => $producttoEdit,'productsCart'=>null,'categories'=>$categories]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted("ROLE_ADMIN")
     * @Route("/seeOrders/{func}", name="seeOrders")
     * @param $func
     */
    public function seeOrders(Request $request,$func)
    {

        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        $newOrders = Array();
        $title='';
        if (strcmp($func,'all')==0)
        {
            $newOrders=$orders;
            $title='ВСИЧКИ ПОРЪЧКИ';
        }
        if (strcmp($func,'archived')==0)
        {
            foreach ($orders as $order) {
                if ($order->getNewOrArchived() === true) {
                    array_push($newOrders, $order);
                }
            }
            $title='ИЗПЪЛНЕНИ ПОРЪЧКИ';
        }
        if (strcmp($func,'new')==0)
        {
            foreach ($orders as $order) {
                if ($order->getConfirmed() === false) {
                    array_push($newOrders, $order);
                }
            }
            $title='НОВИ ПОРЪЧКИ';
        }
        if (strcmp($func,'confirmed')==0)
        {
            foreach ($orders as $order) {
                if ($order->getConfirmed() === true&&$order->getNewOrArchived() === false) {
                    array_push($newOrders, $order);
                }
            }
            $title='ПОТВЪРДЕНИ ПОРЪЧКИ';
        }


       //if (isset($_COOKIE['_SC_KO']))
       //{
       //    $cookie=$_COOKIE['_SC_KO'];

       //    $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


       //    if ($shoppingCart!=null)
       //    {
       //        return $this->render("admin/seeOrders.html.twig", ['orders' => $newOrders,'title'=>$title,'productsCart'=>$shoppingCart]);
       //    }

       //}
        return $this->render("admin/seeOrders.html.twig", ['orders' => $newOrders,'title'=>$title,'productsCart'=>null]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOrderAdmin/{id}", name="seeOrderAdmin")
     * @param $id
     */
    public function seeOrderAdmin(Request $request, $id)
    {

        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);


        if ($order==null)
        {
            return $this->redirectToRoute('404');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if($order->getConfirmed()==0)
            {
                $order->setConfirmed(true);
            }
            else
            {
                $order->setNewOrArchived(true);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

        }

        $shoppingCart2=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$order->getCoocieId()));

       if (isset($_COOKIE['_SC_KO']))
       {
           $cookie=$_COOKIE['_SC_KO'];

           $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


           if ($shoppingCart!=null)
           {
               return $this->render("admin/seeOrder.html.twig", ['order' => $order,'productsCart'=>$shoppingCart,'shoppingCart'=>$shoppingCart2]);

           }

       }
        return $this->render("admin/seeOrder.html.twig", ['order' => $order,'shoppingCart'=>$shoppingCart2,'productsCart'=>null]);

    }

}