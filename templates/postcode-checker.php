<?php // Postcode Checker Template ?>

<div class="card" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Postcode Delivery Checker</h5>
    <p class="card-text">Enter your postcode to check if we deliver to your postcode.</p>

    <form>
        <div class="form-group">
            <input type="text" class="form-control" id="postcode_checker_value" placeholder="Enter Postcode">
            <p id="validation_error" style="display:none"></p>
            <input type="hidden" name="success_message" value="<?php echo $attributes['success']; ?>">
            <input type="hidden" name="failure_message" value="<?php echo $attributes['failure']; ?>">   
        </div>
        <button class="btn btn-primary" id="post_check_postcode">Check Postcode</button>
    </form>

    <div id="postcode_check_result" style="display:none"></div>
  </div>
</div>