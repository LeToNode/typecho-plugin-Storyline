<?
/**
 * 博客时间线
 * 
 * @package storyline  
 * @author letonode 
 * @version 0.0.1
 * @link http://www.letonife.com
 */
class Storyline_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate(){
    
        return _t('日志时间线 启用成功 ： 建议使用独立页面模板内.');
       
    }
 
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
 
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){}
 
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
		 
 
 
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */

   public static function render() {
    $options = Helper::options();
	$file="./usr/Storyline.json";
	//if($file){
		writeStoryData($file,$options);
	//}
	


    $style = Typecho_Common::url('Storyline/Timeline/timeline.css', $options->pluginUrl);
    $jquery = Typecho_Common::url('Storyline/Timeline/jquery-min.js', $options->pluginUrl);
    $timelinejs = Typecho_Common::url('Storyline/Timeline/timeline-min.js', $options->pluginUrl);
    echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$style}\" /> ";
    echo "<script type=\"text/javascript\" src=\"{$jquery}\"></script>";
    echo "<script type=\"text/javascript\" src=\"{$timelinejs}\"></script>";
    echo "<script>
            $(document).ready(function() {
                timeline = new VMM.Timeline();
                timeline.init('/usr/Storyline.json');
            });
        </script>";
    echo "\n";
	echo "<div id=\"timeline\"></div>";
  }
  

}

  function writeStoryData($file,$options){
	$jsondata = '{"timeline":{"headline":"'.$options->title.'","type":"default","startDate":"2006","text":"'.$options->description.'","date": [';
		
	 /**获取适配器名称 , Typecho_Db::SORT_DESC*/
    $db = Typecho_Db::get();    
    /**获取日志总数*/
    $result = $db->fetchAll($db->select()->from('table.contents')
        ->where('table.contents.status = ?', 'publish')     
        ->where('table.contents.type = ?', 'post')      
        ->order('table.contents.created'));
    
    $size = sizeof($result);
    for($i =0;$i < 50; ++$i)
    {
        $post = $result[$i];
        $value = Typecho_Widget::widget('Widget_Abstract_Contents')->push($post);
		$jsondata = $jsondata.'{"startDate":"'.$value['date']->year.','.$value['date']->month.','.$value['date']->day.'","headline":"'.$value['title'].'"}';
		if($i != 49){
			$jsondata = $jsondata.',';
		}
	}
	
	$jsondata = $jsondata.']}}';
	
	$fp=@fopen($file,'w');
	fwrite($fp,$jsondata);
	fclose($fp);
  }
	