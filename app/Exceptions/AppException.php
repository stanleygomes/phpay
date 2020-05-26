<?php

namespace App\Exceptions;

use Exception;
// use Illuminate\Support\Facades\Log;

class AppException extends Exception {
    public function report(Exception $exception) {
    }

    public function render() {
        // Log::error($this->getMessage());

        return $this;
    }
}
