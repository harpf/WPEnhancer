<?php
class WPEnhancer_UserMeta {

    public function add_custom_user_profile_field($user) {
        ?>
        <h3>WPEnhancer Benutzerfeld</h3>
        <table class="form-table">
            <tr>
                <th><label for="juror_id">Juror ID</label></th>
                <td>
                    <input type="text" name="juror_id" id="juror_id"
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'juror_id', true)); ?>"
                           class="regular-text" /><br/>
                    <span class="description">Bitte gib deine Juror ID ein.</span>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_custom_user_profile_field($user_id) {
        if (!current_user_can('edit_user', $user_id)) return;

        if (isset($_POST['juror_id'])) {
            update_user_meta($user_id, 'juror_id', sanitize_text_field($_POST['juror_id']));
        }
    }
}
