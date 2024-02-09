<?php

use Pagewerx\UswerxApiPhp\Context;
use Pagewerx\UswerxApiPhp\Logging\DefaultLogger;
use PHPUnit\Framework\TestCase;

class DefaultLoggerTest extends TestCase
{
    public static function setupBeforeClass(): void
    {
        echo "Setting up DefaultLoggerTest\n";
        $context = Context::getInstance();
        $context->settings(
            'test_token',
            'https://example.com',
            new DefaultLogger(null, 'phpunit.log'),
            false,
            true,
            null
        );
    }

    public static function tearDownAfterClass(): void
    {
        echo "Tearing down DefaultLoggerTest\n";
        try {
            echo "unlinking ".Context::getInstance()->getLogger()->getLogPath()."info-".date('m-d-Y').".log\n";
            unlink(Context::getInstance()->getLogger()->getLogPath()."info-".date('m-d-Y').".log");
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        try {
            echo "unlinking ".Context::getInstance()->getLogger()->getLogPath()."error-".date('m-d-Y').".log\n";
            unlink(Context::getInstance()->getLogger()->getLogPath()."error-".date('m-d-Y').".log");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function testDefaultLoggerCanSetFilename(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->setFilename('test.log');
        $this->assertSame('test.log', $logger->getFilename());
    }

    public function testDefaultLoggerCanSetLogPath(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->setLogPath('/tmp');
        $this->assertSame('/tmp', $logger->getLogPath());
        $logger->setLogPath(null);
    }

    // add test for debug log level
    public function testDefaultLoggerCanWriteDebugLevelLogs(): void
    {
        try {
            $context = Context::getInstance();
            $logger = $context->getLogger();
            $logger->debug('test log', ['test' => 'test']);
            $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."info-".date('m-d-Y').".log"));
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->fail($e->getMessage());
        }
        //unlink($context->getLogger()->getLogPath().'debug-test.log');
    }
    public function testDefaultLoggerCanWriteInfoLevelLogs(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->info('test log', ['test' => 'test']);
        $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."info-".date('m-d-Y').".log"));
    }

    public function testDefaultLoggerCanWriteNoticeLevelLogs(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->notice('test log', ['test' => 'test']);
        $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."info-".date('m-d-Y').".log"));
    }

    public function testDefaultLoggerCanWriteErrorLevelLogs(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->error('test log', ['test' => 'test']);
        $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."error-".date('m-d-Y').".log"));
    }

    public function testDefaultLoggerCanWriteWarningLevelLogs(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->warning('test log', ['test' => 'test']);
        $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."error-".date('m-d-Y').".log"));
    }

    public function testDefaultLoggerCanWriteCriticalLevelLogs(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->critical('test log', ['test' => 'test']);
        $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."error-".date('m-d-Y').".log"));
    }

    public function testDefaultLoggerCanWriteAlertLevelLogs(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->alert('test log', ['test' => 'test']);
        $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."error-".date('m-d-Y').".log"));
    }

    public function testDefaultLoggerCanWriteEmergencyLevelLogs(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->emergency('test log', ['test' => 'test']);
        $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."error-".date('m-d-Y').".log"));
    }

    public function testDefaultLoggerCanLogCustomLevelsToCustomFilenames(): void
    {
        $context = Context::getInstance();
        $logger = $context->getLogger();
        $logger->setFilename('unit-tests.log');
        $logger->log('test log', 50, ['test' => 'test']);
        $this->assertStringContainsString('test log', file_get_contents($logger->getLogPath()."unit-tests.log"));
        unlink($logger->getLogPath()."unit-tests.log");
    }

}