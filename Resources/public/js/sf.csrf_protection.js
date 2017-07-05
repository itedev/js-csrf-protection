/**
 * Created by c1tru55 on 01.06.15.
 */
(function($) {

  $(function() {

    $('body').on('click', 'a[data-csrf-token]', function(e) {
      var $this = $(this);
      e.preventDefault();

      var confirmMessage = $this.data('confirm');
      if ('undefined' !== typeof confirmMessage) {
        if (!confirm(confirmMessage)) {
          return false;
        }
      }

      var $form = $('<form/>', {
        method: 'POST',
        action: $this.attr('href')
      });

      var $methodInput = $('<input/>', {
        name: '_method',
        type: 'hidden',
        value: $this.data('method')
      });
      $form.append($methodInput);

      var csrfToken = $this.data('csrfToken');
      if ('undefined' !== typeof csrfToken) {
        var $csrfInput = $('<input/>', {
          name: '_token',
          type: 'hidden',
          value: csrfToken
        });
        $form.append($csrfInput);
      }

      $('body').append($form);
      $form.submit();
    });

  });

})(jQuery);