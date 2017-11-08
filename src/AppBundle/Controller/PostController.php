<?php
/**
 * Created by PhpStorm.
 * User: Brent
 * Date: 6-11-2017
 * Time: 14:04
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Blog\Post;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class PostController extends Controller
{
    /**
     * @Route("/post", name="Create new post")
     */
    public function showPostForm(Request $request){
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Save Post'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $post = $form->getData();
            $post->setCreateDate('now');
            $manager->persist($post);
            $manager->flush();
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('post/basic_form_post.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/post/delete/{id}", name="Delete post")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePost($id) {
        $manager = $this->getDoctrine()->getManager();
        $post = $manager->getRepository(Post::class)->find($id);
        if($post !== null) {
            $manager->remove($post);
            $manager->flush();
        }
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/post/edit/{id}", name="Edit post")
     */
    public function editPost($id, Request $request){
        $manager = $this->getDoctrine()->getManager();
        $post = $manager->getRepository(Post::class)->find($id);

        if($post === null){
            return $this->redirect($this->generateUrl('homepage'));
        }

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Edit Post'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post = $form->getData();
            $manager->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('post/basic_form_post.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}