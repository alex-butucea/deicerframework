<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Query\Exception\InvalidArgumentException;
use Deicer\Query\AbstractQuery;
use Deicer\Query\SlugizedQueryInterface;

/**
 * {@inheritdoc}
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractSlugizedQuery extends AbstractQuery implements
     SlugizedQueryInterface
{
    /**
     * Selection slug
     * 
     * @var string
     */
    protected $slug = '';

    /**
     * {@inheritdoc}
     */
    public function decorate(SlugizedQueryInterface $decorable)
    {
        $this->decorated = $decorable;
        $this->syncDecorated();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        if (!is_string($slug)) {
            throw new InvalidArgumentException(
                'Non-string $slug passed in: ' .
                get_called_class() . '::' . __FUNCTION__
            );
        }

        $this->slug = $slug;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    protected function syncDecorated()
    {
        if ($this->decorated) {
            $this->decorated->setSlug($this->slug);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupplementaryMessageAttributes()
    {
        return array ('slug' => $this->slug);
    }
}
