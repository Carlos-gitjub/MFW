<?php
// tests/mfw-session/SessionTest.php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Tests for mfw-session module functions
 */
class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        // Clean up any existing session before each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Load the session functions
        require_once __DIR__ . '/../../app/mfw-session/session.php';
    }

    protected function tearDown(): void
    {
        // Clean up session after each test
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * Test mfw_session_start() initializes session with secure settings
     */
    public function testMfwSessionStartInitializesSession(): void
    {
        $this->assertEquals(PHP_SESSION_NONE, session_status());
        
        mfw_session_start();
        
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());
    }

    /**
     * Test mfw_session_start() doesn't restart an active session
     */
    public function testMfwSessionStartDoesNotRestartActiveSession(): void
    {
        mfw_session_start();
        $originalId = session_id();
        
        mfw_session_start(); // Should not restart
        
        $this->assertEquals($originalId, session_id());
    }

    /**
     * Test mfw_session_get() returns default value for non-existent key
     */
    public function testMfwSessionGetReturnsDefaultForNonExistentKey(): void
    {
        mfw_session_start();
        
        $result = mfw_session_get('non_existent_key', 'default_value');
        $this->assertEquals('default_value', $result);
    }

    /**
     * Test mfw_session_get() returns null as default when not specified
     */
    public function testMfwSessionGetReturnsNullAsDefault(): void
    {
        mfw_session_start();
        
        $result = mfw_session_get('non_existent_key');
        $this->assertNull($result);
    }

    /**
     * Test mfw_session_get() returns stored value
     */
    public function testMfwSessionGetReturnsStoredValue(): void
    {
        mfw_session_start();
        
        $_SESSION['test_key'] = 'test_value';
        
        $result = mfw_session_get('test_key');
        $this->assertEquals('test_value', $result);
    }

    /**
     * Test mfw_session_set() stores value in session
     */
    public function testMfwSessionSetStoresValue(): void
    {
        mfw_session_start();
        
        mfw_session_set('test_key', 'test_value');
        
        $this->assertEquals('test_value', $_SESSION['test_key']);
    }

    /**
     * Test mfw_session_set() overwrites existing value
     */
    public function testMfwSessionSetOverwritesExistingValue(): void
    {
        mfw_session_start();
        
        mfw_session_set('test_key', 'original_value');
        mfw_session_set('test_key', 'new_value');
        
        $this->assertEquals('new_value', $_SESSION['test_key']);
    }

    /**
     * Test mfw_session_set() with different data types
     */
    public function testMfwSessionSetWithDifferentDataTypes(): void
    {
        mfw_session_start();
        
        // String
        mfw_session_set('string_key', 'string_value');
        $this->assertEquals('string_value', mfw_session_get('string_key'));
        
        // Integer
        mfw_session_set('int_key', 42);
        $this->assertEquals(42, mfw_session_get('int_key'));
        
        // Array
        $array = ['a' => 1, 'b' => 2];
        mfw_session_set('array_key', $array);
        $this->assertEquals($array, mfw_session_get('array_key'));
        
        // Boolean
        mfw_session_set('bool_key', true);
        $this->assertTrue(mfw_session_get('bool_key'));
        
        // Null
        mfw_session_set('null_key', null);
        $this->assertNull(mfw_session_get('null_key'));
    }

    /**
     * Test mfw_session_has() returns true for existing key
     */
    public function testMfwSessionHasReturnsTrueForExistingKey(): void
    {
        mfw_session_start();
        
        mfw_session_set('test_key', 'test_value');
        
        $this->assertTrue(mfw_session_has('test_key'));
    }

    /**
     * Test mfw_session_has() returns false for non-existent key
     */
    public function testMfwSessionHasReturnsFalseForNonExistentKey(): void
    {
        mfw_session_start();
        
        $this->assertFalse(mfw_session_has('non_existent_key'));
    }

    /**
     * Test mfw_session_has() returns false for null value
     */
    public function testMfwSessionHasReturnsFalseForNullValue(): void
    {
        mfw_session_start();
        
        mfw_session_set('null_key', null);
        
        $this->assertFalse(mfw_session_has('null_key'));
    }

    /**
     * Test mfw_session_remove() removes existing key
     */
    public function testMfwSessionRemoveRemovesExistingKey(): void
    {
        mfw_session_start();
        
        mfw_session_set('test_key', 'test_value');
        $this->assertTrue(mfw_session_has('test_key'));
        
        mfw_session_remove('test_key');
        $this->assertFalse(mfw_session_has('test_key'));
    }

    /**
     * Test mfw_session_remove() doesn't affect other keys
     */
    public function testMfwSessionRemoveDoesNotAffectOtherKeys(): void
    {
        mfw_session_start();
        
        mfw_session_set('key1', 'value1');
        mfw_session_set('key2', 'value2');
        
        mfw_session_remove('key1');
        
        $this->assertFalse(mfw_session_has('key1'));
        $this->assertTrue(mfw_session_has('key2'));
        $this->assertEquals('value2', mfw_session_get('key2'));
    }

    /**
     * Test mfw_session_remove() with non-existent key (should not error)
     */
    public function testMfwSessionRemoveWithNonExistentKey(): void
    {
        mfw_session_start();
        
        // Should not throw any error
        mfw_session_remove('non_existent_key');
        
        $this->assertTrue(true); // Test passes if no error occurs
    }

    /**
     * Test mfw_session_destroy() clears all session data
     */
    public function testMfwSessionDestroyClearsAllSessionData(): void
    {
        mfw_session_start();
        
        mfw_session_set('key1', 'value1');
        mfw_session_set('key2', 'value2');
        
        $this->assertTrue(mfw_session_has('key1'));
        $this->assertTrue(mfw_session_has('key2'));
        
        mfw_session_destroy();
        
        $this->assertFalse(mfw_session_has('key1'));
        $this->assertFalse(mfw_session_has('key2'));
    }

    /**
     * Test mfw_session_destroy() destroys the session
     */
    public function testMfwSessionDestroyDestroysSession(): void
    {
        mfw_session_start();
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());
        
        mfw_session_destroy();
        
        $this->assertEquals(PHP_SESSION_NONE, session_status());
    }

    /**
     * Test mfw_session_regenerate() creates new session ID
     */
    public function testMfwSessionRegenerateCreatesNewSessionId(): void
    {
        mfw_session_start();
        $originalId = session_id();
        
        mfw_session_regenerate();
        
        $newId = session_id();
        $this->assertNotEquals($originalId, $newId);
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());
    }

    /**
     * Test mfw_session_regenerate() preserves session data
     */
    public function testMfwSessionRegeneratePreservesSessionData(): void
    {
        mfw_session_start();
        
        mfw_session_set('test_key', 'test_value');
        $originalData = $_SESSION;
        
        mfw_session_regenerate();
        
        $this->assertEquals($originalData, $_SESSION);
        $this->assertEquals('test_value', mfw_session_get('test_key'));
    }

    /**
     * Test session functions work with empty string keys
     */
    public function testSessionFunctionsWithEmptyStringKey(): void
    {
        mfw_session_start();
        
        mfw_session_set('', 'empty_key_value');
        $this->assertTrue(mfw_session_has(''));
        $this->assertEquals('empty_key_value', mfw_session_get(''));
        
        mfw_session_remove('');
        $this->assertFalse(mfw_session_has(''));
    }

    /**
     * Test session functions work with special characters in keys
     */
    public function testSessionFunctionsWithSpecialCharactersInKeys(): void
    {
        mfw_session_start();
        
        $specialKey = 'key_with_special_chars_!@#$%^&*()';
        $value = 'special_value';
        
        mfw_session_set($specialKey, $value);
        $this->assertTrue(mfw_session_has($specialKey));
        $this->assertEquals($value, mfw_session_get($specialKey));
        
        mfw_session_remove($specialKey);
        $this->assertFalse(mfw_session_has($specialKey));
    }

    /**
     * Test session functions work with numeric keys
     */
    public function testSessionFunctionsWithNumericKeys(): void
    {
        mfw_session_start();
        
        mfw_session_set('123', 'numeric_key_value');
        $this->assertTrue(mfw_session_has('123'));
        $this->assertEquals('numeric_key_value', mfw_session_get('123'));
        
        mfw_session_remove('123');
        $this->assertFalse(mfw_session_has('123'));
    }

    /**
     * Test session functions work with unicode keys
     */
    public function testSessionFunctionsWithUnicodeKeys(): void
    {
        mfw_session_start();
        
        $unicodeKey = 'clave_con_ñ_y_áéíóú';
        $value = 'unicode_value';
        
        mfw_session_set($unicodeKey, $value);
        $this->assertTrue(mfw_session_has($unicodeKey));
        $this->assertEquals($value, mfw_session_get($unicodeKey));
        
        mfw_session_remove($unicodeKey);
        $this->assertFalse(mfw_session_has($unicodeKey));
    }

    /**
     * Test session functions work with very long keys
     */
    public function testSessionFunctionsWithVeryLongKeys(): void
    {
        mfw_session_start();
        
        $longKey = str_repeat('a', 1000);
        $value = 'long_key_value';
        
        mfw_session_set($longKey, $value);
        $this->assertTrue(mfw_session_has($longKey));
        $this->assertEquals($value, mfw_session_get($longKey));
        
        mfw_session_remove($longKey);
        $this->assertFalse(mfw_session_has($longKey));
    }

    /**
     * Test session functions work with very long values
     */
    public function testSessionFunctionsWithVeryLongValues(): void
    {
        mfw_session_start();
        
        $key = 'test_key';
        $longValue = str_repeat('x', 10000);
        
        mfw_session_set($key, $longValue);
        $this->assertTrue(mfw_session_has($key));
        $this->assertEquals($longValue, mfw_session_get($key));
        
        mfw_session_remove($key);
        $this->assertFalse(mfw_session_has($key));
    }

    /**
     * Test session functions work with complex nested arrays
     */
    public function testSessionFunctionsWithComplexNestedArrays(): void
    {
        mfw_session_start();
        
        $complexArray = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'string' => 'nested_value',
                        'number' => 42,
                        'boolean' => true,
                        'null' => null,
                        'array' => [1, 2, 3]
                    ]
                ]
            ]
        ];
        
        mfw_session_set('complex_key', $complexArray);
        $this->assertTrue(mfw_session_has('complex_key'));
        $this->assertEquals($complexArray, mfw_session_get('complex_key'));
        
        mfw_session_remove('complex_key');
        $this->assertFalse(mfw_session_has('complex_key'));
    }

    /**
     * Test session functions work with objects (serialization)
     */
    public function testSessionFunctionsWithObjects(): void
    {
        mfw_session_start();
        
        $object = new stdClass();
        $object->property = 'object_value';
        
        mfw_session_set('object_key', $object);
        $this->assertTrue(mfw_session_has('object_key'));
        
        $retrievedObject = mfw_session_get('object_key');
        $this->assertEquals($object->property, $retrievedObject->property);
        
        mfw_session_remove('object_key');
        $this->assertFalse(mfw_session_has('object_key'));
    }

    /**
     * Test multiple session operations in sequence
     */
    public function testMultipleSessionOperationsInSequence(): void
    {
        mfw_session_start();
        
        // Set multiple values
        mfw_session_set('key1', 'value1');
        mfw_session_set('key2', 'value2');
        mfw_session_set('key3', 'value3');
        
        // Verify all exist
        $this->assertTrue(mfw_session_has('key1'));
        $this->assertTrue(mfw_session_has('key2'));
        $this->assertTrue(mfw_session_has('key3'));
        
        // Remove one
        mfw_session_remove('key2');
        $this->assertTrue(mfw_session_has('key1'));
        $this->assertFalse(mfw_session_has('key2'));
        $this->assertTrue(mfw_session_has('key3'));
        
        // Update one
        mfw_session_set('key1', 'updated_value1');
        $this->assertEquals('updated_value1', mfw_session_get('key1'));
        
        // Regenerate session
        mfw_session_regenerate();
        $this->assertTrue(mfw_session_has('key1'));
        $this->assertFalse(mfw_session_has('key2'));
        $this->assertTrue(mfw_session_has('key3'));
        $this->assertEquals('updated_value1', mfw_session_get('key1'));
        $this->assertEquals('value3', mfw_session_get('key3'));
    }

    /**
     * Test session functions work with zero values
     */
    public function testSessionFunctionsWithZeroValues(): void
    {
        mfw_session_start();
        
        // Zero integer
        mfw_session_set('zero_int', 0);
        $this->assertTrue(mfw_session_has('zero_int'));
        $this->assertEquals(0, mfw_session_get('zero_int'));
        
        // Zero string
        mfw_session_set('zero_string', '0');
        $this->assertTrue(mfw_session_has('zero_string'));
        $this->assertEquals('0', mfw_session_get('zero_string'));
        
        // Empty array
        mfw_session_set('empty_array', []);
        $this->assertTrue(mfw_session_has('empty_array'));
        $this->assertEquals([], mfw_session_get('empty_array'));
        
        // False boolean
        mfw_session_set('false_bool', false);
        $this->assertTrue(mfw_session_has('false_bool'));
        $this->assertFalse(mfw_session_get('false_bool'));
    }

    /**
     * Test session functions work with resource handles (should fail gracefully)
     */
    public function testSessionFunctionsWithResourceHandles(): void
    {
        mfw_session_start();
        
        // Create a temporary file resource
        $resource = fopen('php://temp', 'r+');
        
        // This should work (resources are serializable in modern PHP)
        mfw_session_set('resource_key', $resource);
        $this->assertTrue(mfw_session_has('resource_key'));
        
        // Clean up
        fclose($resource);
        mfw_session_remove('resource_key');
    }

    /**
     * Test session functions work with closures (should fail gracefully)
     */
    public function testSessionFunctionsWithClosures(): void
    {
        mfw_session_start();
        
        $closure = function() { return 'test'; };
        
        // This should work (closures are serializable in modern PHP)
        mfw_session_set('closure_key', $closure);
        $this->assertTrue(mfw_session_has('closure_key'));
        
        $retrievedClosure = mfw_session_get('closure_key');
        $this->assertEquals('test', $retrievedClosure());
        
        mfw_session_remove('closure_key');
    }

    /**
     * Test session functions work with circular references
     */
    public function testSessionFunctionsWithCircularReferences(): void
    {
        mfw_session_start();
        
        $array1 = ['name' => 'array1'];
        $array2 = ['name' => 'array2'];
        
        $array1['reference'] = &$array2;
        $array2['reference'] = &$array1;
        
        // This should work (PHP handles circular references)
        mfw_session_set('circular_key', $array1);
        $this->assertTrue(mfw_session_has('circular_key'));
        
        $retrieved = mfw_session_get('circular_key');
        $this->assertEquals('array1', $retrieved['name']);
        $this->assertEquals('array2', $retrieved['reference']['name']);
        
        mfw_session_remove('circular_key');
    }

    /**
     * Test session functions work with very large data structures
     */
    public function testSessionFunctionsWithVeryLargeDataStructures(): void
    {
        mfw_session_start();
        
        // Create a large nested structure
        $largeArray = [];
        for ($i = 0; $i < 100; $i++) {
            $largeArray["level1_$i"] = [];
            for ($j = 0; $j < 50; $j++) {
                $largeArray["level1_$i"]["level2_$j"] = "value_$i$j";
            }
        }
        
        mfw_session_set('large_key', $largeArray);
        $this->assertTrue(mfw_session_has('large_key'));
        
        $retrieved = mfw_session_get('large_key');
        $this->assertEquals($largeArray, $retrieved);
        $this->assertEquals('value_9949', $retrieved['level1_99']['level2_49']);
        
        mfw_session_remove('large_key');
    }

    /**
     * Test session functions work with binary data
     */
    public function testSessionFunctionsWithBinaryData(): void
    {
        mfw_session_start();
        
        $binaryData = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F";
        
        mfw_session_set('binary_key', $binaryData);
        $this->assertTrue(mfw_session_has('binary_key'));
        
        $retrieved = mfw_session_get('binary_key');
        $this->assertEquals($binaryData, $retrieved);
        
        mfw_session_remove('binary_key');
    }

    /**
     * Test session functions work with mixed data types in arrays
     */
    public function testSessionFunctionsWithMixedDataTypesInArrays(): void
    {
        mfw_session_start();
        
        $mixedArray = [
            'string' => 'hello',
            'integer' => 42,
            'float' => 3.14159,
            'boolean' => true,
            'null' => null,
            'array' => [1, 2, 3],
            'object' => new stdClass(),
            'empty_string' => '',
            'zero' => 0,
            'false' => false
        ];
        
        mfw_session_set('mixed_key', $mixedArray);
        $this->assertTrue(mfw_session_has('mixed_key'));
        
        $retrieved = mfw_session_get('mixed_key');
        $this->assertEquals($mixedArray, $retrieved);
        $this->assertEquals('hello', $retrieved['string']);
        $this->assertEquals(42, $retrieved['integer']);
        $this->assertEquals(3.14159, $retrieved['float']);
        $this->assertTrue($retrieved['boolean']);
        $this->assertNull($retrieved['null']);
        $this->assertEquals([1, 2, 3], $retrieved['array']);
        $this->assertIsObject($retrieved['object']);
        $this->assertEquals('', $retrieved['empty_string']);
        $this->assertEquals(0, $retrieved['zero']);
        $this->assertFalse($retrieved['false']);
        
        mfw_session_remove('mixed_key');
    }

    /**
     * Test session functions work with deeply nested structures
     */
    public function testSessionFunctionsWithDeeplyNestedStructures(): void
    {
        mfw_session_start();
        
        $deepArray = [];
        $current = &$deepArray;
        
        // Create 10 levels of nesting
        for ($i = 0; $i < 10; $i++) {
            $current['level'] = $i;
            $current['data'] = "data_at_level_$i";
            $current['nested'] = [];
            $current = &$current['nested'];
        }
        
        mfw_session_set('deep_key', $deepArray);
        $this->assertTrue(mfw_session_has('deep_key'));
        
        $retrieved = mfw_session_get('deep_key');
        $this->assertEquals($deepArray, $retrieved);
        $this->assertEquals(0, $retrieved['level']);
        $this->assertEquals('data_at_level_0', $retrieved['data']);
        
        mfw_session_remove('deep_key');
    }

    /**
     * Test session functions work with session-like keys (edge case)
     */
    public function testSessionFunctionsWithSessionLikeKeys(): void
    {
        mfw_session_start();
        
        $sessionLikeKeys = [
            'PHPSESSID',
            'session_id',
            'session_name',
            'session_status',
            'session_save_path',
            'session_cache_limiter',
            'session_cache_expire',
            'session_cookie_params',
            'session_get_cookie_params',
            'session_set_cookie_params',
            'session_start',
            'session_destroy',
            'session_unset',
            'session_write_close',
            'session_commit',
            'session_abort',
            'session_reset',
            'session_status',
            'session_id',
            'session_name',
            'session_save_path',
            'session_cache_limiter',
            'session_cache_expire',
            'session_set_save_handler',
            'session_register_shutdown',
            'session_encode',
            'session_decode',
            'session_gc',
            'session_create_id',
            'session_regenerate_id'
        ];
        
        foreach ($sessionLikeKeys as $key) {
            $value = "value_for_$key";
            mfw_session_set($key, $value);
            $this->assertTrue(mfw_session_has($key));
            $this->assertEquals($value, mfw_session_get($key));
            mfw_session_remove($key);
            $this->assertFalse(mfw_session_has($key));
        }
    }

    /**
     * Test session functions work with keys that are PHP keywords
     */
    public function testSessionFunctionsWithPhpKeywordsAsKeys(): void
    {
        mfw_session_start();
        
        $phpKeywords = [
            'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch',
            'class', 'clone', 'const', 'continue', 'declare', 'default', 'die',
            'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor',
            'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit',
            'extends', 'final', 'finally', 'for', 'foreach', 'function', 'global',
            'goto', 'if', 'implements', 'include', 'include_once', 'instanceof',
            'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or',
            'print', 'private', 'protected', 'public', 'require', 'require_once',
            'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset',
            'use', 'var', 'while', 'xor', 'yield', 'yield_from'
        ];
        
        foreach ($phpKeywords as $keyword) {
            $value = "value_for_$keyword";
            mfw_session_set($keyword, $value);
            $this->assertTrue(mfw_session_has($keyword));
            $this->assertEquals($value, mfw_session_get($keyword));
            mfw_session_remove($keyword);
            $this->assertFalse(mfw_session_has($keyword));
        }
    }

    /**
     * Test session functions work with keys containing whitespace
     */
    public function testSessionFunctionsWithKeysContainingWhitespace(): void
    {
        mfw_session_start();
        
        $whitespaceKeys = [
            ' ',
            '  ',
            "\t",
            "\n",
            "\r",
            " key with spaces ",
            "key\twith\ttabs",
            "key\nwith\nnewlines",
            "key\rwith\rreturns",
            "key with\ttabs\nand\nnewlines\r"
        ];
        
        foreach ($whitespaceKeys as $key) {
            $value = "value_for_whitespace_key";
            mfw_session_set($key, $value);
            $this->assertTrue(mfw_session_has($key));
            $this->assertEquals($value, mfw_session_get($key));
            mfw_session_remove($key);
            $this->assertFalse(mfw_session_has($key));
        }
    }

    /**
     * Test session functions work with values containing control characters
     */
    public function testSessionFunctionsWithControlCharacters(): void
    {
        mfw_session_start();
        
        $controlChars = [
            "\x00", "\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07",
            "\x08", "\x09", "\x0A", "\x0B", "\x0C", "\x0D", "\x0E", "\x0F",
            "\x10", "\x11", "\x12", "\x13", "\x14", "\x15", "\x16", "\x17",
            "\x18", "\x19", "\x1A", "\x1B", "\x1C", "\x1D", "\x1E", "\x1F"
        ];
        
        foreach ($controlChars as $char) {
            $value = "value_with_control_char_$char";
            mfw_session_set('control_key', $value);
            $this->assertTrue(mfw_session_has('control_key'));
            $this->assertEquals($value, mfw_session_get('control_key'));
            mfw_session_remove('control_key');
            $this->assertFalse(mfw_session_has('control_key'));
        }
    }
} 