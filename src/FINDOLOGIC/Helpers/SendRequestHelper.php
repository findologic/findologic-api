<?php

namespace FINDOLOGIC\Helpers;

use FINDOLOGIC\Exceptions\ServiceNotAliveException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SendRequestHelper
{
    /**
     * Sends an alivetest. If it was successful, it will return true.
     *
     * @param $alivetestUrl
     * @param $timeout
     * @return bool returns true if the alivetest was successful.
     *
     * @throws ServiceNotAliveException if the alivetest was not successful.
     */
    public static function sendAlivetest($alivetestUrl, $timeout)
    {
        $client = new Client();
        try {
            $request = $client->request('GET', $alivetestUrl, ['timeout' => $timeout]);
        } catch (GuzzleException $e) {
            $request = null;
        }

        if ($request->getStatusCode() == 200 && $request->getBody() == 'alive') {
            return true;
        }

        throw new ServiceNotAliveException($alivetestUrl);
    }
}