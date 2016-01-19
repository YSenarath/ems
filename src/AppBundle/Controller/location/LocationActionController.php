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
use AppBundle\Form\location\LocationChangeType;
use AppBundle\Form\location\LocationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\location\LocationController;
use Symfony\Component\HttpFoundation\Request;

class LocationActionController extends Controller
{

    //by dulanjaya
    /**
     * @Route("/map", name="mapView")
     */
    public function mapViewAction()
    {
        $conn = $this->get('database_connection');
        $locations = new LocationController($conn);
        $districts = $locations->getAllDistrictsAction();
        return $this->render(
            'AppBundle:location:location.html.twig', array('areas'=>$districts)
        );
    }

    /**
     * @Route("/location/area/{viewArea}/addLocation", name="addLocationView")
     */
    public function addLocationsAction($viewArea, Request $request)
    {
        //create the location object
        $newLoc = new Location();
        $connection = $this->get('database_connection');
        $locationController = new LocationController($connection);
        $areas = $locationController->getAllAreaCodes();

        $areaController = new AreaController($connection);
        $area = $areaController->getAreaDetailsAction($viewArea);
        $areaId = $area->getAreaCode();

        //build the form
        $form = $this->createForm(LocationType::class, $newLoc, array(
            'areas' => $areas, 'areaId' => $areaId));

        //Handle submission (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            //add location
            $connection = $this->get('database_connection');
            $locationController = new LocationController($connection);

            $locationController->addLocation($newLoc);

            return $this->redirectToRoute('locationAreaView', array('viewArea'=> $viewArea)    );
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

    /**
     * @Route("/location/area/{viewArea}/{viewLocation}", name="changeLocationView")
     */
    public function changeLocationViewAction($viewArea, $viewLocation, Request $request)
    {
        //create the location object

        $connection = $this->get('database_connection');
        $locationController = new LocationController($connection);
        $newLoc = $locationController->getLocationbyIDAction($viewLocation);
        $areas = $locationController->getAllAreaCodes();

        $areaController = new AreaController($connection);
        $area = $areaController->getAreaDetailsAction($viewArea);
        $areaId = $area->getAreaCode();

        //build the form
        $form = $this->createForm(LocationChangeType::class, $newLoc, array(
            'areas' => $areas, 'areaId' => $areaId));

        //Handle submission (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            //add location
            $locationController->changeLocation($newLoc);
            $this->get('session')->getFlashBag()->add('msg', 'ABC');
            return $this->redirectToRoute('locationAreaView', array('viewArea'=> $viewArea));
        }

        return $this->render(
            'AppBundle:location:changeLocation.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/location/area/{viewArea}/{viewLocation}/delete", name="deleteLocationView")
     */
    public function deleteLocationAction($viewArea, $viewLocation)
    {
        $connection = $this->get('database_connection');
        $locationController = new LocationController($connection);
        if($locationController->deleteLocation($viewLocation)) {
            $this->get('session')->getFlashBag()->add('msg', 'Location \''.$viewLocation.'\' Delete Successfully.');
        } else {
            $this->get('session')->getFlashBag()->add('msg', 'Location \''.$viewLocation.'\' Delete Failed (Warning - can not remove locations having registered sensors)');
        }
        return $this->redirectToRoute('locationAreaView', array('viewArea' => $viewArea));

    }

    /**
     * @Route("/location/add", name="addLocations")
     */
    public function addLocationAction(Request $request)
    {
        //create the location object
        $newLoc = new Location();
        $connection = $this->get('database_connection');
        $locationController = new LocationController($connection);
        $areas = $locationController->getAllAreaCodes();

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
}