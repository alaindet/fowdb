<?php

#js-modal
#js-modal-title
#js-modal-content
#js-modal-submit

?>
<div
  class="modal fade"
  id="js-modal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="js-modal"
>
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <!-- Close -->
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>

        <!-- Title -->
        <h4
          class="modal-title"
          id="js-modal-title"
        >
          MODAL TITLE
        </h4>

      </div>

      <!-- Body -->
      <div class="modal-body" id="js-modal-content">
        MODAL CONTENT
      </div>

      <div class="modal-footer">

        <!-- Cancel -->
        <button
          type="button"
          class="btn btn-default"
          data-dismiss="modal"
        >
          Cancel
        </button>

        <!-- Submit -->
        <button
          type="button"
          class="btn fd-btn-primary"
          id="js-modal-submit"
        >
          MODAL SUBMIT
        </button>

      </div>
    </div>
  </div>
</div>
