<?php
/* List Block */
if(!class_exists('Team_Block')) {
class Team_Block extends AQ_Block {
	function __construct() {
		$block_options = array(
		'name' => '<i class="fa fa-user"></i> Team',
		'size' => 'col-md-12',
	);
	
	//create the widget
	parent::__construct('Team_Block', $block_options);
	
	//add ajax functions
	add_action('wp_ajax_aq_block_team_add_new', array($this, 'add_team_item'));
	
	}
	
	function form($instance){
		$defaults = array(
            'title' => '',
        	'columm' => '3',           
            'items' => array(
				1 => array(
				'title' => 'John Doe',
				'image' => '',
				'name' => '',
				'job' => 'Senior SEO expert',
				'social' => '<li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
<li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
<li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
<li><a class="dribble" href="#"><i class="fa fa-dribbble"></i></a></li>',
				'content' => 'Donec ornare mattis suscipit. Praesent fermentum accumsan vulputate.'
				)
            ),
        );
        $instance = wp_parse_args($instance, $defaults);
        extract($instance);
        
        ?>
		<p class="description ">
			<label for="<?php echo $this->get_field_id('title') ?>">
				Title
				<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			</label>
		</p>
		<div class="description cf">
			<ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
			<?php
				$items = is_array($items) ? $items : $defaults['items'];
				$count = 1;
				foreach($items as $item) {	
					$this->item($item, $count);
					$count++;
				}
			?>
			</ul>
			<p></p>
				<a href="#" rel="team" class="aq-sortable-add-new button">Agregar nuevo</a>
			<p></p>
		</div>
		
<?php	
	}
function item($item = array(), $count = 0) {

?>
	<li id="sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">
		<div class="sortable-head cf">
			<div class="sortable-title">
				<strong><?php echo $item['title'] ?></strong>
			</div>
			<div class="sortable-handle">
				<a href="#">Open / Close</a>
			</div>
		</div>
		<div class="sortable-body">
			<div class="tab-desc description">
				<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-title">
					Name <br/>
					<input type="text" id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][title]" value="<?php echo $item['title'] ?>" />
				</label>
			</div>
		
			<div class="tab-desc description">
				<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-image">
					<b>Avatar </b><em style="font-size: 0.8em;"></em>
					<input type="text" id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-image" class="input-full input-upload" value="<?php echo $item['image'] ?>" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][image]">
					<a href="#" class="aq_upload_button button" rel="image">Upload</a><p></p>
				</label>
			</div>
			
			<div class="tab-desc description">
				<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-job">
					Info <br/>
					<input type="text" id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-job" class="input-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][job]" value="<?php echo $item['job'] ?>" />
				</label>
			</div>
			<div class="tab-desc description">
				<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-social">
					Socials <br/>
					<textarea rows="10" id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-social" class="input-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][social]"><?php echo $item['social'] ?></textarea>
				</label>
			</div>
			<div class="tab-desc description">
				<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-content">
					Content <br/>
					<textarea rows="10" id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-content" class="input-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][content]"><?php echo $item['content'] ?></textarea>
				</label>
			</div>
		<p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
		</div>
	</li>

  <?php  
    }
	
	function block($instance){
		extract($instance); ?>
		<div class="team-box">
			<div id="owl-demo" class="owl-carousel owl-theme">
		
				<?php  $t = 1; foreach($items as $item){ ?>
				
				<div class="item team-post">
					<img alt="<?php echo $item['title'] ?>" src="<?php echo $item['image'] ?>">
					<div class="team-head">
						<h2><?php echo $item['title'] ?></h2>
						<span><?php echo $item['job'] ?></span>
					</div>
					<p><?php echo do_shortcode(htmlspecialchars_decode($item['content'])); ?></p>
					<ul class="team-social">
						<?php echo do_shortcode(htmlspecialchars_decode( $item['social'])); ?>
					</ul>
				</div>
				
				<?php } ?>
			</div>
			<div class="buttons">
				<a class="owl-prev button-third" href="#"><i class="fa fa-angle-left"></i></a>
				<a class="owl-next button-third" href="#"><i class="fa fa-angle-right"></i></a>
			</div>
		</div>
	<?php
		
	}
	
	/* AJAX add testimonial */
		function add_team_item() {
		$nonce = $_POST['security'];	
		if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');
		
		$count = isset($_POST['count']) ? absint($_POST['count']) : false;
		$this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
		
		//default key/value for the testimonial
		$item = array(
				'title' => 'John Doe',
				'image' => '',
				'name' => '',
				'job' => 'Senior SEO expert',
				'social' => '<li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
<li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
<li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
<li><a class="dribble" href="#"><i class="fa fa-dribbble"></i></a></li>',
				'content' => 'Donec ornare mattis suscipit. Praesent fermentum accumsan vulputate.'
				);
		
		if($count) {
		$this->item($item, $count);
		} else {
		die(-1);
		}
		
		die();
		}
	function update($new_instance, $old_instance) {
		$new_instance = aq_recursive_sanitize($new_instance);
		return $new_instance;
	}
}
}
?>