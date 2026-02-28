<?php
// Load WordPress environment
require_once('../../../wp-load.php');

// HTML File Path
$html_file = 'C:\Users\Demirhan Özdemir\Desktop\eternal\eternal-tasarim\urunlerimiz.html';

if (!file_exists($html_file)) {
    die("HTML file not found: $html_file");
}

$html_content = file_get_contents($html_file);

// Parse HTML
$dom = new DOMDocument();
@$dom->loadHTML($html_content); // Suppress warnings for malformed HTML
$xpath = new DOMXPath($dom);

// Find all product cards
$product_cards = $xpath->query("//div[contains(@class, 'eternal-product-card')]");

$products = [];
$count = 0;

foreach ($product_cards as $card) {
    // Category (from data-category attribute)
    $category = $card->getAttribute('data-category');

    // Image
    $img_node = $xpath->query(".//div[contains(@class, 'eternal-product-image')]//img", $card)->item(0);
    $image = $img_node ? $img_node->getAttribute('src') : '';

    // Badge
    $badge_node = $xpath->query(".//span[contains(@class, 'eternal-product-badge')]", $card)->item(0);
    $badge = $badge_node ? trim($badge_node->textContent) : '';

    // Name
    $name_node = $xpath->query(".//h3[contains(@class, 'eternal-product-name')]", $card)->item(0);
    $name = $name_node ? trim($name_node->textContent) : '';

    // Description
    $desc_node = $xpath->query(".//p[contains(@class, 'eternal-product-desc')]", $card)->item(0);
    $desc = $desc_node ? trim($desc_node->textContent) : '';

    if ($name) {
        $products[] = [
            'name' => $name,
            'desc' => $desc,
            'category' => $category,
            'image' => $image,
            'badge' => $badge
        ];
        $count++;
    }
}

// Update Option
if (!empty($products)) {
    update_option('eternal_products_products_list', $products);
    echo "Successfully imported $count products from HTML file.";
    echo "<pre>";
    print_r($products);
    echo "</pre>";
} else {
    echo "No products found in the HTML file.";
}
?>