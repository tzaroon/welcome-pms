<?php
if (! function_exists('_token_payload')) {
    /**
     * Get the token bearer payload.
     *
     * @param string $authToken
     *
     * @return array
     */
    function _token_payload(string $authToken) : array
    {
        return [
            'auth_token' => $authToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ];
    }
}
