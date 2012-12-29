<?php

function getMemoryInfo(){
	$result =null;
	 exec('cat /proc/meminfo',$result);
		$res=array();
		foreach( $result as $v){
			$r = explode(':',$v);
			$res["$r[0]"] = trim($r[1]);
		}

	return $res;		

}

function getCpuInfo(){
	$result = null ; 
	exec('cat /proc/cpuinfo',$result);
	$res=array();
	foreach($result as $v){
		$r = explode(':',$v);
		$res["$r[0]"] = trim($r[1]);
	}
}

function getdiskinfo(){
	$result = null;
	exec('df -h',$result);
	$r = array();
	foreach($result as $v){
		$v = preg_replace('/\s+/',' ',$v);
		$res = explode(' ',$v);
        $items = array('deviceID','name',
            'fileSystem','freeSpace',
            'size','volumeName'
        );


		$r['size']=$res[1];
		$r['volumeName']=$res[2];
		$r['freeSpace']=$res[3];
		$r['name']=$res[5];
		if(!$r['sizeTotal'] && !$r['use']){
			continue;
		}
		$s[]=$r;
		}	
	var_dump($s);	
}
/**** get some info of network ***/
function getnetworkinfo(){
	$result = null;
	exec('cat /proc/net/dev | grep -i :',$result);
	foreach($result as $v){
		$v = preg_replace('/\s{2,}/','  ',$v);
		$v = explode('  ',$v);
		$s=explode(':',$v[1]);
		$r['name']=$s[0];
		$r['rx']=$s[1];
		$r['tx']=$v[2];
		var_dump($r);		
	}


}


class _linux_sys_info  extends SysStatus{
public function __construct(){}
public function cpu(){
$this->cpu = $this->execute('cat /proc/cpuinfo');

}
public function memory(){
$this->memory = $this->execute('cat /proc/meminfo');

}
public function disk(){
    $items = array('deviceID','name',
        'fileSystem','freeSpace',
        'size','volumeName'
    );
    $result = null;
    exec('df -h',$result);
    $r = array();
    foreach($result as $v){
        $v = preg_replace('/\s+/',' ',$v);
        $res = explode(' ',$v);
        $r['size']=$res[1];
        $r['used']=$res[2];
        $r['freeSpace']=$res[3];
        $r['use']=$res[4];
        $r['name']=$res[5];
        $r['volumeName'] = $res[0];
        if(!$r['size']){
            continue;
        }
        $s[]=$r;
    }
$this->disk['partitions'] =$s;

}

public function os(){
$this->os = $this->execute('dmiencode -t 1');
}

public function network(){
    $result = null;
    exec('cat /proc/net/dev | grep -i :',$result);
    foreach($result as $v){
        $v = preg_replace('/\s{2,}/','  ',$v);
        $v = explode('  ',$v);
        $s=explode(':',$v[1]);
        $r['name']=$s[0];
        $r['rx']=$s[1];
        $r['tx']=$v[2];
    }
	$this->network = $r;
}

private function execute($cmd,$sqlit=':'){
	$result = null;
	exec($cmd,$result);
	if(!$result){
		return false;
	}	
	if(!is_array($result)){
		$res = explode($sqlit,$result);
		$value = str_replace($res[0].$sqlit,'',$result);
		$r["$res[0]"] = $value;
		return $r;
	}

	foreach($result as $v){
		$res = explode($sqlit,$v);
		$value = str_replace($res[0].$sqlit,'',$v);
		$r["$res[0]"] = $value;
					
				

	}
	return $r;
}

}



 ?>
