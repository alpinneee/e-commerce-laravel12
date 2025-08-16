<?php
// Check if GD extension is loaded
if (extension_loaded('gd')) {
    echo "✅ GD extension is loaded\n";
    
    // Get GD info
    $gdInfo = gd_info();
    echo "GD Version: " . $gdInfo['GD Version'] . "\n";
    
    // Check supported formats
    echo "Supported formats:\n";
    echo "- JPEG: " . (imagetypes() & IMG_JPG ? "Yes" : "No") . "\n";
    echo "- PNG: " . (imagetypes() & IMG_PNG ? "Yes" : "No") . "\n";
    echo "- GIF: " . (imagetypes() & IMG_GIF ? "Yes" : "No") . "\n";
} else {
    echo "❌ GD extension is NOT loaded\n";
    echo "Please enable GD extension in php.ini\n";
}
?>