<?php

namespace SoftUniBlogBundle\Controller;

use phpDocumentor\Reflection\Types\Array_;
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

class UserController extends Controller
{
    /**
     * @Route("/manOfficialShoes", name="manOfficialShoes")
     */
    public function manOfficialShoes()
    {
        $allProduct= $this->getDoctrine()->getRepository(Models::class)->findAll();
        $manOfficialShoes=Array();

        foreach ($allProduct as $item)
        {
            if ($item->getCategory()=="мъжки официални обувки")
            {
               array_push($manOfficialShoes,$item);
            }
        }
        return $this->render('user/listingCatalog.html.twig', ['products' => $manOfficialShoes,'category' => 'мъжки официални обувки']);
    }
    /**
     * @Route("/manSportShoes", name="manSportShoes")
     */
    public function manSportShoes()
    {
        $allProduct= $this->getDoctrine()->getRepository(Models::class)->findAll();
        $manSportShoes=Array();

        foreach ($allProduct as $item)
        {
            if ($item->getCategory()=="мъжки спортни обувки")
            {
                array_push($manSportShoes,$item);
            }
        }
        return $this->render('user/listingCatalog.html.twig', ['products' => $manSportShoes,'category' => 'мъжки спортни обувки']);
    }
    /**
     * @Route("/womenSandals", name="womenSandals")
     */
    public function womenSandals()
    {
        $allProduct= $this->getDoctrine()->getRepository(Models::class)->findAll();
        $womenSandals=Array();

        foreach ($allProduct as $item)
        {
            if ($item->getCategory()=="дамски сандали")
            {
                array_push($womenSandals,$item);
            }
        }
        return $this->render('user/listingCatalog.html.twig', ['products' => $womenSandals,'category' => 'дамски сандали']);
    }
    /**
     * @Route("/womenSportShoes", name="womenSportShoes")
     */
    public function womenSportShoes()
    {
        $allProduct= $this->getDoctrine()->getRepository(Models::class)->findAll();
        $womenSportShoes=Array();

        foreach ($allProduct as $item)
        {
            if ($item->getCategory()=="дамски спортни обувки")
            {
                array_push($womenSportShoes,$item);
            }
        }
        return $this->render('user/listingCatalog.html.twig', ['products' => $womenSportShoes,'category' => 'дамски спортни обувки']);
    }
    /**
     * @Route("/womenEverydayShoes", name="womenEverydayShoes")
     */
    public function womenEverydayShoes()
    {
        $allProduct= $this->getDoctrine()->getRepository(Models::class)->findAll();
        $womenEverydayShoes=Array();

        foreach ($allProduct as $item)
        {
            if ($item->getCategory()=="дамски ежедневни обувки")
            {
                array_push($womenEverydayShoes,$item);
            }
        }
        return $this->render('user/listingCatalog.html.twig', ['products' => $womenEverydayShoes,'category' => 'дамски ежедневни обувки']);
    }
    /**
     * @Route("/womenBoots", name="womenBoots")
     */
    public function womenBoots()
    {
        $allProduct= $this->getDoctrine()->getRepository(Models::class)->findAll();
        $womenBoots=Array();

        foreach ($allProduct as $item)
        {
            if ($item->getCategory()=="дамски боти")
            {
                array_push($womenBoots,$item);
            }
        }
        return $this->render('user/listingCatalog.html.twig', ['products' => $womenBoots,'category' => 'дамски боти']);
    }
    /**
     * @Route("/womenBBoots", name="womenBBoots")
     */
    public function womenBBoots()
    {
        $allProduct= $this->getDoctrine()->getRepository(Models::class)->findAll();
        $womenBBoots=Array();

        foreach ($allProduct as $item)
        {
            if ($item->getCategory()=="дамски ботуши")
            {
                array_push($womenBBoots,$item);
            }
        }
        return $this->render('user/listingCatalog.html.twig', ['products' => $womenBBoots,'category' => 'дамски ботуши']);
    }

}
