<?php

/*
 * The MIT License
 *
 * Copyright 2016 Allen Linatoc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Quickstart\Tests\IO;

/**
 * Description of DirectoryTest
 *
 * @author Allen
 */
class DirectoryTest extends \QuickTestCase
{

    protected $dir;

    /** @var \IO\Directory */
    protected $instance;
    protected $chmod_tests = [
        0655, 0640, 0755, 0750
    ];


    public function __construct()
    {
        $this->dir = \System::StoragePath() . "/DirectoryTest_" . md5(rand());

        // Now make sure testing directory is ok
        if (file_exists($this->dir))
        {
            if (is_file($this->dir))
            {
                unlink($this->dir);
            }
            else
            {
                rmdir($this->dir);
            }
        }
    }


    public function test_1_Constructor()
    {
        // Test without creating
        $dir = new \IO\Directory($this->dir);
        $exists = $dir->exists();

        $this->assertFalse($exists);

        // Test with auto creation
        $dir = new \IO\Directory($this->dir, true);
        $exists = $dir->exists();

        $this->assertTrue($exists);

        $this->instance = $dir;
    }


    public function test_2_Delete()
    {
        $result = $this->instance->delete();

        // Check if deletion succeed
        $this->assertTrue($result);

        // Check as well if this this directory does not exist anymore
        $this->assertEqualStrict($this->instance->exists(), false);
    }


    public function test_3_Create()
    {
        // Testing for manual creation
        $createSuccess = $this->instance->create();
        $exists = $this->instance->exists();

        // Check if creation and succeed and if it really exists
        $this->assertEqualStrict($createSuccess, true);
        $this->assertEqualStrict($exists, true);

        $this->instance->delete();
    }


    public function test_4_CreateWithCHMOD()
    {

        foreach ($this->chmod_tests as $chmod)
        {
            // Creation test @ constructor
            $this->instance = new \IO\Directory($this->dir, true, $chmod);
            $this->assertEqualStrict($this->instance->exists(), true);
            $this->assertEqual($this->instance->chmod(), decoct($chmod));
            $this->instance->delete();

            // Creation test @ create() method
            $this->instance = new \IO\Directory($this->dir, false, $chmod);
            $this->instance->create();
            $this->assertTrue($this->instance->exists());
            $this->assertEqual($this->instance->chmod(), decoct($chmod));
            $this->instance->delete();

            // Creation test @ createRecursively() method
            $this->instance = new \IO\Directory($this->dir, false, $chmod);
            $this->instance->create();
            $this->assertTrue($this->instance->exists());
            $this->assertEqual($this->instance->chmod(), decoct($chmod));
            $this->instance->delete();
        }

    }


    public function test_5_CreateRecursively()
    {
        $this->instance = new \IO\Directory($this->dir . "/path/to/this/folder");

        // Testing for manual creation
        $createSuccess = $this->instance->createRecursively();
        $exists = $this->instance->exists();

        // Check if creation and succeed and if it really exists
        $this->assertTrue($createSuccess);
        $this->assertTrue($exists);
    }

    /**
     * Should be able to resolve relative path
     */
    public function test_6_Derive()
    {
        $newinstance = $this->instance->derive("../../../../");
        $this->assertEqualStrict(realpath($newinstance->getPath()), realpath($this->dir));

        // Pass new instance to next test
        $this->instance = $newinstance;
    }

    public function test_7_File()
    {
        // Create an empty file
        $file = $this->instance->file('sample.txt', true);
        $this->assertTrue($file->exists());

        // Passively create file from non-existent path
        $file = $this->instance->file('./one/../one/hell/of/a/folder/sample2.txt', true);
        $this->assertTrue($file->exists());

        $files = [
            '/this/goes/around/index.txt',
            '/this/goes/around.txt',
            '/this/goes2/around/the/world.txt',
            '/this/goes/somewhere.txt'
        ];

        foreach ($files as $filepath)
        {
            $file = $this->instance->file($filepath, true);
            $result = $file->exists();
            $this->assertTrue($result);
        }
    }

    public function test_7_1_Children()
    {
        $result = $this->instance->children(true);
//        echo \Utils\JSON::Encode(\Utils\Objects::GetProperties($result[0]), true, true);
    }

    /**
     * List files
     */
    public function test_8_Listfiles()
    {
        // SECTION 1: Normal file listing
        $result = $this->instance->listfiles();

        // {{ Manual listing
        $files = glob($this->instance->getPath() . "/*");
        $files = array_map(
                function($value)
                {
                    return localpath($value);
                }, $files);
        // }}

        $this->assertArrayEqual($result, $files);

//        // SECTION 2: Recursive file listing
        $result = $this->instance->listfiles(true);

        // {{ Manual listing
        $files = listfiles($this->instance->getPath(), true);
        dumpbr($files);
        // }}
//        $this->assertArrayEqual($result, $files);
    }

}
