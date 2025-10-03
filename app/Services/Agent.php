<?php

namespace App\Services;

use Closure;
use Detection\Cache\CacheException;
use Detection\Exception\MobileDetectException;
use Detection\MobileDetect;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Override;

/**
 * @copyright Originally created by Jens Segers: https://github.com/jenssegers/agent
 */
class Agent extends MobileDetect
{
    /**
     * A type for the version() method indicating a string return value.
     */
    protected const string VERSION_TYPE_STRING = 'text';

    /**
     * A type for the version() method indicating a float return value.
     */
    protected const string VERSION_TYPE_FLOAT = 'float';

    /**
     * List of desktop devices.
     *
     * @var array
     */
    protected static array $desktopDevices = [
        'Macintosh' => 'Macintosh',
    ];

    /**
     * List of additional operating systems.
     *
     * @var array<string, string>
     */
    protected static array $additionalOperatingSystems = [
        'Windows'    => 'Windows',
        'Windows NT' => 'Windows NT',
        'OS X'       => 'Mac OS X',
        'Debian'     => 'Debian',
        'Ubuntu'     => 'Ubuntu',
        'Macintosh'  => 'PPC',
        'OpenBSD'    => 'OpenBSD',
        'Linux'      => 'Linux',
        'ChromeOS'   => 'CrOS',
    ];

    /**
     * List of additional browsers.
     *
     * @var array<string, string>
     */
    protected static array $additionalBrowsers = [
        'Opera Mini' => 'Opera Mini',
        'Opera'      => 'Opera|OPR',
        'Edge'       => 'Edge|Edg',
        'Coc Coc'    => 'coc_coc_browser',
        'UCBrowser'  => 'UCBrowser',
        'Vivaldi'    => 'Vivaldi',
        'Chrome'     => 'Chrome',
        'Firefox'    => 'Firefox',
        'Safari'     => 'Safari',
        'IE'         => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Netscape'   => 'Netscape',
        'Mozilla'    => 'Mozilla',
        'WeChat'     => 'MicroMessenger',
    ];

    /**
     * List of additional properties.
     *
     * @var array
     */
    protected static array $additionalProperties = [
        // Operating systems
        'Windows'      => 'Windows NT [VER]',
        'Windows NT'   => 'Windows NT [VER]',
        'macOS'        => 'OS X [VER]',
        'BlackBerryOS' => ['BlackBerry[\w]+/[VER]', 'BlackBerry.*Version/[VER]', 'Version/[VER]'],
        'AndroidOS'    => 'Android [VER]',
        'ChromeOS'     => 'CrOS x86_64 [VER]',

        // Browsers
        'Opera Mini' => 'Opera Mini/[VER]',
        'Opera'      => [' OPR/[VER]', 'Opera Mini/[VER]', 'Version/[VER]', 'Opera [VER]'],
        'Netscape'   => 'Netscape/[VER]',
        'Mozilla'    => 'rv:[VER]',
        'IE'         => ['IEMobile/[VER];', 'IEMobile [VER]', 'MSIE [VER];', 'rv:[VER]'],
        'Edge'       => ['Edge/[VER]', 'Edg/[VER]'],
        'Vivaldi'    => 'Vivaldi/[VER]',
        'Coc Coc'    => 'coc_coc_browser/[VER]',
    ];

    /**
     * Key value store for resolved strings.
     *
     * @var array<string, mixed>
     */
    protected array $store = [];

    /**
     * @var CrawlerDetect
     */
    protected static CrawlerDetect $crawlerDetect;

    protected ?array $temporaryHttpHeaders = null;

    public function getExtendedRules(): array
    {
        static $rules;

        if (! $rules) {
            $rules = static::mergeRules(
                static::$desktopDevices, // NEW
                static::$phoneDevices,
                static::$tabletDevices,
                static::$operatingSystems,
                static::$additionalOperatingSystems, // NEW
                static::$browsers,
                static::$additionalBrowsers, // NEW
                //                static::$utilities
            );
        }

        return $rules;
    }

    /**
     * @return CrawlerDetect
     */
    public function getCrawlerDetect(): CrawlerDetect
    {
        return static::$crawlerDetect ??= new CrawlerDetect;
    }

    #[Override]
    public static function getBrowsers(): array
    {
        return static::mergeRules(
            static::$additionalBrowsers,
            static::$browsers
        );
    }

    #[Override]
    public static function getOperatingSystems(): array
    {
        return static::mergeRules(
            static::$operatingSystems,
            static::$additionalOperatingSystems
        );
    }

    public static function getPlatforms(): array
    {
        return static::mergeRules(
            static::$operatingSystems,
            static::$additionalOperatingSystems
        );
    }

    public static function getDesktopDevices(): array
    {
        return static::$desktopDevices;
    }

    #[Override]
    public static function getProperties(): array
    {
        return static::mergeRules(
            static::$additionalProperties,
            static::$properties
        );
    }

    /**
     * Get accept languages.
     *
     * @param string|null $acceptLanguage
     *
     * @return array
     */
    public function languages(?string $acceptLanguage = null): array
    {
        if ($acceptLanguage === null) {
            $acceptLanguage = $this->getHttpHeader('HTTP_ACCEPT_LANGUAGE');
        }

        if (! $acceptLanguage) {
            return [];
        }

        $languages = [];

        // Parse accept language string.
        foreach (explode(',', $acceptLanguage) as $piece) {
            $parts = explode(';', $piece);
            $language = strtolower($parts[0]);
            $priority = blank($parts[1]) ? 1. : (float) str_replace('q=', '', $parts[1]);

            $languages[$language] = $priority;
        }

        // Sort languages by priority.
        arsort($languages);

        return array_keys($languages);
    }

    /**
     * Get the platform name from the User Agent.
     *
     * @param string|null $userAgent
     *
     * @throws CacheException
     *
     * @return string|null
     */
    public function platform(?string $userAgent = null): ?string
    {
        return $this->retrieveUsingCacheOrResolve('app.platform', fn (): bool|string => $this->findDetectionRulesAgainstUA(static::getPlatforms(), $userAgent));
    }

    /**
     * Get the browser name from the User Agent.
     *
     * @param string|null $userAgent
     *
     * @throws CacheException
     *
     * @return string|null
     */
    public function browser(?string $userAgent = null): ?string
    {
        return $this->retrieveUsingCacheOrResolve('app.browser', fn (): bool|string => $this->findDetectionRulesAgainstUA(static::getBrowsers(), $userAgent));
    }

    /**
     * Determine if the device is a desktop computer.
     *
     * @throws CacheException
     * @throws MobileDetectException
     *
     * @return bool|null
     */
    public function isDesktop(?string $userAgent = null, ?array $httpHeaders = null): ?bool
    {
        return $this->overrideUAAndHeaders(
            $userAgent,
            $httpHeaders,
            function (?string $userAgent) {
                // Check specifically for cloudfront headers if the useragent === 'Amazon CloudFront'
                if (
                    $userAgent === static::$cloudFrontUA
                    && $this->getHttpHeader('HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER') === 'true'
                ) {
                    return true;
                }

                return ! $this->isMobile()
                    && ! $this->isTablet()
                    && ! $this->isRobot($userAgent);
            }
        );
    }

    /**
     * Get the robot name.
     *
     * @param string|null $userAgent
     *
     * @return string|bool
     */
    public function robot(?string $userAgent = null): bool|string
    {
        if ($this->getCrawlerDetect()->isCrawler($userAgent ?: $this->userAgent)) {
            return ucfirst((string) $this->getCrawlerDetect()->getMatches());
        }

        return false;
    }

    /**
     * Check if device is a robot.
     *
     * @param string|null $userAgent
     *
     * @return bool
     */
    public function isRobot(?string $userAgent = null): bool
    {
        return $this->getCrawlerDetect()->isCrawler($userAgent ?: $this->userAgent);
    }

    /**
     * Get the device type
     *
     * @param string|null $userAgent
     * @param array|null  $httpHeaders
     *
     * @throws MobileDetectException|CacheException
     *
     * @return string
     */
    public function deviceType(?string $userAgent = null, ?array $httpHeaders = null): string
    {
        if ($this->isDesktop($userAgent, $httpHeaders)) {
            return 'desktop';
        }

        if ($this->isPhone($userAgent, $httpHeaders)) {
            return 'phone';
        }

        if ($this->isTablet()) {
            return 'tablet';
        }

        if ($this->isRobot($userAgent)) {
            return 'robot';
        }

        return 'other';
    }

    #[Override]
    public function version(string $propertyName, string $type = self::VERSION_TYPE_STRING): float|bool|string
    {
        if (blank($propertyName)) {
            return false;
        }

        // set the $type to the default if we don't recognize the type
        if ($type !== self::VERSION_TYPE_STRING && $type !== self::VERSION_TYPE_FLOAT) {
            $type = self::VERSION_TYPE_STRING;
        }

        $properties = self::getProperties();

        // Check if the property exists in the properties array.
        if (isset($properties[$propertyName]) === true) {
            // Prepare the pattern to be matched.
            // Make sure we always deal with an array (string is converted).
            $properties[$propertyName] = (array) $properties[$propertyName];

            foreach ($properties[$propertyName] as $propertyMatchString) {
                if (is_array($propertyMatchString)) {
                    $propertyMatchString = implode('|', $propertyMatchString);
                }

                $propertyPattern = str_replace('[VER]', self::VERSION_REGEX, $propertyMatchString);

                // Identify and extract the version.
                preg_match(sprintf('#%s#is', $propertyPattern), (string) $this->userAgent, $match);

                if (blank($match[1]) === false) {
                    $version = ($type === self::VERSION_TYPE_FLOAT ? $this->prepareVersionNo($match[1]) : $match[1]);

                    return $version;
                }
            }
        }

        return false;
    }

    /**
     * Match a detection rule and return the matched key.
     *
     * @param array       $rules
     * @param string|null $userAgent
     *
     * @return string|bool
     */
    protected function findDetectionRulesAgainstUA(array $rules, ?string $userAgent = null): bool|string
    {
        // Begin a general search.
        foreach ($rules as $key => $_regex) {
            if (blank($_regex)) {
                continue;
            }

            // regex is an array of "strings"
            if (is_array($_regex)) {
                foreach ($_regex as $regexString) {
                    if ($this->match($regexString, $userAgent)) {
                        return $key;
                    }
                }
            } else {
                // assume regex is "string"
                if ($this->match($_regex, $userAgent)) {
                    return $key ?: reset($this->matchesArray);
                }
            }
        }

        return false;
    }

    #[Override]
    public function match(string $regex, ?string $userAgent = null): bool
    {
        $userAgent ??= $this->userAgent;

        return parent::match($regex, $userAgent);
    }

    /**
     * Retrieve from the given key from the cache or resolve the value.
     *
     * @param string  $key
     * @param Closure $callback
     *
     * @throws CacheException
     *
     * @return mixed
     */
    protected function retrieveUsingCacheOrResolve(string $key, Closure $callback): mixed
    {
        $cacheKey = $this->createCacheKey($key);

        if (! is_null($cacheItem = $this->store[$cacheKey] ?? null)) {
            return $cacheItem;
        }

        return tap(call_user_func($callback), function ($result) use ($cacheKey): void {
            $this->store[$cacheKey] = $result;
        });
    }

    /**
     * Merge multiple rules into one array.
     *
     * @param array $all
     *
     * @return array<string, string>
     */
    protected static function mergeRules(...$all): array
    {
        $merged = [];

        foreach ($all as $rules) {
            foreach ($rules as $key => $value) {
                if (! array_key_exists($key, $merged)) {
                    $merged[$key] = $value;
                } elseif (is_array($merged[$key])) {
                    $merged[$key][] = $value;
                } else {
                    $merged[$key] .= '|'.$value;
                }
            }
        }

        return $merged;
    }

    protected function overrideUAAndHeaders(
        ?string $userAgent,
        ?array $temporaryHttpHeaders,
        Closure $callback
    ) {
        $ua = $this->userAgent;

        if ($userAgent !== null) {
            $this->userAgent = $userAgent;
        }

        $this->temporaryHttpHeaders = $temporaryHttpHeaders;

        $result = $callback($this->userAgent, $this->temporaryHttpHeaders ?? $this->httpHeaders);

        $this->temporaryHttpHeaders = null;

        $this->userAgent = $ua;

        return $result;
    }

    #[Override]
    public function getHttpHeader(string $header): ?string
    {
        if ($this->temporaryHttpHeaders !== null) {
            // are we using PHP-flavored headers?
            if (! str_contains($header, '_')) {
                $header = str_replace('-', '_', $header);
                $header = strtoupper($header);
            }

            // test the alternate, too
            $altHeader = 'HTTP_'.$header;

            // Test both the regular and the HTTP_ prefix
            if (isset($this->temporaryHttpHeaders[$header])) {
                return $this->temporaryHttpHeaders[$header];
            } elseif (isset($this->temporaryHttpHeaders[$altHeader])) {
                return $this->temporaryHttpHeaders[$altHeader];
            }

            return null;
        }

        return parent::getHttpHeader($header);
    }

    #[Override]
    protected function matchUserAgentWithRule(string $ruleName): bool
    {
        $result = false;
        // Make the keys lowercase, so we can match: isIphone(), isiPhone(), isiphone(), etc.
        $ruleName = strtolower($ruleName);
        // change the keys to lower case
        $_rules = array_change_key_case($this->getExtendedRules());

        if (blank($_rules[$ruleName]) === false) {
            $regexString = $_rules[$ruleName];
            if (is_array($_rules[$ruleName])) {
                $regexString = implode('|', $_rules[$ruleName]);
            }

            $result = $this->match($regexString, $this->getUserAgent());
        }

        return $result;
    }
}
