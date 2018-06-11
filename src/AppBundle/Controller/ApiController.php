<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MarketData;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 *
 * @package AppBundle\Controller
 *
 * @Route("/api")
 */
class ApiController extends BaseController
{
    /**
     * @Route("/chart_data", name="chart_data")
     */
    public function chartDataAction() {
        $entityManager = $this->getDoctrine()->getManager();

        $serializer = $this->get('serializer');

        $limit = count(array_column($this->markets, 'exchange'));

        $records = $entityManager
            ->getRepository(MarketData::class)
            ->findBy([], ['date' => 'DESC'], $limit);

        $data = $serializer->serialize($records, 'json');

        return new Response($data);
    }
}