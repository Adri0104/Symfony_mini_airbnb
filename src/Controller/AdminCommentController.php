<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin.comment.index")
     */
    public function index(CommentRepository $repo)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/edit", name="admin.comment.edit")
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(AdminCommentFormType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', "Le commentaire n°" . $comment->getId() . "a bien été modifié");
        }
        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/delete", name="admin.comment.delete")
     * @param Comment $comment
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $em) : Response
    {
        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', "Le commentaire de°" . $comment->getAuthor()->getFullName() . "a bien été supprimé");
        return $this->redirectToRoute('admin.comment.index');
    }
}
