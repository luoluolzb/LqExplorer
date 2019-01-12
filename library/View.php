<?php

class View
{
	protected $tplDir;
	protected $vars;

    /**
     * @var object 对象实例
     */
    protected static $instance;

	protected function __construct()
	{
		$this->vars = [];
	}

    /**
     * 获取实例
     * @access public
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    public function setTemplateDir($tplDir)
    {
		$this->tplDir = $tplDir;
    }

	public function assign($name, $value)
	{
		$this->vars[$name] = $value;
	}

	public function display($tpl)
	{
		$content = '$vars = View::instance()->vars(); ?>';
		$content .= file_get_contents($this->tplDir . $tpl);
		eval($content);
	}

	public function vars()
	{
		return $this->vars;
	}
}
