/**
 * Created by jocelyn.joubert2017 on 26/02/2018.
 */
// web/js/app.js

// mise sur écoute du bouton de vote
$("#js-vote-btn").on("click", function(event){
    // on désactive le lien pour ne pas quitter la page
    event.preventDefault();

    $.ajax({
        url: $(this).attr("data-ajax-url"),
        type: "POST"
    })
    .done(function(response) {

        if (response.status == "already_voted") {
            alert("Déjà voté !")
            $("#js-vote-btn").remove();
        }
        else {
            //actualise le contenu de la balise affichant le nombre de likes
            // en passant le likesCount en html (voir fichier detail.html.twig)
            $("#js-votes-count").html( response.votesCount )
        }



    })

})



$("#js-addWatch-btn").on("click", function(event){
    // on désactive le lien pour ne pas quitter la page
    event.preventDefault();

    $("#js-addWatch-btn").remove();

    $.ajax({
        url: $(this).attr("data-add-ajax-url"),
        type: "POST"
    })

    .done(function(response) {

        if (response == "ok") {
            alert('Item bien ajouté !')
            $("#js-addWatch-btn").remove();

        } else if (response == 'not'){
            alert("Déjà ajouté !")
            $("#js-addWatch-btn").remove();
        }
    })
})

$("#js-delWatch-btn").on("click", function(event){
    // on désactive le lien pour ne pas quitter la page
    event.preventDefault();

    $("#js-delWatch-btn").remove();

    $.ajax({
        url: $(this).attr("data-del-ajax-url"),
        type: "POST"
    })

        .done(function(response) {

            if (response == "ok") {
                alert("Item bien supprimé !")
                $("#js-delWatch-btn").remove();

            } else {
                alert("Cet item ne fait pas partie de votre watchlist !")
                $("#js-delWatch-btn").remove();
            }
        })
})
