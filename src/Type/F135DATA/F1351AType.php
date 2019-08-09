<?php

namespace Gam6itko\CbrRu\Type\F135DATA;

/**
 * Class representing F1351AType
 */
class F1351AType
{

    /**
     * @property string $c1
     */
    private $c1 = null;

    /**
     * @property float $v1
     */
    private $v1 = null;

    /**
     * Gets as c1
     *
     * @return string
     */
    public function getC1()
    {
        return $this->c1;
    }

    /**
     * Sets a new c1
     *
     * @param string $c1
     * @return self
     */
    public function setC1($c1)
    {
        $this->c1 = $c1;
        return $this;
    }

    /**
     * Gets as v1
     *
     * @return float
     */
    public function getV1()
    {
        return $this->v1;
    }

    /**
     * Sets a new v1
     *
     * @param float $v1
     * @return self
     */
    public function setV1($v1)
    {
        $this->v1 = $v1;
        return $this;
    }


}

