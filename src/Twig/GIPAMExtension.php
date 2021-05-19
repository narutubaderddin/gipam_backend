<?php


namespace App\Twig;

use App\Entity\PropertyStatus;
use App\Entity\Movement;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GIPAMExtension extends AbstractExtension
{
    private $baseUrl;
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->baseUrl = $parameterBag->get('server_base_url');

    }
    public function getFunctions()
    {
        return [
            new TwigFunction('PDFValues', [$this, 'PDFValues']),
            new TwigFunction('instanceOfPropertyStatus', [$this, 'instanceOfPropertyStatus']),
            new TwigFunction('getLastMovement', [$this, 'getLastMovement']),
            new TwigFunction('lastMovementMemeber', [$this, 'lastMovementMemeber']),
            new TwigFunction('getLastAction', [$this, 'getLastAction']),
            new TwigFunction('lastActionMemeber', [$this, 'lastActionMemeber']),
        ];
    }
    public function getFilters()
    {
        return [
            new TwigFilter('notice_image', [$this, 'NoticeImage'])
        ];
    }

    public function NoticeImage($photography){

        if(is_null($photography))
            return "";
        $uri = strpos($photography->getImagePreview(),'uploads')!==false ?$photography->getImagePreview():'uploads'.DIRECTORY_SEPARATOR.$photography->getImagePreview();
        $path = $this->baseUrl . DIRECTORY_SEPARATOR . $uri;
        $path = preg_replace('/\\\\/', "/", $path);
        return $path;

    }

    /**
     * @param $value
     * @return string
     */
    public function PDFValues($value)
    {
        if(!is_null($value))
            return $value;
        else
            return "-";
    }

    /**
     * @param $value
     * @param $statusType
     * @param $status
     * @return string
     */
    public function instanceOfPropertyStatus($status){
        return ($status instanceof  PropertyStatus);
    }

    /**
     * @param $movments
     * @return null
     */
    public function getLastMovement($movments){
        $lastMovement = null;
        foreach ($movments as $movment){
            if(is_null($lastMovement) ||$movment->getDate() >  $lastMovement->getDate()){
                $lastMovement = $movment;
            }
        }

        return $lastMovement;
    }

    /**
     * @param $lastMovement
     * @return null
     */
    public function getLastAction($lastMovement){
        if(is_null($lastMovement))
            return null;
        $actions =$lastMovement->getActions();
        $lastAction = null;
        foreach ($actions as $action){
            if(is_null($lastAction) || $action->getUpdatedAt() >  $lastAction->getUpdatedAt()){
                $lastAction = $action;
            }
        }

        return $lastAction;
    }

    public function lastMovementMemeber($lastMovment, $memeber): ?string
    {
        if(is_null($lastMovment))
            return $lastMovment;
        else{
            switch ($memeber){
                case "ministry_name":
                    return $lastMovment->getLocation()->getEstablishment()->getMinistry()->getName();
                case "establishment_label":
                    return $lastMovment->getLocation()->getEstablishment()->getLabel();
                case "site_label":
                    return $lastMovment->getLocation()->getRoom()->getBuilding()->getSite()->getLabel();
                case "building_label":
                    return $lastMovment->getLocation()->getRoom()->getBuilding()->getLabel();
                case "room_reference":
                    return $lastMovment->getLocation()->getRoom()->getReference();
                case "room_id":
                    return $lastMovment->getLocation()->getRoom()->getid();
                case "commune_name":
                    return $lastMovment->getLocation()->getRoom()->getBuilding()->getCommune()->getName();
                case "department_name":
                    return $lastMovment->getLocation()->getRoom()->getBuilding()->getCommune()->getDepartment()->getName();
                case "region_name":
                    return $lastMovment->getLocation()->getRoom()->getBuilding()->getCommune()->getDepartment()->getRegion()->getName();
                case "subDivision_label":
                    return $lastMovment->getLocation()->getSubDivision()->getLabel();
                case "subDivision_services":
                    $servicesLabel = "";
                    foreach ($lastMovment->getLocation()->getSubDivision()->getServices() as $service){
                        if($servicesLabel != "")
                            $servicesLabel .= " / ";
                        $servicesLabel .= $service->getLabel();
                    }
                    return $servicesLabel;
                default:
                    return $lastMovment->getId();
            }
        }
    }

    /**
     * @param $lastAction
     * @param $memeber
     * @return string|null
     */
    public function lastActionMemeber($lastAction, $memeber): ?string
    {
        if(is_null($lastAction))
            return "-";
        else{
            switch ($memeber){
                case "action_type":
                    if(is_null($lastAction->getType()))
                        return "-";
                    return $lastAction->getType()->getLabel();
                case "action_creation":
                    return $lastAction->getCreatedAt();

                default:
                    return $lastAction->getId();
            }
        }
    }
}
