<?php

namespace FINDOLOGIC\Request\Parameters;

use FINDOLOGIC\Request\Parameters\Types\Referer;
use FINDOLOGIC\Request\Parameters\Types\Revision;
use FINDOLOGIC\Request\Parameters\Types\Shopkey;
use FINDOLOGIC\Request\Parameters\Types\Shopurl;
use FINDOLOGIC\Request\Parameters\Types\Userip;

class ParameterBuilder
{
    private $params = [];

    /**
     * @param $value string Required shopkey. It is used to determine the service.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setShopkey($value)
    {
        $shopkey = new Shopkey();
        $shopkey->setValue($value);
        $this->addParam(Shopkey::PARAM_KEY, $value);
        return $this;
    }

    /**
     * @param $value string Required shopurl. It is used to determine the service url.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setShopurl($value)
    {
        $shopurl = new Shopurl();
        $shopurl->setValue($value);
        $this->addParam(Shopurl::PARAM_KEY, $value);
        return $this;
    }

    /**
     * @param $value string Required userip. It is used for billing and for the user identifier.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setUserip($value)
    {
        $userip = new Userip();
        $userip->setValue($value);
        $this->addParam(Userip::PARAM_KEY, $value);
        return $this;
    }

    /**
     * @param $value string Required referer. It is used to track the search history.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setReferer($value)
    {
        $referer = new Referer();
        $referer->setValue($value);
        $this->addParam(Referer::PARAM_KEY, $value);
        return $this;
    }

    /**
     * @param $value string Required revision. It is used to identify the version of the plugin. Can be set to 1.0.0 if
     *      you are not sure which value you should pass to the API.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setRevision($value)
    {
        $revision = new Revision();
        $revision->setValue($value);
        $this->addParam(Revision::PARAM_KEY, $value);
        return $this;
    }

    /**
     * Returns a specific param or all if no explicit param is given.
     *
     * @param string|null $param
     * @return string|array
     */
    public function getParam($param = null)
    {
        if ($param !== null) {
            return $this->params[$param];
        }
        return $this->params;
    }

    /**
     * @param $key string The key or the param name, that identifies the param.
     * @param $value string The value for the param.
     */
    private function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }
}