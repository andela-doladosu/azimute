<?php

namespace Xmltime;

class XmlTimeParser
{
    public function getArrayFromXmlObject($object)
    {
        return json_decode(json_encode($object), true);
    }

    public function getLocationDataFromResponse($response)
    {
        $parsedResult = $this->getArrayFromXmlObject(simplexml_load_string($response));


        $geo = $parsedResult['location']['geo'];
        $astro = $parsedResult['location']['astronomy']['object'];
        $locationData['place'] = $geo['name'];
        $locationData['country'] = $geo['country'];
        $locationData['latitude'] = $geo['latitude'];
        $locationData['longitude'] = $geo['longitude'];
        $locationData['celestialBody'] = $astro['@attributes']['name'];


        if (!empty($locationData['celestialBody'])
            && $locationData['celestialBody'] === 'sun'
        ) {
            foreach ($astro['event'] as $event) {

                if ($event['@attributes']['type'] === 'rise') {

                    $locationData['sunrise'] = $event['@attributes']['hour'].":".$event['@attributes']['minute'];
                } else {
                    $locationData['sunset'] = $event['@attributes']['hour'].":".$event['@attributes']['minute'];
                }
            }
        }

        return $locationData;
    }
}
