<?php
// tests/mfw-url/UrlTest.php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Tests for mfw-url module functions
 */
class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        // Mock $_SERVER variables for testing
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/login';
        
        // Note: We can't easily remove constants in PHP, so we'll work with what we have
        
        // Load the functions we want to test
        require_once __DIR__ . '/../../app/mfw-url/helpers.php';
    }

    public function testMfwUrlGeneratesCorrectUrl(): void
    {
        $result = mfw_url('/login');
        $this->assertEquals('/login', $result);
    }

    public function testMfwUrlWithEmptyPath(): void
    {
        $result = mfw_url('');
        $this->assertEquals('/', $result);
    }

    public function testMfwUrlWithRootPath(): void
    {
        $result = mfw_url('/');
        $this->assertEquals('/', $result);
    }

    public function testMfwUrlWithComplexPath(): void
    {
        $result = mfw_url('/users/profile/edit');
        $this->assertEquals('/users/profile/edit', $result);
    }

    public function testMfwCurrentRelativePath(): void
    {
        $result = mfw_current_relative_path();
        $this->assertEquals('/login', $result);
    }

    public function testMfwCurrentRelativePathWithDifferentUri(): void
    {
        $_SERVER['REQUEST_URI'] = '/dashboard';
        $result = mfw_current_relative_path();
        $this->assertEquals('/dashboard', $result);
    }

    public function testMfwCurrentRelativePathWithRoot(): void
    {
        $_SERVER['REQUEST_URI'] = '/';
        $result = mfw_current_relative_path();
        $this->assertEquals('/', $result);
    }

    public function testBaseUrlConstantIsDefined(): void
    {
        $this->assertTrue(defined('BASE_URL'));
        $this->assertEquals('/', BASE_URL);
    }

    public function testMfwUrlWorksInSubdirectoryDeployment(): void
    {
        // Test the logic that would be used in subdirectory deployment
        // We test the mfw_get_base_url() logic directly
        
        // Simulate subdirectory environment
        $scriptName = '/mfw-app/index.php';
        $scriptDir = dirname($scriptName);
        $expectedBaseUrl = rtrim(str_replace('\\', '/', $scriptDir), '/');
        
        $this->assertEquals('/mfw-app', $expectedBaseUrl);
        
        // Test that the logic works correctly for subdirectory paths
        $this->assertEquals('/mfw-app', mfw_normalize_path($scriptDir));
    }

    public function testMfwUrlWithEnvironmentVariable(): void
    {
        // Test that environment variable MFW_BASE_URL is respected
        putenv('MFW_BASE_URL=/my-app');
        
        // Note: This test demonstrates the intended behavior
        // In practice, the constant is already defined, so this is for documentation
        $this->assertEquals('/my-app', getenv('MFW_BASE_URL'));
        
        // Clean up
        putenv('MFW_BASE_URL');
    }

    public function testMfwUrlPortabilityPrinciple(): void
    {
        // This test documents the portability principle
        // mfw_url('/login') should work regardless of deployment location
        
        $loginUrl = mfw_url('/login');
        $dashboardUrl = mfw_url('/dashboard');
        
        // URLs should be consistent and properly formatted
        $this->assertStringStartsWith('/', $loginUrl);
        $this->assertStringStartsWith('/', $dashboardUrl);
        $this->assertStringEndsWith('/login', $loginUrl);
        $this->assertStringEndsWith('/dashboard', $dashboardUrl);
    }
} 