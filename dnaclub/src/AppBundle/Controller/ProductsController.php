<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductForm;

class ProductsController extends Controller
{
    /**
     * @Route("/products", name="productsList")
     */
    public function productsListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $product = new Product();
        $newProductForm = $this->createForm(new ProductForm(), $product);
        $newProductForm->handleRequest($request);

        if ($newProductForm->isSubmitted() && $newProductForm->isValid())
        {
            $newProduct = $newProductForm->getData();
            $em->persist($newProduct);
            $em->flush();
        }

        $products = $em->getRepository('AppBundle:Product')->getActiveProducts();

        return $this->render('products/products_list.html.twig', ['products' => $products, 'form' => $newProductForm->createView()]);
    }

    /**
     * @Route("/product/delete/{productId}", name="deleteProduct")
     */
    public function deleteProductAction(Request $request, $productId)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($productId);

        if ($product)
        {
            $product->setIsDeleted(true);
            $em->merge($product);
            $em->flush();
        }

        return $this->redirectToRoute('productsList');
    }

    /**
     * @Route("/product/edit/{productId}", name="editProduct")
     */
    public function editProductAction(Request $request, $productId)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($productId);

        $productForm = $this->createForm(new ProductForm(), $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid())
        {
            $product = $productForm->getData();
            $em->merge($product);
            $em->flush();
            return $this->redirectToRoute('productsList');
        }

        return $this->render('products/product_form.html.twig', ['form' => $productForm->createView()]);
    }
}