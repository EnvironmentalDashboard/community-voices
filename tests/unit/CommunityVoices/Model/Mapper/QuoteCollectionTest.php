<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\QuoteCollection
 */
class QuoteCollectionTest extends TestCase
{
    public function test_fetchAll()
    {
        $collection = new Entity\QuoteCollection;
        $collection->forMediaType(Entity\QuoteCollection::MEDIA_TYPE_QUOTE);

        $pdo = $this
            ->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statement = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $arrToReturn = [
            [	
            	'id' => 6,
            	'type' => Entity\QuoteCollection::MEDIA_TYPE_QUOTE,
            	'attribution' => 'Left Person',
            	'text' => 'Snow'
            ],
            [
            	'id' => 4, 
            	'type' => Entity\QuoteCollection::MEDIA_TYPE_QUOTE, 
            	'attribution' => 'Right Bird', 
            	'text' => 'now'
            ]
        ];

        $statement
            ->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue($arrToReturn));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new QuoteCollection($pdo);
        $mapper->fetch($collection);

        foreach ($collection as $key => $entry) {
            $this->assertSame($entry->getId(), $arrToReturn[$key]['id']);
            $this->assertSame($entry->getType(), $arrToReturn[$key]['type']);
            $this->assertSame($entry->getText(), $arrToReturn[$key]['text']);
            $this->assertSame($entry->getAttribution(), $arrToReturn[$key]['attribution']);
        }
    }
}
