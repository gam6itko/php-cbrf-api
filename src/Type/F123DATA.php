<?php

namespace Gam6itko\CbrRu\Type;

/**
 * Class representing F123DATA
 */
class F123DATA
{

    /**
     * @property \Gam6itko\CbrRu\Type\F123DATA\F123AType[] $f123
     */
    private $f123 = [
        
    ];

    /**
     * Adds as f123
     *
     * @return self
     * @param \Gam6itko\CbrRu\Type\F123DATA\F123AType $f123
     */
    public function addToF123(\Gam6itko\CbrRu\Type\F123DATA\F123AType $f123)
    {
        $this->f123[] = $f123;
        return $this;
    }

    /**
     * isset f123
     *
     * @param int|string $index
     * @return bool
     */
    public function issetF123($index)
    {
        return isset($this->f123[$index]);
    }

    /**
     * unset f123
     *
     * @param int|string $index
     * @return void
     */
    public function unsetF123($index)
    {
        unset($this->f123[$index]);
    }

    /**
     * Gets as f123
     *
     * @return \Gam6itko\CbrRu\Type\F123DATA\F123AType[]
     */
    public function getF123()
    {
        return $this->f123;
    }

    /**
     * Sets a new f123
     *
     * @param \Gam6itko\CbrRu\Type\F123DATA\F123AType[] $f123
     * @return self
     */
    public function setF123(array $f123)
    {
        $this->f123 = $f123;
        return $this;
    }


}

