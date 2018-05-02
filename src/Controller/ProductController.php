<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/produits")
     */
    public function list(): Response
    {
        // Récupération des produits
        $products = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->findBy([], ["createdAt" => "DESC"])
        ;
        // On retourne la vue en passant les produits
        return $this->render('product/list.html.twig', [
            "products" => $products
        ]);
    }

    /**
     * @Route("/produits/ajout")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        // Récupération du formulaire
        $produit = new Product();
        $form = $this->createForm(ProductType::class, $produit);
        // Vérication du formulaire
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est valide :
            // => on ajoute la catégorie en BDD
            $produit = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute('app_product_list');
        }
        // Sinon on renvoit une vue avec le formulaire
        return $this->render("product/add.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/produits/edition/{id}")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        // Récupération du formulaire
        $produit =  $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id)
        ;
        $form = $this->createForm(ProductType::class, $produit);
        // Vérication du formulaire
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est valide :
            // => on ajoute la catégorie en BDD
            $produit = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirectToRoute('app_product_list');
        }
        // Sinon on renvoit une vue avec le formulaire
        return $this->render("product/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/produits/{id}")
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function show(int $id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findOneWithCategory($id)
        ;
        return $this->render("product/show.html.twig", [
            "product" => $product
        ]);
    }

    /**
     * @Route("/produits/suppression/{id}")
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function delete(int $id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id)
        ;

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('app_product_list');
    }
}