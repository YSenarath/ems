<?php
/**
 * Created by PhpStorm.
 * User: Dulanjaya Tennekoon
 * Date: 1/6/2016
 * Time: 4:23 PM
 */

namespace AppBundle\Controller\location;

use AppBundle\Controller\location\AreaController;
use AppBundle\Entity\location\Location;
use AppBundle\Form\location\LocationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\location\LocationController;
use Symfony\Component\HttpFoundation\Request;


class LocationActionController extends Controller
{

    //by dulanjaya
    /**
     * @Route("/location", name="location")
     */
    public function locationAction()
    {
        $conn = $this->get('database_connection');
        $locations = new LocationController($conn);
        $locs = $locations->getLocationsAction();
        return $this->render(
            'AppBundle:location:location.html.twig', array('locations'=>$locs)
        );
    }

    /**
     * @Route("/location/add", name="addLocations")
     */
    public function addLocationsAction(Request $request)
    {
        //create the location object
        $newLoc = new Location();
        $connection = $this->get('database_connection');
        $locationController = new LocationController($connection);
        $areas = $locationController->getAllAreas();

        //build the form
        $form = $this->createForm(LocationType::class, $newLoc, array(
            'areas' => $areas));

        //Handle submission (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            //add location
            $connection = $this->get('database_connection');
            $locationController = new LocationController($connection);

            $locationController->addLocation($newLoc);

            return $this->render(
                'AppBundle:location:addLocations.html.twig',
                array('form' => $form->createView())
            );
        }

        return $this->render(
            'AppBundle:location:addLocations.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/location/area", name="areaView")
     */
    public function areaAction()
    {
        $conn = $this->get('database_connection');
        $locations = new LocationController($conn);
        $areas = $locations->getArea();
        return $this->render(
            'AppBundle:location:areas.html.twig', array('areas'=>$areas)
        );
    }

    /**
     * @Route("/location/area/{viewArea}", name="locationAreaView")
     */
    public function specificLocationAction($viewArea) {
        $conn = $this->get('database_connection');
        $locationController = new LocationController($conn);
        $areaController = new AreaController($conn);
        $area = $areaController->getAreaDetailsAction($viewArea);
        $areaId = $area->getAreaCode();
        $locationArray = $locationController->getLocationDetailsByAreaAction($areaId);
        return $this->render(
            '@App/location/areaView.html.twig',
            array(
                'locations' => $locationArray,
                'area_longitude' => $area->getCenterLongitude(),
                'area_latitude' => $area->getCenterLatitude(),
                'areaView' => $viewArea,
            )
        );
    }
}