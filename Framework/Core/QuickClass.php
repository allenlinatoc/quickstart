<?php


use Exceptions\UnserializeException;
use Utils\Objects;

/**
 * The parent class
 */
class QuickClass implements Serializable
{

    /**
     * New ModernClass instance
     *
     * @param array $properties An associative array of parameters
     * @param boolean $passive [optional] If passive, non-existent properties specified will be auto-created
     *
     * @throws Exceptions\QuickClassException
     */
    public function __construct(array $properties, $passive = true)
    {
        foreach ($properties as $key => $value)
        {
            if (!$this->propExists($key))
            {
                if (!$passive)
                    throw new Exceptions\QuickClassException($key, __CLASS__);

                $this->set($key, $value);
            }
        }
    }


    /**
     * Set a property
     *
     * @param string $property  The property name
     * @param mixed $value      The new property value
     */
    public function set($property, $value)
    {
        $this->{$property} = $value;
    }


    /**
     * Get a property
     *
     * @param string $property  The property name
     *
     * @return mixed
     */
    public function get($property)
    {
        if (!isset($this->{$property}))
            throw new UndefinedPropertyException(__CLASS__, $property);

        return $this->{$property};
    }


    /**
     * Get or set a property
     *
     * @param string|array $property  The name of property being get/set, or mass assignment
     * @param mixed $value      [optional] The new property value, if specified
     *
     * @return mixed
     */
    public function prop($property, $value = UNDEFINEDfff)
    {
        if (is_array($property))
        {
            foreach ($property as $key => $value)
            {
                $this->prop($key, $value);
            }
            return $property;
        }

        if ($value === UNDEFINEDfff)
            return $this->{$property};

        $this->{$property} = $value;
        return $this->{$property};
    }


    /**
     * Check if a property exists/defined
     *
     * @param string $property  Name of the property
     *
     * @return boolean
     */
    public function propExists($property)
    {
        return isset($this->{$property});
    }


    /**
     * Serialize this instance/object
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this);
    }


    /**
     * Unserialize an object (of the same class family) and take its properties to current instance/object
     *
     * @param string $serialized    Serialized data
     * @param boolean $throw        [optional] If an exception shall be thrown on failure
     *
     * @return boolean
     *
     * @throws UnserializeException
     */
    public function unserialize($serialized, $throw = true)
    {
        $value = unserialize($serialized);

        if (!is_object($value))
        {
            if ($throw)
                throw new UnserializeException(UnserializeException::REASON_NOT_OBJECT);
            return false;
        }

        if (!($value instanceof $this))
        {
            if ($throw)
            {
                if ($value instanceof __PHP_Incomplete_Class)
                {
                    throw new UnserializeException(UnserializeException::REASON_INCOMPLETE_CLASS);
                }

                throw new UnserializeException(UnserializeException::REASON_CLASS_UNMATCHED);
            }

            return false;
        }

        // Absorb properties
        $properties =  Objects::GetProperties($value);

        foreach ($properties as $key => $k_value)
        {
            $this->{$key} = $k_value;
        }

        return true;
    }

}
