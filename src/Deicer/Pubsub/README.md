# Pubsub Component
The Pubsub component is utilised by several framework components to provide transparent execution flow and opportunities for extension whilst maintaining loose coupling.
[Publish-subscribe pattern on Wikipedia][0]

Messages are implemented as immutable objects and are comprised of the following properties:

- `topic (string)` - Used to categorise messages and perform filtering
- `content (mixed)` - Arbitrary data that makes up message payload
- `publisher (PublisherInterface)` - Message originator instance
- `attributes (array)` - Optional associative array of message meta-data

Published messages are delivered and subscriptions are maintained by Message Brokers to separate concern and minimise boilerplate code in publisher implementations.
Simply implement one of the following publisher interfaces as-per example, depending on whether filtering is required.

## Unfiltered Subscription
Publishers should implement `UnfilteredPublisherInterface` and return an instance of
`UnfilteredMessageBroker` on calls to the `getUnfilteredMessageBroker()` method.

The `UnfilteredMessageBroker` delivers messages of all topics to it's subscribers.

```php
namespace My;

use My\AuthService;
use Deicer\Pubsub\Message;
use Deicer\Pubsub\MessageInterface;
use Deicer\Pubsub\SubcriberInterface;
use Deicer\Pubsub\UnfilteredPublisherInterface;

class Authenticator implements UnfilteredPublisherInterface
{
    const MAX_FAILED_ATTEMPTS = 5;
    protected $failedAttepts = 0;
    protected $unfilteredBroker;
    protected $service;

    public function __construct(UnfilteredMessageBrokerInterface $broker, AuthService $service)
    {
        $this->unfilteredBroker = $broker;
        $this->service = $service;
    }

    public function authenticate($username, $password)
    {
        // ... Invoke $service to authenticate user ...

        $messageContent = array (
            'username' => $username,
            'password' => md5($password),
            'address'  => $_SERVER['REMOTE_ADDR'],
        );

        $attribs = array ('failedAttempts' => $failedAttempts);

        // User submitted incorrect credentials
        if ($invalidPassword) {
            $this->unfilteredBroker->publish(
                new Message(
                    'auth.invalid_password', // Topic
                    $messageContent,         // Content
                    $this,                   // Publisher
                    $attribs                 // Attributes
                );
            );
        }

        // Potential brute force attack
        if ($failedAttempts > self::MAX_FAILED_ATTEMPTS) {
            $this->unfilteredBroker->publish(
                new Message(
                    'auth.failed_attempts_exceeded',
                    $messageContent,
                    $this,
                    $attribs
                );
            );

            // ... Present CAPTCHA ...
        }

        // User successfully authed
        if ($success) {
            $this->unfilteredBroker->publish(
                new Message(
                    'auth.success',
                    $messageContent,
                    $this,
                    $attribs
                );
            );

            // ... Present CAPTCHA ...
        }

        return $result;
    }

    public function getUnfilteredMessageBroker()
    {
        return $this->unfilteredBroker;
    }
}

class AuthLogger implements SubcriberInterface
{
    // ...

    public function update(MessageInterface $message)
    {
        // Record all messages
        $this->log('info', $message);
    }
}

$auth = new Authenticator(...);
$logger = new AuthLogger();

/**
 * Subscribe logger to all messages
 * Index of subscriber is returned, enabling future unsubscription
 */
$subscriberIndex = $auth->getUnfilteredMessageBroker()->addSubscriber($logger);
```

## Topic Filtered Subscription
Publishers should implement `TopicFilteredPublisherInterface` and return an instance of
`TopicFilteredMessageBroker` on calls to the `getTopicFilteredMessageBroker()` method.

The `TopicFilteredMessageBroker` selectively delivers messages by allowing subscribers to state which topics they are interested in receiving.

```php
namespace My;

use My\SmsService;
use Deicer\Pubsub\Message;
use Deicer\Pubsub\MessageInterface;
use Deicer\Pubsub\SubcriberInterface;
use Deicer\Pubsub\TopicFilteredPublisherInterface;

class OutageNotifier implements SubcriberInterface
{
    protected $smsService;

    // ...

    public function update(MessageInterface $message)
    {
        /**
         * Only outage resulting errors received - Notify of service disruption
         * When string serialized, messages appear as follows:
         * Publisher: *publisher_class* | Topic: "*topic*" | Content: *jsoned_content*
         */
        $this->smsService->sendOutageMessage('Service disruption - ' . $message);
    }
}

class ErrorHandler implements TopicFilteredPublisherInterface
{
    protected $filteredBroker;

    public function __construct(TopicFilteredMessageBroker $filteredBroker)
    {
        $this->filteredBroker = $filteredBroker;
    }

    public function getTopicFilteredMessageBroker()
    {
        return $this->filteredBroker;
    }

    public function handleError($errno, $errstr, $errfile, $errline)
    {
        // Compose message content
        $content = array (
            'errstr'  => $errstr,
            'errfile' => $errfile,
            'errline' => $errline,
        )

        $attribs = array ('timestamp' => date('Y-m-d H:i:s'));

        // Notify subscribers of error
        $this->filteredBroker->publish(
            new Message(
                'error.' . $errno, // Use unique error indentifier for filtering
                $content,
                $this,
                $attribs
            );
        );

        // ... exit if error is unrecoverable ...
    }
}

$handler  = new ErrorHandler(...);
$notifier = new OutageNotifier(...);

// Adding subscriber returns unique index - used to set up topic filters
$subscriberIndex = $handler->getTopicFilteredMessageBroker()->addSubscriber($notifier);

// Use index to subscribe OutageNotifier to only unrecoverable PHP errors
$handler->getTopicFilteredMessageBroker()->subscribeToTopics(
    $subscriberIndex,
    array (
        'error.' . E_ERROR,
        'error.' . E_PARSE,
        'error.' . E_CORE_ERROR,
        'error.' . E_COMPILE_ERROR,
        'error.' . E_USER_ERROR,
    )
);

// Redirect all PHP errors to handler for distribution as Pubsub messages
set_error_handler(array ($handler, 'handleError'));
```

## Message Builder
A Message Builder is available to ease the composition of messages and help keep your code DRY.

```php
namespace My\Mvc;

use Deicer\Pubsub\MessageBuilder;
use My\Mvc\ActionControllerInterface;

class IndexController implements ActionControllerInterface, PublisherInterface
{
    protected $messageBroker;
    protected $messageBuilder;

    // ...

    public function __construct(
        TopicFilteredMessageBrokerInterface $messageBroker,
        MessageBuilderInterface $messageBuilder
    ) {
        $this->messageBroker  = $messageBroker;
        $this->messageBuilder = $messageBuilder;
    }

    public function indexAction()
    {
        // ... Populate $this->viewModel ...

        $this->messageBuilder->withTopic('mvc.index.index')
    }

    public function aboutAction()
    {
        // ... Populate $this->viewModel ...

        $this->messageBuilder->withTopic('mvc.index.about')
    }

    public function newsAction()
    {
        // ... Populate $this->viewModel ...

        $this->messageBuilder->withTopic('mvc.index.news')
    }

    public function postDispatch()
    {
        // Build message using set topic
        $message = $this->messageBuilder
            ->withContent($this->viewModel)
            ->withPublisher($this)
            ->withAttributes($_REQUEST)
            ->build();

        // Publish built message to subscribers
        $this->messageBroker->publish($message);
    }
}
```

For more usage examples, check out the `DeicerTest\Pubsub` namespace.

[0]: http://en.wikipedia.org/wiki/Publish_subscribe "Read about the Publish-subscribe Pattern on Wikipedia"

---------------------------------------------------
Copyright (c) 2013 Alex Butucea <alex826@gmail.com>
