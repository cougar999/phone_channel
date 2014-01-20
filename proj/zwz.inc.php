<?php
defined('Zwz_PATH') or define('Zwz_PATH',dirname(__FILE__));
require_once Zwz_PATH.'/base/interfaces.php';
class ZwzInc
{
	public static $classMap=array();
	public static $enableIncludePath=true;
	public static $starEscape='#';

	private static $_aliases=array('system'=>Zwz_PATH,'lib'=>TP_LIB_DIR); // alias => path
	private static $_imports=array();					// alias => class name or directory
	private static $_includePaths;						// list of include paths
	private static $_app;
	private static $_coreClasses=array(
		'CUrlManager' => '/CUrlManager.php',
		'CHttpRequest' => '/CHttpRequest.php',
		'CComponent' => '/base/CComponent.php',
		'CApplicationComponent' => '/base/CApplicationComponent.php',
		'CMapIterator' => '/collections/CMapIterator.php',
		'CMap' => '/collections/CMap.php',
		'CException' => '/base/CException.php',
		'CHttpException' => '/base/CHttpException.php',
		'PHPExcel' => '/class/excel/PHPExcel.php',
		'PHPMailer' => '/class/mail/class.phpmailer.php',
		'HTMLPurifier_Config' => '/class/htmlpurifier-4.4.0/HTMLPurifier.includes.php',
		'HTMLPurifier' => '/class/htmlpurifier-4.4.0/HTMLPurifier.includes.php',
		'RegVerification' => '/class/RegVerification.php',
		'CommonFunc' => '/class/CommonFunc.php',
		'AutoFilter' => '/class/AutoFilter.php'
	);
	public static function t($category,$message,$params=array()){
		return $params!==array() ? strtr($message,$params) : $message;
	}
	public static function import($alias,$forceInclude=false)
	{
		if(isset(self::$_imports[$alias]))  // previously imported
			return self::$_imports[$alias];

		if(class_exists($alias,false) || interface_exists($alias,false))
			return self::$_imports[$alias]=$alias;

		if(($pos=strrpos($alias,'\\'))!==false) // a class name in PHP 5.3 namespace format
		{
			$namespace=str_replace('\\','.',ltrim(substr($alias,0,$pos),'\\'));
			if(($path=self::getPathOfAlias($namespace))!==false)
			{
				$classFile=$path.DIRECTORY_SEPARATOR.substr($alias,$pos+1).'.php';
				if($forceInclude)
				{
					if(is_file($classFile))
						require($classFile);
					else
						throw new CException(self::t('Zwz','Alias "{alias}" is invalid. Make sure it points to an existing PHP file.',array('{alias}'=>$alias)));
					self::$_imports[$alias]=$alias;
				}
				else
					self::$classMap[$alias]=$classFile;
				return $alias;
			}
			else
				throw new CException(self::t('Zwz','Alias "{alias}" is invalid. Make sure it points to an existing directory.',
					array('{alias}'=>$namespace)));
		}

		if(($pos=strrpos($alias,'.'))===false)  // a simple class name
		{
			if($forceInclude && self::autoload($alias))
				self::$_imports[$alias]=$alias;
			return $alias;
		}

		$className=(string)substr($alias,$pos+1);
		$isClass=$className!=='*';

		if($isClass && (class_exists($className,false) || interface_exists($className,false)))
			return self::$_imports[$alias]=$className;

		if(($path=self::getPathOfAlias($alias))!==false)
		{
			if($isClass)
			{
				if($forceInclude)
				{
					if(is_file($path.'.php'))
						require($path.'.php');
					else
						throw new CException(self::t('Zwz','Alias "{alias}" is invalid. Make sure it points to an existing PHP file.',array('{alias}'=>$alias)));
					self::$_imports[$alias]=$className;
				}
				else
					self::$classMap[$className]=$path.'.php';
				return $className;
			}
			else  // a directory
			{
				if(self::$_includePaths===null)
				{
					self::$_includePaths=array_unique(explode(PATH_SEPARATOR,get_include_path()));
					if(($pos=array_search('.',self::$_includePaths,true))!==false)
						unset(self::$_includePaths[$pos]);
				}

				array_unshift(self::$_includePaths,$path);

				if(self::$enableIncludePath && set_include_path('.'.PATH_SEPARATOR.implode(PATH_SEPARATOR,self::$_includePaths))===false)
					self::$enableIncludePath=false;

				return self::$_imports[$alias]=$path;
			}
		}
		else
			throw new CException(self::t('Zwz','Alias "{alias}" is invalid. Make sure it points to an existing directory or file.',
				array('{alias}'=>$alias)));
	}
	public static function getPathOfAlias($alias)
	{
		if(isset(self::$_aliases[$alias]))
			return self::$_aliases[$alias];
		else if(($pos=strpos($alias,'.'))!==false)
		{
			$rootAlias=substr($alias,0,$pos);
			if(isset(self::$_aliases[$rootAlias]))
				return self::$_aliases[$alias]=str_ireplace(self::$starEscape, '.', rtrim(self::$_aliases[$rootAlias].DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,substr($alias,$pos+1)),'*'.DIRECTORY_SEPARATOR));
			else if(self::$_app instanceof CWebApplication)
			{
				if(self::$_app->findModule($rootAlias)!==null)
					return self::getPathOfAlias($alias);
			}
		}
		return false;
	}
	public static function setPathOfAlias($alias,$path)
	{
		if(empty($path))
			unset(self::$_aliases[$alias]);
		else
			self::$_aliases[$alias]=rtrim($path,'\\/');
	}
	public static function autoload($className)
	{
		// use include so that the error PHP file may appear
		if(isset(self::$classMap[$className]))
			include(self::$classMap[$className]);
		else if(isset(self::$_coreClasses[$className]))
			include(Zwz_PATH.self::$_coreClasses[$className]);
		else
		{
			// include class file relying on include_path
			if(strpos($className,'\\')===false)  // class without namespace
			{
				if(self::$enableIncludePath===false)
				{
					foreach(self::$_includePaths as $path)
					{
						$classFile=$path.DIRECTORY_SEPARATOR.$className.'.php';
						if(is_file($classFile))
						{
							include($classFile);
							break;
						}
					}
				}
				else
					include($className.'.php');
			}
			else  // class name with namespace in PHP 5.3
			{
				$namespace=str_replace('\\','.',ltrim($className,'\\'));
				if(($path=self::getPathOfAlias($namespace))!==false)
					include($path.'.php');
				else
					return false;
			}
			return class_exists($className,false) || interface_exists($className,false);
		}
		return true;
	}
	public static function registerAutoloader($callback, $append=false)
	{
		if($append)
		{
			self::$enableIncludePath=false;
			spl_autoload_register($callback);
		}
		else
		{
			spl_autoload_unregister(array('ZwzInc','autoload'));
			spl_autoload_register($callback);
			spl_autoload_register(array('ZwzInc','autoload'));
		}
	}
}
function myDoException($exception)
{
	echo $exception->getMessage();
}
spl_autoload_register(array('ZwzInc','autoload'));
set_exception_handler('myDoException');