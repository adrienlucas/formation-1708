<?php

declare(strict_types=1);

namespace App\Gateway;


use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class NasaApiCacheableGateway extends NasaApiGateway
{
    /** @var NasaApiGateway */
    private $actualGateway;

    /** @var CacheInterface */
    private $cache;

    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(
        NasaApiGateway $actualGateway,
        CacheInterface $cache,
        SluggerInterface $slugger
    )
    {
        $this->actualGateway = $actualGateway;
        $this->cache = $cache;
        $this->slugger = $slugger;
    }

    public function isEarthInDanger(): bool
    {
        return $this->cache->get(
            $this->slugger->slug(self::class)->toString(),
            function(ItemInterface $cache){
                $cache->expiresAfter(20);
                return $this->actualGateway->isEarthInDanger();
            });
    }
}
