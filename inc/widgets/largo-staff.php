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

		$markup = '<ul class="staff-roster">';
		foreach ($users as $user) {
			if (get_user_meta($user->ID, 'hide', true))
				continue;

			$avatar = get_avatar($user->ID, '65');
			$author_url = get_author_posts_url($user->ID);
			$twitter = get_user_meta($user->ID, 'twitter', true);
			$emeritus = get_user_meta($user->ID, 'emeritus', true);
			$honorary = get_user_meta($user->ID, 'honorary', true);
			$twitter = (!empty($twitter))? $twitter : $author_url;
			$job_title = get_user_meta($user->ID, 'job_title', true);

			if (!empty($honorary) && !empty($emeritus)) {
				$job_title = $job_title . ' (Honorary, emeritus)';
			} else {
				if (!empty($honorary))
					$job_title = $job_title . ' (Honorary)';

				if (!empty($emeritus))
					$job_title = $job_title . ' (Emeritus)';
			}

			$user_posts_link = '';
			if (count_user_posts($user->ID) > 0)
				$user_posts_link = "<a href=\"$author_url\">{$user->first_name}'s posts</a>";

			$markup .= <<<EOD
<li>
	<div>
		<a href="$twitter">
			$avatar
			<span class="staff-name">{$user->display_name}</span>
		</a>
		<p>$job_title<p>
		$user_posts_link
	</div>
</li>
EOD;
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
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label><?php _e( 'Include:' ); ?></label><br/>
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
