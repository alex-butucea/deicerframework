# Query Component
The Query component provides several classes for your application's data access layer.
Queries are designed to provide read-only access to a back-end DB, API or other persistent data storage.
[Wikipedia Article on Command Query Separation][0]

Queries are designed to be single-responsibility data access objects to promote separation of concern in your application's data access layer.
There are four abstract query classes available, distinguished by the selection API they provide:

- `Deicer\Query\AbstractInvariableQuery` - Fetch models using a fixed selection algorithm
- `Deicer\Query\AbstractIdentifiedQuery` - Use integer based ID selection to fetch models
- `Deicer\Query\AbstractSlugizedQuery` - Use slug based selection to fetch models
- `Deicer\Query\AbstractParameterizedQuery` - Retrieve models using a set of key, value parameters

## Implementation
Extend one of the above abstract classes based on the required selection algorithm to fetch models from a data store.

### Invariable Queries

```php
namespace My\Query;

use Deicer\Query\AbstractInvariableQuery;

class FetchAllActiveListingsFromEtsy extends AbstractInvariableQuery
{
    protected function fetchData()
    {
        // Instance of My\Http\Client - cURL wrapper
        $this->dataProvider->setUri('/v2/listings/active');
        $this->dataProvider->setOpt(CURLOPT_RETURNTRANSFER, true);

        $response = $this->dataProvider->execute();

        // Validate response and return array to Model Hydrator if valid
        if (!empty($response['results']) {
            return $response['results'];
        }
    }
}
```

### Identified Queries
Identified queries provide you with a integer scalar for selection:

```php
namespace My\Query;

use Deicer\Query\AbstractIdentifiedQuery;

class GetListingFromEtsyById extends AbstractIdentifiedQuery
{
    protected function fetchData()
    {
        // ...

        $this->dataProvider->setUri('/v2/listings/' . $this->getId());

        // ...
    }
}
```

### Slugized Queries
Slugized queries provide you with a string scalar for selection:

```php
namespace My\Query;

use Deicer\Query\AbstractSlugizedQuery;

class GetActiveListingsFromEtsyByTag extends AbstractSlugizedQuery
{
    protected function fetchData()
    {
        // ...

        $this->dataProvider->setUri('/v2/listings/active?tags=' . $this->getSlug());

        // ...
    }
}
```

### Parameterized Queries
Concrete Parameterized Queries are expected to define their parameters.
This helps identify logical problems early where unexpected parameters could be referenced.

```php
namespace My\Query;

use Deicer\Query\AbstractParametizedQuery;

class SearchActiveEtsyListings extends AbstractParameterizedQuery
{
    protected $params = array (
        'limit'     => null,
        'offset'    => null,
        'page'      => null,
        'keywords'  => null,
    );

    protected function fetchData()
    {
        // ...

        $this->dataProvider->setUri('/v2/listings/active?' . http_build_query($this->getParams()));

        // ...
    }
}
```

## Expectations
Concrete queries are expected to implement the abstract method `fetchData` and have an associated model and model composite class.
Please see the Query Builder section for an easy way to compose your concrete queries and populate then with model prototypes.
`fetchData` is called on query execution and is expected to return an array that is either:

- Numerically indexed - Implies data is a set of models with each array element mapping to a single model.
- Associative - Implies data is a single model.

The array returned from `fetchData` is then used to hydrate and return either a single model or composite using the prototypes specified.
An exception will be thrown, should any of the above expectations not be met, unless there is a decorated query available to fall back to.
Please see the Decorator API section for a guide on how to create fall back hierarchies using queries.

## Data Provider Injection
In order to ease testability, you may wish to inject a data provider into your queries that can be mocked.
To do this, simply implement the method `setDataProvider` and internalise the passed value to the protected property `dataProvider`.
Calling execute with an empty `dataProvider` property on queries that implement `setDataProvider`, will cause an exception to be thrown.
This is to provide early indication of a logical defect and increase problem traceability.

## Query Builder
The Query Builder simplifies the composition of your concrete queries by abstracting framework details:

```php
namespace My\Query;

use Deicer\Query\AbstractInvariableQuery;
use Deicer\Query\Exception\ExceptionInterface as QueryException;

class FetchAllListingsFromEtsy extends AbstractInvariableQuery
{
    public function setDataProvider(My\Http\Client $provider)
    {
        $this->dataProvider = $provider;
    }

    // ...
}

class FetchAllListingsFromLocalDb extends AbstractInvariableQuery
{
    public function setDataProvider(My\Mysql\Client $provider)
    {
        $this->dataProvider = $provider;
    }

    // ...
}

class FetchAllListingsFromCache extends AbstractInvariableQuery
{
    public function setDataProvider(My\Memcache\Client $provider)
    {
        $this->dataProvider = $provider;
    }

    // ...
}

namespace My\Model;

use Deicer\Model\AbstractModel;
use Deicer\Model\AbstractModelComposite;
use Deicer\Query\QueryBuilder;

class Listing extends AbstractModel 
{
    public $listing_id;
    public $title;
    public $description;
    public $price;
    public $tags;
}

class Listings extends AbstractModelComposite
{
}

$builder = new QueryBuilder('My\Query');
$builder
    ->withModelPrototype(new Listing())
    ->withModelCompositePrototype(new Listings())

$cacheQuery = $builder
    ->withDataProvider(new \My\Memcache\Client())
    ->build('FetchAllListingsFromCache');

$localDbQuery = $builder
    ->withDataProvider(new \My\Mysql\Client())
    ->build('FetchAllListingsFromLocalDb');

$etsyQuery = $builder
    ->withDataProvider(new \My\Http\Client())
    ->build('FetchAllListingsFromEtsy');
```

## Decorator API
A decorator pattern is implemented to allow for queries of the same interface to decorate each other.
Query execution will then fall back to the decorated child query, should the parent execution fail for any reason.
Fall back strategies using the above decorator API can be easily set up to fetch data from progressively less volatile data stores:

```php
$cacheQuery   = new My\Query\FetchAllListingsFromCache(...);
$localDbQuery = new My\Query\FetchAllListingsFromLocalDb(...);
$etsyQuery    = new My\Query\FetchAllListingsFromEtsy(...);

$localDbQuery->decorate($etsyQuery);
$cacheQuery->decorate($localDbQuery);

/**
 * Cache query will fall back to local DB on cache miss
 * Local DB will fall back to Etsy API on empty result
 * Etsy query will throw exception on empty result
 */
try {
    $listings = $cacheQuery->execute();
} catch (QueryException $e) {
    // Render graceful error
}
```

Please note, Parameterized Queries will throw an exception when asked to decorate another query that doesn't share the same parameters.
This stops potentially undesirable behaviour where incompatible queries could dilute selection criteria and cause silent problems that only manifest at runtime.

## PubSub API
Queries implement both unfiltered and topic-filters publisher interfaces; `Deicer\PubSub\UnfilteredPublisherInterface` and `Deicer\PubSub\TopicFilteredPublisherInterface`.
Whenever a query is executed, a PubSub message is published to any subscribers with the content being the data returned from the `fetchData` implementation.
Subscribers need only implement `Deicer\Pubsub\SubscriberInterface` to subscribe to key message types / topics raised from a query's execution.

```php
namespace My;

use Deicer\Pubsub\MessageInterface;
use Deicer\Pubsub\SubcriberInterface;
use Deicer\Query\Message\MessageTopic;
use Deicer\Query\Exception\ExceptionInterface as QueryException;

class Logger implements SubcriberInterface
{
    public function update(MessageInterface $message)
    {
        /**
         * Only failure messages are received - log as error
         * Please see the Pubsub component readme for breakdown of message serialization format
         */
        $this->log('error', (string) $message);
    }
}

class Debugger implements SubcriberInterface
{
    public function update(MessageInterface $message)
    {
        /**
         * All messages are received
         * Add message to a pretty message trace for later rendering
         */
        $this->addToTrace((string) $message);
    }
}

$query    = new \My\Query\FetchAllActiveListingsFromEtsy(...);
$logger   = new Logger();
$debugger = new Debugger();

// Subscribe logger to only query failure messages
$loggerIndex = $query->getTopicFilteredMessageBroker()->addSubscriber($logger);
$query->getTopicFilteredMessageBroker()->subscribeToTopics(
    $loggerIndex,
    array (
        MessageTopic::FAILURE_DATA_TYPE,
        MessageTopic::FAILURE_DATA_EMPTY,
        MessageTopic::FAILURE_DATA_FETCH,
        MessageTopic::FAILURE_MODEL_HYDRATOR,
        MessageTopic::FAILURE_MISSING_DATA_PROVIDER,
    )
);

// Subscribe debugger to messages of all topics
$debuggerIndex = $query->getUnfilteredMessageBroker()->addSubscriber($debugger);

try {
    $listings = $cacheQuery->execute();
} catch (QueryException $e) {

    // Render graceful error if not in production environment
    if (getenv('ENVIRONMENT' != 'live') {
        $debugger->renderTrace();
    }
}
```
Identified and Slugized queries set a supplementary message attribute denoting what the `id` / `slug` selector was set to at the time of execution,
Parameterized queries capture selection criteria at the time of execution in a similar way by dumping all parameters into message attributes.
For a more detailed explanation of the Pubsub component, please see the README.md contained under `src/Deicer/Pubsub`.

For concrete examples of the above, check out the `DeicerTestAsset\Query` namespace.

[0]: http://en.wikipedia.org/wiki/Command_query_separation "Read about the concept of Command Query Separation on Wikipedia"

---------------------------------------------------
Copyright (c) 2013 Alex Butucea <alex826@gmail.com>
