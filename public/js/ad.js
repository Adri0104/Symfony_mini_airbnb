$('#add-img').click(function () {
    const index = +$('#widgets-counter').val();
    console.log(index);
    const tmpl = $('#ad_form_images').data('prototype').replace(/__name__/g, index);
    $('#ad_form_images').append(tmpl);
    $('#widgets-counter').val(index + 1);
    handleDeleteButton();
});

function handleDeleteButton() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#ad_form_images div.form-group').length;
    $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButton();
