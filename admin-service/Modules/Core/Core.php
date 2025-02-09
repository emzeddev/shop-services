<?php

namespace Modules\Core;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\LocaleRepository;
use Modules\Core\Models\Channel;
use Modules\Core\Repositories\ChannelRepository;
use Modules\Core\Repositories\CurrencyRepository;
use Modules\Core\Repositories\ExchangeRateRepository;
use Modules\Core\Concerns\CurrencyFormatter;



class Core
{

    use CurrencyFormatter;

    public function __construct(
        protected ChannelRepository $channelRepository,
        protected LocaleRepository $localeRepository,
        protected CurrencyRepository $currencyRepository,
        protected ExchangeRateRepository $exchangeRateRepository,
    ) {}


    /**
     * Current Locale.
     *
     * @var \Modules\Core\Models\Locale
     */
    protected $currentLocale;


    /**
     * Default Channel.
     *
     * @var \Modules\Core\Models\Channel
     */
    protected $defaultChannel;



    /**
     * Current Channel.
     *
     * @var \Modules\Core\Models\Channel
     */
    protected $currentChannel;


    /**
     * Currency.
     *
     * @var \Modules\Core\Models\Currency
     */
    protected $currentCurrency;


     /**
     * Exchange rates
     *
     * @var array
     */
    protected $exchangeRates = [];

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
     * @return \Modules\Core\Contracts\Locale
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
     * @return \Modules\Core\Contracts\Channel
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
     * @return \Modules\Core\Contracts\Channel
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
     * Returns current channel code.
     *
     * @return \Modules\Core\Contracts\Channel
    */
    public function getCurrentChannelCode(): string
    {
        return $this->getCurrentChannel()?->code;
    }

    /**
     * Returns current channel models.
     *
     * @return \Modules\Core\Contracts\Channel
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

    /**
     * Returns the default channel code configured in `config/app.php`.
     */
    public function getDefaultChannelCode(): string
    {
        return $this->getDefaultChannel()?->code;
    }

    /**
     * Returns base channel's currency model.
     *
     * @return \Modules\Core\Contracts\Currency
     */
    public function getChannelBaseCurrency()
    {
        return $this->getCurrentChannel()->base_currency;
    }

    /**
     * Returns current channel's currency model.
     *
     * Will fallback to base currency if not set.
     *
     * @return \Modules\Core\Contracts\Currency
     */
    public function getCurrentCurrency()
    {
        if ($this->currentCurrency) {
            return $this->currentCurrency;
        }

        return $this->currentCurrency = $this->getChannelBaseCurrency();
    }


    /**
     * Returns current channel's currency code.
     *
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        return $this->getCurrentCurrency()?->code;
    }

    /**
     * Get channel code from request.
     *
     * @param  bool  $fallback  optional
     * @return string
     */
    public function getRequestedChannelCode($fallback = true)
    {
        $channelCode = request()->get('channel');

        if (! $fallback) {
            return $channelCode;
        }

        return $channelCode ?: ($this->getCurrentChannelCode() ?: $this->getDefaultChannelCode());
    }


    /**
     * Retrieve information from payment configuration.
     */
    public function getConfigData(string $field, ?string $currentChannelCode = null, ?string $currentLocaleCode = null): mixed
    {
        return system_config()->getConfigData($field, $currentChannelCode, $currentLocaleCode);
    }

    /**
     * Get config field.
     *
     * @param  string  $fieldName
     * @return array
     */
    public function getConfigField($fieldName)
    {
        return system_config()->getConfigField($fieldName);
    }

    /**
     * Returns exchange rates.
     *
     * @return object
     */
    public function getExchangeRate($targetCurrencyId)
    {
        if (array_key_exists($targetCurrencyId, $this->exchangeRates)) {
            return $this->exchangeRates[$targetCurrencyId];
        }

        return $this->exchangeRates[$targetCurrencyId] = $this->exchangeRateRepository->findOneWhere([
            'target_currency' => $targetCurrencyId,
        ]);
    }


     /**
     * Converts price.
     *
     * @param  float  $amount
     * @param  string  $targetCurrencyCode
     * @return string
     */
    public function convertPrice($amount, $targetCurrencyCode = null)
    {
        $targetCurrency = ! $targetCurrencyCode
            ? $this->getCurrentCurrency()
            : $this->currencyRepository->findOneByField('code', $targetCurrencyCode);

        if (! $targetCurrency) {
            return $amount;
        }

        $exchangeRate = $this->getExchangeRate($targetCurrency->id);

        if (! $exchangeRate) {
            return $amount;
        }

        return (float) $amount * $exchangeRate->rate;
    }

    /**
     * Format and convert price with currency symbol.
     *
     * @param  float  $price
     * @return string
     */
    public function currency($amount = 0)
    {
        if (is_null($amount)) {
            $amount = 0;
        }

        return $this->formatPrice($this->convertPrice($amount));
    }


     /**
     * Format price.
     */
    public function formatPrice(?float $price, ?string $currencyCode = null): string
    {
        if (is_null($price)) {
            $price = 0;
        }

        $currency = $currencyCode
            ? $this->getAllCurrencies()->where('code', $currencyCode)->first()
            : $this->getCurrentCurrency();

        return $this->formatCurrency($price, $currency);
    }
    
    /**
     * Returns all currencies.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllCurrencies()
    {
        return $this->currencyRepository->all();
    }

     /**
     * Returns all channels.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllChannels()
    {
        return $this->channelRepository->all();
    }


}
