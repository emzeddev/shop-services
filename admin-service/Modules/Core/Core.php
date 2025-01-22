<?php

namespace Modules\Core;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\LocaleRepository;
use Modules\Core\Models\Channel;
use Modules\Core\Repositories\ChannelRepository;



class Core
{

    public function __construct(
        protected ChannelRepository $channelRepository,
        protected LocaleRepository $localeRepository,
    ) {}


    /**
     * Current Locale.
     *
     * @var \Webkul\Core\Models\Locale
     */
    protected $currentLocale;


    /**
     * Default Channel.
     *
     * @var \Webkul\Core\Models\Channel
     */
    protected $defaultChannel;



    /**
     * Current Channel.
     *
     * @var \Webkul\Core\Models\Channel
     */
    protected $currentChannel;


    /**
     * Return all locales.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllLocales()
    {
        return $this->localeRepository->all()->sortBy('name');
    }

    /**
     * Get locale code from request. Here if you want to use admin locale,
     * you can pass it as an argument.
     *
     * @param  string  $localeKey  optional
     * @param  bool  $fallback  optional
     * @return string
     */
    public function getRequestedLocaleCode($localeKey = 'locale', $fallback = true)
    {
        $localeCode = request()->get($localeKey);

        if (! $fallback) {
            return $localeCode;
        }

        return $localeCode ?: app()->getLocale();
    }

    /**
     * Returns current locale.
     *
     * @return \Webkul\Core\Contracts\Locale
     */
    public function getCurrentLocale()
    {
        if ($this->currentLocale) {
            return $this->currentLocale;
        }

        $this->currentLocale = $this->localeRepository->findOneByField('code', app()->getLocale());

        if (! $this->currentLocale) {
            $this->currentLocale = $this->localeRepository->findOneByField('code', config('app.fallback_locale'));
        }

        return $this->currentLocale;
    }

    /**
     * Returns default locale code from default channel.
     */
    public function getDefaultLocaleCodeFromDefaultChannel(): string
    {
        return $this->getDefaultChannel()->default_locale->code;
    }


    /**
     * Returns default channel models.
     *
     * @return \Webkul\Core\Contracts\Channel
     */
    public function getDefaultChannel(): ?Channel
    {
        if ($this->defaultChannel) {
            return $this->defaultChannel;
        }

        $this->defaultChannel = $this->channelRepository->findOneByField('code', config('app.channel'));

        if ($this->defaultChannel) {
            return $this->defaultChannel;
        }

        return $this->defaultChannel = $this->channelRepository->first();
    }

    /**
     * Get channel code from request.
     *
     * @return \Webkul\Core\Contracts\Channel
     */
    public function getRequestedChannel()
    {
        $code = request()->query('channel');

        if ($code) {
            return $this->channelRepository->findOneByField('code', $code);
        }

        return $this->getCurrentChannel();
    }

    /**
     * Returns current channel models.
     *
     * @return \Webkul\Core\Contracts\Channel
     */
    public function getCurrentChannel(?string $hostname = null)
    {
        if (! $hostname) {
            $hostname = request()->getHttpHost();
        }

        if ($this->currentChannel) {
            return $this->currentChannel;
        }

        $this->currentChannel = $this->channelRepository->findWhereIn('hostname', [
            $hostname,
            'http://'.$hostname,
            'https://'.$hostname,
        ])->first();

        if (! $this->currentChannel) {
            $this->currentChannel = $this->channelRepository->first();
        }

        return $this->currentChannel;
    }
}
