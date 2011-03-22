<?php

require_once 'test/include/initTestClassLoader.php';

require_once 'include/helpers.php';

use \Mockery as m;

class BufferingSqlExecutorTest extends PHPUnit_Framework_TestCase {

    private $sqlExecutor;
    private $bufferingSqlExecutor;

    public function setUp() {
        $this->sqlExecutor = m::mock('BufferingSqlExecutor');
        $this->bufferingSqlExecutor = new BufferingSqlExecutor(
            $this->sqlExecutor);
    }

    public function teardown()
    {
        m::close();
    }

    public function testHoldsExecBackUntilFlushIsCalled() {
        $statementA = 'st1';
        $statementB = 'st2';

        $this->sqlExecutor->shouldReceive('exec')
            ->with("/$statementA\s*;\s*$statementB(\s*;)?/")->once();

        $this->bufferingSqlExecutor->exec($statementA);
        $this->bufferingSqlExecutor->exec($statementB);
        $this->bufferingSqlExecutor->flush();
    }

    public function testDoesNotReexecFlushedStatements() {
        $statementA = 'st1';
        $statementB = 'st2';

        $this->sqlExecutor->shouldReceive('exec')->with($statementA)->once();
        $this->sqlExecutor->shouldReceive('exec')->with($statementB)->once();

        $this->bufferingSqlExecutor->exec($statementA);
        $this->bufferingSqlExecutor->flush();
        $this->bufferingSqlExecutor->exec($statementB);
        $this->bufferingSqlExecutor->flush();
    }

    public function testDestructionFlushes() {
        $statement = 'st';
        $this->sqlExecutor->shouldReceive('exec')->with($statement)->once();
        $this->bufferingSqlExecutor->exec($statement);
        unset($this->bufferingSqlExecutor);
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testDestructerConvertsExceptionsToWarnings() {
        $this->sqlExecutor->shouldReceive('exec')->with(m::any())
            ->andThrow(new Exception());
        $this->bufferingSqlExecutor->exec('st');
        unset($this->bufferingSqlExecutor);
    }

}



?>
