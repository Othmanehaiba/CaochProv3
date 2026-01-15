<?php

echo "Hello, World!";

htmlspecialchars("<script>alert('XSS');</script>", ENT_QUOTES, 'UTF-8');