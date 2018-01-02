<?php

namespace CommunityVoices\Model\Component;

use PHPUnit\Framework\TestCase;
use Exception;
use OutOfBoundsException;

/**
 * @covers CommunityVoices\Model\Component\StateObserver
 */
class StateObserverTest extends TestCase
{
    public function test_Add_Entry_No_StateObserver()
    {
        $this->expectException(Exception::class);

        $stateObserver = new StateObserver;

        $stateObserver->addEntry('key', 'message');
    }

    public function test_Add_Entry_Null_Key()
    {
        $this->expectException(Exception::class);

        $stateObserver = new StateObserver;

        $stateObserver->setSubject('test');
        $stateObserver->addEntry(null, 'message');
    }

    public function test_Add_Entry()
    {
        $stateObserver = new StateObserver;

        $stateObserver->setSubject('test');
        $stateObserver->addEntry('foo', 'bar');
        $stateObserver->addEntry('bar', 'foo');

        $this->assertTrue($stateObserver->hasEntries());
    }

    public function test_Entry_Retrieval()
    {
        $stateObserver = new StateObserver;

        $stateObserver->setSubject('test');
        $stateObserver->addEntry('foo', 'bar');
        $stateObserver->addEntry('bar', 'foo');

        $expected = [
            'test' => [
                'foo' => ['bar'],
                'bar' => ['foo']
            ]
        ];

        $this->assertSame($expected, $stateObserver->getEntries());
    }

    public function test_Multiple_Entry_Retrieval()
    {
        $stateObserver = new StateObserver;

        $stateObserver->setSubject('test');
        $stateObserver->addEntry('foo', 'bar');
        $stateObserver->addEntry('bar', 'foo');

        $stateObserver->setSubject('test2');
        $stateObserver->addEntry('lorem', 'ipsum');

        $expected = [
            'test' => [
                'foo' => ['bar'],
                'bar' => ['foo']
            ],
            'test2' => [
                'lorem' => ['ipsum']
            ]
        ];

        $this->assertSame($expected, $stateObserver->getEntries());
    }

    public function test_Entry_Retrieval_Single_StateObserver()
    {
        $stateObserver = new StateObserver;

        $stateObserver->setSubject('test');
        $stateObserver->addEntry('foo', 'bar');
        $stateObserver->addEntry('bar', 'foo');

        $stateObserver->setSubject('test2');
        $stateObserver->addEntry('lorem', 'ipsum');

        $expected = [
            'lorem' => ['ipsum']
        ];

        $this->assertSame($expected, $stateObserver->getEntriesBySubject('test2'));
    }

    public function test_Multiple_Entries_Same_Key()
    {
        $stateObserver = new StateObserver;

        $stateObserver->setSubject('foo');
        $stateObserver->addEntry('fruit', 'oranges');
        $stateObserver->addEntry('fruit', 'grapes');

        $expected = [
            'foo' => [
                'fruit' => ['oranges', 'grapes']
            ]
        ];

        $this->assertSame($expected, $stateObserver->getEntries());
    }

    public function test_Entry_Retrieval_Single_Invalid_StateObserver()
    {
        $this->expectException(OutOfBoundsException::class);
        $stateObserver = new StateObserver;

        $stateObserver->getEntriesBySubject('invalidstateObserver');
    }

    public function test_Entry_Search()
    {
        $stateObserver = new StateObserver;
        $stateObserver->setSubject('foo');

        $stateObserver->addEntry('fruit', 'oranges');
        $stateObserver->addEntry('fruit', 'grapes');

        $this->assertTrue($stateObserver->hasEntry('fruit'));
        $this->assertTrue($stateObserver->hasEntry('fruit', 'oranges'));
        $this->assertTrue($stateObserver->hasEntry('fruit', 'grapes'));
        $this->assertFalse($stateObserver->hasEntry('vegetables'));
    }

    public function test_Entry_Search_No_StateObserver()
    {
        $stateObserver = new StateObserver;

        $this->expectException(Exception::class);

        $stateObserver->hasEntry('blah');
    }

    public function test_Entry_Search_No_Key()
    {
        $stateObserver = new StateObserver;
        $stateObserver->setSubject('foo');

        $this->expectException(Exception::class);

        $stateObserver->hasEntry(null);
    }

    public function test_Entry_Search_No_Entries()
    {
        $stateObserver = new StateObserver;
        $stateObserver->setSubject('blah');

        $this->assertFalse($stateObserver->hasEntry('blah'));
    }
}
