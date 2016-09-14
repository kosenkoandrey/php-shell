<?
include_once 'init.php';
use PHPUnit\Framework\TestCase;

class CronTest extends TestCase {

	protected $email = 'admin@phpshell';
	protected $password = '111';

    /**
     * @dataProvider prodviderAPIListJobs
     */
	public function testAPIListJobs($ssh){
		$tmp_jobs = APP::Module('Registry')->Get(['module_cron_job'], ['id', 'value'], isset($ssh) ? $ssh : 0);
		
		$ch = curl_init(APP::Module('Routing')->root.'admin/cron/api/jobs/list.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, "modules[users][token]=".APP::Module('Crypt')->Encode($this->password).";modules[users][email]=".$this->email.";PHPSESSID=".session_id());
        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query(['ssh'=>$ssh, 'current'=>1, 'rowCount'=>10, 'searchPhrase'=>''])));
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

		if(!isset($tmp_jobs['module_cron_job'])){
        	$this->assertEquals(0, $response['total']);
		}elseif(isset($tmp_jobs['module_cron_job']) && count($tmp_jobs['module_cron_job'])){
			$this->assertEquals(count($tmp_jobs['module_cron_job']), $response['total']);
			$this->assertEquals(10, $response['rowCount']);
			$this->assertEquals(1, $response['current']);
		}
	}

	public function prodviderAPIListJobs(){
		$con = APP::Module('Registry')->Get('module_ssh_connection', ['id']);
		return [
			[0],
            [$con['id']],
        ];
	}

	/**
     * @dataProvider prodviderAPIAddJob
     */
	public function testAPIAddJob($ssh_id_hash, $job){
		$ch = curl_init(APP::Module('Routing')->root.'admin/cron/api/jobs/add.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, "modules[users][token]=".APP::Module('Crypt')->Encode($this->password).";modules[users][email]=".$this->email.";PHPSESSID=".session_id());
        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query(['ssh_id_hash'=>$ssh_id_hash, 'job'=>$job])));
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if(!$ssh_id_hash){
        	$this->assertEquals('error', $response['status']);
        	$this->assertCount(7, $response['errors']);
        }elseif($ssh_id_hash && !$job[0]){
        	$this->assertEquals('error', $response['status']);
        	$this->assertCount(6, $response['errors']);
        }elseif($ssh_id_hash && count($job) == 1){
        	$this->assertEquals('error', $response['status']);
        	$this->assertCount(5, $response['errors']);
        }elseif($ssh_id_hash && count($job) == 2){
        	$this->assertEquals('error', $response['status']);
        	$this->assertCount(4, $response['errors']);
        }elseif($ssh_id_hash && count($job) == 3){
        	$this->assertEquals('error', $response['status']);
        	$this->assertCount(3, $response['errors']);
        }elseif($ssh_id_hash && count($job) == 4){
        	$this->assertEquals('error', $response['status']);
        	$this->assertCount(2, $response['errors']);
        }elseif($ssh_id_hash && count($job) == 5){
        	$this->assertEquals('error', $response['status']);
        	$this->assertCount(1, $response['errors']);
        }elseif($ssh_id_hash && count($job) == 6){
        	$this->assertEquals('success', $response['status']);
        	$this->assertCount(0, $response['errors']);
        	$this->assertNotNull($response['job_id']);
        }

        if(isset($response['job_id']) && $response['job_id']){
	        $ch = curl_init(APP::Module('Routing')->root.'admin/cron/api/jobs/remove.json');
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	        curl_setopt($ch, CURLOPT_HEADER, false);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_COOKIE, "modules[users][token]=".APP::Module('Crypt')->Encode($this->password).";modules[users][email]=".$this->email.";PHPSESSID=".session_id());
	        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query(['id'=>$response['job_id'], 'job'=>$job])));
	        $response = json_decode(curl_exec($ch), true);
	        curl_close($ch);
	        $this->assertEquals('success', $response['status']);
	        $this->assertCount(0, $response['errors']);
	    }
	}

	public function prodviderAPIAddJob(){
		$con = APP::Module('Registry')->Get('module_ssh_connection', ['id']);
		return [
			[APP::Module('Crypt')->Encode(0), ['']],
            [APP::Module('Crypt')->Encode($con['id']), ['']],
            [APP::Module('Crypt')->Encode($con['id']), ['*/1']],
            [APP::Module('Crypt')->Encode($con['id']), ['*/1', '*']],
            [APP::Module('Crypt')->Encode($con['id']), ['*/1', '*', '*']],
            [APP::Module('Crypt')->Encode($con['id']), ['*/1', '*', '*', '*']],
            [APP::Module('Crypt')->Encode($con['id']), ['*/1', '*', '*', '*', '*']],
            [APP::Module('Crypt')->Encode($con['id']), ['*/1', '*', '*', '*', '*', 'cron tab -l']]
        ];
	}
}