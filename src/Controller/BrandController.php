<?php

/*
 * Copyright 2016-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Controller;

use App\Entity\Brand;
use App\Form\Type\BrandType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class brand controller.
 *
 * @Route("/brand")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("/",  name="brand_index", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        $list = $this->listAction();

        return $this->render('brand/index.html.twig', [
            'list' => $list,
            'query' => $request->get('q'),
        ]);
    }

    /**
     * @Route("/list",  condition="request.isXmlHttpRequest()", name="brand_index_ajax", methods={"GET"})
     */
    public function listAction()
    {
        $results = $this->get('App\Utils\IndexFilter')->filter(Brand::class, true, true);

        return $this->render('brand/_list.html.twig', [
            'results' => $results,
        ]);
    }

    /**
     * @Route("/add", name="brand_add", methods={"GET", "POST"})
     * @Security("user.isInGroup() or is_granted('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'data-btn-group' => 'btn-group',
                    'data-btn-position' => 'btn-first',
                ],
            ])
            ->add('saveAndAdd', SubmitType::class, [
                'label' => 'Save & Add',
                'attr' => [
                    'data-btn-group' => 'btn-group',
                    'data-btn-position' => 'btn-last',
                ],
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush();

            $this->addFlash('success', 'The brand has been added successfully.');

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'brand_add'
                : 'brand_index';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('brand/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add-ajax", name="brand_embded_add", condition="request.isXmlHttpRequest()", methods={"POST"})
     * @Security("user.isInGroup() or is_granted('ROLE_ADMIN')")
     */
    public function embdedAddAction(Request $request)
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand, [
            'action' => $this->generateUrl('brand_embded_add'),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush();
            // return a json response with the new type
            return new JsonResponse([
                'success' => true,
                'id' => $brand->getId(),
                'name' => $brand->getName(),
            ]);
        }

        return $this->render('brand/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="brand_edit", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Brand $brand, Request $request)
    {
        $form = $this->createForm(BrandType::class, $brand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The brand has been edited successfully.');

            return $this->redirectToRoute('brand_index');
        }

        return $this->render('brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/delete", name="brand_delete", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(Brand $brand, Request $request)
    {
        // If the brand is used by a product, redirect user
        if (!$brand->getProducts()->isEmpty()) {
            $this->addFlash('warning', 'The brand cannot be deleted, it\'s used by product(s).');

            return $this->redirectToRoute('brand_index');
        }

        // If the CSRF token is invalid, redirect user
        if (!$this->isCsrfTokenValid('brand_delete', $request->get('token'))) {
            $this->addFlash('warning', 'The CSRF token is invalid.');

            return $this->redirectToRoute('brand_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($brand);
        $em->flush();

        $this->addFlash('success', 'The brand has been deleted successfully.');

        return $this->redirectToRoute('brand_index');
    }
}
