<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;
use Exception;
use OutOfBoundsException;

class NotifierTest extends TestCase
{
    public function test_Add_Entry_No_Notifier()
    {
        $this->expectException(Exception::class);

        $notifier = new Notifier;

        $notifier->addEntry('key', 'message');
    }

    public function test_Add_Entry_Null_Key()
    {
        $this->expectException(Exception::class);

        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addEntry(null, 'message');
    }

    public function test_Add_Entry()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addEntry('foo', 'bar');
        $notifier->addEntry('bar', 'foo');

        $this->assertTrue($notifier->hasEntries());
    }

    public function test_Entry_Retrieval()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addEntry('foo', 'bar');
        $notifier->addEntry('bar', 'foo');

        $expected = [
            'test' => [
                'foo' => ['bar'],
                'bar' => ['foo']
            ]
        ];

        $this->assertSame($expected, $notifier->getEntries());
    }

    public function test_Multiple_Entry_Retrieval()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addEntry('foo', 'bar');
        $notifier->addEntry('bar', 'foo');

        $notifier->setNotifier('test2');
        $notifier->addEntry('lorem', 'ipsum');

        $expected = [
            'test' => [
                'foo' => ['bar'],
                'bar' => ['foo']
            ],
            'test2' => [
                'lorem' => ['ipsum']
            ]
        ];

        $this->assertSame($expected, $notifier->getEntries());
    }

    public function test_Entry_Retrieval_Single_Notifier()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addEntry('foo', 'bar');
        $notifier->addEntry('bar', 'foo');

        $notifier->setNotifier('test2');
        $notifier->addEntry('lorem', 'ipsum');

        $expected = [
            'lorem' => ['ipsum']
        ];

        $this->assertSame($expected, $notifier->getEntriesByNotifier('test2'));
    }

    public function test_Multiple_Entries_Same_Key()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('foo');
        $notifier->addEntry('fruit', 'oranges');
        $notifier->addEntry('fruit', 'grapes');

        $expected = [
            'foo' => [
                'fruit' => ['oranges', 'grapes']
            ]
        ];

        $this->assertSame($expected, $notifier->getEntries());
    }

    public function test_Entry_Retrieval_Single_Invalid_Notifier()
    {
        $this->expectException(OutOfBoundsException::class);
        $notifier = new Notifier;

        $notifier->getEntriesByNotifier('invalidnotifier');
    }

    public function test_Entry_Search()
    {
        $notifier = new Notifier;
        $notifier->setNotifier('foo');

        $notifier->addEntry('fruit', 'oranges');
        $notifier->addEntry('fruit', 'grapes');

        $this->assertTrue($notifier->hasEntry('fruit'));
        $this->assertTrue($notifier->hasEntry('fruit', 'oranges'));
        $this->assertTrue($notifier->hasEntry('fruit', 'grapes'));
        $this->assertFalse($notifier->hasEntry('vegetables'));
    }

    public function test_Entry_Search_No_Notifier()
    {
        $notifier = new Notifier;

        $this->expectException(Exception::class);

        $notifier->hasEntry('blah');
    }

    public function test_Entry_Search_No_Key()
    {
        $notifier = new Notifier;
        $notifier->setNotifier('foo');

        $this->expectException(Exception::class);

        $notifier->hasEntry(null);
    }

    public function test_Entry_Search_No_Entries()
    {
        $notifier = new Notifier;
        $notifier->setNotifier('blah');

        $this->assertFalse($notifier->hasEntry('blah'));
    }
}
