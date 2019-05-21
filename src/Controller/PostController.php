<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
use App\Services\Notification;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/post", name="post.")
 */

class PostController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(PostRepository $postRepository)
    {
        // toon posts
        // Repository is waar je de entity kan bereiken en kan aanpassen
        $posts = $postRepository->findAll();



        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function create(Request $request, FileUploader $fileUploader, Notification $notification)
    {
        // dit is create

        $post = new Post();

        // accepteert 2 params, 1 voor de formclass zelf en de post object

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $form->getErrors();

        if($form->isSubmitted() && $form->isValid()){
            // entity manager is de koppeling naar db, zelfde als listview en adapter in Andriod
        $em = $this->getDoctrine()->getManager();
        /** @var  UploadedFile $file */

        //post is een array, access de attachment daarin

        $file =$request->files->get('post')['attachment'];

        if($file){
            // als er geen file is kunnen we niet flushen
            $filename = $fileUploader->uploadFile($file);
            $post->setImage($filename);

            $em->persist($post);
            $em->flush();
        }
            return $this->redirect($this->generateUrl('post.index'));

        }


        // In twig.yaml kan ik form_theme aanpassen naar bootstrap dan hoef je elke keer form-group te doen
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     * @param $id
     * @param PostRepository $postRepository
     * @return Response
     */

    public function show($id, PostRepository $postRepository){
        // wat ook kan is Post meegeven in de params like, Post $post
        $post = $postRepository->find($id);

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param $id
     * @param PostRepository $postRepository
     * @return Response
     */

    public function delete($id, PostRepository $postRepository) : Response{
        $post = $postRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        // flash messages bij action

        $this->addFlash('success', "Post is deleted");

        return $this->redirect($this->generateUrl('post.index'));


    }



}
