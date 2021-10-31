$(document).ready(function () {
    var $container = $('#cc-list');
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('div.line').length;
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addLine($container);
    } else {
        if (index > 0) {
            // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
            $container.children('tr.prototype_conditionCommerciale').each(function () {
                const $this = $(this);
                $this.find("select").each(function () {
                    const $this = $(this);
                    $this.select2($this.hasClass('select2_ajax') ? selectedOptions : {});
                });
            });
        }
    }
    // La fonction qui ajoute un formulaire Categorie
    function addLine($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-cc").attr('data-prototype').replace(/__name__label__/g, 'conditionCommerciale ' + (index + 1)).replace(/__name__/g, index));
        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);
        $prototype.find("select").each(function () {
            const $this = $(this);
            $this.select2();
        });
        index++;
    }

});