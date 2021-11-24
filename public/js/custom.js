$('form').submit(function (event) {
    event.preventDefault();
    let form = $(this);

    $.ajax({
        url: '/_internal/search',
        type: "POST",
        data: form.serialize(),
        success: function(res) {
           $('#jobs').empty();
           $('#jobs').append(res);
        },
        error: function(err) {
            console.log(err);
        }
    });
});
