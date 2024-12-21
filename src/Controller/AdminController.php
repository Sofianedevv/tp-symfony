<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\Chapter;
use App\Entity\Exercice;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\SubjectType;
use App\Form\ChapterType;
use App\Form\ExerciceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\NotificationService;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    #[Route('/', name: 'app_admin_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $subjects = $entityManager->getRepository(Subject::class)->findAll();
        
        return $this->render('admin/index.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    #[Route('/subject/new', name: 'app_admin_subject_new')]
    public function newSubject(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subject);
            $entityManager->flush();

            $this->notificationService->notifyAllUsers(
                "Nouvelle matière disponible : " . $subject->getName(),
                'new_subject',
                $this->generateUrl('app_subject_show', ['id' => $subject->getId()])
            );

            $this->addFlash('success', 'Matière ajoutée avec succès');
            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/subject/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subject/{id}/edit', name: 'app_admin_subject_edit')]
    public function editSubject(Subject $subject, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Matière modifiée avec succès');
            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/subject/edit.html.twig', [
            'form' => $form->createView(),
            'subject' => $subject,
        ]);
    }

    #[Route('/chapter/new/{subject_id}', name: 'app_admin_chapter_new')]
    public function newChapter(Request $request, EntityManagerInterface $entityManager, int $subject_id): Response
    {
        $subject = $entityManager->getRepository(Subject::class)->find($subject_id);
        
        $chapter = new Chapter();
        $chapter->setSubject($subject);
        
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chapter);
            $entityManager->flush();

            $this->notificationService->notifyAllUsers(
                "Nouveau chapitre disponible : " . $chapter->getTitle() . " dans " . $subject->getName(),
                'new_chapter',
                $this->generateUrl('app_subject_show', ['id' => $subject->getId()])
            );

            $this->addFlash('success', 'Chapitre ajouté avec succès');
            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/chapter/new.html.twig', [
            'form' => $form->createView(),
            'subject' => $subject,
        ]);
    }

    #[Route('/exercice/new/{chapter_id}', name: 'app_admin_exercice_new')]
    public function newExercice(Request $request, EntityManagerInterface $entityManager, int $chapter_id): Response
    {
        $chapter = $entityManager->getRepository(Chapter::class)->find($chapter_id);
        
        $exercice = new Exercice();
        $exercice->setChapter($chapter);
        
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($exercice);
            $entityManager->flush();

            $this->notificationService->notifyAllUsers(
                "Nouvel exercice disponible : " . $exercice->getTitle() . " dans " . $chapter->getTitle(),
                'new_exercise',
                $this->generateUrl('app_chapter_show', ['id' => $chapter->getId()])
            );

            $this->addFlash('success', 'Exercice ajouté avec succès');
            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/exercice/new.html.twig', [
            'form' => $form->createView(),
            'chapter' => $chapter,
        ]);
    }

    #[Route('/comment/delete/{id}', name: 'app_admin_comment_delete')]
    public function deleteComment(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $exercice = $comment->getExercice();
        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Commentaire supprimé avec succès');
        return $this->redirectToRoute('app_exercice_show', ['id' => $exercice->getId()]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}/ban', name: 'app_admin_user_ban')]
    public function banUser(User $user, EntityManagerInterface $entityManager): Response
    {
        // Empêcher de bannir un admin
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('error', 'Impossible de bannir un administrateur');
            return $this->redirectToRoute('app_admin_users');
        }

        // Basculer entre banni et non banni
        if (in_array('ROLE_BANNED', $user->getRoles())) {
            $user->setRoles(['ROLE_USER']);
            $message = 'Utilisateur débanni avec succès';
        } else {
            $user->setRoles(['ROLE_BANNED']);
            $message = 'Utilisateur banni avec succès';
        }

        $entityManager->flush();
        $this->addFlash('success', $message);

        return $this->redirectToRoute('app_admin_users');
    }
} 