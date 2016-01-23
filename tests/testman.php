<?php

/*
 * The MIT License
 *
 * Copyright 2015 Allen Linatoc.
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


//------------------------------------------------------------------------------
//  Testman 1.0
//
//  A PHP Unit testing stub, powered by SimpleTest
//------------------------------------------------------------------------------

$defaults = [

    'DebugMode' => false,
    'TestClassRegex' => '(.+)Test',
    'ReporterClass' => 'HtmlReporter'

]; // note that this can be overridden by "config.json"

ob_start();


// Initialize configuration

$fileConfig = realpath(__DIR__ . "/config.json");

$config = $defaults;

if ($fileConfig === false || !file_exists($fileConfig))
{
    echo concat("<b>Warning:</b> Config file <b>", __DIR__ . "/config.json", "</b> not found. Will use the defaults.<br>");
}
else
{
    $config = json_decode(file_get_contents($fileConfig), true);
    if (empty($config))
    {
        echo "<b>Warning:</b> Config file is not a valid JSON configuration. Will use the defaults.<br>";
    }
}

echo "<br><br>Proceeding using the following configuration:<br>";
printbr(nl2br(str_replace(" ", "&nbsp;", \Zend\Json\Json::prettyPrint(json_encode($config, true)))), true);

$debug_output = ob_get_clean();

if ($config['DebugMode'])
{
    echo $debug_output;
}



// Load and Run all test files

$recursiveIterator = new RecursiveDirectoryIterator(__DIR__, RecursiveDirectoryIterator::SKIP_DOTS);
$it = new RecursiveIteratorIterator($recursiveIterator);
$regexIterator = new RegexIterator($it, '/Test\.php$/');

foreach ($regexIterator as $key => $value)
{
    // By default, we only get classes which names end with "Test", e.g. MyUtilityTest, etc.

    $classname = preg_replace(sprintf('/(.+)(\/|\\\\)(%s)\.(.+)$/i', $config['TestClassRegex']), '$3', (string)$value);
    if ($classname == $value)
    {
        continue;
    }
    require_once (string)$value;


    // Extract namespace

    $classfile_contents = file_get_contents((string)$value);
    $namespace = preg_replace('/([\S\s]+)[^\/\*]namespace(.+)[\n\s\t]*(\{|\;)([\S\s]+)/i', '$2', $classfile_contents);


    // Properly sanitize

    $namespace = $namespace == $classfile_contents ? "" : sprintf('\\%s', ltrim(trim($namespace), '\\'));


    // Instantiate current report

    $class = sprintf('%s\\%s', $namespace, ltrim(trim($classname), '/\\'));
    $instance = new $class();
    if ($instance instanceof SimpleTestCase)
    {
        $reporter_class = trim($config['ReporterClass']);
        $reporter = new $reporter_class();
        $instance->run($reporter);
    }
}