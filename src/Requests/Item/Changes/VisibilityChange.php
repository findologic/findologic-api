<?php

namespace FINDOLOGIC\Api\Requests\Item\Changes;

class VisibilityChange extends Change
{
    /** @var bool */
    private $isVisible = false;

    public function getKey()
    {
        return 'visible';
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->isVisible();
    }

    public function setVisible()
    {
        $this->isVisible = true;
    }

    public function setInvisible()
    {
        $this->isVisible = false;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->isVisible;
    }
}
