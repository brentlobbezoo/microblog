<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Blog\Post;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(array(), array('create_date' => 'DESC'));

        if(!$posts){
            $this->generatePost();
            $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        }

        $last = end($posts);

        return $this->render('default/index.html.twig', [
            'posts' => $posts,
            'last' => $last
        ]);
    }

    private function generatePost(){
        $manager = $this->getDoctrine()->getManager();

        $post = new Post();
        $post->setTitle('Lorem ipsum');
        $post->setContent('dolor sit amet, consectetur adipiscing elit. Donec hendrerit, sapien in vulputate hendrerit, nunc mauris condimentum est, nec varius ex augue et massa. Curabitur imperdiet est in sem euismod suscipit. Vestibulum lobortis tempor mauris, ut tempor elit posuere et. Mauris vel nibh sit amet leo consequat luctus sed id justo. Praesent sit amet tortor interdum, ullamcorper odio sit amet, volutpat neque. In sed nisl eget turpis vulputate gravida pharetra mattis lorem. Etiam dictum elit sit amet lacinia semper. Etiam bibendum mi sed condimentum porttitor. Donec augue purus, pellentesque vitae tincidunt sit amet, semper nec massa. In aliquet nulla quam, a imperdiet erat varius facilisis. Curabitur sit amet dignissim ligula, ut facilisis odio. Cras ut tristique nunc. Donec vitae mi commodo, aliquet risus quis, commodo felis.');
        $post->setCreateDate('now');

        $manager->persist($post);
        $manager->flush();
    }
}
