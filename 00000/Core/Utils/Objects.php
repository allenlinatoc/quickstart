<?php

namespace Utils;

/**
 * Utility class for object manipulations and reflections
 *
 * @author Allen
 */
class Objects
{

    /**
     * Get array of properties and values of an object
     *
     * @param object $object    The source object
     * @param int $filter       Flag filter, refer to \ReflectionProperty constants
     *
     * @return array
     */
    static public function GetProperties($object, $filter = null)
    {
        $result = [];

        $rObject = new \ReflectionObject($object);

        $properties = $rObject->getProperties($filter == null ?
                \ReflectionProperty::IS_PRIVATE  |
                \ReflectionProperty::IS_PUBLIC   |
                \ReflectionProperty::IS_PROTECTED|
                \ReflectionProperty::IS_STATIC : $filter);

        foreach ($properties as $prop)
        {
            if ($prop instanceof \ReflectionProperty)
            {
                if (!$prop->isPublic() || !$prop->isStatic())
                {
                    $prop->setAccessible(true);
                }

                $result[$prop->getName()] = $prop->getValue(is_object($object) ? $object : null);
            }
        }

        return $result;
    }


}
