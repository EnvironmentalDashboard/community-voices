<?php

namespace CommunityVoices\Model\Component;

use PHPUnit\Framework\TestCase;
use Exception;
use OutOfBoundsException;

class NotifierTest extends TestCase
{
    public function testAddErrorNoNotifier()
    {
        $this->expectException(Exception::class);

        $notifier = new Notifier;

        $notifier->addError('key', 'message');
    }

    public function testAddErrorNullKey()
    {
        $this->expectException(Exception::class);

        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addError(null, 'message');
    }

    public function testAddError()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addError('foo', 'bar');
        $notifier->addError('bar', 'foo');

        $this->assertTrue($notifier->hasErrors());
    }

    public function testErrorRetrieval()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addError('foo', 'bar');
        $notifier->addError('bar', 'foo');

        $expected = [
            'test' => [
                'foo' => 'bar',
                'bar' => 'foo'
            ]
        ];

        $this->assertSame($expected, $notifier->getErrors());
    }

    public function testMultipleErrorRetrieval()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addError('foo', 'bar');
        $notifier->addError('bar', 'foo');

        $notifier->setNotifier('test2');
        $notifier->addError('lorem', 'ipsum');

        $expected = [
            'test' => [
                'foo' => 'bar',
                'bar' => 'foo'
            ],
            'test2' => [
                'lorem' => 'ipsum'
            ]
        ];

        $this->assertSame($expected, $notifier->getErrors());
    }

    public function testErrorRetrievalSingleNotifier()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addError('foo', 'bar');
        $notifier->addError('bar', 'foo');

        $notifier->setNotifier('test2');
        $notifier->addError('lorem', 'ipsum');

        $expected = [
            'lorem' => 'ipsum'
        ];

        $this->assertSame($expected, $notifier->getErrorsByNotifier('test2'));
    }

    public function testErrorRetrievalSingleInvalidNotifier()
    {
        $this->expectException(OutOfBoundsException::class);
        $notifier = new Notifier;

        $notifier->getErrorsByNotifier('invalidnotifier');
    }

}
