<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use DeicerTestAsset\Query\TestableInvariableQueryWithValidFetchData;
use DeicerTestAsset\Query\TestableInvariableQueryWithExceptionThrowingFetchData;
use DeicerTestAsset\Query\TestableInvariableQueryWithNonArrayReturningFetchData;
use DeicerTestAsset\Query\TestableInvariableQueryWithEmptyArrayReturningFetchData;
use DeicerTestAsset\Query\TestableInvariableQueryWithModelIncompatibleFetchData;
use DeicerTestAsset\Query\TestableInvariableQueryWithDataProviderDependency;
use DeicerTest\Query\AbstractQueryTest;

/**
 * Deicer Invariable Query tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class InvariableQueryTest extends AbstractQueryTest
{
    public function setUpFixture()
    {
        $this->fixture =
            new TestableInvariableQueryWithValidFetchData(
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithExceptionThrowingFetchData()
    {
        $this->fixtureWithExceptionThrowingFetchData =
            new TestableInvariableQueryWithExceptionThrowingFetchData(
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithNonArrayReturningFetchData()
    {
        $this->fixtureWithNonArrayReturningFetchData =
            new TestableInvariableQueryWithNonArrayReturningFetchData(
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithEmptyArrayReturningFetchData()
    {
        $this->fixtureWithEmptyArrayReturningFetchData =
            new TestableInvariableQueryWithEmptyArrayReturningFetchData(
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithModelIncompatibleFetchData()
    {
        $this->fixtureWithModelIncompatibleFetchData =
            new TestableInvariableQueryWithModelIncompatibleFetchData(
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithDataProviderDependency()
    {
        $this->fixtureWithDataProviderDependency =
            new TestableInvariableQueryWithDataProviderDependency(
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpMockFixture()
    {
        $this->mockFixture = $this->getMock(
            'Deicer\Query\InvariableQueryInterface'
        );

        return $this;
    }
}
