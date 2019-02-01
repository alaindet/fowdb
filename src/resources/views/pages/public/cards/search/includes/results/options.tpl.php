<?php

// VARIABLES
// $spoilers (Optional)

?>
<aside class="col-xs-12 hidden" id="the-options">
  <div class="fd-box --xs-less-padding">

    <!-- Title -->
    <div class="fd-box__title">
      <h2>
        Options
        <button
          type="button"
          class="btn btn-xs btn-link link-as-text js-hider js-options-toggle"
          data-target="#the-options"
          data-open-icon="fa-times"
          data-closed-icon="fa-plus"
        >
          <i class="fa fa-times"></i>
          Close
        </button>
      </h2>
    </div>

    <!-- Content -->
    <div class="fd-box__content">

      <!-- Option: Cards per row ======================================== -->
      <div>
        <h3>Cards per row</h3>
        <div class="controls">
          <div class="input-group">
            
            <!-- Minus -->
            <span class="input-group-btn text-center">
              <button
                class="btn fd-btn-primary"
                id="js-items-per-line-less"
              >
                <i class="fa fa-minus"></i>
              </button>
            </span>

            <!-- Input -->
            <input
              type="text"
              class="form-control text-center font-150"
              size="2"
              maxlength="2"
              value="3"
              id="js-items-per-line-input"
            >

            <!-- Plus -->
            <span class="input-group-btn text-center">
              <button
                class="btn fd-btn-primary"
                id="js-items-per-line-more"
              >
                <i class="fa fa-plus"></i>
              </button>
            </span>

          </div>
        </div>
      </div>

      <?php if (isset($spoilers)): ?>
        
        <hr>

        <!-- OPTION: SHOW MISSING -->
        <div>
          <h4>Missing cards</h4>
          <div class="controls">
            <div class="btn-group" data-toggle="buttons">
              <label
                for="js-showmissing"
                class="btn fd-btn-default"
                id="js-showmissing"
              >
                <input
                  type="checkbox"
                  class="form-control"
                  name="js-showmissing"
                >
                <i class="fa fa-th-large"></i>
                Show missing
              </label>
            </div>
          </div>
        </div>

        <hr>

        <!-- OPTION: SPOILER LIST -->
        <div class="option" id="option-spoiler-list">
          <h4>Spoiler sets</h4>
          <ul class="fd-list">
            <?php foreach ($spoilers as $set): ?>
              <li>
                <a href='#<?=$set['code']?>'>
                  <?=$set['name']?> (<?=$set['code']?>)
                </aside>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

      <?php endif; ?>

    </div>
  </div>
</aside>
