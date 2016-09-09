<?
include_once 'init.php';
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase {
	public $settings;

	public function setUp(){
		$this->settings = APP::Module('Registry')->Get(['module_cache_memcache_host', 'module_cache_memcache_port']);
		$this->assertArrayHasKey('module_cache_memcache_host', $settings);
        $this->assertArrayHasKey('module_cache_memcache_port', $settings);
        $this->assertCount(1, APP::Module('Cache')->memcache->getServerList());
	}

	/**
     * @dataProvider additionProvider
     */
	public function testAPIUpdateSettings($host, $port){
		$this->assertTrue(APP::Module('Registry')->Update(['value' => $host], [['item', '=', 'module_cache_memcache_host', PDO::PARAM_STR]]));
        $this->assertTrue(APP::Module('Registry')->Update(['value' => $port], [['item', '=', 'module_cache_memcache_port', PDO::PARAM_STR]]));

        APP::Module('Registry')->Update(['value' => $this->settings['module_cache_memcache_host']], [['item', '=', 'module_cache_memcache_host', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $this->settings['module_cache_memcache_port']], [['item', '=', 'module_cache_memcache_port', PDO::PARAM_STR]]);
	}

	public function additionProvider(){
		return [
            ['128.0.0.2', '22222']
        ];
	}
}