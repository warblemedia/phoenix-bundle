(function ($, Stripe, Phoenix) {
    Stripe.setPublishableKey(Phoenix.stripeKey);

    var form = $('#stripe_form');
    var tokenField = $('#' + Phoenix.tokenFieldId);
    var submitButton = form.find('[type="submit"]');
    var submitButtonText = submitButton.text();
    var errorContainer = form.find('.stripe-errors');

    form.on('submit', function (e) {
        e.preventDefault();

        submitButton.prop('disabled', true).text('Processing...');
        errorContainer.empty();

        Stripe.card.createToken(form, function (status, response) {
            console.log(status, response);
            if (response.error) {
                errorContainer.html($('<div class="alert alert-danger">' + response.error.message + '</div>'));
                submitButton.prop('disabled', false).text(submitButtonText);
            } else {
                tokenField.val(response.id);
                form.get(0).submit();
            }
        });

        return false;
    });

})(jQuery, Stripe, window.Phoenix || {});
