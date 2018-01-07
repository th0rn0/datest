<?php

namespace FootballInterface\Transformer;

class ResponseTransformer
{

    /**
     * Transforms a json Football event to a array and formats for display.
     *
     * @param  Response|null $response
     * @return array
     */
    public static function transformFootballEvent($response)
    {
        $response = self::jsonToArray($response);
        $return['name']                   = $response->event->name;
        $return['id']                     = $response->event->eventId;
        $return['displayOrder']           = $response->event->displayOrder;
        $return['linkedEventTypeName']    = $response->event->linkedEventTypeName;
        $return['scores']                 = $response->event->scores;
        $return['startTime']              = $response->event->startTime;
        $return['status']                 = $response->event->status;
        foreach ($response->event->competitors as $competitor) {
            if (strtolower($competitor->position) == 'away') {
                $return['competitors']['away'] = $competitor->name; 
            }
            if (strtolower($competitor->position) == 'home') {
                $return['competitors']['home'] = $competitor->name; 
            }
        }
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
     * Transforms a json Football event to a array and formats for display.
     *
     * @param  Response|null $response
     * @return array
     */
    public static function transformFootballOverview($response)
    {
        $response = self::jsonToArray($response);
        foreach ($response->events as $event) {
            $eventId = $event->eventId;
            $return[$eventId]['name']                   = $event->name;
            $return[$eventId]['id']                     = $event->eventId;
            $return[$eventId]['displayOrder']           = $event->displayOrder;
            $return[$eventId]['linkedEventTypeName']    = $event->linkedEventTypeName;
            $return[$eventId]['scores']                 = $event->scores;
            foreach ($event->competitors as $competitor) {
                if (strtolower($competitor->position) == 'away') {
                    $return[$eventId]['competitors']['away'] = $competitor->name; 
                }
                if (strtolower($competitor->position) == 'home') {
                    $return[$eventId]['competitors']['home'] = $competitor->name; 
                }
            }
            $return[$eventId]['startTime']              = $event->startTime;
            foreach ($response->markets as $marketEventId => $markets) {
                if ($marketEventId == $eventId) {
                    $return[$eventId]['primaryMarket'] = $markets{0};
                }
            }
            foreach ($response->outcomes as $outcomeMarketId => $outcomes) {
                if ($outcomeMarketId == $return[$eventId]['primaryMarket']->marketId) {
                    $return[$eventId]['outcomes'] = $outcomes;
                }
            }
        }

        usort($return, function($a, $b)
        {
            return $a['displayOrder'] < $b['displayOrder'];
        });

        return $return;
    }

    private function jsonToArray($json)
    {
        return json_decode($json);
    }
}