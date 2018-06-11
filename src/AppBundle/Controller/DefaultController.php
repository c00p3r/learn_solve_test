<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MarketData;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Attachment;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        $entityManager = $this->getDoctrine()->getManager();

        $client = new Client(['base_uri' => 'https://www.google.com']);

        $markets = [];

        foreach ($this->markets as $market) {
            try {
                $response = $client->request('GET', 'finance/getprices', [
                    'query' => [
                        'x' => $market['exchange'],
                        'q' => $market['ticker'],
                        'i' => '86400',
                        'p' => '3d',
                        'f' => 'd,o,h,l,c,v',
                        'df' => 'cpct'
                    ]
                ]);
            } catch (GuzzleException $e) {
                $msg = $e->getMessage();

                // log
                // display
                continue;
            }
            $body = $response->getBody()->getContents();

            $dataArray = explode("\n", $body);

            $data = $this->parseData($dataArray, $market);

            $record = $entityManager->getRepository(MarketData::class)->findBy(['date' => $data['DATE']]);

            if (!$record) {
                $this->saveToDb($data);
            }
            $markets[] = $data;
        }

        $entityManager->flush();

        $title = 'Markets info';

        $columns = array_keys(@$markets[0]);

        $this->sendEmail($title, $columns, $markets);

        return $this->render('default/index.html.twig', compact('title', 'columns', 'markets'));
    }

    /**
     * @param $dataArray
     * @param $market
     *
     * @return array
     */
    private function parseData($dataArray, $market) {
        if (empty($dataArray[4])) {
            // log
            // display
            // columns data empty
            throw new UnprocessableEntityHttpException('Markets data does not contain columns info');
        }

        $columnsRaw = substr($dataArray[4], strpos($dataArray[4], '=') + 1);

        $columns = explode(',', $columnsRaw);

        if (empty($dataArray[6])) {
            // log
            // display
            // timezone offset empty
            throw new UnprocessableEntityHttpException('Markets data does not contain timezone offset info');
        }

        $timezoneOffset = (int)substr($dataArray[6], strpos($dataArray[6], '=') + 1);

        // Not working for Mumbai timezone (+5:30 UTC, IST)
        // $timezone = timezone_name_from_abbr('', $timezoneOffset * 60, 1);

        $timezoneOffsetInHours = $timezoneOffset / 60;
        $timezoneOffsetMinutes = ($timezoneOffsetInHours - (int)$timezoneOffsetInHours) ? 30 : 0;
        $timezoneOffset = sprintf('%+03d%02d', (int)$timezoneOffsetInHours, $timezoneOffsetMinutes);

        if (empty($dataArray[7])) {
            // log
            // display
            // values data empty
            throw new UnprocessableEntityHttpException('Markets data does not contain values info');
        }

        $firstRowValues = explode(',', $dataArray[7]);
        $firstRowData = array_combine($columns, $firstRowValues);

        $timestamp = substr($firstRowData['DATE'], 1);
        $firstRowData['DATE'] = new DateTime('@' . $timestamp);
        $firstRowData['DATE']->setTimezone(new DateTimeZone($timezoneOffset));

        $secondRowValues = explode(',', $dataArray[8]);
        $secondRowData = array_combine($columns, $secondRowValues);

        $secondRowData['DATE'] = clone $firstRowData['DATE'];

        if ($secondRowData['DATE']->format('N') == 5) {
            $modifier = '+3 day';
        } else {
            $modifier = '+1 day';
        }
        $secondRowData['DATE']->modify($modifier);

        if (!empty($dataArray[9])) {
            // 3 rows = mid-week
            $thirdRowValues = explode(',', $dataArray[9]);
            $thirdRowData = array_combine($columns, $thirdRowValues);

            $thirdRowData['DATE'] = clone $secondRowData['DATE'];

            if ($thirdRowData['DATE']->format('N') == 5) {
                $modifier = '+3 day';
            } else {
                $modifier = '+1 day';
            }
            $thirdRowData['DATE']->modify($modifier);

            $prevDayData = $secondRowData;
            $lastDayData = $thirdRowData;
        } else {
            // 2 rows = weekend
            $prevDayData = $firstRowData;
            $lastDayData = $secondRowData;
        }

        $lastDayData['CHANGE'] = round(($lastDayData['CLOSE'] - $prevDayData['CLOSE']) / $lastDayData['CLOSE'] * 100, 2);

        $extraData = [
            'EXCHANGE' => $market['exchange'],
            'TICKER' => $market['ticker']
        ];

        $lastDayData = array_merge($extraData, $lastDayData);

        return $lastDayData;
    }

    /**
     * @param $lastData
     */
    private function saveToDb($lastData) {
        $entityManager = $this->getDoctrine()->getManager();

        $marketData = new MarketData();
        $marketData->setExchange($lastData['EXCHANGE']);
        $marketData->setTicker($lastData['TICKER']);
        $marketData->setDate($lastData['DATE']);
        $marketData->setOpen($lastData['OPEN']);
        $marketData->setHigh($lastData['HIGH']);
        $marketData->setLow($lastData['LOW']);
        $marketData->setClose($lastData['CLOSE']);
        $marketData->setChange($lastData['CHANGE']);
        $marketData->setVolume($lastData['VOLUME']);

        $entityManager->persist($marketData);
    }

    /**
     * @param $title
     * @param $columns
     * @param $markets
     */
    private function sendEmail($title, $columns, $markets) {
        $serializer = $this->get('serializer');

        $xml = $serializer->serialize($markets, 'xml');

        $message = (new \Swift_Message($title))
            ->setTo('recipient@yopmail.com')
            ->setBody(
                $this->renderView('emails/letter.html.twig', compact('title', 'columns', 'markets')),
                'text/html'
            )
            ->attach(Swift_Attachment::newInstance($xml, 'today_markets.xml', 'application/xml'));

        // queue might be used here
        $this->get('mailer')->send($message);
    }
}
