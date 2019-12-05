$(".form-element").on("keypress, change", function() {
    var formElementArray = $(".form-element");
    var listElementArray = $(".list-element");
    var cpt = 0;
    for (var i = 0; i < $(formElementArray).length; i++) {
        $(listElementArray[i]).removeClass("active");
        if ($(formElementArray[i]).val() != "" || $(formElementArray[i]).attr("file") != null) {
            $(listElementArray[i]).addClass("active");
            cpt++;
        }
    }
    if (cpt == 5) {
        //$("form").css("height", "300px");
        $("button[type='submit']").removeAttr("hidden");
    } else {
        //$("form").css("height", "240px");
        $("button[type='submit']").attr("hidden", "hidden");
    }
});