<?php
/**
 * Created by PhpStorm.
 * User: jetro
 * Date: 2016.03.15.
 * Time: 22:58


 */

namespace Gtin;

/**
 * Class Processor
 * @package Gtin
 */
class Gtin
{
    /**
     * @var int
     */
    protected $gtinNumber;

    protected $fullGtinNumber;

    protected $alowedLength = [7,11,12,13];

    protected $imputLegnht;

    protected $checksum;

    /**
     * Processor constructor.
     * @param $gtin int
     */
    public function __construct($gtin = null)
    {
        If($gtin !== null)
        {
            $this->setGtinNumber($gtin);
        }
    }

    protected function gtinNull()
    {
        if($this->gtinNumber === null)
        {
            throw new NoGtinNrException('No Gtin number set', 1001);
        }
    }

    /**
     * @param null $gtin
     * @return float|string
     */
    protected function calculateChecksum()
    {
        $this->checksum = null;
        try
        {
            $this->gtinNull();
            $sumOdd = 0;
            $sumEven = 0;

            $gtinArray  = array_map('intval', str_split($this->gtinNumber));

            foreach($gtinArray as $key => $item)
            {
                $pos = ($key+1) % 2;
                if($pos)
                {
                    $sumOdd += $item;
                } else {
                    $sumEven += $item;
                }
            }

            $total =  $sumOdd + ($sumEven*3);
            $this->checksum = (ceil($total/10));

            $this->setFullGtinNumber($this->checksum);

        }
        catch (NoGtinNrException $e)
        {
            return json_encode([$e->getCode() => $e->getMessage()]);
        }
        catch (Exception $e)
        {
            return json_encode([$e->getCode() => $e->getMessage()]);
        }

        return $this->checksum;

    }

    /**
     * @return int
     */
    public function getChecksum($number = null)
    {
        if(!$this->checksum)
        {
            $this->getFullGtinNumber($number);
        }

        return $this->checksum;
    }

    /**
     * @param $number int
     */
    public function setGtinNumber($number)
    {
        try{
            if($number === null)
            {
                throw new NoGtinNrException('No Gtin number set', 1001);
            }
            $setNumber = $this->checkLength($number);
            if ($setNumber === true) {
                $this->gtinNumber = $number;
                return true;
            }
        }
        catch (InvalidGtinNrException $e)
        {
            return json_encode([$e->getCode() => $e->getMessage()]);
        }
        catch (NoGtinNrException $e)
        {
            return json_encode([$e->getCode() => $e->getMessage()]);
        }
    }

    /**
     * @return int
     */
    public function getGtinNumber()
    {
        return $this->gtinNumber;
    }

    /**
     * @param $number int
     * @return bool
     */
    private function checkLength($number)
    {
        $this->imputLength = strlen((string)$number);
        if(in_array($this->imputLength, $this->alowedLength))
        {
            return true;
        }
        else
        {
            throw new InvalidGtinNrException('Invalid Gtin number leght', 1002);
        }
    }

    /**
     * @param $checksum int
     */
    protected function setFullGtinNumber($checksum)
    {
        $this->fullGtinNumber = $this->gtinNumber . $checksum;
    }

    /**
     * @return int
     */
    public function getFullGtinNumber($number = null)
    {
        $this->setGtinNumber($number);

        if(is_null($this->fullGtinNumber))
        {
            $result = $this->calculateChecksum();
        }
        if($this->checksum)
        {
            return $this->fullGtinNumber;
        }

        return $result;

    }
}