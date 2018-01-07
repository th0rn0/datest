<?php

namespace FootballInterface\Transformer;

class ResponseTransformer
{
    /**
     * Transforms a json Football event to a array and formats for display.
     *
     * @param  Response $response
     * @return array
     */
    public function transformFootballEvent($response)
    {
        $response = $this->jsonToArray($response);
        $return = $this->transformEvent($response->event);
        $return['competitors'] = $this->transformCompetitors($response->event->competitors);
       
        foreach ($response->markets->{$response->event->eventId} as $market) {
            if (!isset($response->outcomes->{$market->marketId})) {
                $url = getenv('API_URL') . '/sportsbook/market/' . $market->marketId;
                // This was added in as I couldn't get lazy loading in time.
                $client = new \GuzzleHttp\Client();
                $response = $client->get($url);
                $response = json_decode($response->getBody()->getContents());
            }
            $outcomes = array();
            foreach ($response->outcomes->{$market->marketId} as $outcome) {
                if ($market->type == 'correct-score') {
                    $outcomes[$outcome->type][] = $outcome;
                } else {
                    $outcomes[] = $outcome;
                }
            }
            $market->outcomes = $outcomes;
            $return['markets'][] = $market;
        }
        return $return;
    }

    /**
     * Transforms a json Football events to a array and formats for display.
     *
     * @param  Response $response
     * @return array
     */
    public function transformFootballOverview($response)
    {
        $response = $this->jsonToArray($response);
        foreach ($response->events as $event) {
            $return[$event->eventId] = $this->transformEvent($event);
            $return[$event->eventId]['competitors'] = $this->transformCompetitors($response->event->competitors);
            $return[$event->eventId]['startTime']              = $event->startTime;
            foreach ($response->markets as $marketEventId => $markets) {
                if ($marketEventId == $event->eventId) {
                    $return[$event->eventId]['primaryMarket'] = $markets{0};
                }
            }
            foreach ($response->outcomes as $outcomeMarketId => $outcomes) {
                if ($outcomeMarketId == $return[$event->eventId]['primaryMarket']->marketId) {
                    $return[$event->eventId]['outcomes'] = $outcomes;
                }
            }
        }

        usort($return, function($a, $b)
        {
            return $a['displayOrder'] < $b['displayOrder'];
        });

        return $return;
    }

    /**
     * Transforms a json to array.
     *
     * @param  json $json
     * @return array
     */
    private function jsonToArray($json)
    {
        return json_decode($json);
    }

    /**
     * Transforms a json event to a array and formats for display.
     *
     * @param  Event| $event
     * @return array
     */
    private function transformEvent($event)
    {
        $return['name']                   = $event->name;
        $return['id']                     = $event->eventId;
        $return['displayOrder']           = $event->displayOrder;
        $return['linkedEventTypeName']    = $event->linkedEventTypeName;
        $return['scores']                 = $event->scores;
        $return['startTime']              = $event->startTime;
        $return['status']                 = $event->status;
        return $return;
    }

    /**
     * Transforms a json competitors to a array and formats for display.
     *
     * @param  Competitors| $competitors
     * @return array
     */
    private function transformCompetitors($competitors)
    {
        foreach ($competitors as $competitor) {
            if (strtolower($competitor->position) == 'away') {
                $return['away'] = $competitor->name; 
            }
            if (strtolower($competitor->position) == 'home') {
                $return['home'] = $competitor->name; 
            }
        }
        return $return;
    }

}