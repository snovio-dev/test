<?php

namespace App\Repostories\Domains;

use App\BannedDomain;

/**
 * Class DomainRepository
 * @package App\Repostories\Domains
 */
class DomainRepository
{
    /**
     * @var BannedDomain
     */
    protected $model;

    /**
     * DomainRepository constructor.
     * @param BannedDomain $model
     */
    public function __construct(BannedDomain $model)
    {
        $this->model = $model;
    }

    /**
     * @param $domain
     * @return mixed
     */
    public function domainIn($domain)
    {
        return $this->model->where('domain', $domain)->first();
    }
}
