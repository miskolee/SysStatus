=================================================================================================
== 项目名称：SysStatus
== 主要编程语言： PHP
== 创建人： Misko_Lee
== Email: Misko_Lee@hotmail.com
== Sina Weibo:@misko_lee
== 创建时间：2012-12-01
== 当前版本：0.55
== 简介：
   SysStatus 是一个简单的系统信息获取工具。能够在windows于linux双平台
   运行。
   Windows下使用WMIC/COM技术调用系统API进行信息获取
   Linux下则使用exec进行原生命令获取信息，并将其解析。(这是一个局限性，这样无法保证
   跨系统跨版本的解析正确性未来版本将于Windows一样，使用原生API获取资源)
== 架构思路
   接口 _sys_info 定义了信息接口。核心类SysStatus 实现这一接口，SysStatus并不进行如何的
   具体实现，仅仅是调度其系列子类。比如_win_sys_info 实现了windows平台下的_sys_info接口,
   _linux_sys_info则实现了linux下的_sys_info接口。
   这个设计最大的缺陷是 环形依赖的关系。 SysStatus依赖子类，子类又依赖SysStatus类。
================================================================================================= 


   Example:
	<?php
		include 'includesFile.php';
		$sys = new SysStatus(); //自动判断系统(windows/linux)
		$diskInfo =$sys->disk;  //此时已经获取到系统硬盘信息。
		var_dump($sys->os);    //返回结果为关联数组。1维或2维
	
	?>


    SysStatus 就是拥有如此简单的使用方法。目前为止,SysStatus能够提供以下信息的获取,分别是：	
	1.os //系统信息
	2.cpu 	
	3.memory
	4.disk
	5.network
	
       使用例子中的调用方法即可。

声明：该工具目前仅仅是一个实验阶段的产品，无法保证跨系统分支。希望早日能够发布1.0版本