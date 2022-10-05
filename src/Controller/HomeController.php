<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Entity\Category;

use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $trending = $this->filterStatusData('isTrending', $products);
        $bestSellers = $this->filterStatusData('isBestSeller', $products);

        return $this->render('home/index.html.twig', [
            'trending' => $trending, 'bestSellers' => $bestSellers,
            'featuredProduct' => $this->getDoctrine()->getRepository(Product::class)->find(11196)
        ]);
    }

    /**
     * @Route("/singleProduct/{id}", name="singleProduct")
     * @param $id
     */
    public function singleProduct($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $differentColors = $this->getDoctrine()->getRepository(Product::class)->findBy(array('model' => $product->getModel()));
        $colors = array();

        for ($i = 0; $i < count($differentColors); $i++) {
            $decodedJson = json_decode($differentColors[$i]->getPictures());
            $colors[$differentColors[$i]->getId()] = $decodedJson[0];
        }

        $relativesTemp = ($product->getCategoryR()->getProducts());
        $arr = array();

        for ($i = 0; $i < count($relativesTemp); $i++) {
            array_push($arr, $relativesTemp[$i]);
        }

        $relativesTemp = array_reverse($arr);
        $relProducts = array();
        $counter = 0;

        while ($counter < count($relativesTemp) && $counter <= 10) {
            array_push($relProducts, $relativesTemp[$counter]);

            $counter++;
        }

        if ($product === null || $product->getIsDeleted() == 1) {
            return $this->redirectToRoute('404');
        }
        return $this->render(
            'home/singleProduct.html.twig',
            [
                'product' => $product,
                'productsCart' => null,
                'diffColors' => $colors,
                'relProducts' => $relProducts,
                'sizes' => json_decode($product->getSizes(), true),
                'pictures' => json_decode($product->getPictures(), true)
            ]
        );
    }

    /**
     * @param $categoryName
     *
     * @Route("/catalog/{categoryName}/{page}", defaults={"page"=1}, name="catalog")
     */
    public function catalog($categoryName, $page)
    {
        $products = null;
        $category = new Category();

        if ($categoryName == 'sale') {
            $products = $this->getDoctrine()->getRepository(Product::class)->findBy(array('isInPromotion' => true));
            $category->setName('НАМАЛЕНИЕ');
            $category->setTag('sale');
        } else {
            $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(array('tag' => $categoryName));

            $products = $category->getProducts()->toArray();
        }

        $products = array_reverse($products);
        return $this->render('home/catalog.html.twig', ['products' => array_slice($products, ($page - 1) * 12, 12), 'pages' => $this->getPageCount($products, 12), 'currentPage' => $page, 'currentCategory' => $category, 'allProductsCount' => count($products)]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $findedProducts = array();
        $products = array();
        $category = 'Няма намерени резултати';
        if (isset($_POST['forSearch'])) {

            $search = $_POST['forSearch'];
            $category = $search;
            $products = $this->getDoctrine()->getRepository(Product::class)->findBy(array('title' => $search, "isDeleted" => 0));
        }

        return $this->render('home/search.html.twig', ['products' => $products, 'allCategories' => $categories, 'category' => $category]);
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

    private function filterStatusData($filterBy, $products)
    {
        $trending = array();
        for ($i = 0; $i < count($products); $i++) {
            if ((json_decode($products[$i]->getStatuses(), true))[$filterBy]) {
                array_push($trending, $products[$i]);
            }
        }
        return $trending;
    }

    private function getPageCount($array, $countProductsInPage)
    {
        $count = count($array);

        if ($count <= $countProductsInPage) {
            return 1;
        } else {
            $pagesCount = $count / $countProductsInPage;

            if ($count % $countProductsInPage != 0) {
                $pagesCount++;
            }

            return floor($pagesCount);
        }
    }
}
