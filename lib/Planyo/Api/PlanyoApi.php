<?php

namespace Planyo\Api;

/**
 * Implementation PlanyoApi
 *
 * @author Dagomar Paulides <dagomar@onlineagency.nl>
 */
class PlanyoApi extends AbstractApi
{
    public function query($params = array())
    {
        $missingParams = $this->checkRequired($params);

        if ($missingParams) {
            throw new MissingArgumentException($missingParams);
        }

        $params['method'] = $this->method;

        return $this->get($params);
    }
}
