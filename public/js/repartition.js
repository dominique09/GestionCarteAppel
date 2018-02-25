$(document).ready(function(){

    refresh();

    function refresh() {
        refreshEquipes();
        refreshCartes();
        setTimeout(refresh, 5000);
    }

    var lastUpdateEquipe = new Date;
    var loadDataEquipe = true;

    var lastUpdateCarte = new Date;
    var loadDataCarte = true;

    function refreshEquipes() {
        $.ajax({
            url: "/api/equipe/repartition",
            success: function (data) {
                var updatedAt = new Date(data.updated_at);

                if (lastUpdateEquipe <= updatedAt || loadDataEquipe) {
                    updateEquipe(data.html);
                    lastUpdateEquipe = new Date();
                    loadDataEquipe = false;
                    loadDataCarte = true;
                }

                $("#updatedAtEquipe").html(lastUpdateEquipe + " / " + updatedAt)
            }
        });
    }

    function updateEquipe(data){
        $("#tableauEquipe").html(data);
    }

    function refreshCartes() {
        $.ajax({
            url: "/api/carte/repartition",
            success: function (data) {
                var updatedAt = new Date(data.updated_at);

                if (lastUpdateCarte <= updatedAt || loadDataCarte) {
                    updateCarte(data.html);
                    lastUpdateCarte = new Date();
                    loadDataCarte = false;
                }

                $("#updatedAtCarte").html(lastUpdateCarte + " / " + updatedAt)
            }
        });
    }

    function updateCarte(data){
        $("#tableauCarte").html(data);
    }
});