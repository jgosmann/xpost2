<?php

require_once 'wp-installer/test/initTestClassLoader.php';

require_once 'helpers/helpers.php';

use \Mockery as m;

class PhpCodeGeneratorsTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
    }

    public function teardown()
    {
        m::close();
    }


    public function testAsDefineDefinesInteger() {
        $key = 'test';
        $value = 1;
        $asDefine = PhpCodeGenerators::asDefine($key, $value);
        assertThat($asDefine, is(equalToIgnoringWhiteSpace(
            "define('$key', $value);")));
    }

    public function testAsDefineDefinesFloat() {
        $key = 'test';
        $value = 1.5;
        $asDefine = PhpCodeGenerators::asDefine($key, $value);
        assertThat($asDefine, is(equalToIgnoringWhiteSpace(
            "define('$key', $value);")));
    }

    public function testAsDefineDefinesString() {
        $key = 'test';
        $value = 'str';
        $asDefine = PhpCodeGenerators::asDefine($key, $value);
        assertThat($asDefine, is(equalToIgnoringWhiteSpace(
            "define('$key', '$value');")));
    }

    public function testAsAssignmentAssignsInteger() {
        $key = 'test';
        $value = 1;
        $asAssignment = PhpCodeGenerators::asAssignment($key, $value);
        assertThat($asAssignment, is(equalToIgnoringWhiteSpace(
            "$$key = $value;")));
    }

    public function testAsAssignmentAssignsFloat() {
        $key = 'test';
        $value = 1.5;
        $asAssignment = PhpCodeGenerators::asAssignment($key, $value);
        assertThat($asAssignment, is(equalToIgnoringWhiteSpace(
            "$$key = $value;")));
    }

    public function testAsAssignmentAssignsString() {
        $key = 'test';
        $value = 'str';
        $asAssignment = PhpCodeGenerators::asAssignment($key, $value);
        assertThat($asAssignment, is(equalToIgnoringWhiteSpace(
            "$$key = '$value';")));
    }

    public function testEscapeStringsEscapesCorrect() {
        $unescaped = 'aa\\aa\'a$aa';
        $expected = '\'aa\\aa\'a$aa\'';
        $got = PhpCodeGenerators::escapeString($unescaped);
        assertThat($got, is(equalTo($expected)));
    }
}



?>
