// Bootstrap ------------------------------------------------------------------
$(document).ready(function () {
  
  $(document)

    // Original events
    .on('click', css_modalOpen, function () {
      $(document).trigger('fd:show-modal', [$(this)]);
    })

    .on('click', css_modalSubmit, function () {
      $(document).trigger('fd:submit-modal', [$(this)]);
    })

    // Custom events
    .on('fd:show-modal', handleShowModalEvent)
    .on('fd:submit-modal', handleSubmitModalEvent)
    .on('fd:create-cluster', handleCreateClusterEvent)
    .on('fd:update-cluster', handleUpdateClusterEvent)
    .on('fd:delete-cluster', handleDeleteClusterEvent)
    .on('fd:update-list', handleUpdateListEvent)

    // Bootstrap events
    .on('show.bs.modal', handleBootstrapModalShowEvent)
    .on('shown.bs.modal', handleBootstrapModalShownEvent)

});
