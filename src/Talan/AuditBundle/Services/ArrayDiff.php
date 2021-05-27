<?php

namespace App\Talan\AuditBundle\Services;


class ArrayDiff
{
    public function diff($oldData, $newData)
    {
        $diff = array();

        $keys = array_keys($oldData + $newData);
        foreach ($keys as $field) {
            $old = array_key_exists($field, $oldData) ? $oldData[$field] : null;
            $new = array_key_exists($field, $newData) ? $newData[$field] : null;

            if ($old == $new) {
                $row = array('old' => '', 'new' => '', 'same' => $old);
            } else {
                $row = array('old' => $old, 'new' => $new, 'same' => '');
            }

            $diff[$field] = $row;
        }

        return $diff;
    }
}