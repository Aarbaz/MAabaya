<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Label extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    /**
     * Display the label generation form
     */
    public function index()
    {
        $data['title'] = 'Generate Label';
        $this->load->view('layout/header', $data);
        $this->load->view('layout/menubar');
        $this->load->view('label_generator');
        $this->load->view('layout/footer');
    }

    /**
     * Generate labels based on form input (AJAX endpoint)
     */
    public function generate()
    {
        // Get POST data
        $design_no = $this->input->post('design_no');
        $fabric = $this->input->post('fabric');
        $product_size = $this->input->post('product_size');
        $price = $this->input->post('price');
        $size = $this->input->post('size');
        $custom_width = $this->input->post('custom_width');
        $custom_height = $this->input->post('custom_height');
        $quantity = $this->input->post('quantity');
        $font_size = $this->input->post('font_size', 10);
        $font_bold = $this->input->post('font_bold', 'normal');

        // Validate input
        if (empty($design_no) || empty($fabric) || empty($product_size) || empty($price) || empty($quantity)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please fill all required fields'
            ]);
            return;
        }

        // Determine label size class
        $size_class = '';
        $size_name = '';
        
        if ($size === 'custom') {
            if (empty($custom_width) || empty($custom_height)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Please enter custom size dimensions'
                ]);
                return;
            }
            $size_class = 'custom';
            $size_name = $custom_width . '" x ' . $custom_height . '"';
        } else {
            $size_class = $size;
            // Map size codes to display names
            $size_names = [
                'size-30' => '30 per sheet (2.625" x 1")',
                'size-40' => '40 per sheet (1.799" x 1.003")',
                'size-24' => '24 per sheet (2.48" x 1.334")',
                'size-20' => '20 per sheet (4" x 1")',
                'size-18' => '18 per sheet (2.5" x 1.835")',
                'size-14' => '14 per sheet (4" x 1.33")',
                'size-12' => '12 per sheet (2.5" x 2.834")',
                'size-10' => '10 per sheet (4" x 2")'
            ];
            $size_name = isset($size_names[$size]) ? $size_names[$size] : $size;
        }

        // Generate labels HTML
        $font_weight = ($font_bold === 'bold') ? 'bold' : 'normal';
        $labels_html = '';
        for ($i = 0; $i < $quantity; $i++) {
            $labels_html .= '
            <div class="label-item ' . $size_class . '" style="font-size: ' . $font_size . 'px; font-weight: ' . $font_weight . ';">
                <div class="label-content">
                    <div class="label-field"><strong>Design:</strong> ' . htmlspecialchars($design_no) . '</div>
                    <div class="label-field"><strong>Fabric:</strong> ' . htmlspecialchars($fabric) . '</div>
                    <div class="label-field"><strong>Size:</strong> ' . htmlspecialchars($product_size) . '</div>
                    <div class="label-field"><strong>Price:</strong> ₹' . htmlspecialchars($price) . '</div>
                </div>
            </div>';
        }

        // Return response
        echo json_encode([
            'success' => true,
            'labels_html' => $labels_html,
            'size_class' => $size_class,
            'custom_width' => $custom_width,
            'custom_height' => $custom_height,
            'quantity' => $quantity
        ]);
    }
}
