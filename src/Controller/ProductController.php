<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints\IsTrue;


class ProductController extends Controller {

/**
* @Route("/product", name="product_index")
*/ 

 public function index()
    {
 
  $repository = $this
    ->getDoctrine()
    ->getRepository(Product::class); 

  $products = $repository->findAll();

  return  $this->render('designation/index.html.twig' ,[
  'list_products' => $products

]);
    }


  /**
     * @Route("/product/create", name="product_create")
     */

  public function create(Request $request){

    $product = new Product();

    $form = $this  
      ->createFormBuilder($product)
      ->add('designation', Type\TextType::class)
      ->add('reference', Type\TextType::class)
      ->add('brand', Type\TextType::class)
      ->add('price', Type\NumberType::class)
      ->add('stock', Type\TextType::class)
      ->add('description', Type\TextType::class)
      ->add('active', Type\CheckboxType::class)
      ->add('submit', Type\SubmitType::class)
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) { 


      $em = $this->getDoctrine()->getManager();

      $em->persist($product);
      $em->flush();

      return $this->redirectToRoute('product_index', ['id' => $product->getId() ]);

    }

    return $this->render('designation/create.html.twig', [ 'form' => $form->createView() ]);

  }




  /**
     * @Route("/product/{id}/show", name="product_show")
     */


  public function show($id)
  {

    $repository = $this
    ->getDoctrine()
    ->getRepository(Product::class); 

    $product = $repository->find($id);

    if (null === $product) {
    throw $this->createNotFoundException("produit introuvable");
    }

    return  $this->render('designation/show.html.twig' ,[
    'product' => $product 
    ]);

  }


/**
     * @Route("/product/{id}/update", name="product_update")
     */


    public function update(Request $request)
    {

      $repository = $this
    ->getDoctrine()
    ->getRepository(Product::class); 

    $product = $repository->find($request->attributes->get('id'));
   
    $form = $this  
      ->createFormBuilder($product)
      ->add('designation', Type\TextType::class)
      ->add('reference', Type\TextType::class)
      ->add('brand', Type\TextType::class)
      ->add('price', Type\NumberType::class)
      ->add('stock', Type\TextType::class)
      ->add('description', Type\TextType::class)
      ->add('active', Type\CheckboxType::class)
      ->add('submit', Type\SubmitType::class)
      ->getForm();



    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) { 


    $em = $this->getDoctrine()->getManager();

    $em->persist($product);
    $em->flush();

    return $this->redirectToRoute('product_show', ['id' => $product->getId() ]);

  }

    return $this->render('designation/update.html.twig', [ 'form' => $form->createView()
  ]);


    }

/**
     * @Route("/product/{id}/delete", name="product_delete")
     */

    public function delete(Request $request){

      $repository = $this
    ->getDoctrine()
    ->getRepository(Product::class); 

    $product = $repository->find($request->attributes->get('id'));

    $form = $this
    ->createFormBuilder()
    ->add('confirm', Type\CheckboxType::class, [ 
      'required' => false,
    'constraints' => [ new IsTrue(),]

    ])
    ->add('submit', Type\SubmitType::class)
    ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){

    $em = $this->getDoctrine()->getManager();

    $em->remove($product);
    $em->flush();

    return $this->redirectToRoute('product_index');

    }

    return $this->render('designation/delete.html.twig', [

    'form' => $form->createView()

    ]);

}




}

?>
