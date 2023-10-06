<?php
/**
 * Plugin Name: Product ID Exporter
 * Description: Export product IDs to a CSV file.
 * Version: 1.0
 * Author: Hasnain Qureshi
 */

// Add a menu item to the admin sidebar
function add_product_id_export_menu_item() {
    add_menu_page(
        'Product ID Export',
        'Product ID Export',
        'manage_options',
        'product-id-export',
        'product_id_export_page'
    );
}
add_action('admin_menu', 'add_product_id_export_menu_item');

// Callback function to display the export page
function product_id_export_page() {
    ?>
    <div class="wrap">
        <h2>Product ID Export</h2>
        <p>This page allows you to export product IDs to a CSV file.</p>
        
        <!-- Display a value at the top of the page -->
        <div class="notice notice-info">
            <p>Value to be displayed at the top of the page.</p>
        </div>

        <!-- Export button -->
        <form method="post" action="">
            <input type="hidden" name="product_id_export" value="1">
            <button class="button button-primary" type="submit">Export Product IDs</button>
        </form>
    </div>
    <?php

    // Handle the export action
    if (isset($_POST['product_id_export']) && $_POST['product_id_export'] == 1) {
        export_product_ids_to_csv();
    }
}

// Function to export product IDs to CSV
function export_product_ids_to_csv() {
    global $wpdb;

    $product_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'product'");

    if (!empty($product_ids)) {
        ob_clean(); // Clean the output buffer
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="product_ids.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        fputcsv($output, array('Product ID'));

        foreach ($product_ids as $product_id) {
            fputcsv($output, array($product_id));
        }

        fclose($output);
        exit;
    }
}

// Hook to handle the export action
function handle_export_action() {
    if (isset($_POST['product_id_export']) && $_POST['product_id_export'] == 1) {
        export_product_ids_to_csv();
    }
}
add_action('admin_init', 'handle_export_action');