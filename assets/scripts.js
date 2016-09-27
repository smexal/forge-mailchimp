var forgeMailchimp = {

    formCallback : function(data) {
        var container = $(".forge-mailchimp-form");
        var emailField = container.find("#forge-mailchimp-email");

        // tell container, there is a message
        if(data.type == 'error') {
            emailField.removeClass('success');
            emailField.addClass(data.type);
        } else if (data.type == 'success') {
            emailField.removeClass('error');
            emailField.addClass(data.type);
        }

        // add message  
        container.find("p.message").each(function() {
            $(this).remove();
        });
        container.append('<p class="message '+data.type+'">'+data.message+'</p>');
    }

};