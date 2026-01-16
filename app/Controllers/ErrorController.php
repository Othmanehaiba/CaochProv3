<?php

class ErrorController
{
    public function error(): void
    {
        http_response_code(404);
        echo "Page not found.";
    }
}