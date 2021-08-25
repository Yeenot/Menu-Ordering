<?php


namespace App\Core\Services\Orders;

use App\Core\Services\Orders\IsOrderCodeExist;

class GenerateOrderCode
{
    protected $isOrderCodeExist;

    public function __construct(IsOrderCodeExist $isOrderCodeExist)
    {
        $this->isOrderCodeExist = $isOrderCodeExist;
    }

    public function execute()
    {
        do{
            $number = mt_rand(10000000, 99999999);
            $code = "O{$number}";
        } while($this->isOrderCodeExist->execute($code));

        return $code;
    }
}