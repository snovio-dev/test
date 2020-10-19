<?php

namespace App\Http\Requests\Auth\AuthValidationRules;

use Illuminate\Contracts\Validation\Rule;
use App\BannedDomain\DomainRepository;


/**
 * Class DomainValidationRule
 * @package App\Http\Requests\Auth\AuthValidationRules
 */
class DomainValidationRule implements Rule
{
    /**
     * @var DomainRepository
     */
    protected $domainRepository;

    /**
     * DomainValidationRule constructor.
     * @param DomainRepository $domainRepository
     */
    public function __construct(DomainRepository $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return !is_null($this->domainRepository->domainIn($value));
    }
}
