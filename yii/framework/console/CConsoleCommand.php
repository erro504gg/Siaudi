<?php
/**
 * CConsoleCommand class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CConsoleCommand represents an executable console command.
 *
 * It works like {@link CController} by parsing command line options and dispatching
 * the request to a specific action with appropriate option values.
 *
 * Users call a console command via the following command format:
 * <pre>
 * yiic CommandName ActionName --Option1=Value1 --Option2=Value2 ...
 * </pre>
 *
 * Child classes mainly needs to implement various action methods whose name must be
 * prefixed with "action". The parameters to an action method are considered as options
 * for that specific action. The action specified as {@link defaultAction} will be invoked
 * when a user does not specify the action name in his command.
 *
 * Options are bound to action parameters via parameter names. For example, the following
 * action method will allow us to run a command with <code>yiic sitemap --type=News</code>:
 * <pre>
 * class SitemapCommand {
 *     public function actionIndex($type) {
 *         ....
 *     }
 * }
 * </pre>
 *
 * @property string $name The command name.
 * @property CConsoleCommandRunner $commandRunner The command runner instance.
 * @property string $help The command description. Defaults to 'Usage: php entry-script.php command-name'.
 * @property array $optionHelp The command option help information. Each array element describes
 * the help information for a single action.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CConsoleCommand.php 3548 2012-01-24 11:42:59Z mdomba $
 * @package system.console
 * @since 1.0
 */
abstract class CConsoleCommand extends CComponent
{
	/**
	 * @var string the name of the default action. Defaults to 'index'.
	 * @since 1.1.5
	 */
	public $defaultAction='index';

	private $_name;
	private $_runner;

	/**
	 * Constructor.
	 * @param string $name name of the command
	 * @param CConsoleCommandRunner $runner the command runner
	 */
	public function __construct($name,$runner)
	{
		$this->_name=$name;
		$this->_runner=$runner;
	}

	/**
	 * Initializes the command object.
	 * This method is invoked after a command object is created and initialized with configurations.
	 * You may override this method to further customize the command before it executes.
	 * @since 1.1.6
	 */
	public function init()
	{
	}

	/**
	 * Executes the command.
	 * The default implementation will parse the input parameters and
	 * dispatch the command request to an appropriate action with the corresponding
	 * option values
	 * @param array $args command line parameters for this command.
	 */
	public function run($args)
	{
		list($action, $options, $args)=$this->resolveRequest($args);
		$methodName='action'.$action;
		if(!preg_match('/^\w+$/',$action) || !method_exists($this,$methodName))
			$this->usageError("Unknown action: ".$action);

		$method=new ReflectionMethod($this,$methodName);
		$params=array();
		// named and unnamed options
		foreach($method->getParameters() as $i=>$param)
		{
			$name=$param->getName();
			if(isset($options[$name]))
			{
				if($param->isArray())
					$params[]=is_array($options[$name]) ? $options[$name] : array($options[$name]);
				else if(!is_array($options[$name]))
					$params[]=$options[$name];
				else
					$this->usageError("Option --$name requires a scalar. Array is given.");
			}
			else if($name==='args')
				$params[]=$args;
			else if($param->isDefaultValueAvailable())
				$params[]=$param->getDefaultValue();
			else
				$this->usageError("Missing required option --$name.");
			unset($options[$name]);
		}

		// try global options
		if(!empty($options))
		{
			$class=new ReflectionClass(get_class($this));
			foreach($options as $name=>$value)
			{
				if($class->hasProperty($name))
				{
					$property=$class->getProperty($name);
					if($property->isPublic() && !$property->isStatic())
					{
						$this->$name=$value;
						unset($options[$name]);
					}
				}
			}
		}

		if(!empty($options))
			$this->usageError("Unknown options: ".implode(', ',array_keys($options)));

		if($this->beforeAction($action,$params))
		{
			$method->invokeArgs($this,$params);
			$this->afterAction($action,$params);
		}
	}

	/**
	 * This method is invoked right before an action is to be executed.
	 * You may override this method to do last-minute preparation for the action.
	 * @param string $action the action name
	 * @param array $params the parameters to be passed to the action method.
	 * @return boolean whether the action should be executed.
	 */
	protected function beforeAction($action,$params)
	{
		return true;
	}

	/**
	 * This method is invoked right after an action finishes execution.
	 * You may override this method to do some postprocessing for the action.
	 * @param string $action the action name
	 * @param array $params the parameters to be passed to the action method.
	 */
	protected function afterAction($action,$params)
	{
	}

	/**
	 * Parses the command line arguments and determines which action to perform.
	 * @param array $args command line arguments
	 * @return array the action name, named options (name=>value), and unnamed options
	 * @since 1.1.5
	 */
	protected function resolveRequest($args)
	{
		$options=array();	// named parameters
		$params=array();	// unnamed parameters
		foreach($args as $arg)
		{
			if(preg_match('/^--(\w+)(=(.*))?$/',$arg,$matches))  // an option
			{
				$name=$matches[1];
				$value=isset($matches[3]) ? $matches[3] : true;
				if(isset($options[$name]))
				{
					if(!is_array($options[$name]))
						$options[$name]=array($options[$name]);
					$options[$name][]=$value;
				}
				else
					$options[$name]=$value;
			}
			else if(isset($action))
				$params[]=$arg;
			else
				$action=$arg;
		}
		if(!isset($action))
			$action=$this->defaultAction;

		return array($action,$options,$params);
	}

	/**
	 * @return string the command name.
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @return CConsoleCommandRunner the command runner instance
	 */
	public function getCommandRunner()
	{
		return $this->_runner;
	}

	/**
	 * Provides the command description.
	 * This method may be overridden to return the actual command description.
	 * @return string the command description. Defaults to 'Usage: php entry-script.php command-name'.
	 */
	public function getHelp()
	{
		$help='Usage: '.$this->getCommandRunner()->getScriptName().' '.$this->getName();
		$options=$this->getOptionHelp();
		if(empty($options))
			return $help;
		if(count($options)===1)
			return $help.' '.$options[0];
		$help.=" <action>\nActions:\n";
		foreach($options as $option)
			$help.='    '.$option."\n";
		return $help;
	}

	/**
	 * Provides the command option help information.
	 * The default implementation will return all available actions together with their
	 * corresponding option information.
	 * @return array the command option help information. Each array element describes
	 * the help information for a single action.
	 * @since 1.1.5
	 */
	public function getOptionHelp()
	{
		$options=array();
		$class=new ReflectionClass(get_class($this));
        foreach($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
        	$name=$method->getName();
        	if(!strncasecmp($name,'action',6) && strlen($name)>6)
        	{
        		$name=substr($name,6);
        		$name[0]=strtolower($name[0]);
        		$help=$name;

				foreach($method->getParameters() as $param)
				{
					$optional=$param->isDefaultValueAvailable();
					$defaultValue=$optional ? $param->getDefaultValue() : null;
					$name=$param->getName();
					if($optional)
						$help.=" [--$name=$defaultValue]";
					else
						$help.=" --$name=value";
				}
				$options[]=$help;
        	}
        }
        return $options;
	}

	/**
	 * Displays a usage error.
	 * This method will then terminate the execution of the current application.
	 * @param string $message the error message
	 */
	public function usageError($message)
	{
		echo "Error: $message\n\n".$this->getHelp()."\n";
		exit(1);
	}

	/**
	 * Copies a list of files from one place to another.
	 * @param array $fileList the list of files to be copied (name=>spec).
	 * The array keys are names displayed during the copy process, and array values are specifications
	 * for files to be copied. Each array value must be an array of the following structure:
	 * <ul>
	 * <li>source: required, the full path of the file/directory to be copied from</li>
	 * <li>target: required, the full path of the file/directory to be copied to</li>
	 * <li>callback: optional, the callback to be invoked when copying a file. The callback function
	 *   should be declared as follows:
	 *   <pre>
	 *   function foo($source,$params)
	 *   </pre>
	 *   where $source parameter is the source file path, and the content returned
	 *   by the function will be saved into the target file.</li>
	 * <li>params: optional, the parameters to be passed to the callback</li>
	 * </ul>
	 * @see buildFileList
	 */
	public function copyFiles($fileList)
	{
		$overwriteAll=false;
		foreach($fileList as $name=>$file)
		{
			$source=strtr($file['source'],'/\\',DIRECTORY_SEPARATOR);
			$target=strtr($file['target'],'/\\',DIRECTORY_SEPARATOR);
			$callback=isset($file['callback']) ? $file['callback'] : null;
			$params=isset($file['params']) ? $file['params'] : null;

			if(is_dir($source))
			{
				$this->ensureDirectory($target);
				continue;
			}

			if($callback!==null)
				$content=call_user_func($callback,$source,$params);
			else
				$content=file_get_contents($source);
			if(is_file($target))
			{
				if($content===file_get_contents($target))
				{
					echo "  unchanged $name\n";
					continue;
				}
				if($overwriteAll)
					echo "  overwrite $name\n";
				else
				{
					echo "      exist $name\n";
					echo "            ...overwrite? [Yes|No|All|Quit] ";
					$answer=trim(fgets(STDIN));
					if(!strncasecmp($answer,'q',1))
						return;
					else if(!strncasecmp($answer,'y',1))
						echo "  overwrite $name\n";
					else if(!strncasecmp($answer,'a',1))
					{
						echo "  overwrite $name\n";
						$overwriteAll=true;
					}
					else
					{
						echo "       skip $name\n";
						continue;
					}
				}
			}
			else
			{
				$this->ensureDirectory(dirname($target));
				echo "   generate $name\n";
			}
			file_put_contents($target,$content);
		}
	}

	/**
	 * Builds the file list of a directory.
	 * This method traverses through the specified directory and builds
	 * a list of files and subdirectories that the directory contains.
	 * The result of this function can be passed to {@link copyFiles}.
	 * @param string $sourceDir the source directory
	 * @param string $targetDir the target directory
	 * @param string $baseDir base directory
	 * @return array the file list (see {@link copyFiles})
	 */
	public function buildFileList($sourceDir, $targetDir, $baseDir='')
	{
		$list=array();
		$handle=opendir($sourceDir);
		while(($file=readdir($handle))!==false)
		{
			if($file==='.' || $file==='..' || $file==='.svn' ||$file==='.yii')
				continue;
			$sourcePath=$sourceDir.DIRECTORY_SEPARATOR.$file;
			$targetPath=$targetDir.DIRECTORY_SEPARATOR.$file;
			$name=$baseDir===''?$file : $baseDir.'/'.$file;
			$list[$name]=array('source'=>$sourcePath, 'target'=>$targetPath);
			if(is_dir($sourcePath))
				$list=array_merge($list,$this->buildFileList($sourcePath,$targetPath,$name));
		}
		closedir($handle);
		return $list;
	}

	/**
	 * Creates all parent directories if they do not exist.
	 * @param string $directory the directory to be checked
	 */
	public function ensureDirectory($directory)
	{
		if(!is_dir($directory))
		{
			$this->ensureDirectory(dirname($directory));
			echo "      mkdir ".strtr($directory,'\\','/')."\n";
			mkdir($directory);
		}
	}

	/**
	 * Renders a view file.
	 * @param string $_viewFile_ view file path
	 * @param array $_data_ optional data to be extracted as local view variables
	 * @param boolean $_return_ whether to return the rendering result instead of displaying it
	 * @return mixed the rendering result if required. Null otherwise.
	 */
	public function renderFile($_viewFile_,$_data_=null,$_return_=false)
	{
		if(is_array($_data_))
			extract($_data_,EXTR_PREFIX_SAME,'data');
		else
			$data=$_data_;
		if($_return_)
		{
			ob_start();
			ob_implicit_flush(false);
			require($_viewFile_);
			return ob_get_clean();
		}
		else
			require($_viewFile_);
	}

	/**
	 * Converts a word to its plural form.
	 * @param string $name the word to be pluralized
	 * @return string the pluralized word
	 */
	public function pluralize($name)
	{
		$rules=array(
			'/move$/i' => 'moves',
			'/foot$/i' => 'feet',
			'/child$/i' => 'children',
			'/human$/i' => 'humans',
			'/man$/i' => 'men',
			'/tooth$/i' => 'teeth',
			'/person$/i' => 'people',
			'/([m|l])ouse$/i' => '\1ice',
			'/(x|ch|ss|sh|us|as|is|os)$/i' => '\1es',
			'/([^aeiouy]|qu)y$/i' => '\1ies',
			'/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
			'/(shea|lea|loa|thie)f$/i' => '\1ves',
			'/([ti])um$/i' => '\1a',
			'/(tomat|potat|ech|her|vet)o$/i' => '\1oes',
			'/(bu)s$/i' => '\1ses',
			'/(ax|test)is$/i' => '\1es',
			'/s$/' => 's',
		);
		foreach($rules as $rule=>$replacement)
		{
			if(preg_match($rule,$name))
				return preg_replace($rule,$replacement,$name);
		}
		return $name.'s';
	}

	/**
	 * Reads input via the readline PHP extension if that's available, or fgets() if readline is not installed.
	 *
	 * @param string $message to echo out before waiting for user input
	 * @return mixed line read as a string, or false if input has been closed
	 *
	 * @since 1.1.9
	 */
	public function prompt($message)
	{
		if(extension_loaded('readline'))
		{
			$input = readline($message.' ');
			readline_add_history($input);
			return $input;
		}
		else
		{
			echo $message.' ';
			return trim(fgets(STDIN));
		}
	}

	/**
	 * Asks user to confirm by typing y or n.
	 *
	 * @param string $message to echo out before waiting for user input
	 * @return bool if user confirmed
	 *
	 * @since 1.1.9
	 */
	public function confirm($message)
	{
		echo $message.' [yes|no] ';
		return !strncasecmp(trim(fgets(STDIN)),'y',1);
	}
}
