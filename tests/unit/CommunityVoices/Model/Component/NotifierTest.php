<?php

namespace CommunityVoices\Model\Component;

use PHPUnit\Framework\TestCase;
use Exception;
use OutOfBoundsException;

class NotifierTest extends TestCase
{
    public function test_Add_Error_No_Notifier()
    {
        $this->expectException(Exception::class);

        $notifier = new Notifier;

        $notifier->addError('key', 'message');
    }

    public function test_Add_Error_Null_Key()
    {
        $this->expectException(Exception::class);

        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addError(null, 'message');
    }

    public function test_Add_Error()
    {
        $notifier = new Notifier;

        $notifier->setNotifier('test');
        $notifier->addError('foo', 'bar');
        $notifier->addError('bar', 'foo');

        $this->assertTrue($notifier->hasErrors());
    }

    public function test_Error_Retrieval()
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

    public function test_Multiple_Error_Retrieval()
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

    public function test_Error_Retrieval_Single_Notifier()
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

    public function test_Error_Retrieval_Single_Invalid_Notifier()
    {
        $this->expectException(OutOfBoundsException::class);
        $notifier = new Notifier;

        $notifier->getErrorsByNotifier('invalidnotifier');
    }

}
