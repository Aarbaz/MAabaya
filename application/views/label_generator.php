<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('assets/css/label.css'); ?>" rel="stylesheet">

<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-12">
            <h2>Generate Labels</h2>
            <hr>
        </div>
    </div>

    <!-- Label Generation Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Label Information</h4>
                </div>
                <div class="panel-body">
                    <form id="labelForm">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="design_no">Design No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="design_no" name="design_no" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="fabric">Fabric <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="fabric" name="fabric" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="product_size">Size <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="product_size" name="product_size" placeholder="e.g., S, M, L, XL, 32, 34, etc." required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="price">Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-lg-4">

                                <div class="form-group">
                                    <label for="size">Paper Size <span class="text-danger">*</span></label>
                                    <select class="form-control" id="size" name="size" required>
                                        <option value="">Select Size</option>
                                        <option value="size-30">30 per sheet (2.625" x 1")</option>
                                        <option value="size-40">40 per sheet (a4) (1.799" x 1.003")</option>
                                        <option value="size-24">24 per sheet (a4) (2.48" x 1.334")</option>
                                        <option value="size-20">20 per sheet (4" x 1")</option>
                                        <option value="size-18">18 per sheet (a4) (2.5" x 1.835")</option>
                                        <option value="size-14">14 per sheet (4" x 1.33")</option>
                                        <option value="size-12">12 per sheet (a4) (2.5" x 2.834")</option>
                                        <option value="size-10">10 per sheet (4" x 2")</option>
                                        <option value="custom">Custom Size (in inches)</option>
                                    </select>
                                </div>

                                <!-- Custom Size Input (Hidden by default) -->
                                <div id="customSizeDiv" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="custom_width">Width (inches)</label>
                                                <input type="number" class="form-control" id="custom_width" name="custom_width" step="0.01" min="0.5">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="custom_height">Height (inches)</label>
                                                <input type="number" class="form-control" id="custom_height" name="custom_height" step="0.01" min="0.5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="quantity">Quantity of Labels <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                                </div>
                            </div>
                        </div>



                        <hr>
                        <h5>Font Customization</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="font_size">Font Size</label>
                                    <select class="form-control" id="font_size" name="font_size">
                                        <option value="12">Small (12px)</option>
                                        <option value="14">Normal (14px)</option>
                                        <option value="16" selected>Medium (16px)</option>
                                        <option value="18">Large (18px)</option>
                                        <option value="20">Extra Large (20px)</option>
                                        <option value="22">XXL (22px)</option>
                                        <option value="24">XXXL (24px)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="font_bold">Font Weight</label>
                                    <select class="form-control" id="font_bold" name="font_bold">
                                        <option value="normal">Normal</option>
                                        <option value="bold">Bold</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <span class="glyphicon glyphicon-tag"></span> Generate Labels
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Labels Preview Section -->
    <div class="row" id="labelsSection" style="display: none;">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4>Generated Labels <span id="labelCount"></span></h4>
                </div>
                <div class="panel-body">
                    <div class="text-right" style="margin-bottom: 15px;">
                        <button type="button" class="btn btn-success btn-lg" onclick="printLabels()">
                            <span class="glyphicon glyphicon-print"></span> Print Labels
                        </button>
                    </div>
                    <div id="labelsContainer" class="labels-grid">
                        <!-- Labels will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Custom CSS for custom sizes -->
<style id="customSizeStyle"></style>

<script>
$(document).ready(function() {
    // Show/hide custom size inputs
    $('#size').change(function() {
        if ($(this).val() === 'custom') {
            $('#customSizeDiv').slideDown();
            $('#custom_width, #custom_height').prop('required', true);
        } else {
            $('#customSizeDiv').slideUp();
            $('#custom_width, #custom_height').prop('required', false);
        }
    });

    // Handle form submission
    $('#labelForm').submit(function(e) {
        e.preventDefault();

        // Validate form
        if (!this.checkValidity()) {
            return false;
        }

        // Show loading state
        var $submitBtn = $(this).find('button[type="submit"]');
        var originalText = $submitBtn.html();
        $submitBtn.html('<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Generating...').prop('disabled', true);

        // Get form data
        var formData = {
            design_no: $('#design_no').val(),
            fabric: $('#fabric').val(),
            product_size: $('#product_size').val(),
            price: $('#price').val(),
            size: $('#size').val(),
            custom_width: $('#custom_width').val(),
            custom_height: $('#custom_height').val(),
            quantity: $('#quantity').val(),
            font_size: $('#font_size').val(),
            font_bold: $('#font_bold').val()
        };

        // Send AJAX request
        $.ajax({
            url: '<?php echo base_url("index.php/Label/generate"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Insert labels
                    $('#labelsContainer').html(response.labels_html);
                    $('#labelCount').text('(' + response.quantity + ' labels)');
                    
                    // Apply custom size if needed
                    if (response.size_class === 'custom') {
                        applyCustomSize(response.custom_width, response.custom_height);
                    } else {
                        $('#customSizeStyle').html('');
                    }
                    
                    // Show labels section
                    $('#labelsSection').slideDown();
                    
                    // Scroll to labels
                    $('html, body').animate({
                        scrollTop: $('#labelsSection').offset().top - 20
                    }, 500);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while generating labels. Please try again.');
            },
            complete: function() {
                $submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
});

function applyCustomSize(width, height) {
    var css = `
        .label-item.custom {
            width: ${width}in !important;
            height: ${height}in !important;
        }
    `;
    $('#customSizeStyle').html(css);
}

function printLabels() {
    window.print();
}
</script>
