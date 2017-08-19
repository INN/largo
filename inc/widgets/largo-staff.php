<?php

class largo_staff_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'largo_staff_widget', // Base ID
			'Largo Staff Roster Widget', // Name
			array( 'description' => 'Display a list of staff members with photos and bios.' ) // Args
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		if (!empty($title))
			echo $args['before_title'] . $title . $args['after_title'];

		if (empty($instance['roles'])) {
			$users = get_users(array(
				'blog_id' => get_current_blog_id(),
				'role' => 'author'
			));
		} else {
			$users = array();
			foreach ($instance['roles'] as $key => $val) {
				if ($val == 'on') {
					$result = get_users(array(
						'blog_id' => get_current_blog_id(),
						'role' => $key
					));
					$users = array_merge($users, $result);
				}
			}
		}

		$users = apply_filters('largo_staff_widget_users', $users);

		$markup = '<ul class="staff-roster">';
		foreach ($users as $user) {
			$hide = get_user_meta($user->ID, 'hide', true);
			if ($hide == 'on')
				continue;

			$avatar_alt = esc_attr( $user->first_name . ' ' . $user->last_name );
			if ( $job_title = get_user_meta( $user->ID, 'job_title', true ) ) {
				$avatar_alt .= ' (' . $job_title . ')';
			}
			$avatar = get_avatar($user->ID, '65', null, esc_attr($avatar_alt));
			$author_url = get_author_posts_url($user->ID);

			$user_posts_link = '';
			if (count_user_posts($user->ID) > 0)
				$user_posts_link = "<a href=\"$author_url\">{$user->first_name}'s posts</a>";

			$markup .= "<li>";
			$markup .= "<div>";

			if (count_user_posts($user->ID) > 0)
				$markup .= "<a href='$author_url'>";

			$markup .= "		$avatar";
			$markup .= "		<span class='staff-name'>{$user->display_name}</span>";

			if (count_user_posts($user->ID) > 0)
				$markup .= "</a>";

			$markup .= "	<p>$job_title<p>";
			$markup .= "	$user_posts_link";
			$markup .= "</div>";
			$markup .= "</li>";

		}
		$markup .= '</ul>';

		echo $markup;
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$roles = get_editable_roles();

		if (isset($instance['title']))
			$title = $instance['title'];
		else
			$title = 'Staff Members';

		if (empty($instance['roles']))
			$instance['roles']['author'] = 'on';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'largo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label><?php _e( 'Include:', 'largo' ); ?></label><br/>
			<?php foreach ($roles as $key => $role) { ?>
			<label><input <?php checked($instance['roles'][$key], 'on', true); ?>
					type="checkbox"
					id="<?php echo $this->get_field_id($key); ?>"
					name="<?php echo $this->get_field_name($key); ?>"> <?php echo $role['name']; ?>s</label><br />
			<?php } ?>
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title']))? strip_tags($new_instance['title']) : '';

		$roles = get_editable_roles();
		foreach ($roles as $key => $role)
			$instance['roles'][$key] = (!empty($new_instance[$key]))? $new_instance[$key] : 'off';

		return $instance;
	}


}
