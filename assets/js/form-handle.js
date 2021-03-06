var formSend = require('./form-send');

module.exports = function formHandle(targetField, modal, formSelector) {
    // If target field is empty, exit
    if (1 !== targetField.length) {
        return;
    }

    var form = $( formSelector );

    form.find(':submit').click( function( e ){
        e.preventDefault();

        formSend( form, function( response ) {
            if (typeof response === "object") {
                targetField
                    .append($('<option>', {value: response.id, text: response.name}))
                    .val(response.id);
                modal.modal('hide');
            }
            else {
                // Unbind the click event on the button
                form.find(':submit').unbind("click");
                // Change the form code
                modal.find('.modal-body').html(response);

                // Recall this method on a new event
                formHandle(targetField, modal, formSelector);
            }
        });
    });
};
