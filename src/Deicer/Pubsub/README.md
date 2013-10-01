# Pubsub Component
The Pubsub component is utilised by several framework components to provide transparent execution flow and opportunities for extension whilst maintaining loose coupling.
[Publish-subscribe pattern on Wikipedia][0]

Published messages are delivered by Message Brokers to separate concern and minimise boilerplate code in publisher implementations.

## Unfiltered Subscription
The `UnfilteredMessageBroker` delivers messages of all topics to it's subscribers.

```php
namespace My;

use My\AuthService;
use Deicer\Stdlib\Pubsub\Message;
use Deicer\Stdlib\Pubsub\MessageInterface;
use Deicer\Stdlib\Pubsub\SubcriberInterface;
use Deicer\Stdlib\Pubsub\UnfilteredPublisherInterface;

class Authenticator implements UnfilteredPublisherInterface
{
    const MAX_RETRIES = 5;
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
        ),

        // User submitted incorrect credentials
        if ($invalidPassword) {
            $this->unfilteredBroker->publish(
                new Message(
                    'auth.invalid_password', // Topic
                    $messageContent,         // Content
                    $this                    // Publisher
                );
            );
        }

        // Potential brute force attack
        if ($retries > self::MAX_RETRIES) {
            $this->unfilteredBroker->publish(
                new Message(
                    'auth.retries_exceeded',
                    $messageContent,
                    $this
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
                    $this
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
The `TopicFilteredMessageBroker` selectively delivers messages by allowing subscribers to state which topics they are interested in receiving.

```php
namespace My;

use My\SmsService;
use Deicer\Stdlib\Pubsub\Message;
use Deicer\Stdlib\Pubsub\MessageInterface;
use Deicer\Stdlib\Pubsub\SubcriberInterface;
use Deicer\Stdlib\Pubsub\TopicFilteredPublisherInterface;

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

        // Notify subscribers of error
        $this->filteredBroker->publish(
            new Message(
                'error.' . $errno, // Use unique error indentifier for filtering
                $content,
                $this
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
