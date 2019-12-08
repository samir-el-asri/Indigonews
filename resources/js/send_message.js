$("#choose-message-recipient li").on("click", function() {
    $("#choose-message-recipient-input").val($.trim($(this).children().eq(2).text()));
    $("#choose-message-recipient").parent().children().eq(0).text($.trim($(this).children().eq(1).text()));
});