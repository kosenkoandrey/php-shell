<?
include_once 'init.php';
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase {

	protected $settings;
    protected $email = 'admin@phpshell';
    protected $password = '111';

	protected function setUp(){
        $this->settings = APP::Module('Cache')->settings;
    }

	public function testInit(){
		$this->assertArrayHasKey('module_cache_memcache_host', $this->settings);
        $this->assertArrayHasKey('module_cache_memcache_port', $this->settings);
        $this->assertCount(1, APP::Module('Cache')->memcache->getServerList());
	}

	/**
     * @dataProvider prodvider
     */
	public function testAPIUpdateSettings($host, $port){
        $ch = curl_init(APP::Module('Routing')->root.'admin/cache/api/settings/update.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, "modules[users][token]=".APP::Module('Crypt')->Encode($this->password).";modules[users][email]=".$this->email.";PHPSESSID=".session_id());
        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query(['module_cache_memcache_host'=>$host, 'module_cache_memcache_port'=>$port])));
        $content = curl_exec($ch);
        curl_close($ch);
        
        $this->assertJsonStringEqualsJsonString(
            json_encode([
	            'status' => 'success',
	            'errors' => []
        	]),
            $content
        );

        $testSettings = APP::Module('Registry')->Get(['module_cache_memcache_host', 'module_cache_memcache_port']);
        $this->assertEquals($host, $testSettings['module_cache_memcache_host']);
        $this->assertEquals($port, $testSettings['module_cache_memcache_port']);

        $ch = curl_init( APP::Module('Routing')->root.'admin/cache/api/settings/update.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, "modules[users][token]=".APP::Module('Crypt')->Encode($this->password).";modules[users][email]=".$this->email.";PHPSESSID=".session_id());
        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query(['module_cache_memcache_host'=>$this->settings['module_cache_memcache_host'], 'module_cache_memcache_port'=>$this->settings['module_cache_memcache_port']])));
        $content = curl_exec($ch);
        curl_close($ch);
        
        $this->assertJsonStringEqualsJsonString(
            json_encode([
	            'status' => 'success',
	            'errors' => []
        	]),
            $content
        );
	}

	public function prodvider(){
		return [
            ['128.0.0.2', '22222']
        ];
	}
}