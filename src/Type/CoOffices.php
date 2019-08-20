<?php

namespace Gam6itko\CbrRu\Type;

/**
 * Class representing CoOffices
 */
class CoOffices
{

    /**
     * @property \Gam6itko\CbrRu\Type\CoOffices\OfficesAType[] $offices
     */
    private $offices = [
        
    ];

    /**
     * Adds as offices
     *
     * @return self
     * @param \Gam6itko\CbrRu\Type\CoOffices\OfficesAType $offices
     */
    public function addToOffices(\Gam6itko\CbrRu\Type\CoOffices\OfficesAType $offices)
    {
        $this->offices[] = $offices;
        return $this;
    }

    /**
     * isset offices
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOffices($index)
    {
        return isset($this->offices[$index]);
    }

    /**
     * unset offices
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOffices($index)
    {
        unset($this->offices[$index]);
    }

    /**
     * Gets as offices
     *
     * @return \Gam6itko\CbrRu\Type\CoOffices\OfficesAType[]
     */
    public function getOffices()
    {
        return $this->offices;
    }

    /**
     * Sets a new offices
     *
     * @param \Gam6itko\CbrRu\Type\CoOffices\OfficesAType[] $offices
     * @return self
     */
    public function setOffices(array $offices)
    {
        $this->offices = $offices;
        return $this;
    }


}
