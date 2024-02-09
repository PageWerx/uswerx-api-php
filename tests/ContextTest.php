<?php

use Pagewerx\UswerxApiPhp\Context;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{

    public function setup(): void
    {
        $context = Context::getInstance();
        $context->settings(
            'test_token',
            'https://example.com',
            null,
            false,
            true,
            null
        );
    }

    public function testContextReturnsAnInstance(): void
    {
        $context = Context::getInstance();
        $this->assertSame(Context::class, get_class($context));
    }

    public function testContextCanEnableTestMode(): void
    {
        $context = Context::getInstance();
        $context->enableTestMode();
        $this->assertTrue($context->testMode());
    }

    public function testContextCanDisableTestMode(): void
    {
        $context = Context::getInstance();
        $context->disableTestMode();
        $this->assertFalse($context->testMode());
    }

    public function testContextCanEnableDebug(): void
    {
        $context = Context::getInstance();
        $context->enableDebug();
        $this->assertTrue($context->debugMode());
    }

    public function testContextCanDisableDebug(): void
    {
        $context = Context::getInstance();
        $context->disableDebug();
        $this->assertFalse($context->debugMode());
    }

    public function testContextReturnsToken(): void
    {
        $context = Context::getInstance();
        $this->assertSame('test_token', $context->getToken());
    }


}