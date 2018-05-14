<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Kingdom;
use AppBundle\Entity\Person;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Person controller
 *
 * @Route("kingdom", name="kingdom")
 */
class KingdomController extends Controller
{
    /**
     *
     *
     * @Route("/", name="person_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('kingdom/index.html.twig');
    }


    /**
     * Ajouter une personne
     *
     * @Route("/add/name/{name}/capital/{capital}/nbInhabitant/{nbInhabitant}", name="kingdom_add")
     * @Method("GET")
     */
    public function addKingdomAction($name, $capital, $nbInhabitant)
    {
        // On appelle Doctrine
        $em = $this->getDoctrine()->getManager();

        // je crée un nouveau kingdom
        $kingdom = new Kingdom();

        // J’assigne des valeurs à mes propriétés
        $kingdom->setName($name);
        $kingdom->setCapital($capital);
        $kingdom->setNbInhabitant($nbInhabitant);

        // prise en compte de l’objet par Doctrine (pas de requete SQL)
        $em->persist($kingdom);
        $em->flush();

        return new Response('Le kingdom ' . $kingdom->getName() . ' ' . $kingdom->getCapital() . ' a été créé');
    }

}
