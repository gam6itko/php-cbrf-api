<?php

namespace Gam6itko\CbrRu\Type;

/**
 * Class representing EnumBIC
 */
class EnumBIC
{

    /**
     * @property \Gam6itko\CbrRu\Type\EnumBIC\BICAType[] $bIC
     */
    private $bIC = [
        
    ];

    /**
     * Adds as bIC
     *
     * @return self
     * @param \Gam6itko\CbrRu\Type\EnumBIC\BICAType $bIC
     */
    public function addToBIC(\Gam6itko\CbrRu\Type\EnumBIC\BICAType $bIC)
    {
        $this->bIC[] = $bIC;
        return $this;
    }

    /**
     * isset bIC
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBIC($index)
    {
        return isset($this->bIC[$index]);
    }

    /**
     * unset bIC
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBIC($index)
    {
        unset($this->bIC[$index]);
    }

    /**
     * Gets as bIC
     *
     * @return \Gam6itko\CbrRu\Type\EnumBIC\BICAType[]
     */
    public function getBIC()
    {
        return $this->bIC;
    }

    /**
     * Sets a new bIC
     *
     * @param \Gam6itko\CbrRu\Type\EnumBIC\BICAType[] $bIC
     * @return self
     */
    public function setBIC(array $bIC)
    {
        $this->bIC = $bIC;
        return $this;
    }


}

