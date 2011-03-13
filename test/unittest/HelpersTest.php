<?php

require_once 'Hamcrest/hamcrest.php';

require_once 'include/helpers.php';

class HelpersTest extends PHPUnit_Framework_TestCase {

    public function testJoinPathsReturnsSingleArgumentUnchanged() {
        $relPath = 'testpath';
        $absPath = '/testpath';

        assertThat('relative path', joinPaths(array($relPath)),
            is(equalTo($relPath)));
        assertThat('absolute path', joinPaths(array($absPath)),
            is(equalTo($absPath)));
    }

    public function testJoinPathsJoinsRelativePath() {
        $parts = array('a', 'b/', 'c/', '/d', '/e');
        $expected = 'a/b/c/d/e';

        assertThat(joinPaths($parts), is(equalTo($expected)));
    }

    public function testJoinPathsJoinsAbsolutePath() {
        $parts = array('/a', 'b/', 'c/', '/d', '/e');
        $expected = '/a/b/c/d/e';

        assertThat(joinPaths($parts), is(equalTo($expected)));
    }

    public function testJoinPathsJoinsAbsoluteWindowsPath() {
        $parts = array('C:', 'dir');
        $expected = 'C:/dir';

        assertThat(joinPaths($parts), is(equalTo($expected)));
    }

}

?>
