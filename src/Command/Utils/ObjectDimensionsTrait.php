<?php

namespace App\Command\Utils;

use App\Entity\Furniture;

trait ObjectDimensionsTrait
{
    private function getDimensionUnit(string $dimension)
    {
        if (preg_match("/[0-9\s]cm/i", $dimension)) {
            return ' cm';
        }
        if (preg_match("/[0-9\s]m/i", $dimension)) {
            return ' m';
        }
        return '';
    }

    private function getLinearDimensions(array $inputDimensions, $withPatterns = false)
    {
        $patterns = [
            'longueur' => "/^L\s*",
            'largeur' => "/^l\s*:?\s*",
            'hauteur' => "/^(hauteur|h|H)\s*:?\s*",
            'profondeur' => "/^(p|P)\s*:?\s*",
//            'diametre' => "/^(ø|DIA)\s*:?/i"
        ];
//        $digits = "[+-]?([0-9]*[.,])?[0-9]+";
        $digits = "\d{1,}";
        $outputDimensions = [];
        if ($withPatterns) {
            foreach ($inputDimensions as $dimension) {
                foreach ($patterns as $property => $pattern) {
                    if (preg_match($pattern . $digits . "/", $dimension)) {
                        $dimension = trim($dimension);
                        $outputDimensions[$property] = preg_replace("/[^" . $digits . "]/", '', $dimension);
                    }
                }
            }
        }
        // here we set the default L x l x h x p
        if (!$withPatterns) {
            $inputDimensionsKeys = array_keys($inputDimensions);
            $patternsKeys = array_keys($patterns);
            $i = 0;
            while (($i < count($patterns)) && ($i < count($inputDimensions))) {
                $dimension = trim($inputDimensions[$inputDimensionsKeys[$i]]);
                $outputDimensions[$patternsKeys[$i]] = preg_replace("/[^" . $digits . "]/", '', $dimension);
                $i++;
            }
        }
        if (empty($outputDimensions)) {
            return null;
        }
        return $outputDimensions;
    }

    private function findDimensions($oldFurniture, Furniture &$newFurniture)
    {
        $digits = "[+-]?([0-9]*[.,])?[0-9]+";
//        $digits = "\d{1,}";
        $linearDimensionPattern = "/(^(((\s?" . $digits . "\s?)[xX]){2}(\s?" . $digits . "))\s*(cm|m)?$)" .
            "|" . "(^((\s?" . $digits . "\s?)[xX](\s?" . $digits . "))\s*(cm|m)?$)/";
        $oldDimensions = MigrationDb::utf8Encode($oldFurniture['OE_DIM']);
        if (!$oldDimensions) {
            return null;
        }
        $dimensions = preg_split('/[xX\-;\r\n]/', $oldDimensions);
        foreach ($dimensions as $key => $dimension) {
            $dimensions[$key] = trim($dimension);
        }
        if (preg_match("/^(ø|DIA)\s*:?/i", $oldDimensions)) {
            $this->getCircularDimensions($dimensions);
        } else {
            if (preg_match($linearDimensionPattern, $oldDimensions)) {
                $dimensions = $this->getLinearDimensions($dimensions, false);
            } else {
                $dimensions = $this->getLinearDimensions($dimensions, true);
            }
            $unit = $this->getDimensionUnit($oldDimensions);
            if (!empty($dimensions)) {
                $this->setDimensions($dimensions, $unit, $newFurniture);
            }
        }
        if (empty($dimensions)) {
            return null;
        }
        return $dimensions;
    }

    private function furnitureDimensions($oldFurniture, Furniture $newFurniture)
    {
        return [
            'id' => $newFurniture->getId(),
            'ancien ID' => $oldFurniture['C_MGPAM'],
            'anciennes dimensions' => MigrationDb::utf8Encode($oldFurniture['OE_DIM']),
            'longueur' => $newFurniture->getLength(),
            'largeur' => $newFurniture->getWidth(),
            'hauteur' => $newFurniture->getHeight(),
            'profondeur' => $newFurniture->getDepth(),
            'diametre' => $newFurniture->getDiameter(),
            'poids' => $newFurniture->getWeight(),
        ];
    }

    private function getCircularDimensions(array $inputDimensions)
    {
        // todo
    }

    /**
     * @param array $dimensions array with keys that are the attributes of furniture object to be set
     * @param string $unit
     * @param Furniture $furniture
     */
    private function setDimensions(array $dimensions, string $unit, Furniture $furniture)
    {
        foreach ($dimensions as $property => $dimension) {
            $setter = "set" . ucfirst($property);
            $furniture->$setter($dimension . $unit);
        }
    }
}