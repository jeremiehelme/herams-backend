<?php


namespace prime\components;


use prime\exceptions\SurveyDoesNotExist;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\di\Instance;

class LimesurveyDataProvider extends Component
{
    public $cacheResponses;
    /** @var CacheInterface */
    public $cache = 'cache';

    /** @var Client */
    public $client = 'limesurvey';

    public $responseCacheDuration = 3600;

    public function init()
    {
        parent::init();
        if ($this->cache === 'cache') {
            throw new InvalidConfigException();
        }
        $this->client = Instance::ensure($this->client, Client::class);
        $this->cache = Instance::ensure($this->cache, CacheInterface::class);
    }

    public function createToken(
        int $surveyId,
        string $token
    ) {
        return $this->client->createToken($surveyId, [
            'token' => $token
        ]);
    }


    public function getToken(int $surveyId, string $token)
    {
        return $this->client->getToken($surveyId, $token);
    }

    public function getResponsesByTokenFromCache(int $surveyId, string $token): ?iterable
    {
        \Yii::beginProfile(__FUNCTION__);
        $key = $this->responsesCacheKey($surveyId, $token);
        $result = $this->cache->get($key) ;
        if ($result === false) {
            $result = null;
        }

        \Yii::info('CACHE ' . ($result ? 'HIT' : 'MISS') . ' for ' . $key, __CLASS__);
        \Yii::endProfile(__FUNCTION__);
        return $result;
    }

    public function surveyCacheTime(int $surveyId): ?int
    {
        $result = $this->cache->get($this->responsesCacheKey($surveyId). 'present');
        return is_int($result) ? $result : null;
    }

    public function tokenCacheTime(int $surveyId, string $token): ?int
    {
        $result = $this->cache->get($this->responsesCacheKey($surveyId, $token). 'present');
        return is_int($result) ? $result : null;
    }

    protected function responsesCacheKey(int $surveyId, ?string $token = null): string
    {
        return 'LDPrsps' . $surveyId . $token;
    }

    /**
     * Get all responses in a survey and store them in the cache.
     * This function never uses the cache for reading.
     * @param int $surveyId
     * @return iterable
     */
    public function refreshResponsesByToken(int $surveyId, string $token): iterable
    {
        \Yii::beginProfile($surveyId. '|| ' . $token, __FUNCTION__);
        $result = $this->client->getResponsesByToken($surveyId, $token);
        $key = $this->responsesCacheKey($surveyId, $token);
        $this->cache->multiSet([
            $key => $result,
            "{$key}present" => time()
        ], $this->responseCacheDuration);


        \Yii::endProfile($surveyId. '|| ' . $token, __FUNCTION__);
        return $result;
    }

    /**
     * @param int $surveyId
     * @param string $token
     * @return iterable|ResponseInterface[]
     */
    public function getResponsesByToken(int $surveyId, string $token): iterable
    {
        return $this->getResponsesByTokenFromCache($surveyId, $token) ?? $this->refreshResponsesByToken($surveyId, $token);
    }


    public function getSurvey(int $surveyId): SurveyInterface
    {
        try {
            return $this->client->getSurvey($surveyId);
        } catch (\Exception $e) {
            throw SurveyDoesNotExist::fromClient($e);
        }
    }

    public function getUrl(int $surveyId, array $params = []): string
    {
        return $this->client->getUrl($surveyId, $params);
    }

    public function listSurveys(): array
    {
        return $this->client->listSurveys();
    }

    /** @return TokenInterface[] */
    public function getTokens(int $surveyId): array
    {
        return $this->client->getTokens($surveyId);
    }
}