<?php
/**
 * Awesomesauce class.
 *
 * @category   Class
 * @package    ElementorAwesomesauce
 * @subpackage WordPress
 * @author     Ben Marshall <me@benmarshall.me>
 * @copyright  2020 Ben Marshall
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://www.benmarshall.me/build-custom-elementor-widgets/,
 *             Build Custom Elementor Widgets)
 * @since      1.0.0
 * php version 7.3.9
 */

namespace ElementorDoccure\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class PostGrid extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'doccure-posts-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Posts Grid', 'doccure_elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'doccure' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
  // 'limit'=>'6',
  //           'orderby'=> 'date',
  //           'order'=> 'DESC',
  //           'categories' => '',
  //           'exclude_posts' => '',
  //           'include_posts' => '',
  //           'ignore_sticky_posts' => 1,
  //           'limit_words' => 15,
  //           'from_vs' => 'no'


		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Query', 'doccure_elementor' ),
			)
		);

		$this->add_control(
			'limit',
			[
				'label' => __( 'Posts to display', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 21,
				'step' => 1,
				'default' => 3,
			]
		);

		$this->add_control(
			'styletype',
			[
				'label' => __( 'Style', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style_main' =>  __( 'Style Main', 'doccure_elementor' ),
					'style1' =>  __( 'Style 1', 'doccure_elementor' ),
					'style2' =>  __(  'Style 2', 'doccure_elementor' ),
					'style3' =>  __(  'Style 3', 'doccure_elementor' ),
					'style4' =>  __(  'Style 4', 'doccure_elementor' ),
					'style5' =>  __(  'Style 5', 'doccure_elementor' ),
				 
				],
			]
		);
		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'none' =>  __( 'No order', 'doccure_elementor' ),
					'ID' =>  __(  'Order by post id. ', 'doccure_elementor' ),
					'author'=>  __(  'Order by author.', 'doccure_elementor' ),
					'title' =>  __(  'Order by title.', 'doccure_elementor' ),
					'name' =>  __( ' Order by post name (post slug).', 'doccure_elementor' ),
					'type'=>  __( ' Order by post type.', 'doccure_elementor' ),
					'date' =>  __( ' Order by date.', 'doccure_elementor' ),
					'modified' =>  __( ' Order by last modified date.', 'doccure_elementor' ),
					'parent' =>  __( ' Order by post/page parent id.', 'doccure_elementor' ),
					'rand' =>  __( ' Random order.', 'doccure_elementor' ),
					'comment_count' =>  __( ' Order by number of commen', 'doccure_elementor' ),
					
				],
			]
		);
		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'doccure_elementor' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Latest Articles',
				'condition' => [
					'styletype' => [ 'style_main' ], 
				],
			)
		);

		$this->add_control(
			'link',
			array(
				'label'   => __( 'Link', 'doccure_elementor' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'styletype' => ['style1','style2','style3','style4','style5'], 
				],
			)
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'doccure_elementor'  ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' =>  __( 'Descending', 'doccure_elementor' ),
					'ASC' =>  __(  'Ascending. ', 'doccure_elementor' ),
				
					
				],
			]
		);


			$this->add_control(
				'categories',
				[
					'label' => __( 'Show from categories', 'doccure_elementor' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'default' => [],
					'options' => $this->get_terms('category'),
					
				]
			);	

			$this->add_control(
				'exclude_posts',
				[
					'label' => __( 'Exclude posts', 'doccure_elementor' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'default' => [],
					'options' => $this->get_posts(),
					
				]
			);	
			$this->add_control(
				'include_posts',
				[
					'label' => __( 'Include posts', 'doccure_elementor' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'default' => [],
					'options' => $this->get_posts(),
					
				]
			);

			$this->add_control(
				'limit_words',
				[
					'label' => __( 'Excerpt length', 'doccure_elementor' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 5,
					'max' => 99,
					'step' => 1,
					'default' => 15,
				]
			);

			$this->add_control(
			'after_excerpt',
			[
				'label' => __( 'Add after excerpt', 'doccure_elementor'  ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '...', 'plugin-domain' ),
				
			]);

			 
			$this->add_control(
				'show_excerpt',
				[
					'label' => __( 'Show post excerpt', 'doccure_elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'doccure_elementor' ),
					'label_off' => __( 'Hide', 'doccure_elementor' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);		
			$this->add_control(
				'show_date',
				[
					'label' => __( 'Show post date', 'doccure_elementor'  ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'doccure_elementor' ),
					'label_off' => __( 'Hide', 'doccure_elementor' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);
			$this->add_control(
				'show_category',
				[
					'label' => __( 'Show post category', 'doccure_elementor'  ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'doccure_elementor' ),
					'label_off' => __( 'Hide', 'doccure_elementor' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);




		$this->end_controls_section();


		// $this->add_control(
		// 	'with_line',
		// 	[
		// 		'label' => __( 'With Line', 'plugin-domain' ),
		// 		'type' => \Elementor\Controls_Manager::SWITCHER,
		// 		'label_on' => __( 'Show', 'doccure_elementor' ),
		// 		'label_off' => __( 'Hide', 'doccure_elementor' ),
		// 		'return_value' => 'yes',
		// 		'default' => 'yes',
		// 	]
		// );
	



		/* Add the options you'd like to show in this tab here */

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'subtitle', 'none' );
		$limit = $settings['limit'] ? $settings['limit'] : 3;
		$orderby = $settings['orderby'] ? $settings['orderby'] : 'title';
		$order = $settings['order'] ? $settings['order'] : 'ASC';
		$exclude_posts = $settings['exclude_posts'] ? $settings['exclude_posts'] : 'ASC';
		$categories = $settings['categories'] ? $settings['categories'] : array();
		$limit_words = $settings['limit_words'] ? $settings['limit_words'] : 15;
	

		$args = array(
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'order' => $order,
            );

        if(!empty($exclude_posts)) {
            $exl = is_array( $exclude_posts ) ? $exclude_posts : array_filter( array_map( 'trim', explode( ',', $exclude_posts ) ) );
            $args['post__not_in'] = $exl;
        }

        if(!empty($include_posts)) {
            $exl = is_array( $include_posts ) ? $include_posts : array_filter( array_map( 'trim', explode( ',', $include_posts ) ) );
            $args['post__in'] = $exl;
        }

       
        if(!empty($categories)) {
            $categories         = is_array( $categories ) ? $categories : array_filter( array_map( 'trim', explode( ',', $categories ) ) );
            $args['category__in'] = $categories;
        }
      

        $i = 0;

        $wp_query = new \WP_Query( $args ); ?>
		
		<?php if($settings['styletype'] == 'style_main'){ ?>
			<section class="articles-section">
				<div class="container">
				<?php if($settings['title']!= '')  { ?>
					<div class="row">
						<div class="col-md-12 aos" data-aos="fade-up">
							<div class="section-header-one text-center">
								<h2 class="section-title"><?php echo $settings['title']; ?></h2>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="row">
			 <?php }  else { ?>
			<div class="row latest-news">
			<?php } ?>
			<?php if ( $wp_query->have_posts() ) { ?>


				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$i++;
                $id = $wp_query->post->ID;
                $thumb = get_post_thumbnail_id();
                $img_url = wp_get_attachment_url( $thumb,'doccure-blog-related-post');
                  
				$custom = get_post_custom( get_the_ID() );
				if ( has_post_thumbnail() ) { 
						 $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
					
			 }else{ 
					$url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
			 } 
   ?>

<?php if($settings['styletype'] == 'style_main'){ ?>
 

		<!-- Articles Section -->
  						<div class="col-lg-6 col-md-6 d-flex aos" data-aos="fade-up">
							<div class="articles-grid w-100">
								<div class="articles-info">
									<div class="articles-left">
										<a href="<?php the_permalink();?>">
											<div class="articles-img">
 												<img src="<?php echo $url;?>" class="img-fluid"/>
											</div>
										</a>
									</div>
									<div class="articles-right">
										<div class="articles-content">
											<ul class="articles-list nav">
												<li>
													<i class="feather-user"></i>  <?php echo get_the_author(); ?>
												</li>
 
												<?php if($settings['show_date'] == 'yes'){ ?>
												<li>
													<i class="feather-calendar"></i> <?php echo get_the_date(); ?>
												</li>
												<?php } ?>
											</ul>
											<h4>
												<a href="<?php the_permalink();?>"><?php the_title();?></a>
											</h4>
											<?php if($settings['show_excerpt'] == 'yes'){ ?>
												<p><?php 
                                $excerpt = get_the_excerpt();
                               echo doccure_string_limit_words($excerpt,$limit_words); echo $settings['after_excerpt']; ?>
                            </p>
											<a href="<?php the_permalink();?>" class="btn"><?php esc_html_e('Read More','doccure_elementor'); ?></a>
											<?php } ?>
											
										</div>
									</div>
								</div>
							</div>
						</div>
						 
						 
						 
 				 
			<!-- /Articles Section -->

 <?php } else if($settings['styletype'] == 'style1'){ ?>
<div class="col-lg-4 col-md-6">
              <article>
                <div class="homeblog_img">
                  <a href="<?php the_permalink();?>"><img src="<?php echo $url;?>"/></a>
                </div>
                <div class="homeblog_content_block">
                  <div class="homeblog_desc">
				  <?php if($settings['show_date'] == 'yes'){ ?>
                      <div class="homeblog_date">
                        <?php echo get_the_date(); ?>
                      </div>
					  <?php } ?>
					  <?php  if($settings['show_category'] == 'yes'){ ?>
                      <div class="homeblog_meta">
                      <?php 
                      $category_detail=get_the_category(get_the_ID() );
                          foreach($category_detail as $cd){
                            echo $cd->cat_name;
                      }
                      ?>
                      </div>
					  <?php } ?>
                      <h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>

					  <?php if($settings['show_excerpt'] == 'yes'){ ?>
                            <p><?php 
                                $excerpt = get_the_excerpt();
                               echo doccure_string_limit_words($excerpt,$limit_words); echo $settings['after_excerpt']; ?>
                            </p>
                         <?php } ?>

                  </div>


                  <div class="homeblog_readmore">
                      <a href="<?php the_permalink();?>"><?php esc_html_e('Read More','doccure_elementor'); ?></a>
                  </div>
                </div>
              </article>
            </div>

 <?php } else if($settings['styletype'] == 'style2') {?>

	<div class="col-lg-4 col-md-6">
              <article>
                <div class="homeblog_img">
                  <a href="<?php the_permalink();?>"><img src="<?php echo $url;?>"/></a>
                </div>
                <div class="homeblog_content_block">
                  <div class="homeblog_desc">
                      <div class="hometwoblog_date">
                        <span class="posted-on">
                        <i class="far fa-clock"></i>
                        <a href="<?php the_permalink();?>" rel="bookmark">
                        <?php echo get_the_date(); ?></a>
                        </span>
                      </div>
                      <div class="hometwoblog_meta">
                      <span class="categories-list">
                          <i class="fas fa-tags"></i>
                          <a href="https://doccure-wp.dreamstechnologies.com/category/clinic/" rel="category tag">
                          <?php 
                          $category_detail=get_the_category(get_the_ID() );
                              foreach($category_detail as $cd){
                                echo $cd->cat_name;
                          }
                          ?>
                      </a> </span>
                      </div>
                      <h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                  </div>
                  <div class="hometwoblog_readmore">
                      <a href="<?php the_permalink();?>"><?php esc_html_e('Read More','doccure_elementor'); ?></a>
                  </div>
                </div>
              </article>
            </div>
			
	<?php } else if($settings['styletype'] == 'style3') {?>

		<div class="col-lg-4 col-md-6">
              <article>
                <div class="homeblog_img">
                  <a href="<?php the_permalink();?>"><img src="<?php echo $url;?>"/></a>
                  <div class="overlay"></div>
                </div>
                
                <div class="homeblog_content_block">
                  <div class="homeblog_desc">
                      <div class="hometwoblog_date">
                        <span class="posted-on">
                        <i class="far fa-clock"></i>
                        <a href="<?php the_permalink();?>" rel="bookmark">
                        <?php echo get_the_date(); ?></a>
                        </span>
                      </div>
                      <h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                  </div>
                </div>
                <div>
                <a href="<?php the_permalink();?>" class="blog-news-arrows" tabindex="0">
                    <i class="fas fa-arrow-right"></i>
                </a>
              </div>
              </article>
            </div>
			<?php } else if($settings['styletype'] == 'style4') {?>
				<div class="col-lg-4 col-md-6">
              <article>
                <div class="homeblog_img">
                   <a href="<?php the_permalink();?>"><img src="<?php echo $url;?>"/></a>
                   <div class="blog-date-four">
                    <span class="posted-on">
                    <i class="far fa-clock"></i>
                    <a href="<?php the_permalink();?>" rel="bookmark" tabindex="0">
                    <?php echo get_the_date(); ?></a></a>
                    </span>
                    </div>
                  </div>
                
                <div class="homeblog_content_block">
                  <div class="blog-info-four">
                      <h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                  </div>
                  <div class="blog-doctors-four">
                  <div class="d-flex justify-content-between align-items-center">
                  <div>
                  <span class="author">
                  <?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
                  <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                  <?php echo get_the_author(); ?> </a>
                  </span>
                  </div>
                  <div>
                  <span class="categories-list">
                    <i class="fas fa-tags"></i>
                    <a href="https://doccure-wp.dreamstechnologies.com/category/clinic/" rel="category tag" tabindex="0">
                    <?php 
                            $category_detail=get_the_category(get_the_ID() );
                                foreach($category_detail as $cd){
                                  echo $cd->cat_name;
                            }
                            ?>
                    </a> 
                  </span>
                  </div>
                  </div>
                  </div>
                  <div class="homefour_blogcontent">
                  <p>
                  <?php echo wp_trim_words( get_the_content(), 25, '...' );?>
                  </p>
                  <div class="blog-four-arrow">
                    <a href="<?php the_permalink(); ?>"><i class="fas fa-arrow-right"></i></a>
                        </div>
                  </div>
                  
                </div>
               
              </article>
            </div>

			<?php } else if($settings['styletype'] == 'style5') {?>
				<div class="col-lg-4 col-md-6">
              <article>
                <div class="homeblog_img blog-five-img">
                   <a href="<?php the_permalink();?>"><img src="<?php echo $url;?>"/></a>
                   <div class="blog-item-info">
  
                    <div class="blog-news-date">
                        <div class="homefive_postmeta">
                          <span class="posted-on">
                          <i class="far fa-clock"></i>
                          <a href="<?php the_permalink();?>" rel="bookmark" tabindex="0">
                          <?php echo get_the_date(); ?></a>
                          </span>
                        </div>
                    </div>
  
                    <div class="blog-doctors-profile">
                        <span class="author">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
                        <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                        <?php echo get_the_author(); ?> </a>
                        </span>
                    </div>
  
                    </div>
                   
                </div>
                
                <div class="blog-info-five">
                      <h3 class="blog-news-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                      <p>
                        <?php echo wp_trim_words( get_the_content(), 20, '...' );?>
                      </p>
                      <a href="<?php the_permalink();?>" class="btn-link" tabindex="0">
                        <?php echo esc_html__('Read News','doccure'); ?>
                    </a>
                </div>
  
  
              </article>
            </div>
			<?php } else { ?>

				<div class="col-lg-4 col-md-6">
              <article>
                <div class="homeblog_img">
                  <a href="<?php the_permalink();?>"><img src="<?php echo $url;?>"/></a>
                </div>
                <div class="homeblog_content_block">
                  <div class="homeblog_desc">
                      <div class="homeblog_date">
                        <?php echo get_the_date(); ?>
                      </div>
                      <div class="homeblog_meta">
                      <?php 
                      $category_detail=get_the_category(get_the_ID() );
                          foreach($category_detail as $cd){
                            echo $cd->cat_name;
                      }
                      ?>
                      </div>
                      <h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                  </div>
                  <div class="homeblog_readmore">
                      <a href="<?php the_permalink();?>"><?php esc_html_e('Read More','doccure_elementor'); ?></a>
                  </div>
                </div>
              </article>
            </div>
			

		<?php } ?>
<?php 
			 endwhile; // end of the loop. 
		} else {
			//do_action( "woocommerce_shortcode_{$loop_name}_loop_no_results" );
		}
        ?>
  </div>
  <?php   if($settings['styletype'] == 'style_main') {?>
				</div>
			</section>
	<?php } ?>
  <?php   if($settings['styletype'] == 'style2') {?>
 <?php if ( !empty($settings['link']) ) { ?>
			<div class="text-center"><a class="btn view-more" href="<?php echo $settings['link']; ?>"><?php esc_html_e ('View More','doccure_elementor'); ?><i class="fas fa-arrow-right ms-2"></i></a></div>
 <?php } ?>
 <?php } ?>
 <?php   if($settings['styletype'] == 'style4') {?>
 <?php if ( !empty($settings['link']) ) { ?>
 <div class="homefiveblog_section ">
			<div class="vc_btn3-container text-center viewall_btn vc_btn3-center d-flex justify-content-center"><a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom" href="#" title=""><?php esc_html_e ('View More','doccure_elementor'); ?></a></div>
 </div>
 <?php } ?>
 <?php } ?>

         
        <?php 
    
		wp_reset_postdata();
	
	
		
	}


		protected function get_terms($taxonomy) {
			$taxonomies = get_terms( array( 'taxonomy' =>$taxonomy,'hide_empty' => false) );

			$options = [ '' => '' ];
			
			if ( !empty($taxonomies) ) :
				foreach ( $taxonomies as $taxonomy ) {
					$options[ $taxonomy->term_id ] = $taxonomy->name;
				}
			endif;

			return $options;
		}

		protected function get_posts() {
			$posts = get_posts( array( 'numberposts' => 99,) );

			$options = [ '' => '' ];
			
			if ( !empty($posts) ) :
				foreach ( $posts as $post ) {
					$options[ $post->ID ] = get_the_title($post->ID);
				}
			endif;

			return $options;
		}
	
}