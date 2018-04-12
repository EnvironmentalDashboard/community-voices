<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\Cookieable;

class RememberedIdentity implements Cookieable
{
    private $accountId;

    private $key;

    private $series;

    private $expiresOn;

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getSeries()
    {
        return $this->series;
    }

    public function setSeries($series)
    {
        $this->series = $series;
    }

    public function getExpiresOn()
    {
        return $this->expiresOn;
    }

    public function setExpiresOn($expiresOn)
    {
        $this->expiresOn = $expiresOn;
    }

    public function toJson()
    {
        $arr = [
            'accountId' => $this->accountId,
            'key' => $this->key,
            'series' => $this->series,
            'expiresOn' => $this->expiresOn
        ];

        return json_encode($arr);
    }

    public function getUniqueLabel()
    {
        return 'rememberedIdentity';
    }

    public function toArray()
    {
        return ['rememberedIdentity' => [
            'accountId' => $this->accountId,
            'key' => $this->key,
            'series' => $this->series,
            'expiresOn' => $this->expiresOn
        ]];
    }
}
