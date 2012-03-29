<?php
/**
* 神奇的timeline主题版
*
* @package custom
*/
?>
<html>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="leton,leton2008(at)yahoo.com.cn" />
    <title>
      <?php $this->options->title(); ?>
      <?php $this->archiveTitle(); ?>
    </title>	
 <link rel="stylesheet" type="text/css" media="all" href="<?php $this->options->themeUrl('Timeline/timeline.css'); ?>" /> 	

 		<!-- JavaScript -->
<script type="text/javascript" src="<?php $this->options->themeUrl('Timeline/jquery-min.js'); ?>"></script>
<!-- <script type="text/javascript" src="jquery.mobile-1.0.min.js"></script> -->
<script type="text/javascript" src="<?php $this->options->themeUrl('Timeline/timeline-min.js'); ?>"></script>
<script>
	$(document).ready(function() {
		timeline = new VMM.Timeline();
		timeline.init();
	});
</script>
<body>
	<div id="timeline">
		<section>												
			<time>2006,1,1</time>								
			<h2><?php $this->options->title(); ?></h2>						
			<article>									
				<p><?php $this->options->description(); ?></p>
			</article>
		</section>

		<ul>
			<?php
			/**获取适配器名称 , Typecho_Db::SORT_DESC*/
			$db = Typecho_Db::get(); 	
			/**获取日志总数*/
			$result = $db->fetchAll($db->select()->from('table.contents')
				->where('table.contents.status = ?', 'publish')		
				->where('table.contents.type = ?', 'post')		
				->order('table.contents.created'));
			
			$size = sizeof($result);
			for($i =0;$i < $size ; ++$i)
			{
				$post = $result[$i];
				$value = Typecho_Widget::widget('Widget_Abstract_Contents')->push($post);
				
				echo '<li>';
				echo '<time>'.$value['date']->year.','.$value['date']->month.','. $value['date']->day .'</time>';
				echo '<h3>' .$value['title'].'</h3>';
				

				echo '</li>';
			}
			?>

		</ul> 
	</div>





</body>
</html>
