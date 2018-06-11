<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @var array
     */
    public $markets = [
        [
            'exchange' => 'INDEXDJX',
            'ticker' => 'DJI'
        ],
        [
            'exchange' => 'INDEXDB',
            'ticker' => 'DAX'
        ],
        [
            'exchange' => 'INDEXBOM',
            'ticker' => 'SENSEX'
        ],
        [
            'exchange' => 'INDEXFTSE',
            'ticker' => 'UKX'
        ],
        [
            'exchange' => 'INDEXBME',
            'ticker' => 'IB'
        ],
    ];
}