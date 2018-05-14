<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Form\PersonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Person controller
 *
 * @Route("person")
 */
class PersonController extends Controller
{
    /**
     *
     *
     * @Route("/", name="person_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository(Person::class)->findAll();

        // replace this example code with whatever you need
        return $this->render('person/index.html.twig', [
            'persons' => $persons,
            ]);
    }

    /**
     * Afficher un seul personnage et son royaume
     *
     * @Route("/{id}", name="person_showPerson", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     */
    public function showPersonAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $person = $entityManager->getRepository('AppBundle:Person')->find($id);

        return $this->render('person/showPerson.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * Afficher la liste des personnes en fonction de leur genre
     *
     * @Route("/gender/{gender}", name="person_listPerson", requirements={"gender": "\d+"})
     * @Method("GET")
     */
    public function listPersonAction($gender)
    {
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository('AppBundle:Person')->findBy(
            ['gender' => $gender],
            ['name' => 'ASC']
        );

        return $this->render('person/index.html.twig', [
            'persons' => $persons,
        ]);
    }

    /**
     * Ajouter une personne
     *
     * @Route("/add", name="person_add")
     * @Method({"GET", "POST"})
     */
    public function addPersonAction(Request $request)
    {
        // On appelle Doctrine
        //$em = $this->getDoctrine()->getManager();

        // je crée une nouvelle person
        $person = new Person();

        // J’assigne des valeurs à mes propriétés
//        $person->setName($name);
//        $person->setFirstname($firstname);
//        $person->setGender($gender);
//        $person->setBiography($bio);

        //Kingdom est un objet qui faut aller chercher pour l'ajouter ensuite
//        $kingdomObj = $em->getRepository('AppBundle:Kingdom')->find($kingdom);
//        $person->setKingdom($kingdomObj);

        $form = $this->createForm(PersonType::class, $person); //objet form qui récupère le retour de la méthode createform. l'objet $review recevra toutes les infos envoyés lors de la soumission du formulaire

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // prise en compte de l’objet par Doctrine (pas de requete SQL)
            $em->persist($person);
            $em->flush();

        //return new Response('Le personnage ' . $person->getName() . ' ' . $person->getFirstname() . ' a été créé');
            //Return sur le show une fois le formulaire soumis
            return $this->redirectToRoute('person_showPerson', array(
                'id' => $person->getId()
            ));
        }

        return $this->render('person/new.html.twig', array(
            'review' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * Changer le royaume d’un personnage existant
     *
     * @Route("/change/{person}/kingdom/{kingdom}", name="person_changeKingdom", requirements={"person": "\d+", "kingdom": "\d+"})
     * @Method("GET")
     */
    public function changePersonKingdomAction($person, $kingdom)
    {
        // On appelle Doctrine
        $em = $this->getDoctrine()->getManager();

        // je recherche les info de la personne concernée
        $person = $em->getRepository('AppBundle:Person')->find($person);

        //Kingdom est un objet qui faut aller chercher
        $kingdomObj = $em->getRepository('AppBundle:Kingdom')->find($kingdom);
        $person->setKingdom($kingdomObj);

        // prise en compte de l’objet par Doctrine (pas de requete SQL)
        $em->persist($person);
        $em->flush();

        return new Response($person->getName() . ' ' . $person->getFirstname() . ' fait maintenant parti du royaume ' . $person->getKingdom());

    }

    /**
     * Delete d’un personnage existant
     *
     * @Route("/delete/{person}", name="person_delete", requirements={"person": "\d+"})
     * @Method("GET")
     */
    public function deletePerson($person)
    {
        // On appelle Doctrine
        $em = $this->getDoctrine()->getManager();

        // je recherche les info de la personne concernée
        $person = $em->getRepository('AppBundle:Person')->find($person);

        $em->remove($person);
        $em->flush();

         return new Response($person->getName() . ' ' . $person->getFirstname() . '  s\'est fait manger par un dragon !');

    }

}
