# Query Component
The Query component provides several classes for your application's data access layer.
Queries are designed to provide read-only access to a back-end DB, API or other persistent data storage.
[Wikipedia Article on Command Query Separation][0]

Queries are designed to be single-responsibility data access objects to promote separation of concern in your application's data access layer.
There are three abstract query classes available, distinguished by the selection API they provide:

- `Deicer\Query\AbstractInvariableQuery` - Fetch models using a fixed selection algorithm
- `Deicer\Query\AbstractTokenizedQuery` - Use slug/ID based selection to fetch models
- `Deicer\Query\AbstractParameterizedQuery` - Retrieve models using a set of key, value parameters

## Implementation
Extend one of the above abstract classes based on the selection algorithm required to fetch models from a data store.

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

### Tokenized Queries
Tokenized queries provide you with a single scalar for selection:

```php
namespace My\Query;

use Deicer\Query\AbstractTokenizedQuery;

class FetchSingleListingFromEtsy extends AbstractTokenizedQuery
{
    protected function fetchData()
    {
        // Instance of My\Http\Client - cURL wrapper
        $this->dataProvider->setUri('/v2/listings/' . (int) $this->getToken());
        $this->dataProvider->setOpt(CURLOPT_RETURNTRANSFER, true);

        $response = $this->dataProvider->execute();

        // Validate response and return array to Model Hydrator if valid
        if (!empty($response['results']) {
            return $response['results'];
        }
    }
}
```

### Parameterized Queries
Concrete Parameterized Queries are expected to define their parameters.
This helps identify logical problems early where unexpected parameters could be referenced:

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
        // Instance of My\Http\Client - cURL wrapper
        $this->dataProvider->setUri('/v2/listings/active?' . http_build_query($this->getParams()));
        $this->dataProvider->setOpt(CURLOPT_RETURNTRANSFER, true);

        $response = $this->dataProvider->execute();

        // Validate response and return array to Model Hydrator if valid
        if (!empty($response['results']) {
            return $response['results'];
        }
    }
}
```

Concrete queries are expected to implement the abstract method `fetchData` and have an associated model and model composite class.
A [Query Builder](#query-builder) is available to ease the composition of your concrete queries.
`fetchData` is called on query execution and is expected to return an array that meets the following criteria:

- Numerically indexed<a id="fetch-data-criteria"></a>
- Elements must be associative arrays
- Keys of associative arrays must match properties of associated model class

An exception will be thrown, should any of the above expectations not be met, unless there is a [Decorated](#decorator-api) query available to fall back to.
The array returned is then used to hydrate and return a set of models using the prototypes specified.

## Query Builder<a id="query-builder"></a>
The Query Builder simplifies the composition of your concrete queries by abstracting framework details:

```php
namespace My\Query;

use Deicer\Query\AbstractInvariableQuery;
use Deicer\Query\Exception\ExceptionInterface as QueryException;

class FetchAllListingsFromEtsy extends AbstractInvariableQuery
{
    ...
}

class FetchAllListingsFromLocalDb extends AbstractInvariableQuery
{
    ...
}

class FetchAllListingsFromCache extends AbstractInvariableQuery
{
    ...
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

## Decorator API<a id="decorator-api"></a>
A decorator pattern is implemented to allow for queries of the same interface to decorate each other.
Query execution will then fall back to the decorated child query, should the parent execution fail.
The following conditions are classified as a failure and therefore cause a query to fall back to it's decorated instance if available:

- Concrete implementation of `fetchData` throws an exception
- `fetchData` returns a non-array
- Array returned by `fetchData` doesn't meet the following [Criteria](#fetch-data-criteria)

Fall back strategies using the above decorator API can be easily set up to fetch data from progressively less volatile data stores:

```php
$cacheQuery   = new My\Query\FetchAllListingsFromCache(...);
$localDbQuery = new My\Query\FetchAllListingsFromLocalDb(...);
$etsyQuery    = new My\Query\FetchAllListingsFromEtsy(...);

$localDbQuery->decorate($etsyQuery);
$cacheQuery->decorate($localDbQuery);

/**
 * Cache query will fall back to local db on cache miss
 * Local db will fall back to Etsy API on empty result
 * Etsy query will throw exception on empty result
 */
try {
    $listings = $cacheQuery->execute();
} catch (QueryException $e) {
    // Render graceful error
}
```

## PubSub API
Whenever a query is executed, a PubSub message is published to any subscribers with the content being the data returned from the `fetchData` implementation.
Subscribers need only implement `Deicer\Stdlib\Pubsub\SubscriberInterface` to subscribe to key message types / topics raised from a query's execution:

```php
namespace My;

use Deicer\Stdlib\Pubsub\MessageInterface;
use Deicer\Stdlib\Pubsub\SubcriberInterface;
use Deicer\Query\Exception\ExceptionInterface as QueryException;
use Deicer\Query\Message\QueryMessageInterface as QueryMessage;

class Logger implements SubcriberInterface
{
    public function update(MessageInterface $message)
    {
        /**
         * Query messages are string serialized as either:
         *
         * Invariable Query Execution: *concrete_class* | Result: "*topic*" | Elapsed Time: *time*ms | Content: *jsoned_content*
         * Tokenized Query Execution: *concrete_class* | Result: "*topic*" | Elapsed Time: *time*ms | Token: "*token*" | Content: *jsoned_content*
         * Parameterized Query Execution: *concrete_class* | Result: "*topic*" | Elapsed Time: *time*ms | Params: *jsoned_params* | Content: *jsoned_content*
         */
         $message = (string) $message;

        // Only failure messages are received - log as error
        $this->write(LOG_ERR, $message);
    }
}

$query  = new \My\Query\FetchAllActiveListingsFromEtsy(...);
$logger = new Logger();

// Subscribe logger to only query failure messages
$query->
    subscribe($logger, QueryMessage::TOPIC_FAILURE_DATA_TYPE)
    subscribe($logger, QueryMessage::TOPIC_FAILURE_DATA_FETCH)
    subscribe($logger, QueryMessage::TOPIC_FAILURE_MODEL_HYDRATOR);

try {
    $listings = $cacheQuery->execute();
} catch (QueryException $e) {
    // Render graceful error
}
```

For more concrete examples of the above, check out the `DeicerTest\Query` namespace.

[0]: http://en.wikipedia.org/wiki/Command_query_separation "Read about the concept of Command Query Separation on Wikipedia"

---------------------------------------------------
Copyright (c) 2013 Alex Butucea <alex826@gmail.com>
