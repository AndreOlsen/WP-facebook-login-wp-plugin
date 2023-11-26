<?php
/**
 * @var string|int $app_id
 * @var string|int $secret_key
 * @var string     $login_id
 * @var string     $logout_id
 */
?>

<div class="wrap">
	<h1><?php _e('Easy Facebook Login Settings', 'easy-facebook-login'); ?></h1>

	<form method="POST" novalidate="novalidate">
		<input type="hidden" name="action" value="efl_setting-update">
		<?php wp_nonce_field(); ?>

		<table class="form-table">
			<tr>
				<th>
					<label for="efl_app-id"><?php _e('Facebook App ID', 'easy-facebook-login'); ?></label>
				</th>
				<td>
					<input type="text" name="efl_app-id" id="efl_app-id" value="<?php echo $app_id; ?>">
				</td>
			</tr>

			<tr>
				<th>
					<label for="efl_secret-key"><?php _e('Facebook Secret Key', 'easy-facebook-login'); ?></label>
				</th>
				<td>
					<input type="text" name="efl_secret-key" id="efl_secret-key" value="<?php echo $secret_key; ?>">
				</td>
			</tr>

			<tr>
				<th>
					<label for="efl_login-id"><?php _e('Login Button ID', 'easy-facebook-login'); ?></label>
				</th>
				<td>
					<input type="text" name="efl_login-id" id="efl_login-id" value="<?php echo $login_id; ?>">
					<p class="description" id="tagline-description"><?php _e('ID of the facebook login button.', 'easy-facebook-login'); ?></p>
				</td>
			</tr>

			<tr>
				<th>
					<label for="efl_logout-id"><?php _e('Logout Button ID', 'easy-facebook-login'); ?></label>
				</th>
				<td>
					<input type="text" name="efl_logout-id" id="efl_logout-id" value="<?php echo $logout_id; ?>">
					<p class="description" id="tagline-description"><?php _e('ID of the facebook logout button.', 'easy-facebook-login'); ?></p>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" class="button button-primary" name="efl_submit" value="<?php _e('Submit', 'easy-facebook-login'); ?>">
		</p>
	</form>
</div>