<?php
// Include header
include_once __DIR__ . '/../inc/header.php';

// Include view content based on the requested view
$viewContent = isset($viewContent) ? $viewContent : '';
echo $viewContent;

// Include footer
include_once __DIR__ . '/../inc/footer.php';
?> 