<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 30/03/2018
 * Time: 15:12
 */
namespace IT\DispositifBundle\Controller;

use IT\DispositifBundle\Entity\Categories;
use IT\DispositifBundle\Entity\Fruit;
use IT\DispositifBundle\Form\CategoryType;
use IT\DispositifBundle\Form\FruitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;
use IT\DispositifBundle\Entity\Categorie;

/**
 * Class CategoryController
 * @package IT\DispositifBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * @Route("/category/modifier" , name="modifier_category")
     * @Template()
     * @return array
     */
    public function modifier_categoryAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $dataArray=$em->getRepository("ITDispositifBundle:Categorie")->findAll();
        $data = [
            'categories' => $dataArray
        ];

        $form = $this
            ->get('form.factory')
            ->createNamedBuilder('categForm', FormType::class, $data)
            ->add('categories', CollectionType::class, [
                'entry_type'   => CategoryType::class,
                'label'        => 'Liste des catégories.',
                'allow_add'    => true,
                'allow_delete' => false,
                'prototype'    => true,
                'required'     => false,
                'attr'         => [
                    'class' => "categoryForm-collection",
                ],
            ])
            ->add('Envoyer', SubmitType::class, array(
                'attr' => array('class' =>"btn btn-success")))
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            foreach ($data['categories'] as $categ){
                $em->persist($categ);
                $em->flush();
            }
        }

        return [
            'categoryForm'         => $form->createView(),
            "categoryFormData" => $data,
        ];
    }


}