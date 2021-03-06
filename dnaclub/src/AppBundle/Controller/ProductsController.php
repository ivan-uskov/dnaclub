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
        $newProductForm = $this->createForm(new ProductForm(), $product, array('isNew' => true));
        $newProductForm->handleRequest($request);

        $hideAddForm = true;
        if ($newProductForm->isSubmitted() && $newProductForm->isValid())
        {
            $em->persist($newProductForm->getData());
            $em->flush();
            return $this->redirectToRoute('productsList');
        }
        if ($newProductForm->isSubmitted() && !$newProductForm->isValid())
        {
            $hideAddForm = false;
        }

        $products = $em->getRepository('AppBundle:Product')->getActiveProducts();

        return $this->render('products/products_list.html.twig', [
            'products' => $products,
            'form' => $newProductForm->createView(),
            'hideAddForm' => $hideAddForm
        ]);
    }

    /**
     * @Route("/product/delete/{productId}", name="deleteProduct")
     */
    public function deleteProductAction(Request $request, $productId)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($productId);

        $isUsedInOrders = (bool)$em->getRepository('AppBundle:OrderItem')->findBy(['product' => $product]);

        $flashBag = $this->get('session')->getFlashBag();
        if ($isUsedInOrders)
        {
            $flashBag->add('error', 'Нельзя удалить продукт "'. $product->getName() .'", т.к. он уже используется в покупках');
        }
        else if ($product)
        {
            $product->setIsDeleted(true);
            $em->merge($product);
            $em->flush();
            $flashBag->add('success', 'Продукт "'. $product->getName() .'" удален');
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