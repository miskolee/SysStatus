<?php
/****
    get info of windows
 * use WMIC/COM
 ****/


class _win_sys_info extends SysStatus
{
    public function __construct()
    {

        $objLocator = new COM("WbemScripting.SWbemLocator");
        $this->wmi = $objLocator->ConnectServer();
    }

    public function cpu()
    {
        $this->cpu = $this->_getWMI('Win32_Processor', array('Caption',
            'CpuStatus', 'CurrentClockSpeed', 'MaxClockSpeed', 'Name'));

    }

    public function memory()
    {
        $this->memory = $this->_getWMI('Win32_PhysicalMemory', array('TotalWidth',
            'Manufacturer', 'Speed'));
    }
    public function disk()
    {
        $result = null;
        $items = array('caption' => ' Caption', 'interfaceType' => 'InterfaceType',
            'size' => 'Size ', 'status' => 'Status');

        foreach ($items as $k => $v) {
            $result = null;
            exec('wmic diskdrive get ' . $v, $result);
            $this->disk["$k"] = $result[1];

        }
        $result = null;

        $items = array('caption' => 'caption', 'partitions' => 'Partitions',
            'size' => ' Partitions'
        );
        foreach ($items as $k => $v) {
            exec('wmic diskdrive get ' . $v, $result);
            $this->disk["$k"] = $result[1];
            $result = null;
        }
        exec('wmic LOGICALDISK', $result);
        $items = array('deviceID' => 'DeviceID', 'name' => ' Name',
            'fileSystem' => 'FileSystem', 'freeSpace' => 'FreeSpace',
            'size' => 'Size', 'volumeName' => 'VolumeName'
        );
        $res = array();
        $count = 0;
        foreach ($items as $k => $v) {
            $result = null;
            exec('wmic logicaldisk get ' . $v, $result);
            $res [$k] = $result;
            $result = null;
            $count++;
        }
        unset($count);

        $par = array();
        foreach ($res as $k => $v) {
            for ($i = 1; $i < count($v); $i++) {
                $par [$i - 1] ["$k"] = $v [$i];
            }
        }
        $this->disk['partitions'] = $par;
    }

    public function os()
    {
        $this->os = $this->_getWMI('Win32_OperatingSystem', array('BootDevice',
            'Caption', 'FreePhysicalMemory', 'LastBootUpTime', 'MaxNumberOfProcesses',
            'MaxProcessMemorySize', 'FreeVirtualMemory'));
    }

    public function network()
    {
        $result = null;
        exec('netstat -e', $result);
        $count = $result[4];
        //echo $count;
        $match = null;
        preg_match_all('/[0-9]+/', $count, $match);
        $match = $match[0];
        $re['receiveCount'] = $match[0];
        $re['sendCount'] = $match[1];
        $this->network = $re;
    }

    private function _getWMI($strClass, $strValue = array())
    {

        $arrData = array();


        $objWEBM = $this->wmi->Get($strClass);

        $arrProp = $objWEBM->Properties_;

        $arrWEBMCol = $objWEBM->Instances_();
        foreach ($arrWEBMCol as $objItem) {
            @reset($arrProp);

            $arrInstance = array();
            foreach ($arrProp as $propItem) {
                eval("\$value = \$objItem->" . $propItem->Name . ";");
                if (empty($strValue)) {

                    $arrInstance[$propItem->Name] = trim($value);
                } else {
                    //echo $propItem->Name . '-----' . $value . '<br>';
                    //cho $propItem->Name.'<br>';
                    if (in_array($propItem->Name, $strValue)) {

                        $arrInstance[$propItem->Name] = trim($value);
                    }
                }
            }

            $arrData[] = $arrInstance;
        }
        if (count($arrData) <= 1) {
            $arrData = $arrData[0];
        }
        return $arrData;
    }

    private $wmi;
}

