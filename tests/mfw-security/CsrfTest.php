<?php
// tests/mfw-security/CsrfTest.php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Tests for mfw-security module CSRF functions
 */
class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        // Start a clean session for each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Mock session functions for testing
        if (!function_exists('mfw_session_start')) {
            require_once __DIR__ . '/../../app/mfw-session/session.php';
        }
        
        // Load the security functions
        require_once __DIR__ . '/../../app/mfw-security/functions.php';
        
        // Start session
        mfw_session_start();
    }

    protected function tearDown(): void
    {
        // Clean up session after each test
        mfw_session_destroy();
    }

    public function testMfwCsrfGenerateCreatesToken(): void
    {
        $token = mfw_csrf_generate();
        
        $this->assertIsString($token);
        $this->assertEquals(64, strlen($token)); // 32 bytes = 64 hex chars
        $this->assertTrue(ctype_xdigit($token));
    }

    public function testMfwCsrfGenerateReusesExistingToken(): void
    {
        // Generate first token
        $token1 = mfw_csrf_generate();
        
        // Generate second token - should be the same
        $token2 = mfw_csrf_generate();
        
        $this->assertEquals($token1, $token2);
    }

    public function testMfwCsrfGetTokenReturnsStoredToken(): void
    {
        $generatedToken = mfw_csrf_generate();
        $retrievedToken = mfw_csrf_get_token();
        
        $this->assertEquals($generatedToken, $retrievedToken);
    }

    public function testMfwCsrfGetTokenReturnsEmptyStringWhenNoToken(): void
    {
        // Ensure no token exists
        mfw_session_remove('csrf_token');
        
        $token = mfw_csrf_get_token();
        $this->assertEquals('', $token);
    }

    public function testMfwCsrfValidateWithValidToken(): void
    {
        $token = mfw_csrf_generate();
        $isValid = mfw_csrf_validate($token);
        
        $this->assertTrue($isValid);
    }

    public function testMfwCsrfValidateWithInvalidToken(): void
    {
        mfw_csrf_generate(); // Create a token
        
        $isValid = mfw_csrf_validate('invalid_token_123');
        $this->assertFalse($isValid);
    }

    public function testMfwCsrfValidateWithNullToken(): void
    {
        mfw_csrf_generate(); // Create a token
        
        $isValid = mfw_csrf_validate(null);
        $this->assertFalse($isValid);
    }

    public function testMfwCsrfValidateWithEmptyString(): void
    {
        mfw_csrf_generate(); // Create a token
        
        $isValid = mfw_csrf_validate('');
        $this->assertFalse($isValid);
    }

    public function testMfwCsrfValidateWhenNoTokenExists(): void
    {
        // Ensure no token exists
        mfw_session_remove('csrf_token');
        
        $isValid = mfw_csrf_validate('any_token');
        $this->assertFalse($isValid);
    }

    public function testMfwCsrfValidateWithTimingAttackProtection(): void
    {
        $token = mfw_csrf_generate();
        
        // Test that hash_equals is used (timing attack protection)
        $start = microtime(true);
        mfw_csrf_validate('invalid_token');
        $invalidTime = microtime(true) - $start;
        
        $start = microtime(true);
        mfw_csrf_validate($token);
        $validTime = microtime(true) - $start;
        
        // The times should be very similar (within 1ms) due to hash_equals
        $this->assertLessThan(0.001, abs($invalidTime - $validTime));
    }
} 