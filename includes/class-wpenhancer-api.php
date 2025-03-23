<?php
class WPEnhancer_API {

    public function register_rest_routes() {
        register_rest_route('wpenhancer/v1', '/set-jurorid', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle_jurorid_submission'],
            'permission_callback' => [$this, 'check_api_token'],
        ]);
    }

    public function handle_jurorid_submission($request) {
        $params = $request->get_json_params();

        $email = isset($params['email']) ? sanitize_email($params['email']) : null;
        $juror_id = isset($params['juror_id']) ? sanitize_text_field($params['juror_id']) : null;

        if (!$email || !$juror_id) {
            return new WP_REST_Response(['message' => 'Ungültige Anfrage'], 400);
        }

        $user = get_user_by('email', $email);
        if (!$user) {
            return new WP_REST_Response(['message' => 'Benutzer nicht gefunden'], 404);
        }

        update_user_meta($user->ID, 'juror_id', $juror_id);

        return new WP_REST_Response([
            'message' => 'Juror ID gespeichert',
            'user_id' => $user->ID,
            'juror_id' => $juror_id
        ], 200);
    }

    public function check_api_token($request) {
        $headers = $request->get_headers();

        if (!isset($headers['x_api_key'][0])) {
            return false;
        }

        $provided_token = $headers['x_api_key'][0];
        $stored_token = get_option('wpenhancer_api_key', '');

        return hash_equals($stored_token, $provided_token);
    }

    public function generate_api_key() {
        return bin2hex(random_bytes(32));
    }

    public function get_stored_api_key() {
        return get_option('wpenhancer_api_key', '');
    }

    public function set_api_key($key) {
        update_option('wpenhancer_api_key', $key);
    }
}
