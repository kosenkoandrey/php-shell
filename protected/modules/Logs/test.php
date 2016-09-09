<?
use PHPUnit\Framework\TestCase;

class LogsTest extends TestCase {
    
    public function testLogDir() {
        $this->assertEquals(false, file_exists(APP::$conf['logs']));
    }
    
}