<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use stdClass;
use DeicerTestAsset\Query\TestableSlugizedQueryWithValidFetchData;
use DeicerTestAsset\Query\TestableSlugizedQueryWithExceptionThrowingFetchData;
use DeicerTestAsset\Query\TestableSlugizedQueryWithNonArrayReturningFetchData;
use DeicerTestAsset\Query\TestableSlugizedQueryWithModelIncompatibleFetchData;
use DeicerTest\Query\AbstractQueryTest;

/**
 * Deicer Slugized Query tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class SlugizedQueryTest extends AbstractQueryTest
{
    public function setUpFixture()
    {
        $this->fixture =
            new TestableSlugizedQueryWithValidFetchData(
                new stdClass(),
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
            new TestableSlugizedQueryWithExceptionThrowingFetchData(
                new stdClass(),
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
            new TestableSlugizedQueryWithNonArrayReturningFetchData(
                new stdClass(),
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
            new TestableSlugizedQueryWithModelIncompatibleFetchData(
                new stdClass(),
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
            'Deicer\Query\SlugizedQueryInterface'
        );

        return $this;
    }

    public function testPublishedMessagesContainSlugAttribute()
    {
        $content = array (
            array (
                'id'   => 1,
                'name' => 'foo',
            ),
            array (
                'id'   => 2,
                'name' => 'bar',
            ),
        );

        $this->setUpMessageBuilder('success', $content, array ('slug' => 'foo'));
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixture();
        $this->fixture->setSlug('foo');
        $this->fixture->execute();
    }

    public function testSetSlugImplementsFluentInterface()
    {
        $actual = $this->fixture->setSlug('foo');
        $this->assertSame($actual, $this->fixture);
    }

    public function testSetSlugWithNonStringThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
        $actual = $this->fixture->setSlug(array (1, 2, 3, 4));
    }

    public function testSetSlugInternalisesSlug()
    {
        $this->fixture->setSlug('foo');
        $this->assertSame('foo', $this->fixture->getSlug());
    }
}
