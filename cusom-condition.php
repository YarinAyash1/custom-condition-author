add_action( 'elementor/theme/register_conditions', function( $conditions_manager ) {
	
	class SingleAuthor extends ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {
		
		public static function get_type() {
			return 'archive';
		}

		public static function get_priority() {
			return 70;
		}

		public function get_name() {
			return 'author_archive_2';
		}

		public function get_label() {
			return __( 'ארכיון משתמש' );
		}
		public function register_sub_conditions() {

		}
		public function get_author_roles(){
			global $wp_roles;	

			if ( ! isset( $wp_roles ) )
				$wp_roles = new WP_Roles();

			$data = array();
			$data['all'] = 'All Authors';
			foreach ( $wp_roles->get_names() as $id => $val ) {		
				$data[$id] = $val;
			}
			return $data;

		}
		public function check( $args ) {
			$author_id = get_queried_object_id();
			$user_data = get_userdata($author_id);
			$user_role = $user_data->roles;
			if(is_author()){
				if($user_role[0] == $args['id']){
					return true;
				}
				elseif($args['id'] == 'all'){
					return true;
				}
			}
		}

		protected function _register_controls() {
			$this->add_control(
				'author_roles',
				[
					'section' => 'settings',
					'label' => __( '' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $this->get_author_roles(),
				]
			);
		}
	}

	$conditions_manager->get_condition( 'archive' )->register_sub_condition( new SingleAuthor() );
}, 100 );
