<?php

namespace App\Command\Utils;

use App\Entity\Furniture;
use PhpOffice\PhpSpreadsheet\Style\Color;

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

    private function getLinearDimensions(string $oldDimensions, array $inputDimensions)
    {
        $patterns = [
            'length' => "/^L\s*.?\s*:?\s*",
            'width' => "/^l\s*.?\s*:?\s*",
            'height' => "/^(hauteur|h|H)\s*.?\s*:?\s*",
            'depth' => "/^(p|P|Prof|prof)\s*.?\s*:?\s*",
        ];
        $digits = "([0-9]*[.,])?[0-9]+";
//        $digits = "\d{1,}";
        $linearDimensionPattern = "/(^(((\s*" . $digits . "\s*)(cm|m|CM|M)?\s*[xX]){2}(\s*" . $digits . "))\s*(cm|m|CM|M)?\s*$)" .
            "|" . "(^((\s*" . $digits . "\s*)(cm|m|CM|M)?\s*[xX](\s*" . $digits . "))\s*(cm|m|CM|M)?\s*$)/";
        $outputDimensions = [];
        if (!preg_match($linearDimensionPattern, $oldDimensions)) {
            foreach ($inputDimensions as $dimension) {
                foreach ($patterns as $property => $pattern) {
                    if (preg_match($pattern . $digits . "\s*$/", $dimension)) {
                        $dimension = trim($dimension);
                        $outputDimensions[$property] = preg_replace("/[^\d{1,}]/", '', $dimension);
                    }
                }
            }
        }
        // here we set the default L x l x h x p
        if (preg_match($linearDimensionPattern, $oldDimensions)) {
            $inputDimensionsKeys = array_keys($inputDimensions);
            $patternsKeys = array_keys($patterns);
            $i = 0;
            while (($i < count($patterns)) && ($i < count($inputDimensions))) {
                $dimension = trim($inputDimensions[$inputDimensionsKeys[$i]]);
                $outputDimensions[$patternsKeys[$i]] = preg_replace("/[^\d{1,}]/", '', $dimension);
                $i++;
            }
        }
        if (empty($outputDimensions)) {
            return null;
        }
        return $outputDimensions;
    }

    private function findDimensions($oldFurniture, Furniture &$newFurniture, &$convertingErrors = false)
    {
        $oldDimensions = MigrationDb::utf8Encode($oldFurniture['OE_DIM']);
        // Sometimes dimensions field is null in the old DB
        if (!$oldDimensions) {
            return null;
        }
        $oldDimensions = preg_replace("/(env|\(|cadre|\))/i", '', $oldDimensions);
        $newDimensions = [];
        $dimensions = preg_split('/[xX\-;\r\n]/', $oldDimensions);

        foreach ($dimensions as $key => $dimension) {
            $dimensions[$key] = preg_replace("/(cm|m|M|CM|\s)/i", '', $dimension);
        }
        $newDimensions = array_merge($newDimensions, $this->getCircularDimensions($oldDimensions, $dimensions) ?? []);
        $newDimensions = array_merge($newDimensions, $this->getLinearDimensions($oldDimensions, $dimensions) ?? []);
        $unit = $this->getDimensionUnit($oldDimensions);
        $this->addDimensionsUnit($newDimensions, $unit);
        $newDimensions = array_merge($newDimensions, $this->getWeight($oldDimensions) ?? []);
        if (empty($newDimensions)) {
            $convertingErrors = true;
            return null;
        }
        $this->setDimensions($newDimensions, $newFurniture);
        $convertingErrors = count($newDimensions) !== count($dimensions);
        return $newDimensions;
    }

    private function addDimensionsUnit(array &$dimensions, string $unit)
    {
        foreach ($dimensions as $key => $dimension) {
            $dimensions[$key] = $dimension . " " . $unit;
        }
    }

    private function getWeight(string $oldDimensions)
    {
        $pattern = "/=?\s*(([0-9]*[.,])?[0-9]+)\s*(kg|t)/i";
        $matches = [];
        if (!preg_match($pattern, $oldDimensions, $matches)) {
            return null;
        }
        return ['weight' => $matches[1] . " " . end($matches)];
    }

    private function logFurnitureDimensions($oldEntity, $newEntity, int $excelRow, $foundErrors = false)
    {
        $cellColors = Color::COLOR_WHITE;
        if ($foundErrors) {
            $cellColors = Color::COLOR_RED;
        }
        $dimensions = $this->furnitureDimensions($oldEntity, $newEntity, $foundErrors);
        $this->excelLogger->write($dimensions, $excelRow, $cellColors);
    }

    private function furnitureDimensions($oldFurniture, Furniture $newFurniture, $foundErrors = false)
    {
        $comment = "Ok";
        if ($foundErrors) {
            $comment = "Erreur";
        }
        return [
            'id' => $newFurniture->getId(),
            'ancien ID' => $oldFurniture['C_MGPAM'],
            'anciennes dimensions' => MigrationDb::utf8Encode($oldFurniture['OE_DIM']),
            'commentaire' => $comment,
            'longueur' => $newFurniture->getLength(),
            'largeur' => $newFurniture->getWidth(),
            'hauteur' => $newFurniture->getHeight(),
            'profondeur' => $newFurniture->getDepth(),
            'diametre' => $newFurniture->getDiameter(),
            'poids' => $newFurniture->getWeight(),
        ];
    }

    private function getCircularDimensions(string $oldDimensions, array $inputDimensions)
    {
        $digits = "([0-9]*[.,])?[0-9]+";
        $patterns = [
            'height' => "/^((hauteur|h|H)\s*.?\s*:?\s*)",
            'diameter' => "/^(Ø|ø|DIA)|(d\s*=?)\s*.?\s*:?" . $digits . "\s*(cm|m|CM|M)?\s*$/i"
        ];
//        $digits = "\d{1,}";
        if (!preg_match("/(Ø|ø|DIA)|(d\s*=)\s*:?\s*" . $digits . "/i", $oldDimensions)) {
            return null;
        }
        $outputDimensions = [];
        $i = 0;
        while (($i < 2) && ($i < count($inputDimensions)) && !preg_match($patterns['diameter'], $inputDimensions[$i])) {
            if (preg_match($patterns['height'] . "?" . $digits . "\s*$/", $inputDimensions[$i])) {
                $outputDimensions['height'] = $inputDimensions[$i];
            }
            $i++;
        }
        if ($i === 0 && preg_match($patterns['diameter'], $inputDimensions[$i])) {
            $outputDimensions['diameter'] = $inputDimensions[0];
            // sometimes only the diameter is mentioned
            if (isset($inputDimensions[1])) {
                $outputDimensions['height'] = $inputDimensions[1];
            }
        }
        if ($i === 1 && isset($inputDimensions[1])) {
            $outputDimensions['diameter'] = $inputDimensions[1];
        }
        // here sometimes the dimension string is h20 dia20 for example
        // so we split the string to an array and we recall the function
        if (count($inputDimensions) === 1 && empty($outputDimensions)) {
            $dimensions = $this->splitDimensionsString($inputDimensions[0]);
            $outputDimensions = $this->getCircularDimensions($oldDimensions, $dimensions);
        }
        if (empty($outputDimensions)) {
            return null;
        }
        foreach ($outputDimensions as $key => $dimension) {
            $outputDimensions[$key] = trim($dimension);
            $outputDimensions[$key] = preg_replace("/[^\d{1,}]/", '', $dimension);
        }
        return $outputDimensions;
    }

    private function splitDimensionsString(string $inputString)
    {
        $i = 0;
        $dimensions = [];
        $start = $i;
        while ($i < strlen($inputString) - 1) {
            if (preg_match("/[^\d,]/", $inputString[$i + 1]) && preg_match("/\d/", $inputString[$i])) {
                $dimensions[] = substr($inputString, $start, $i + 1);
                $start = $i + 1;
            }
            $i++;
        }
        $dimensions[] = substr($inputString, $start, $i);
        return $dimensions;
    }

    /**
     * @param array $dimensions array with keys that are the attributes of furniture object to be set
     * @param string $unit
     * @param Furniture $furniture
     */
    private function setDimensions(array $dimensions, Furniture $furniture)
    {
        foreach ($dimensions as $property => $dimension) {
            $setter = "set" . ucfirst($property);
            $furniture->$setter($dimension);
        }
    }
}