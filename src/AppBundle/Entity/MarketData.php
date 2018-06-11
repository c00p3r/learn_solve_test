<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * MarketData
 *
 * @ORM\Table(name="market_data")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarketDataRepository")
 */
class MarketData
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="exchange", type="string", length=255)
     */
    private $exchange;

    /**
     * @var string
     *
     * @ORM\Column(name="ticker", type="string", length=255)
     */
    private $ticker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetimetz")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="open", type="decimal", precision=10, scale=2)
     */
    private $open;

    /**
     * @var string
     *
     * @ORM\Column(name="high", type="decimal", precision=10, scale=2)
     */
    private $high;

    /**
     * @var string
     *
     * @ORM\Column(name="low", type="decimal", precision=10, scale=2)
     */
    private $low;

    /**
     * @var string
     *
     * @ORM\Column(name="close", type="decimal", precision=10, scale=2)
     */
    private $close;

    /**
     * @var string
     *
     * @ORM\Column(name="change", type="decimal", precision=10, scale=2)
     */
    private $change;

    /**
     * @var int
     *
     * @ORM\Column(name="volume", type="integer")
     */
    private $volume;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set exchange
     *
     * @param string $exchange
     *
     * @return MarketData
     */
    public function setExchange($exchange) {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * Get exchange
     *
     * @return string
     */
    public function getExchange() {
        return $this->exchange;
    }

    /**
     * Set ticker
     *
     * @param string $ticker
     *
     * @return MarketData
     */
    public function setTicker($ticker) {
        $this->ticker = $ticker;

        return $this;
    }

    /**
     * Get ticker
     *
     * @return string
     */
    public function getTicker() {
        return $this->ticker;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return MarketData
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set open
     *
     * @param string $open
     *
     * @return MarketData
     */
    public function setOpen($open) {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return string
     */
    public function getOpen() {
        return $this->open;
    }

    /**
     * Set high
     *
     * @param string $high
     *
     * @return MarketData
     */
    public function setHigh($high) {
        $this->high = $high;

        return $this;
    }

    /**
     * Get high
     *
     * @return string
     */
    public function getHigh() {
        return $this->high;
    }

    /**
     * Set low
     *
     * @param string $low
     *
     * @return MarketData
     */
    public function setLow($low) {
        $this->low = $low;

        return $this;
    }

    /**
     * Get low
     *
     * @return string
     */
    public function getLow() {
        return $this->low;
    }

    /**
     * Set close
     *
     * @param string $close
     *
     * @return MarketData
     */
    public function setClose($close) {
        $this->close = $close;

        return $this;
    }

    /**
     * Get close
     *
     * @return string
     */
    public function getClose() {
        return $this->close;
    }

    /**
     * Set volume
     *
     * @param integer $volume
     *
     * @return MarketData
     */
    public function setVolume($volume) {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return int
     */
    public function getVolume() {
        return $this->volume;
    }

    /**
     * Set change
     *
     * @param string $change
     */
    public function setChange($change) {
        $this->change = $change;
    }

    /**
     * Get change
     *
     * @return string
     */
    public function getChange() {
        return $this->change;
    }
}

