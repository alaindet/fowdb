/**
 * EXPORTS
 * window.APP.triggerEvent
 */

/**
 * Triggers a custom event
 * 
 * The custom event handler signature will be like
 * handler(customEvent, originalEvent, extra...)
 * 
 * Ex.:
 * trigger('my:event', originalEvent, foo, bar) will trigger
 * handleMyEvent(customEvent, orginalEvent, foo, bar)
 * 
 * @param string customEventName
 * @param object originalEvent 
 * @param array extra Any extra argument for custom event handler
 */
window.APP.triggerEvent = (customEventName, originalEvent, extra) => {
  $(document).trigger(customEventName, [originalEvent, ...extra]);
};
