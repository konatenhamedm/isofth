$(document).ready(function() {

    var $collectionHolder;

    // setup an "add a tag" link
    var $addTagButton = $('.add_intervention');
    /*var $after = $('tr');*/
    /*var $newLinkLi = $('<li></li>').append($addTagButton);*/

    $(document).ready(function () {
        $collectionHolder = $('#intervention');
        /*$collectionHolder.append($addTagButton);*/
        $collectionHolder.data('index', $collectionHolder.find('.container').length)
        $collectionHolder.find('.container').each(function () {
            addRemoveButton($(this));
        })
        $addTagButton.click(function (e) {
            e.preventDefault();
            addForm();
            $('select').select2();
        })

    })

    function addForm() {
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);

        var $card = $('<div class="container col-md-12"></div>')
        /*  var $cardbody = $('<div class="row"></div>').append(newForm);*/

        $card.append(newForm);

        addRemoveButton($card);

        $collectionHolder.find('.after').before($card);

    }

    function addRemoveButton($card) {
        var $removeButton = $('<a href="#" class="btn btn-danger supprimer" style="margin-left: -16px">x</a>');
        /*var $cardFooter = $('<div class="modal-footer"></div>').append($removeButton);*/

        $removeButton.click(function (e) {
            console.log($(e.target).parent('.container'));

            $(e.target).parents('.container').slideUp(1000, function () {
                $(this).remove();

            });

        })

        $card.find(".supprimer").append($removeButton);
    }


});